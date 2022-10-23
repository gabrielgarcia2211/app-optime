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

$("#precio").on({
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