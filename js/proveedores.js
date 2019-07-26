'use strict'
var editar = false;
var deleteID = '';
$(document).ready(function () {
    $('#barraNavegacion').load('components/navbar.html');
    $('#piePagina').load('components/footer.html');
    read();
});
$('#formProveedor').submit(function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    data = data.replace('%40', '@');
    if (editar) {
        data += "&script=proveedores&function=update";
        update(data);
    } else {
        data += "&script=proveedores&function=create";
        create(data);
    }
});
$('#nuevo').click(function () {
    editar = false;
    $('#proveedorTitle').text('Nuevo Proveedor');
    $("#formProveedor")[0].reset();
    $('#proveedor').modal('show');
});
function create(data) {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == '') {
                read();
                $('#mensaje').html('<i class="fas fa-check-double"></i>Registrado');
                $('#mensaje').css("color", "#17a2b8");
                $('#mensaje').css("border-bottom", "solid 5px #17a2b8");
                $('#mensaje').animateCss('bounceIn', function () {
                    $('#mensaje').animateCss('bounceOut', function () {
                        $('#mensaje').html('');
                        $('#mensaje').css("border-bottom", "none");
                    });
                });
                $("#formProveedor")[0].reset();
            }
        }
    });
}

function read() {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: "script=proveedores&function=read",
        success: function (r) {
            var template = '';
            r.forEach(r => {
                var data = r.id + '|&|' + r.proveedor + '|&|' + r.contacto + '|&|' + r.email + '|&|' + r.telefono + '|&|' + r.direccion + '|&|' + r.codigo_postal + '|&|' + r.colonia + '|&|' + r.municipio + '|&|' + r.estado + '|&|' + r.pais;
                template += `
                <div class="accordion" id="accordion">
                    <div class="card bg-transparent">
                        <div class="card-header" id="headingTwo">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse${r.id}" aria-expanded="false" aria-controls="collapseTwo">
                                ${r.proveedor}
                            </button>
                            <div class="float-right">
                                <button type="button" class="btn update" onclick="modalEditar('${data}')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn delete" onclick="confirmar('${r.id}','${r.proveedor}')"
                                    title="Eliminar">
                                    <i class="fas fa-backspace"></i>
                                </button>
                            </div>
                        </div>
                        <div id="collapse${r.id}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <fieldset disabled>
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <label for="mostrarID${r.id}">ID</label>
                                    <input type="text" class="form-control" id="mostrarID${r.id}" value="${r.id}">
                                    </div>
                                    <div class="form-group col-md-10">
                                    <label for="mostrarProveedor${r.id}">Proveedor</label>
                                    <input type="text" class="form-control" id="mostrarProveedor${r.id}" value="${r.proveedor}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                    <label for="mostrarContacto${r.id}">Contacto</label>
                                    <input type="text" class="form-control" id="mostrarContacto${r.id}" value="${r.contacto}">
                                    </div>
                                    <div class="form-group col-md-4">
                                    <label for="mostrarEmail${r.id}">Email</label>
                                    <input type="text" class="form-control" id="mostrarEmail${r.id}" value="${r.email}">
                                    </div>
                                    <div class="form-group col-md-4">
                                    <label for="mostrarTelefono${r.id}">Telefono</label>
                                    <input type="text" class="form-control" id="mostrarTelefono${r.id}" value="${r.telefono}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-10">
                                    <label for="mostrarDireccion${r.id}">Dirección</label>
                                    <input type="text" class="form-control" id="mostrarDireccion${r.id}" value="${r.direccion}">
                                    </div>
                                    <div class="form-group col-md-2">
                                    <label for="mostrarCodigoP${r.id}">Codigo Postal</label>
                                    <input type="text" class="form-control" id="mostrarCodigoP${r.id}" value="${r.codigo_postal}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                    <label for="mostrarColonia${r.id}">Colonia</label>
                                    <input type="text" class="form-control" id="mostrarColonia${r.id}" value="${r.colonia}">
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="mostrarMunicipio${r.id}">Municipio</label>
                                    <input type="text" class="form-control" id="mostrarMunicipio${r.id}" value="${r.municipio}">
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="mostrarEstado${r.id}">Estado</label>
                                    <input type="text" class="form-control" id="mostrarEstado${r.id}" value="${r.estado}">
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="mostrarPais${r.id}">Pais</label>
                                    <input type="text" class="form-control" id="mostrarPais${r.id}" value="${r.pais}">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        </div>
                    </div>
                </div>
                `;
            });
            $('#outputProveedores').html(template);
        },
        dataType: "json"
    });
}

function modalEditar(datos) {
    editar = true;
    var edit = datos.split("|&|");
    $('#id').val(edit[0]);
    $('#inputProveedor').val(edit[1]);
    $('#inputContacto').val(edit[2]);
    $('#inputEmail').val(edit[3]);
    $('#inputTelefono').val(edit[4]);
    $('#inputDireccion').val(edit[5]);
    $('#inputCodigoP').val(edit[6]);
    $('#inputColonia').val(edit[7]);
    $('#inputMunicipio').val(edit[8]);
    $('#inputEstado').val(edit[9]);
    $('#inputPais').val(edit[10]);
    $('#proveedorTitle').text('Editar Proveedor');
    $('#proveedor').modal('show');
}

function update(data) {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == '') {
                read();
                $('#proveedor').modal('hide');
            }
        }
    });
}

function confirmar(id, proveedor) {
    //  Mostrar nombre del proveedor
    document.getElementById('proveedorEliminar').innerHTML = proveedor;
    //  Mostrar modal
    $('#eliminarProveedor').modal('show');
    //  Asignacion a la variable global deleteID el id del registro
    deleteID = id;
}
//  Eliminacion de registro
function deleteProveedor() {
    //  Cadena que contiene los datos
    var cadena = "script=proveedores&function=delete&id=" + deleteID;
    //  Envio de datos por ajax al script php
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: cadena,
        success: function (r) {
            if (r == '') {
                read();
                $('#eliminarProveedor').modal('hide');
            }
        }
    });
}