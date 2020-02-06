<?php
    include "glavnaSesija.php";
    include "api/database.php";
    $db;
    if(!isset($db)){
        $db=new Database("matematika");
    }
    if(isset($_GET["metoda"])){
        if($_GET["metoda"]=="vrati iz kviza"){
            $db->ExecuteQuery("select p.*,kp.poeni as 'poeni'  from pitanje p inner join kviz_pitanje kp on(p.id=kp.pitanje) where kp.kviz=".$_GET["kviz"]);
        }
        if($_GET["metoda"]=="vrati koje nisu u kvizu"){
            $db->ExecuteQuery("select p.*  from pitanje p left join kviz_pitanje kp on(p.id=kp.pitanje) where kp.kviz is null or not kp.kviz=".$_GET["kviz"]);
        }
        $rez=$db->getResult();
        $response=array();
        if(!$rez){
            $response["status"]="greska";
            $response["error"]=$db->getError();
        }else{
            $response["status"]="ok";
            $response["data"]=array();
            while($red=$rez->fetch_object()){
                $response["data"][]=$red;
            }
        }
        echo json_encode($response);
    }
    if(isset($_POST["metoda"])){
        if($_POST["metoda"]=="dodajVezu"){
            if(!intval($_POST["kviz"]) ||!intval($_POST["pitanje"]) || !intval($_POST["brojPoena"]) || intval($_POST["brojPoena"])<1){
                echo "Broj primeraka mora biti pozitivan broj";
            }else{
                $db->ExecuteQuery("insert into kviz_pitanje(kviz,pitanje,poeni) values (".$_POST["kviz"].",".$_POST["pitanje"].",".$_POST["brojPoena"].")");
                if(!$db->getResult()){
                    echo $db->getError();
                }else{
                    echo "ok";
                }
            }
        }
        if($_POST["metoda"]=="obrisi"){
            if(!intval($_POST["id"])){
                echo "id nije broj";
                exit();
            }
            $db->ExecuteQuery("delete from kviz where id=".$_POST["id"]);
            if(!$db->getResult()){
                echo $db->getError();
            }else{
                echo "ok";
            }
        }
        if($_POST["metoda"]=="obrisiVezu"){
            if(!intval($_POST["kviz"]) ||!intval($_POST["pitanje"]) ){
                echo "greska";
            }
            $db->ExecuteQuery("delete from kviz_pitanje where kviz=".$_POST["kviz"]." and pitanje=".$_POST["pitanje"]);
            if(!$db->getResult()){
                echo $db->getError();
            }else{
                echo "ok";
            }
        }
        if($_POST["metoda"]=="dodaj"){
            $naziv=$_POST["naziv"];
            $db->ExecuteQuery("insert into kviz(naziv) values ('".$naziv."')");
            if(!$db->getResult()){
                echo $db->getError();
            }else{
                echo "ok";
            }
        }
        if($_POST["metoda"]=="izmeni"){
            $naziv=$_POST["naziv"];
            $db->ExecuteQuery("update kviz set naziv='".$naziv."' where id=".$_POST["id"]);
            if(!$db->getResult()){
                echo $db->getError();
            }else{
                echo "ok";
            }
        }
        
    }


?>