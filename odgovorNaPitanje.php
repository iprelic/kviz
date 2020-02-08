<?php
    include "api/database.php";
    $db;
    if(!isset($db)){
        $db=new Database("matematika");
    }
    if(isset($_POST["metoda"])){
        $db->ExecuteQuery("select * from pitanje where id=".$_POST["pitanje"]);
        $pitanje=$db->getResult()->fetch_object();
        echo json_encode($pitanje);
        if($_POST["metoda"]=="povecaj"){
            $db->ExecuteQuery("update pitanje set pogodili=".(intval($pitanje->pogodili)+1)." where id=".$_POST["pitanje"]);
        }else{
            if($_POST["metoda"]=="smanji"){
                $db->ExecuteQuery("update pitanje set promasili=".(intval($pitanje->promasili)+1)." where id=".$_POST["pitanje"]);
            }
        }
    }

?>