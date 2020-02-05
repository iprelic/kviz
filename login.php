<?php
    include "api/database.php";
    if(isset($_POST["login"])){
      $db=new Database("matematika");
      $user=$_POST["username"];
      $password=$_POST["password"];
      $db->ExecuteQuery("select k.*, u.naziv as 'naziv_uloge' from korisnik k inner join uloga u on (k.uloga=u.id) where username='".$user."' and sifra='".$password."'");
      $rez=$db->getResult();
      $row=$rez->fetch_object();
      if(isset($row)){      
        if(session_status()!==PHP_SESSION_ACTIVE)
          session_start();
        $_SESSION["korisnik"]=$row;
        header("Location:index.php");
      }else{
        echo "Korinsik ne postoji";
      }
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
    <title>Login</title>
</head>
<body>

<div class="about-block content content-center" id="about">
        <div class="container">
          <div class="row">
            <div class="col-md-3">
            </div>
          <div class="col-md-6">
            <h2><strong>Login</strong> forma</h2>
            <h4>Ulogujte se da biste nastavili da koristite aplikaciju</h4>
            <form method="POST" action="login.php">

              <label for="username">Username</label>
              <input type="text" placeholder="Unesite username" id="username" name="username" class="form-control">
              <label for="password">Password</label>
              <input type="password" placeholder="Unesite password" id="password" name="password" class="form-control">
              <label for="submit"></label>
              <input type="submit" value="Uloguj se" id="submit" name="login" class=" btn btn-primary margin-top-10">
            </form>
          </div>
          <div class="col-md-3">
          </div>
        </div>
        </div>
    </div>
    


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>