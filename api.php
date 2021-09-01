<?php require_once("init.php");

if(isset($_SESSION["uid"])){

    if($_SESSION["role"] === ADMIN){

        if(isset($_GET["loadRoles"])){
            $return_arr = [];
            $roles = loadRoles();
            $data = [];
            foreach($roles as $role){
                $data[] = array($role["rid"], $role["type"]);
            }
            $return_arr = array("data" => $data);
            echo json_encode($return_arr, JSON_PRETTY_PRINT);
        }

        if(isset($_GET["loadUsers"])){
            $return_arr = [];
            $users = loadUsers($_SESSION["uid"]);
            $data = [];
            foreach($users as $user){
                $data[] = array(null, $user->getUid(), $user->getDisplayname(), $user->getType(), $user->getCreation());
            }
            $return_arr = array("data" => $data);
            echo json_encode($return_arr, JSON_PRETTY_PRINT);
        }

        if(isset($_POST["createUser"])){
            $return_arr = [];
            $user = $_POST["createUser"];
            if(preg_match("/^([a-zA-Z0-9\s]){1,50}$/", $user[0]) && preg_match("/^([a-zA-Z0-9\s]){1,50}$/", $user[1])){
                $created = createUser($user);
                if($created > 0){
                    $return_arr = array("status" => "ok", "message" => "Usuario creado con éxito.");
                }else if($created === -1){
                    $return_arr = array("status" => "error", "message" => "Ya existe un usuario con ese nombre.");
                }else{
                    $return_arr = array("status" => "error", "message" => "No se ha podido crear el usuario.");
                }
            }else{
                $return_arr = array("status" => "error", "message" => "Los campos tienen un formato incorrecto.");
            }
            echo json_encode($return_arr, JSON_PRETTY_PRINT);
        }

        if(isset($_POST["editUser"])){
            $return_arr = [];
            $user = $_POST["editUser"];
            if(preg_match("/^([a-zA-Z0-9\s]){1,50}$/", $user[0]) && preg_match("/^([a-zA-Z0-9\s]){1,50}$/", $user[1])){
                $edited = editUser($user);
                if($edited > 0){
                    $return_arr = array("status" => "ok", "message" => "Usuario editado con éxito.");
                }else{
                    $return_arr = array("status" => "error", "message" => "No se ha podido editar el usuario.");
                }
            }else{
                $return_arr = array("status" => "error", "message" => "Los campos tienen un formato incorrecto.");
            }
            echo json_encode($return_arr, JSON_PRETTY_PRINT);
        }

        if(isset($_POST["removeUser"])){
            $return_arr = [];
            $uid = $_POST["removeUser"];
            $removed = removeUser($uid);
            if($removed){
                $return_arr = array("status" => "ok", "message" => "Usuario ${uid} eliminado con éxito.");
            }else{
                $return_arr = array("status" => "error", "message" => "No se ha podido eliminar al usuario ${uid}." );
            }
            echo json_encode($return_arr, JSON_PRETTY_PRINT);
        }

    }

    if(isset($_FILES["file"])){
        $return_arr = [];
        $files = $_FILES["file"];
        $return_arr = upload($files, $_SESSION["final"], $_SESSION["uid"]);
        echo json_encode($return_arr, JSON_PRETTY_PRINT);
    }

    if(isset($_POST["addFolder"])){
        $return_arr = [];
        $folder = $_POST["addFolder"];
        $return_arr = addFolder($folder, $_SESSION["final"]);
        echo json_encode($return_arr, JSON_PRETTY_PRINT);
    }

    if(isset($_POST["moveFiles"])){
        $return_arr = [];
        $return_arr = array("status" => "error", "message" => "Error inesperado");
        if(isset($_SESSION["files"]) && isset($_SESSION["src"])){
            $moved = move($_SESSION["files"], $_SESSION["src"], $_POST["moveFiles"], $_SESSION["final"], $_SESSION["uid"]);
            if($moved > 0){
                $return_arr = array("status" => "ok", "message" => "Movidos " . $moved . " archivos con éxito.");
            }else{
                $return_arr = array("status" => "error", "message" => "Error al mover archivos.");
            }
            unset($_SESSION["files"]);
            unset($_SESSION["src"]);
        }else{
            $_SESSION["files"] = $_POST["moveFiles"];
            $_SESSION["src"] = $_SESSION["final"];
            $return_arr = array("status" => "ok", "message" => "Archivos de " . $_SESSION["src"] . " preparados para mover.");
        }
        echo json_encode($return_arr, JSON_PRETTY_PRINT);
    }

    if(isset($_POST["removeFiles"])){
        $return_arr = [];
        $files = $_POST["removeFiles"];
        $removed = remove($files, $_SESSION["final"], $_SESSION["uid"]);
        if($removed > 0){
            $return_arr = array("status" => "ok", "message" => "Borrados " . $removed . " archivos con éxito.");
        }else{
            $return_arr = array("status" => "error", "message" => "Error al borrar archivos.");
        }
        echo json_encode($return_arr, JSON_PRETTY_PRINT);
    }

    if(isset($_POST["editUserPass"])){
        $return_arr = [];
        $passwords = $_POST["editUserPass"];
        $modified = editUserPass($passwords, $_SESSION["uid"]);
        if($modified > 0){
            $return_arr = array("status" => "ok", "message" => "Contraseña modificada con éxito.");
        }else if($modified === -1){
            $return_arr = array("status" => "error", "message" => "La contraseña actual es incorrecta.");
        }else{
            $return_arr = array("status" => "error", "message" => "Error al modificar la contraseña.");
        }
        echo json_encode($return_arr, JSON_PRETTY_PRINT);
    }

    if(isset($_POST["logout"])){
        $return_arr = array("status" => "error", "message" => "No se ha podido cerrar sesión.");
        if(session_destroy()){
            $return_arr = array("status" => "ok", "message" => "Sesión cerrada exitosamente.");
        }
        echo json_encode($return_arr, JSON_PRETTY_PRINT);
    }

}else{

    if(isset($_POST["login"])){
        $return_arr = array("status" => "error", "message" => "No se ha podido iniciar sesión.");
        $user = $_POST["login"];
        $_user = strip_tags($user[0]);
        $_pass = strip_tags($user[1]);
        $User = login($_user, $_pass);
        if($User != null){
            $_SESSION["uid"] = $User->getUid();
            $_SESSION["displayname"] = $User->getDisplayname();
            $_SESSION["role"] = $User->getType();
            $_SESSION["path"] = ".";
            $return_arr = array("status" => "ok", "message" => "Usuario logueado con éxito.");
        }
        echo json_encode($return_arr, JSON_PRETTY_PRINT);
    }
}

?>
