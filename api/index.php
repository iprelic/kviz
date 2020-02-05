<?php
require 'flight/Flight.php';
require 'jsonindent.php';
//registracija baze Database
Flight::register('db', 'Database', array('baza_biblioteka'));

Flight::route('/', function(){
	die("Izabereti neku od ruta...");
});
Flight::route('GET /kviz.json',function(){
	header("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->ExecuteQuery("select * from kviz");
	$niz =  [];
	if(!$db->getResult()){
		$niz['status']="greska";
		
	}else{
		$niz['status']="ok";
		$niz["kvizovi"]=[];
		while ($red = $db->getResult()->fetch_object())
		{
			array_push($niz["kvizovi"],$red);
		}
	}
	
	

	echo indent(json_encode($niz));
});
Flight::route('GET /kviz/@id/pitanja.json',function($id){
	header("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->ExecuteQuery("select p.* from pitanje p inner join kviz_pitanje k on(p.id=k.pitanje) where k.kviz=".$id);
	$niz =  [];
	if(!$db->getResult()){
		$niz['status']="greska";
		
	}else{
		$niz['status']="ok";
		$niz["pitanja"]=[];
		while ($red = $db->getResult()->fetch_object())
		{
			array_push($niz["pitanja"],$red);
		}
	}
	
	

	echo indent(json_encode($niz));
});
Flight::start();
?>
