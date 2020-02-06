<?php
 include "glavnaSesija.php";
 include "api/database.php";
 if(isset($_GET["akcija"])){
     if($_GET["akcija"]=="vratiSve"){
        $db=new Database("matematika");
        $upit=($_SESSION["korisnik"]->naziv_uloge=="admin")?"select p.*, k.username as 'username' from pitanje p inner join korisnik k on(p.korisnik=k.id)": "select p.*, k.username as 'username' from pitanje p left join korisnik k on(p.korisnik=k.id) where korisnik=".$_SESSION["korisnik"]->id;
        $db->ExecuteQuery($upit);
        $rez=$db->getResult();
        $response=array();
        if(!$rez){
            $response["status"]="greska";
            $response["error"]=$db->getError();
        }else{
            $response["status"]="ok";
            $response["pitanja"]=array();
            while($red=$rez->fetch_object()){
                $response["pitanja"][]=$red;
            }
        }
        echo json_encode($response);
     }
 }
 if(isset($_POST["akcija"])){
     if(!intval($_POST["id"])){
         echo "Id mora biti broj";
         exit;
     }
     $db=new Database("matematika");
     $query="";
     if($_POST["akcija"]="izmeni" && isset($_POST["naslov"])&& isset($_POST["tekst"])&& isset($_POST["odgovor"])){
        $query="update pitanje set naslov='".$_POST["naslov"]."',tekst='".$_POST["tekst"]."',odgovor='".$_POST["odgovor"]."' where id=".$_POST["id"];
     }else{
         if($_POST["akcija"]="obrisi"){
            $query="delete from pitanje where id=".$_POST["id"];
         }
     }
     $db->ExecuteQuery($query);
     $rez=$db->getResult();
     echo ($rez)?"ok":$db->getError();
 }

?>