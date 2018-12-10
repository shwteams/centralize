var url = "composant/com_dashbord/controlerdashbord.php";
var datatable = "";
$('[data-toggle="tooltip"]').tooltip();
$(function () {
	haveSession();
    getStatistical();
    getProductionStatistical();

//----- Pie
    /* var dataBoni = 1500;
    var dataMali = 300;
    var dataBoniPotentiel = 540; */

    /**
     * Utilisation d'un callback
     * la fonction, le callback pie est appelé après lexécution du getpiechar
     */
    getPieChar(function (){
        pie();
    });

//----- End pie

});

function getPieChar(cb){
    $.get(url+"?task=getAmoutBoniMaliBoniPotentiel&str_SINISTRE_ID=",function(json, textStatus) {
        var obj = $.parseJSON(json);

        if (obj[0].code_statut == "1") {
            var results = obj[0].results;

            if (obj[0].results.length > 0) {
                $.each(results, function (i, value)
                {
                    $.session.set("dataBoni", results[i].mt_BONI);
                    $.session.set("dataMali", results[i].mt_MALI);
                    $.session.set("dataBoniPotentiel", results[i].mt_BONI_POTENTIEL);
                })
            }
        }
        cb();
    });
}
function pie() {

    var dataBoni = parseInt($.session.get("dataBoni"));
    var dataMali = parseInt($.session.get("dataMali"));
    var dataBoniPotentiel = parseInt($.session.get("dataBoniPotentiel"));

    Highcharts.chart('pie', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'Situation boni, mali et boni potentiel à ce jour'
        },
        subtitle: {
            text: 'Camembert'
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
            name: 'Montant en F CFA',
            data: [
                //['Boni', dataBoni],
                {
                    name: 'Boni',
                    y: dataBoni,
                    sliced: true,
                    selected: true
                },
                ['Mali', dataMali],
                ['Boni potentiel', dataBoniPotentiel]
            ]
        }]
    });
}
function getStatistical()
{
    $.get(url+"?task=getStatisticalSinistreTreat&str_SINISTRE_ID=",function(response) {

    });
    var datas = [];
    var datas2 = [];
    var data = [0,0,0,0,0,0,0,0,0,0,0,0];
    var data2 = [0,0,0,0,0,0,0,0,0,0,0,0];
    var fichier_stat = loadf('composant/com_dashbord/graph_sinistre_traite_per_month.txt');
    var fichier_stat2 = loadf('composant/com_dashbord/graph_sinistre_open_per_month.txt');
    var tab = fichier_stat.split('\r\n');
    var tab2 = fichier_stat2.split('\r\n');
    var taille = tab.length - 1;
    var taille2 = tab2.length - 1;
    $promo = 0;
    for(i=0 ; i < taille ; i++) {
        var d = tab[i].split(',');
        v = parseInt(d[1])-1;
        data[v] = parseInt(d[0]);
        $promo++;
    }
    $perme = 0;
    for(i=0 ; i < taille2 ; i++) {
        var d2 = tab2[i].split(',');
        v2 = parseInt(d2[1])-1;
        data2[v2] = parseInt(d2[0]);
        $perme++;
    }
    for(i=0; i<data.length; i++) {
        datas.push(data[i]);
    }
    for(i=0; i<data2.length; i++) {
        datas2.push(data2[i]);
    }
    //console.log(datas);

    // $('#statisticals').highcharts({
    Highcharts.chart('statisticals', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Evolution des sinistres'
        },
        xAxis: {
            categories: ['Yellow', 'Apples']
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
                'Jan',
                'Fev',
                'Mar',
                'Avr',
                'Mai',
                'Jui',
                'Jul',
                'Aou',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nombres de sinistres'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            type: 'column',
            name: 'Sinistre traité',
            data: datas,
        }, {
            type: 'column',
            name: 'Sinistre ouvert',
            data: datas2,
        }]
    });

    function loadf(f) {
        if(window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET",f,false);
        xmlhttp.send(null);
        if(xmlhttp.readyState == 4) {
            return(xmlhttp.responseText);
        }
        else {
            return(false);
        }
    }
}
function getProductionStatistical()
{
    $.get(url+"?task=getProductionStatistical",function(response) {

    });
    var datas = [];
    var datas2 = [];
    var data = [0,0,0,0,0,0,0,0,0,0,0,0];
    var data2 = [0,0,0,0,0,0,0,0,0,0,0,0];
    var fichier_stat = loadf('composant/com_dashbord/graph_sinistre_production_month.txt');
    var fichier_stat2 = loadf('composant/com_dashbord/graph_sinistre_encaissement_month.txt');
    var tab = fichier_stat.split('\r\n');
    var tab2 = fichier_stat2.split('\r\n');
    var taille = tab.length - 1;
    var taille2 = tab2.length - 1;
    $promo = 0;
    for(i=0 ; i < taille ; i++) {
        var d = tab[i].split(',');
        v = parseInt(d[1])-1;
        data[v] = parseInt(d[0]);
        $promo++;
    }
    $perme = 0;
    for(i=0 ; i < taille2 ; i++) {
        var d2 = tab2[i].split(',');
        v2 = parseInt(d2[1])-1;
        data2[v2] = parseInt(d2[0]);
        $perme++;
    }
    for(i=0; i<data.length; i++) {
        datas.push(data[i]);
    }
    for(i=0; i<data2.length; i++) {
        datas2.push(data2[i]);
    }
    //console.log(datas);

    // $('#statisticals').highcharts({
    Highcharts.chart('production', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Evolution des productions et encaissements'
        },
        xAxis: {
            categories: ['Yellow', 'Apples']
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
                'Jan',
                'Fev',
                'Mar',
                'Avr',
                'Mai',
                'Jui',
                'Jul',
                'Aou',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nombres de polices'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }/*,
            series: {
                colors: ['#303030', '#f0f']
            }*/
        },
        series: [{
            type: 'column',
            name: 'production',
            data: datas,
        }, {
            type: 'column',
            name: 'Encaissement',
            data: datas2,
        }]
    });

    function loadf(f) {
        if(window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET",f,false);
        xmlhttp.send(null);
        if(xmlhttp.readyState == 4) {
            return(xmlhttp.responseText);
        }
        else {
            return(false);
        }
    }
}
