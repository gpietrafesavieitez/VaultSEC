<?php require_once("init.php");

function loadRoles(){
    return Database::run(SQL["GETALLROLES"])->fetchAll();
}

function loadUsers($uid){
    return Database::run(SQL["GETALLUSERS"], [$uid])->fetchAll(PDO::FETCH_CLASS, "User");
}

function createUser($user){
    $uid = $user[0];
    $displayname = $user[1];
    $password = password_hash($user[2], PASSWORD_BCRYPT);
    $role = $user[3];
    try{
        $store = Database::run(SQL["ADDUSER"], [$uid, $displayname, $password, $role]);
        return $store->rowCount();
    }catch(PDOException $pdoe){
        return -1;
    }
}

function editUser($user){
    $uid = $user[0];
    $displayname = $user[1];
    $role = $user[2];
    $store = Database::run(SQL["UPDATEUSERBYUID"], [$displayname, $role, $uid]);
    return $store->rowCount();
}

function removeUser($uid){
    $removed = Database::run(SQL["REMOVEUSERBYUID"], [$uid]);
    if($removed->rowCount())
    {
        $home = STORAGE.DIRECTORY_SEPARATOR . $uid;
        if(file_exists($home))
        {
            rrmdir($home, $uid);
        }
        return true;
    }
    return false;
}

function editUserPass($passwords, $uid){
    $oldPass = $passwords[0];
    $newPass = $passwords[1];
    $res = Database::run(SQL["GETPASSBYUID"], [$uid])->fetch();
    $password = $res["password"];
    if(password_verify($oldPass, $password)){
        $newPassword = password_hash($newPass, PASSWORD_BCRYPT);
        $result = Database::run(SQL["UPDATEPASSBYUID"], [$newPassword, $uid]);
        return $result->rowCount();
    }else{
        return -1;
    }
}

function checkFile($checksum, $uid){
    $Files = Database::run(SQL["GETFILEBYOWNER"], [$checksum, $uid])->fetchAll(PDO::FETCH_CLASS, "File");
    if(!empty($Files))
    {
        $File = $Files[0];
        return $File;
    }
    return null;
}

function login($user, $pass){
    $Users = Database::run(SQL["GETUSERBYUID"], [$user])->fetchAll(PDO::FETCH_CLASS, "User");
    if(!empty($Users))
    {
        $User = $Users[0];
        if(password_verify($pass, $User->getPassword()))
        {
            $home = STORAGE.DIRECTORY_SEPARATOR . $User->getUid();
            if(!file_exists($home))
            {
                mkdir($home, 0777, true);
            }
            return $User;
        }
    }
    return null;
}

function upload($src, $dst, $uid){
    $files = $src;
    $errors = [];
    $relPath = substr($dst, strlen(HOME));
    try
    {
        for($i = 0; $i < count($files["name"]); $i ++)
        {
            $fileName = $files["name"][$i];
            //$fileType = $files["type"][$i];
            $fileTmpName = $files["tmp_name"][$i];
            $fileError = $files["error"][$i];
            $fileSize = $files["size"][$i];
            $ext = explode(".", $fileName);
            $fileExtension = strtolower(end($ext));
            $fileMime = mime_content_type($fileTmpName);
            $fileHash = hash_file("md5", $fileTmpName);
            $filePath = $dst . DIRECTORY_SEPARATOR . $fileName;
            if(!isset($fileError) || is_array($fileError))
            {
                throw new RuntimeException("Parámetros inválidos.");
            }
            if(isStorageFull($fileSize))
            {
                throw new RuntimeException("Límite de almacenamiento alcanzado.");
            }
            if(!in_array($fileExtension, allowedExtensions))
            {
                throw new RuntimeException("Extensión no soportada.");
            }
            switch($fileError)
            {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException("No se han enviado archivos.");
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException("El tamaño del archivo es demasiado grande.");
                default:
                    throw new RuntimeException("Error desconocido.");
            }
            if($fileSize > MAXFILESIZE)
            {
                throw new RuntimeException("El tamaño del archivo es demasiado grande.");
            }
            $file = Database::run(SQL["GETFILEBYOWNER"], [$fileHash, $uid])->fetch();
            if($file == null)
            {
                //buscamos el mimetype en la base de datos
                $mime = Database::run(SQL["GETMIMEBYNAME"], [$fileMime])->fetch();
                //si hay coincidencias guardamos el mid
                if($mime != null)
                {
                    $store = Database::run(SQL["ADDFILE"], [$fileHash, $relPath, $fileName, $mime["mid"], $uid, filesize($fileTmpName)]);
                    if($store->rowCount() > 0)
                    {
                        if(!move_uploaded_file($fileTmpName, $filePath))
                        {
                            throw new RuntimeException("Error al subir el archivo.");
                        }
                    }
                }
                else
                {
                    throw new RuntimeException("Formato de archivo inválido.");
                }
            }
            else
            {
                throw new RuntimeException("El archivo ya existe.");
            }
        }
    }
    catch(RuntimeException $re)
    {
        $errors[] = array("name" => $fileName, "error" => $re->getMessage());
    }
    return $errors;
}

