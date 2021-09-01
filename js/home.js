function upload(files){
    $("#uploadIcon").attr("class", "spinner-border spinner-border-sm");
    let fd = new FormData();
    for(let i = 0; i < files.length; i ++){
        fd.append("file[]", files[i]);
    }
    $.ajax({
        url: "api.php",
        type: "POST",
        data: fd,
        contentType: false,
        processData: false,
        cache: false,
    }).done(function(data){
        let response = JSON.parse(data);
        let message = "";
        for(let index = 0; index < response.length; index++){
            let name = response[index]["name"];
            let error = response[index]["error"];
            message += name + ": " + error + "\n";
        }
        if(message.length > 0){
            alert(message);
        }
        parent.window.location.reload();
    }).always(function(){
        $("#uploadIcon").attr("class", "fa fa-cloud-upload");
    });
}

$(document).ready(function(){
    let button = document.getElementById("btnUpload");
    const dropArea = document.querySelector("#dropArea");
    dragText = dropArea.querySelector("#dragText");
    dragText.textContent = "O suelta tus archivos aquí";
    input = dropArea.querySelector("#myfiles");
    let files;

    button.onclick = ()=> {
        input.click();
    }

    input.addEventListener("change", function(){
        files = this.files;
        console.log(files);
        dropArea.classList.add("active");
        upload(files);
    });

    dropArea.addEventListener("dragover", function(e){
        e.preventDefault();
        dropArea.classList.add("active");
        dragText.textContent = "¡Suelta tus archivos!";
    });

    dropArea.addEventListener("dragleave", function(){
        dropArea.classList.remove("active");
        dragText.textContent = "O suelta tus archivos aquí";
    });

    dropArea.addEventListener("drop", function(e){
        e.preventDefault();
        files = e.dataTransfer.files;
        console.log(files);
        upload(files);
    });

    $("#editUserName").click(function(){
        let name = $("#inputUserName").val();
        if(name.length > 0){
            $.post("api.php", {"editUserName": name})
            .done(function(data){
                try{
                    let response = JSON.parse(data);
                    if(response["status"] === "ok"){
                        parent.window.location.reload();
                    }else if(response["status"] === "error"){
                        $(".statusMsg").html("<span style='color:red;'>" + response["message"] + "</span>");
                    }
                }catch(exception){
                    console.log(exception);
                    alert("Ha ocurrido un error inesperado.");
                };
            });
        }else{
            $(".statusMsg").html("<span style='color:red;'>No puede haber campos vacíos.</span>");
        }
    });
    
    $("#input-search").keyup(function(){
        let value = $(this).val().toLowerCase();
        $("tr.item").each(function(){
            $(this).hide();
            if($(this).attr("id").toLowerCase().indexOf(value) >= 0){
                $(this).show();
            }
        });
    });
    
    $("#remove").click(function(){
        let selected = [];
        $(".item input:checked").each(function(){
            selected.push($(this).parent().parent().attr("id"));
        });
        if(selected.length > 0){
            if(confirm("¿Está seguro de que desea eliminar los siguientes archivos?\n" + selected.join(", "))){
                $("#trashIcon").attr("class", "spinner-border spinner-border-sm");
                $.post("api.php", {"removeFiles[]": selected})
                .done(function(data){
                    $("#trashIcon").attr("class", "fa fa-trash");
                    try{
                        let response = JSON.parse(data);
                        if(response["status"] === "ok"){
                            parent.window.location.reload();
                        }else if(response["status"] === "error"){
                            console.log(response["message"]);
                        }
                    }catch(exception){
                        console.log(exception);
                        alert("Ha ocurrido un error inesperado.");
                    }
                }).always(function(){
                    $("#trashIcon").attr("class", "fa fa-trash");
                });
            }
        }else{
            alert("Selecciona los archivos que deseas eliminar.")
        }
    });
    
    $("#move").click(function(){
        let selected = [];
        $(".item input:checked").each(function(){
            selected.push($(this).parent().parent().attr("id"));
        });
        //if(selected.length > 0){
            console.log(selected);
            $("#arrowsIcon").attr("class", "spinner-border spinner-border-sm");
            $.post("api.php", {"moveFiles[]": selected})
                .done(function(data){
                    $("#arrowsIcon").attr("class", "fa fa-arrows");
                    try{
                        let response = JSON.parse(data);
                        console.log(response["message"]);
                    }catch(exception){
                        console.log(exception);
                        alert("Ha ocurrido un error inesperado.");
                    };
                }).always(function(){
                    $("#arrowsIcon").attr("class", "fa fa-arrows");
                });
        /* }else{
            alert("Selecciona los archivos que deseas mover.")
        } */
    });

    $("#btnAddFolder").click(function(){
        let name = $("#inputNameAddFolder").val();
        if(name.length > 0){
            $("#folderIcon").attr("class", "spinner-border spinner-border-sm");
            $.post("api.php", {"addFolder": name})
            .done(function(data){
                let response = JSON.parse(data);
                if(response.length === 0){
                    parent.window.location.reload();
                }else{
                    $(".statusMsg").html("<span style='color:red;'>" + response["error"] + "</span>");
                }
            })
            .always(function(){
                $("#folderIcon").attr("class", "fa fa-folder-open");
            });
        }else{
            $(".statusMsg").html("<span style='color:red;'>No puede haber campos vacíos.</span>");
        }
    });
    
});