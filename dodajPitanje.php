<?php 
    include "glavnaSesija.php";

if(isset($_POST["dodaj"]) && $_POST["dodaj"]=="dodaj"){
    
    $url = 'http://localhost/kviz/api/pitanje';
            $pitanje='{
                "naziv":"'.$_POST["naziv"].'",
                "opis":"'.$_POST["opis"].'",
                "odgovor":"'.$_POST["odgovor"].'",
                "korisnik":'.$_SESSION["korisnik"]->id.'
            }';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $pitanje);
            $curl_odgovor = curl_exec($curl);
            curl_close($curl);
            echo $curl_odgovor;
}

?>