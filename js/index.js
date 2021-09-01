$(document).ready(function(){

    $("#alert").hide();

    $("#login-form").submit(function(e){
        e.preventDefault();
        let user = $("#input-user").val();
        let pass = $("#input-pass").val();
        let login = [user, pass];
        $.post("api.php", {"login": login})
            .done(function(data){
                try{
                    let response = JSON.parse(data);
                    if(response["status"] === "ok"){
                        window.location.href = "home.php";
                    }else if(response["status"] === "error"){
                        $("#alert").text(response["message"]).show();
                    }
                }catch(exception){
                    console.log(exception);
                }
            });
    });

});