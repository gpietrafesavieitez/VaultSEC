<?php require_once("init.php");

if(isset($_GET["path"]) && isset($_SESSION["uid"])){
    $homeDir = realpath(STORAGE . DIRECTORY_SEPARATOR . $_SESSION["uid"]);
    $path = $_GET["path"];
    $url = realpath($homeDir . $path);
    if(file_exists($url)){
        $checksum = hash_file("md5", $url);
        $file = Database::run(SQL["GETFILEBYOWNER"], [$checksum, $_SESSION["uid"]])->fetch();
        if($file != null){
            if($checksum === $file["checksum"]){
                header("Content-Description: File Transfer");
                header("Content-Type: application/octet-stream");
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: 0");
                header("Content-Disposition: attachment; filename=" . $file["name"]);
                header("Content-Length: " . $file["size"]);
                header("Pragma: public");
                //limpiamos el buffer
                flush();
                readfile($url);
                die();
            }
        }else{
            die("<b>Archivo corrupto.</b> El contenido ha sido modificado.");
        }
    }else{
        die("<b>No encontrado.</b> El archivo especificado no existe.");
    }
}

?>