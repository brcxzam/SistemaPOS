'user strict'
$(document).ready(function () {
    getData('total', '2018-10-00', '2018-10-30');
});

$('#form').submit(function (e) {
    e.preventDefault();
    let dates = $('#date').val();
    graficar(dates);
});

$('#tipo').change(function () {
    let tipo = $('#tipo').val();
    let dates = $('#date').val();
    if (dates == '') {
        getData(tipo, '2018-10-00', '2018-10-30');
    } else {
        graficar(dates);
    }
});

function graficar(dates) {
    dates = dates.split('<->');
    let type = $('#tipo').val();
    let start = dates[0];
    let end = dates[1];
    getData(type, start, end);
}

function getData(type, start, end) {
    let label = '';
    if (type == 'total') {
        label = 'Ingresos';
    } else {
        label = 'Cantidad de Ventas';
    }
    $.ajax({
        type: "POST",
        url: "php/grafica.php",
        data: "type=" + type + "&start=" + start + "&end=" + end,
        success: function (r) {
            let labels = [];
            let data = [];
            r.forEach(r => {
                labels.push(r.fecha);
                data.push(r.resultado);
            });
            ventas(label, labels, data);
        },
        dataType: "json"
    });
}

function ventas(type, labels, data) {
    var ctx = document.getElementById("graph").getContext("2d");
    Chart.defaults.global.defaultFontColor = 'white';
    Chart.defaults.global.defaultFontSize = 14;
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',
        // The data for our dataset
        data: {
            labels: labels,
            datasets: [{
                label: type,
                backgroundColor: '#17a2b8',
                borderColor: '#17a2b8',
                data: data,
                fill: false,

            }]
        },

        // Configuration options go here
        options: {
            title: {
                display: true,
                text: type
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        show: true,
                        color: "#6c757d",
                    }
                }],
                yAxes: [{
                    gridLines: {
                        show: true,
                        color: "#6c757d",
                    }
                }],
            }
        }
    });
}