function addFolder($name, $dst){
    $error = [];
    try
    {
        if(preg_match("/^([a-zA-Z0-9\s]){1,50}$/", $name))
        {
            $path = $dst . DIRECTORY_SEPARATOR . $name;
            if(!file_exists($path))
            {
                if(!mkdir($path, 0777, false))
                {
                    throw new RuntimeException("No se ha podido crear la carpeta.");
                }
            }
            else
            {
                throw new RuntimeException("La carpeta ya existe.");
            }
        }
        else
        {
            throw new RuntimeException("El nombre de la carpeta no es válido.");
        }
    }
    catch(RuntimeException $re)
    {
        $error = array("error" => $re->getMessage());
    }
    return $error;
}

//Hacer una función que sume los bytes de un archivo
function getFileSize($file)
{
    $size = filesize($file);
    $size = $size / 1024;
    $size = number_format($size, 2, ".", "");
    return $size;
}

// files: Nombre de archivos a mover
// src: Ruta de salida (path de sesión de cuando se clicó por primera vez el botón MOVER)
// folder: Carpeta donde ubicar los archivos a mover
// dst: Ruta de entrada (path de sesión de cuando se clicó por segunda vez el botón MOVER)
function move($files, $src, $folder, $dst, $uid){
    $movedFiles = 0;
    $fileDst = $dst . DIRECTORY_SEPARATOR . $folder[0];
    substr($fileDst, strlen(HOME));
    foreach($files as $file)
    {
        $fileSrc = $src . DIRECTORY_SEPARATOR . $file;
        
        if(is_file($fileSrc) && is_dir($fileDst))
        {
            $relPath = substr($fileDst, strlen(HOME));
            $checksum = hash_file("md5", $fileSrc);
            $check = Database::run(SQL["GETFILEBYOWNER"], [$checksum, $uid])->fetch();
            
            if(rename($fileSrc, $fileDst . DIRECTORY_SEPARATOR . $file) && $check != null)
            {
                $modify = Database::run(SQL["UPDATEFILEBYOWNER"], [$relPath, $checksum, $uid]);
                if($modify->rowCount() > 0){
                    $movedFiles = $movedFiles + 1;
                }
            }
        }
        /*
        elseif(is_dir($filePath))
        {
            if(rrmdir($filePath, $uid))
            {
                $movedFiles = $movedFiles + 1;
            }
        }
        */
    }
    return $movedFiles;
}

function rename_win($oldfile, $newfile){
    if (!rename($oldfile, $newfile)) {
        if (copy ($oldfile, $newfile)) {
            unlink($oldfile);
            return TRUE;
        }
        return FALSE;
    }
    return TRUE;
}

function remove($files, $src, $uid)
{
    $result = 0;
    foreach($files as $file){
        $filePath = $src . DIRECTORY_SEPARATOR . $file;
        if(is_file($filePath)){
            if(rmfile($filePath, $uid)){
                $result = $result + 1;
            }
        }elseif(is_dir($filePath)){
            if(rrmdir($filePath, $uid)){
                $result = $result + 1;
            }
        }
    }
    return $result;
}

