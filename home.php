<?php require_once("init.php");
if(!isset($_SESSION["uid"])){
    header("Location: index.php");
}else{
    if(isset($_GET["path"]))
    {
        $_SESSION["path"] = $_GET["path"];
    }
    $currDir = realpath(HOME . DIRECTORY_SEPARATOR . $_SESSION["path"]);
    if($currDir === false || strpos($currDir, HOME) !== 0){
        $_SESSION["final"] = HOME;
        $_SESSION["path"] = null;
    }else{
        $_SESSION["final"] = $currDir;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VaultSEC - Home</title>
    <link rel="icon" type="image/png" href="img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" href="css/home.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="https://use.fontawesome.com/12eb43e664.js"></script>
    <script src="js/home.js"></script>
</head>
<body>
    <?php include("components/user_area.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <img src="img/logo.png" class="img-fluid p-3 mb-3 logo">
                <div class="storage-info">
                    <i class='fa fa-database' aria-hidden='true'></i> <?php echo sizeFormat(dirSize(HOME)) . " de " . sizeFormat(MAXUSERSIZE);?> usados
                </div>
                <div class="uploadFiles" id="dropArea">
                    <div>
                        <button class="btn btn-primary  btn-lg" id="btnUpload">
							<i class="fa fa-cloud-upload" aria-hidden="true" id="uploadIcon"></i> Subir archivos
                        </button>
                        <br>
                        <span id="dragText"></span>
                    </div>
                    <input type="file" id="myfiles" multiple hidden/>
                </div>
                <small>*Tamaño máximo de subida: <?php echo sizeFormat(MAXFILESIZE); ?></small>
            </div>
            <div class="col-md-8">
                <div class="row p-3">
                    <input class="form-control" placeholder="&#xF002; Buscar en directorio" type="text" id="input-search" required/>
                </div>
                <div class="row">
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item" data-toggle="modal" data-target="#modalAddFolder">
                            Nueva carpeta
                        </button>
                        <button class="dropdown-item" id="move">
                            Mover seleccionados
                        </button>
                        <button class="dropdown-item" id="remove">
                            Borrar seleccionados
                        </button>
                    </div>
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones
                    </button>
                    <div class="col-md-12 table-wrapper">
                        <table class="table" id="table">
                            <tbody>
                            <?php
                                $files = scandir($_SESSION["final"]);
                                foreach ($files as $filename) 
                                {
                                    $path = $_SESSION["final"] . DIRECTORY_SEPARATOR . $filename;
                                    $File = new File($path);

                                    $url = "download.php?path=" . $File->getPath();
                                    $title = $File->getName();
                                    if($File->getIsDir())
                                    {
                                        $url = "home.php?path=" . $File->getPath();
                                        $title = "<b>" . $File->getName() . "</b>";
                                    }

                                    if($File->getName() === ".")
                                    {
                                        echo "<nav aria-label='breadcrumb'>";
                                        echo "<ol class='breadcrumb'>";
                                        $breadcrumb = explode(DIRECTORY_SEPARATOR, $File->getPath());
                                        $breadcrumb[0] = ".";
                                        for($i=0; $i < count($breadcrumb) - 1; $i++)
                                        {
                                            $href = array();
                                            for ($j=0; $j <= $i; $j++)
                                            { 
                                                array_push($href, $breadcrumb[$j]);
                                            }
                                            $link = implode(DIRECTORY_SEPARATOR, $href);
                                            if($i == 0)
                                            {
                                                echo "<li class='breadcrumb-item'><a href='home.php?path=${link}'><i class='fa fa-home' aria-hidden='true'></i> VaultSEC</a></li>";
                                                
                                            }
                                            else if($i == count($breadcrumb) - 2)
                                            {
                                                echo "<li class='breadcrumb-item active'>" . $breadcrumb[$i] . "</li>";
                                            }
                                            else
                                            {
                                                echo "<li class='breadcrumb-item'><a href='home.php?path=${link}'>" . $breadcrumb[$i] . "</a></li>";
                                            }
                                        }
                                        echo "</ol>";
                                        echo "</nav>";
                                        continue;
                                    }
                                    else if($File->getName() === "..")
                                    {
                                        continue;
                                    }
                                    else
                                    {
                                        echo "<tr class='item' id='" . $File->getName() . "'>";
                                        echo "<td>" . "<input type='checkbox' id='select'>" . "</td>";
                                        echo "<td class='truncate'>" . "<i class='fa " . font_awesome_file_icon_class($File->getMimetype()) . "' aria-hidden='true'></i> <a href='${url}'>${title}</a>" . "</td>";
                                        echo "<td>" . $File->getSize() . "</td>";
                                        echo "<td>" . $File->getModified() . "</td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include("components/add_folder.html");
include("components/edit_user_pass.html");
?>