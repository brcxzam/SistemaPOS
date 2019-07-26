'user strict'
$(document).ready(function () {
    //  Cargar barra de navegación
    $('#barraNavegacion').load('components/navbar.html');
    //  Cargar pie de pagina
    $('#piePagina').load('components/footer.html');
    //  Cargar ventas
    readSale();
});

function readSale() {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: "script=ventas&function=read",
        success: function (r) {
            var template = '';
            var v = eliminarObjetosDuplicados(r, 'id');
            v.forEach(v => {
                template += `
                <tr>
                    <th>${v.email}</th>
                    <th>${v.fecha}</th>
                    <th>${v.total}</th>
                    <th>${v.efectivo}</th>
                    <th>${v.cambio}</th>
                    <th>
                        <button type="button" class="btn"  data-toggle="collapse" data-target="#collapse${v.id}" aria-expanded="false" aria-controls="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </th>
                </tr>
                <tr>
                    <th colspan="7" class="p-0">
                        <div class="collapse" id="collapse${v.id}">
                            <div class="card card-body bg-transparent">
                                <table class="table table-sm bg-transparent">
                                <thead>
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Impuesto</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                <tbody id="outputVentaDetallada${v.id}">
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </th>
                </tr>
                `;
            });
            $('#outputVentas').html(template);
            v.forEach(v => {
                template = '';
                r.forEach(r => {
                    if (v.id == r.id) {
                        template += `
                        <tr>
                            <th>${r.producto}</th>
                            <th>${r.cantidad}</th>
                            <th>${r.subtotal}</th>
                            <th>${r.impuesto}</th>
                            <th>${r.total}</th>
                        </tr>
                        `;
                    }
                });
                $('#outputVentaDetallada' + v.id).html(template);
            });
        },
        dataType: "json"
    });
}

function eliminarObjetosDuplicados(arr, prop) {
    var nuevoArray = [];
    var lookup = {};

    for (var i in arr) {
        lookup[arr[i][prop]] = arr[i];
    }

    for (i in lookup) {
        nuevoArray.push(lookup[i]);
    }
    return nuevoArray;
}