function rmfile($path, $uid){
    $result = false;
    if(is_file($path)){
        $checksum = hash_file("md5", $path);
        $check = Database::run(SQL["GETFILEBYOWNER"], [$checksum, $uid])->fetch();
        if($check != null){
            if($check["checksum"] === $checksum){
                $sqlpath =  $check["user"] . DIRECTORY_SEPARATOR . $check["path"] . DIRECTORY_SEPARATOR . $check["name"];
                $sqlrealpath = realpath(STORAGE . $sqlpath);
                if(unlink($sqlrealpath)){
                    $rmfile = Database::run(SQL["REMOVEFILEBYOWNER"], [$checksum, $uid]);
                    if($rmfile->rowCount() > 0){
                        $result = true;
                    }
                }
            }
        }
    }
    return $result;
}

function rrmdir($path, $uid)
{
    $result = true;
    if(is_dir($path))
    {
        $objects = scandir($path);
        foreach($objects as $object)
        { 
            if($object != "." && $object != "..")
            {
                $url = $path . DIRECTORY_SEPARATOR . $object;
                if(is_dir($url))
                {
                    if(!rrmdir($url, $uid))
                    {
                        $result = false;
                    }
                }
                elseif(is_file($url))
                {
                    if(!rmfile($url, $uid))
                    {
                        $result = false;
                    }
                }
                else
                {
                    $result = false;
                }
            }
        }
        if(!rmdir($path))
        {
            $result = false;
        }
    }
    else
    {
        $result = false;
    }
    return $result;
}

function dirSize($directory)
{
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
    {
        if($file->getFileName() != ".." && $file->getFileName() != ".")
        {
            $size += $file->getSize();
        }
    }
    return $size;
}

function isStorageFull($newFileSize = 0)
{ 
    return (dirSize(HOME) + $newFileSize) > MAXUSERSIZE ? true : false;
}

function sizeFormat($bytes)
{ 
    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    $tb = $gb * 1024;
    if(($bytes > 0) && ($bytes < $kb))
    {
        return $bytes . " B";
    }
    else if(($bytes >= $kb) && ($bytes < $mb))
    {
        return ceil($bytes / $kb) . " KB";
    }
    else if(($bytes >= $mb) && ($bytes < $gb))
    {
        return round($bytes / $mb, 2) . " MB";
    }
    else if(($bytes >= $gb) && ($bytes < $tb))
    {
        return ceil($bytes / $gb) . " GB";
    }
    else if($bytes >= $tb)
    {
        return ceil($bytes / $tb) . " TB";
    }
    else
    {
        return "0 B";
    }
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        "y" => "año",
        "m" => "mes",
        "w" => "semana",
        "d" => "día",
        "h" => "hora",
        "i" => "minuto",
        "s" => "segundo"
    );
    foreach ($string as $k => &$v)
    {
        if ($diff->$k)
        {
            if($diff->$k > 1)
            {
                if($k === "m")
                {
                    $extra = "es";
                }
                else
                {
                    $extra = "s";
                }
                $v = $diff->$k . ' ' . $v . $extra;
            }
            else
            {
                $v = $diff->$k . ' ' . $v;
            }
        }
        else
        {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? "hace " . implode(', ', $string) : "ahora mismo";
}

function font_awesome_file_icon_class( $mime_type ) {

  // List of official MIME Types: http://www.iana.org/assignments/media-types/media-types.xhtml
  static $font_awesome_file_icon_classes = array(
    // Images
    'image' => 'fa-file-image-o',
    // Audio
    'audio' => 'fa-file-audio-o',
    // Video
    'video' => 'fa-file-video-o',
    // Documents
    'application/pdf' => 'fa-file-pdf-o',
    'text/plain' => 'fa-file-text-o',
    'text/html' => 'fa-file-code-o',
    'application/json' => 'fa-file-code-o',
    // Archives
    'application/gzip' => 'fa-file-archive-o',
    'application/zip' => 'fa-file-archive-o',
    // Misc
    'application/octet-stream' => 'fa-file-o',
  );

  if (isset($font_awesome_file_icon_classes[ $mime_type ])) {
    return $font_awesome_file_icon_classes[ $mime_type ];
  }

  $mime_parts = explode('/', $mime_type, 2);
  $mime_group = $mime_parts[0];
  if (isset($font_awesome_file_icon_classes[ $mime_group ])) {
    return $font_awesome_file_icon_classes[ $mime_group ];
  }

  return "fa-folder-open-o";
}

?>