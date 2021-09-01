$(document).ready(function(){

    $(".modal").on("hidden.bs.modal", function(){
        $(".statusMsg").html("");
        $(this).find("form").trigger("reset");
    });

    let table = $("#table").removeAttr("width").DataTable({
        ajax: "api.php?loadUsers",
        scrollY: "60vh",
        scrollX: true,
        paging: false,
        searching: true,
        language:
        {
            lengthMenu: "Mostrar _MENU_ usuarios por página",
            zeroRecords: "No existen usuarios",
            info: "_END_ usuarios",
            infoEmpty: "_END_ usuarios",
            infoFiltered: "(filtrando entre _MAX_ usuarios)",
            sSearch: "",
            searchPlaceholder: "Buscar usuarios",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
        },
        order: [[ 1, "desc" ]],
        columnDefs:
        [ 
            {
                className: "uid", "targets": [1],
            },
            {
                targets: 0,
                data: null,
                sortable: false,
                defaultContent:
                "<div class='btn-group'>" +
                    "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#modalEditUser' id='editUser'><i class='fa fa-pencil-square-o'></i></button>" +
                    "<button class='btn btn-outline-primary' id='deleteUser'><i class='fa fa-trash'></i></button>" +
                "</div>"
            },
        ],
    });

    $.get("api.php?loadRoles")
    .done(function(data){
        try{
            let response = JSON.parse(data);
            let datos = response["data"];
            for (var index = 0; index < datos.length; index++) {
                $("#inputEditUserRole").append("<option value='" + datos[index][0] + "'>" + datos[index][1] + "</option>");
                $("#inputAddUserRole").append("<option value='" + datos[index][0] + "'>" + datos[index][1] + "</option>");
            }
        }catch(exception){
            console.log(exception);
        };
    });

    $("#table tbody").on("click", "#editUser", function(){
        let data = table.row($(this).parents("tr")).data();
        let uid = data[1];
        let displayname = data[2];
        $("input#inputEditUserUid").val(uid);
        $("input#inputEditUserDisplayname").val(displayname);
    });

    $("#table tbody").on("click", "#deleteUser", function(){
        let data = table.row($(this).parents("tr")).data();
        let uid = data[1];
        if(confirm("¿Está seguro de que desea eliminar a " + uid + "?\n")){
            $.post("api.php", {"removeUser":uid})
            .done(function(data){
                try{
                    let response = JSON.parse(data);
                    if(response["status"] === "ok"){
                        table.ajax.reload();
                    }else if(response["status"] === "error"){
                        $(".statusMsg").html("<span style='color:red;'>" + response["message"] + "</span>");
                    }
                }catch(exception){
                    console.log(exception);
                };
            });
        }
    });

    $("#btnSubmitEditUser").click(function(){
        let uid = $("input#inputEditUserUid").val();
        let displayname = $("input#inputEditUserDisplayname").val();
        let role = $("select#inputEditUserRole").val();
        let user = [uid, displayname, role];
        if(uid.length > 0 && displayname.length > 0 && role.length > 0){
            $.post("api.php", {"editUser" : user})
            .done(function(data){
                try{
                    let response = JSON.parse(data);
                    if(response["status"] === "ok"){
                        table.ajax.reload();
                        $("#modalEditUser").modal("toggle");
                    }else if(response["status"] === "error"){
                        $(".statusMsg").html("<span style='color:red;'>" + response["message"] + "</span>");
                    }
                    console.log(data);
                }catch(exception){
                    console.log(exception);
                };
            });
        }else{
            $(".statusMsg").html("<span style='color:red;'>No puede haber campos vacíos.</span>");
        }
    });

    $("#btnAddUser").click(function(){
        let uid = $("#inputAddUserUid").val();
        let displayname = $("#inputAddUserName").val();
        let password = $("#inputAddUserPass").val();
        let role = $("#inputAddUserRole").val();
        let user = [uid, displayname, password, role];
        if(uid.length > 0 && displayname.length > 0 && password.length > 0 && role.length > 0){
            $.post("api.php", {"createUser": user})
            .done(function(data){
                try{
                    let response = JSON.parse(data);
                    if(response["status"] === "ok"){
                        table.ajax.reload();
                        $("#modalAddUser").modal("toggle");
                    }else if(response["status"] === "error"){
                        $(".statusMsg").html("<span style='color:red;'>" + response["message"] + "</span>");
                    }
                }catch(exception){
                    console.log(exception);
                };
            });
        }else{
            $(".statusMsg").html("<span style='color:red;'>No puede haber campos vacíos.</span>");
        }
    });

});