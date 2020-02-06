<?php
session_start();
unset($_SESSION["korisnik"]);
session_destroy();
header("Location:login.php");

?>