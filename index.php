<?php require_once("init.php");
if(isset($_SESSION["uid"])){
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaultSEC - Acceso</title>
    <link rel="icon" type="image/png" href="img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/12eb43e664.js"></script>
    <script src="js/index.js"></script>
</head>
<body>
    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="container content">
        <div class="row">
            <div class="col-md-12">
                <img class="img-fluid center mb-5" src="img/logo.png">
                <form id="login-form">
                    <input type="text" id="input-user" name="user" class="form-control form-control-lg" placeholder="&#xf007; Usuario" required>
                    <input type="password" id="input-pass" name="pass" class="form-control form-control-lg" placeholder="&#xf023; Contraseña" required>
                    <input type="submit" class="btn btn-primary btn-block btn-lg" value="Acceder"/>
                </form>
            </div>
        </div>
    </div>
</body>
</html>