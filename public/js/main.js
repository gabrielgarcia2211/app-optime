$(document).ready(function () {


    $("#precio", "#precio_edit").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{5})+(?!\d)\.?)/g, ".");
            });
        }
    });

    $.ajax({
        url: "products/list", method: 'GET', success: function (result) {
            if (result) {
                let template = "";
                $('.list-product').dataTable({
                    data: result,
                    columns: [
                        { "data": "id", "visible": false },
                        { "data": "code", },
                        { "data": "name" },
                        { "data": "description" },
                        { "data": "brand" },
                        { "data": "price" },
                        { "data": "category" },
                        {
                            "data": "Acciones", "render": function (data, type, row) {
                                template = '<button onclick="return deleteProduct(' + row.id + ')" class="btn btn-danger" style="margin-right: 5px"> <i title="Eliminar producto" class="fas fa-trash"></i></button>';
                                template += '<button onclick="return editProduct(' + row.id + ')" type="button" class="btn btn-warning" data-bs-toggle="modal" ><i title="Editar producto" class="fas fa-edit"></i></button>';
                                return template;
                            }
                        }
                    ],
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ Entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar: ",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                });
            }
        }
    });


});

$('#list-product tbody').on('click', 'button', function () {

});


function deleteCategory(id) {
    var resp = confirm('Deseas eliminar la categoria ?');

    if (resp) {
        $.ajax({
            url: "category/delete/" + id, method: 'POST', success: function (result) {
                if (result) {
                    location.reload();
                }
            }
        });
    }
}

function editProduct(id) {

    if (id) {
        $.ajax({
            url: "products/edit/" + id, method: 'GET', success: function (result) {
                if (result) {
                    let data = result[0];
                    $("#id_edit").val(data.id);
                    $("#codigo_edit").val(data.code);
                    $("#nombre_edit").val(data.name);
                    $("#descripcion_edit").val(data.description);
                    $("#marca_edit").val(data.brand);
                    $("#precio_edit").val(data.price);
                    //$("#categoria_edit").val(data.category.id);
                    $("#editModal").modal("show");
                }
            }
        });
    }
}

function deleteProduct(id) {
    var resp = confirm('Deseas eliminar el product ?');
    if (resp) {
        $.ajax({
            url: "products/delete/" + id, method: 'POST', success: function (result) {
                if (result) {
                    location.reload();
                }
            }
        });
    }
}

