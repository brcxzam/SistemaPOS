'use strict'
$(document).ready(function () {
	$("#barraNavegacion").load("components/navbaremp.html");
	$("#piePagina").load("components/footer.html");
	productos();
	readSale();
});
$("#codeProduct").submit(function (e) {
	e.preventDefault();
	var str = $("#codeProduct").serialize();
	str += "&script=venta&function=addProduct";
	addProduct(str);
});
$("#selectProduct").submit(function (e) {
	e.preventDefault();
	var str = $("#selectProduct").serialize();
	str += "&script=venta&function=addProduct";
	addProduct(str);
});
$("#cashSale").submit(function (e) {
	e.preventDefault();
	var str = $("#cashSale").serialize();
	str += "&script=venta&function=create";
	sell(str);
});
$("#confirmCancelar").click(function (e) {
	e.preventDefault();
	$("#modalCancelar").modal("show");
});
$("#cancelar").click(function () {
	deleteSale();
	$("#modalCancelar").modal("hide");
});
$("#close").click(function () {
	cerrarCaja();
});
function cerrarCaja() {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: "script=venta&function=closeCaja",
		success: function (r) {
			$('#modalCambio').modal('hide');
		}
	});
}
function productos() {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: "script=productos&function=read",
		success: function (r) {
			var template = "";
			r.forEach(r => {
				template += `
        		<option value="${r.id}">${r.producto}</option>
        		`;
			});
			$("#seleccionProducto").html(template);
		},
		dataType: "json"
	});
}

function readSale() {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: "script=venta&function=read",
		success: function (r) {
			var template = "";
			if (r.status == 'empty') {
				$("#outputProductos").html('<tr><td colspan="6">Esperando...</td></tr>');
				$("#total").text("Esperando...");
			} else {
				r.forEach(r => {
					template += `
					<tr>
						<td>${r.producto}</td>
						<td>${r.cantidad}</td>
						<td>${r.subtotal}</td>
						<td>${r.impuesto}</td>
						<td>${r.total}</td>
						<td>
							<div class="form-row">
								<div class="form-group">
									<button class="btn" onclick="restar('${r.id_producto}')">
										<i class="fas fa-minus"></i>
									</button>
								</div>
								<div class="form-group">
									<button class="btn eliminar" onclick="eliminar('${r.id_producto}')">
										<i class="fas fa-times"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
					`;

				});
				$("#outputProductos").html(template);
				$("#total").text("$" + r[0].v_total);
			}
		},
		dataType: "json"
	});
}

function addProduct(data) {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: data,
		success: function (r) {
			readSale();
		}
	});
}

function sell(data) {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: data,
		success: function (r) {
			if (r == 'insufficient') {
				mensaje('Insuficiente');
			} else if(r != 'non_existent') {
				readSale();
				r = JSON.parse(r);
				$('#modalCambio').modal('show');
				$('#cambio').text('$ '+r.cambio);
				$('#efectivo').val('');
			}
		}
	});
}

function deleteSale() {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: "script=venta&function=delete",
		success: function (r) {
			readSale();
		}
	});
}

function restar(id) {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: "script=venta&function=subsProduct&producto=" + id,
		success: function (r) {
			readSale();
		}
	});
}

function eliminar(id) {
	$.ajax({
		type: "POST",
		url: "php/main.php",
		data: "script=venta&function=deleteProduct&producto=" + id,
		success: function (r) {
			readSale();
		}
	});
}

function mensaje(mensaje) {
	$('#mensaje').html(mensaje);
	$('#mensaje').css("color", "#17a2b8");
	$('#mensaje').animateCss('bounceIn', function () {
		$('#mensaje').animateCss('bounceOut', function () {
			$('#mensaje').html('');
		});
	});
}