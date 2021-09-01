<div class="userArea m-2 mb-4">
	<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="fa fa-user-circle" aria-hidden="true"></i> <?php echo $_SESSION["displayname"]; ?>
	</button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="home.php?path=.">Página principal</a>
        <!-- <button class="dropdown-item" type="button" data-toggle="modal" data-target="#modalEditUserName">Editar nombre</button> -->
        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#modalEditUserPass">Editar contraseña</button>
        <?php echo $_SESSION["role"] === ADMIN ? "<a class='dropdown-item' href='admin.php'>Administración</a>" : null;?>
        <div class="dropdown-divider"></div>
        <button class="dropdown-item" id="btnLogout">Cerrar sesión</button>
    </div>
</div>

<script>
    $("#btnLogout").click(function(){
        $.post("api.php", {"logout": true})
            .done(function(data){
                try{
                    let response = JSON.parse(data);
                    if(response["status"] === "ok"){
                        window.location.href = "index.php";
                    }else if(response["status"] === "error"){
                        console.log(response["error"]);
                    }
                }catch(exception){
                    console.log(exception);
                };
            });
    });
</script>