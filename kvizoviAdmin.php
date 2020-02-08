<?php
    include "glavnaSesija.php";
    include "api/database.php";
    if($_SESSION["korisnik"]->naziv_uloge!="admin"){
        header("location:index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" rel=" stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Izmena kvizova</title>
</head>

<body>
    <?php
    include "header.php";
    ?>
    <div class='container' style='background-color: white;'>
        <div id="kvizoviWrapper">
            <label id="izabraniKviz" hidden=true></label>
            <table id="kvizoviTabela" class="table">
                <tr>
                    <th scope="col">Rb</th>
                    <th scope="col">Naziv</th>
                </tr>
                <tbody id="kvizovi">

                </tbody>

            </table>
        </div>
        <div id="pitanjaWrapper" hidden="true">

            <table id="pitanja" class="table" hidden="true">
                <tr>
                    <th scope="col">Rb</th>
                    <th scope="col">Naslov</th>
                </tr>
                <tbody id="pitanjaBody"></tbody>
            </table>
            <div id="dodavanjeNoveVeze" hidden=true>
                <br>
                <select id="komboSaPitanjima" class="form-control"></select>
                <br>
                <button id="dodajVezu" class="form-control" style="background-color: grey; color: white;">Dodaj pitanje
                    u
                    kviz</button>
            </div>
            <br><br>
        </div>
        <div id="noviKviz">
            <h1>Dodaj kviz</h1>
            <form class="mt-5">
                <div class="form-group">
                    <input type="text" class="form-control" id="nazivkviza" placeholder="Naziv">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary form-control" id="dodajKviz">Dodaj kviz</button>
                </div>
            </form>

        </div>
        <div id="chart_div"></div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="canvasjs.min.js"></script>
    <script type="text/javascript" src="jquery.canvasjs.min.js"></script>
    <script>
        $(document).ready(function () {
            napuniKvizove();
            nacrtajGrafik();
            $("#dodajVezu").click(dodajVezuKlik);
            $("#dodajKviz").click(function (e) {
                e.preventDefault();
                $.post("kvizoviServer.php", { metoda: "dodaj", naziv: $("#nazivkviza").val() }, function (data) {
                    if (data !== "ok") {
                        alert(data);
                    }
                    napuniKvizove();
                })
            })
        })
        function napuniKvizove() {
            $.getJSON("http://localhost/kviz/api/kviz.json", function (data) {
                if (data.status !== "ok") {
                    alert(data.error);
                    return;
                }
                $("#kvizovi").html("");
                let i = 0;
                for (let kviz of data.kvizovi) {
                    $("#kvizovi").append(`<tr>
                        <th>${++i}</th>
                        <td contentEditable=true id="${kviz.id}Naziv">${kviz.naziv}</td>
                        <td>
                        <div class='row'>
                            <div class='col-6'>
                                <button class='form-control' onClick="prikaziPitanja(${kviz.id})">Vidi sva pitanja</button>
                            </div>
                            <div class='col-3'>
                                <button class='form-control' onClick="izmeniKviz(${kviz.id})">Izmeni</button>
                            </div>
                            <div class='col-3'>
                                <button class='form-control' onClick="obrisiKviz(${kviz.id})">Obrisi</button>
                            </div>
                        </div>
                            
                            
                            
                        </td>
                    </tr>`)
                }

            })
        }
        function izmeniKviz(id) {
            let naziv = $(`#${id}Naziv`).text();
            $.post("kvizoviServer.php", {
                metoda: "izmeni",
                id: id,
                naziv: naziv,

            }, function (data) {
                if (data !== "ok") {
                    alert(data);
                }
                napuniKvizove();
            })
        }
        function obrisiKviz(id) {
            $.post("kvizoviServer.php", { metoda: "obrisi", id: id }, function (data) {
                if (data !== "ok") {
                    alert(data);
                }
                napuniKvizove();
            })
        }
        function prikaziPitanja(id) {
            $.getJSON("kvizoviServer.php", { metoda: "vrati iz kviza", kviz: id }, function (data) {

                if (data.status !== "ok") {
                    alert(data.status);
                    return;
                }

                let i = 0;
                $("#pitanja").attr("hidden", false);
                $("#pitanjaWrapper").attr("hidden", false);
                $("#pitanjaBody").html("");
                for (let pitanje of data.data) {
                    $("#pitanjaBody").append(`
                        <tr>
                            <td>${++i}.</td>
                            <td>${pitanje.naslov}</td>
                            <td>
                                <button class='form-control' onClick="obrisiVezu(${id},${pitanje.id})"> Obrisi</button>
                            </td>
                        </tr>
                    `);
                }
                $("#izabraniKviz").val(id);
                napuniKomboSaPitanjima(id);

            })
        }
        function napuniKomboSaPitanjima(kviz) {
            $.getJSON("kvizoviServer.php", { metoda: "vrati koje nisu u kvizu", kviz: kviz }, function (data) {
                console.log(data);
                if (data.status !== "ok") {
                    alert(data.status);
                    return;
                }
                $("#dodavanjeNoveVeze").attr("hidden", false);
                $("#komboSaPitanjima").html("");
                for (let pitanje of data.data) {
                    $("#komboSaPitanjima").append(`<option value ="${pitanje.id}" >${pitanje.naslov}</option>`);
                }
            })
        }
        function dodajVezuKlik(e) {
            e.preventDefault();
            $.post("kvizoviServer.php", {
                metoda: "dodajVezu",
                kviz: $("#izabraniKviz").val(),
                pitanje: $("#komboSaPitanjima").val(),
            }, function (data) {
                if (data !== "ok") {
                    alert(data);
                }
                prikaziPitanja($("#izabraniKviz").val());
                nacrtajGrafik();
            })
        }
        function obrisiVezu(kviz, pitanje) {
            $.post("kvizoviServer.php", {
                metoda: "obrisiVezu",
                kviz: kviz,
                pitanje: pitanje
            }, function (data) {
                if (data !== "ok") {
                    alert(data);
                }
                prikaziPitanja($("#izabraniKviz").val());
                nacrtajGrafik();
            })
        }

        function nacrtajGrafik() {
            $.getJSON("kvizoviServer.php", { metoda: "vrati sa prosecima" }, function (data) {
                if (data.status !== "ok") {
                    alert(data.error);
                    return;
                }
                $("#chart_div").html("");
                let dataPoints = [];
                for (let kviz of data.data) {

                    dataPoints.push({
                        y: parseInt(kviz.prosek),
                        label: kviz.prosek + "%",
                        indexLabel: kviz.naziv
                    })
                }
                let options = {
                    animationEnabled: true,
                    title: {
                        text: "Proseci tacnih odgovora po kvizovima",
                        fontColor: "Peru"
                    },
                    axisY: {
                        tickThickness: 0,
                        lineThickness: 0,
                        valueFormatString: " ",
                        gridThickness: 0
                    },
                    axisX: {
                        tickThickness: 0,
                        lineThickness: 0,
                        labelFontSize: 18,
                        labelFontColor: "Peru"
                    },
                    data: [{
                        indexLabelFontSize: 26,
                        toolTipContent: "<span style=\"color:#62C9C3\">{indexLabel}:</span> <span style=\"color:#CD853F\"><strong>{y}</strong></span>",
                        indexLabelPlacement: "inside",
                        indexLabelFontColor: "white",
                        indexLabelFontWeight: 600,
                        indexLabelFontFamily: "Verdana",
                        color: "#62C9C3",
                        type: "bar",
                        dataPoints: dataPoints
                    }]
                };

                $("#chart_div").CanvasJSChart(options);
            })

        }
    </script>



</body>

</html>