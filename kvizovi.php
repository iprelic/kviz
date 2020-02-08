<?php include "glavnaSesija.php"; ?>
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
    <title>Kvizovi</title>
</head>

<body>
    <?php include "header.php"; ?>
    <div class="container">
        <div class="kvizovi">

            <div id="kvizoviKontejner">


            </div>
            <input type="text" hidden="true" id="trenutniId" />
            <style type="text/css">
                .red {
                    margin-top: 10%;
                    /* border-radius: 10px;
                    border-style: solid;
                    border-width: 0.5px;
                    margin: 10px auto;
                    padding: 10px;
                    width: 33%; */
                }
            </style>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close form-control" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="sadrzaj" class="modal-body">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            napuniKvizove();
            $('#exampleModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let kviz = button.data("kviz");
                let naziv = button.data("naziv");
                $("#exampleModalLabel").text(naziv);
                $.getJSON(`http://localhost/kviz/api/kviz/${kviz}/pitanja.json`, function (data) {
                    if (data.status !== "ok") {
                        alert(data.status);
                        return;
                    }
                    $("#sadrzaj").html(``);
                    for (let pitanje of data.pitanja) {
                        $("#sadrzaj").append(`<div class="red ">
                            <p  >${pitanje.tekst || ""}</p>
                            <br>
                            <input class='form-control' id="${pitanje.id}odgovor" ></input>
                            <br>
                            <button class='form-control' style='background-color:blue; color:white;' id="${pitanje.id}odgovori"> Odgovori</button>
                            <br>
                            <button class='form-control' style='background-color:green; color:white;' id="${pitanje.id}tacanOdgovorDugme">Vidi tacan odgovor</button>
                            <br>
                            <label class='form-control' id="${pitanje.id}tacanOdgovor" hidden="true" />
                        <div>`);
                        $(`#${pitanje.id}odgovori`).click(proveriOdgovor(pitanje));
                        $(`#${pitanje.id}tacanOdgovorDugme`).click(function () {
                            $(`#${pitanje.id}tacanOdgovor`).html(pitanje.odgovor);
                            $(`#${pitanje.id}tacanOdgovor`).attr("hidden", false);
                            $(`#${pitanje.id}odgovori`).attr("disabled", true);
                        });
                    }
                })
            })
        })

        function napuniKvizove() {
            $.getJSON("http://localhost/kviz/api/kvizSaPitanjima.json", function (data) {
                if (data.status !== "ok") {
                    alert(data.status);
                    return;
                }
                let red = 1;
                let kolona = 0;
                $("#kvizoviKontejner").html(`<div class="row red" id="Row-${red}" ></div>`);
                for (let kviz of data.kvizovi) {
                    if (kolona > 2) {
                        kolona = 0;
                        red++;
                        $("#kvizoviKontejner").append(`<div class="row red" data-toggle="modal" ></div>`);
                    }
                    $(`#Row-${red}`).append(`<div class="col-3 kvizDiv">
                 <h4 >${kviz.naziv}</h4>
                <br>
                
                     <button class="btn btn-primary"  id="${kviz.id}pokreni" data-toggle="modal"
                         data-target="#exampleModal" data-backdrop="false" data-kviz="${kviz.id}" data-naziv="${kviz.naziv}">Pokreni</button>
            </div>`);
                    kolona++;

                }
            })
        }

        function proveriOdgovor(pitanje) {
            return function () {
                let odg = $(`#${pitanje.id}odgovor`).val();
                if (odg === pitanje.odgovor) {

                    $(`#${pitanje.id}odgovor`).css("background-color", "green");
                    $.post("odgovorNaPitanje.php", { metoda: "povecaj", pitanje: pitanje.id }, function (data) {
                        console.log(data);
                    });
                } else {
                    $(`#${pitanje.id}odgovor`).css("background-color", "red");
                    $.post("odgovorNaPitanje.php", { metoda: "smanji", pitanje: pitanje.id })
                }
            }
        }


    </script>

</body>

</html>