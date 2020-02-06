<?php
    include "glavnaSesija.php";
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
    <title>Izmena pitanja</title>
</head>

<body>
    <?php
    include "header.php";
    ?>
    <div id="omotac">
        <div id="pitanja">
            <table class="table display">
                <tr>
                    <th scope="col">Naslov</th>
                    <th scope="col">Opis</th>
                    <th scope="col">Odgovor</th>
                    <th scope="col">Korisnik</th>
                </tr>
                <tbody id="pitanjaBody">

                </tbody>
            </table>

        </div>
        <div id="formaZaIzmenuPitanja">
            <form class="mt-5">
                <div class="form-group">
                    <input type="text" class="form-control" id="naslov" placeholder="Naslov">
                </div>
                <div class="form-group">
                    <textarea class="form-control" id="tekst" rows="3" placeholder="Tekst zadatka"></textarea>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" type="text" id="odgovor" placeholder="Odgovor">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" type="text" id="korisnik" disabled="true"
                        placeholder="Korisnik">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" id="izmeniPitanje" disabled="true">Izmeni</button>
                </div>
                <input type="text" hidden="true" id="idPitanja" />
            </form>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {

            napuniTabelu();

            $("#izmeniPitanje").click(function (e) {
                e.preventDefault();
                let naslov = $("#naslov").val();
                let tekst = $("#tekst").val();
                let odgovor = $("#odgovor").val();
                let id = $("#idPitanja").val();
                console.log(id);
                $.post("pitanjaServer.php", { akcija: "izmeni", naslov: naslov, tekst: tekst, odgovor: odgovor, id: id }, function (data) {
                    if (data !== "ok")
                        alert(data);
                    napuniTabelu();
                })
            })

        })
        function napuniTabelu() {
            $.getJSON("pitanjaServer.php", { akcija: "vratiSve" }, function (data) {
                if (data.status !== "ok") {
                    alert(data.error);
                    return;
                }
                $("#pitanjaBody").html("");
                for (let pitanje of data.pitanja) {
                    $("#pitanjaBody").append(`
                        <tr>
                            <td>${pitanje.naslov}</td>
                            <td>${pitanje.tekst}</td>
                            <td>${pitanje.odgovor}</td>
                            <td>${pitanje.username}</td>
                            <td> <button class="dugmeUnutarTabele" id=${pitanje.id}-IzmeniPitanje >Izmeni</button> </td>
                            <td> <button class="dugmeUnutarTabele" id=${pitanje.id}-ObrisiPitanje  >Obrisi</button> </td>
                        </tr>
                    `);

                    $(`#${pitanje.id}-IzmeniPitanje`).click(function () {
                        napuniZaIzmenu(pitanje);
                    })
                    $(`#${pitanje.id}-ObrisiPitanje`).click(function () {
                        console.log("brisanje");
                        $.post("pitanjaServer.php", { akcija: "obrisi", id: pitanje.id }, function (data) {
                            if (data !== "ok") {
                                alert(data);
                            }
                            napuniTabelu();
                        })
                    })

                }

            })

        }
        function napuniZaIzmenu(pitanje) {
            $("#idPitanja").val(pitanje.id);
            $("#izmeniPitanje").attr("disabled", false);
            $("#naslov").val(pitanje.naslov);
            $("#tekst").val(pitanje.tekst);
            $("#odgovor").val(pitanje.odgovor);
            $("#korisnik").val(pitanje.username);
        }
    </script>

</body>

</html>