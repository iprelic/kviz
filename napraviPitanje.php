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
    <title>Napravi svoje pitanje</title>
</head>

<body>
    <?php include "header.php"; ?>

    <form class="mt-5">
        <div class="form-group">
            <input type="text" class="form-control" id="naziv" placeholder="Naziv">
        </div>
        <div class="form-group">
            <textarea class="form-control" id="opis" rows="3" placeholder="Opis"></textarea>
        </div>
        <div class="form-group">
            <input class="form-control" type="text" id="odgovor" placeholder="Tacan odgovor">
        </div>


        <div class="form-group">
            <button class="btn btn-primary" id="dodajPitanje">Dodaj pitanje</button>
        </div>
    </form>
</body>

</html>