<?php require_once("init.php");

if(!isset($_SESSION["uid"])){
    header("Location: index.php");
}else if(isset($_SESSION["uid"]) && $_SESSION["role"] != ADMIN){
    header("Location: home.php");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaultSEC - Administración</title>
    <link rel="icon" type="image/png" href="img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="https://use.fontawesome.com/12eb43e664.js"></script>
    <script src="js/admin.js"></script>
</head>
<body>
    <?php include("components/user_area.php"); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <table class="table display" id="table" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                <button class="btn btn-primary btn-block btn-lg" data-toggle="modal" data-target="#modalAddUser">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                </button>
                            </th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Creación</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include("components/add_user.html");
include("components/edit_user.html");
include("components/edit_user_pass.html");
?>