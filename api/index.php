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
		$niz['greska']=$db->getError();
		
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
		$niz['greska']=$db->getError();
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
Flight::route('POST /pitanje',function(){
	header("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci = file_get_contents('php://input');
	$niz = json_decode($podaci,true);
	if(!isset($niz["naziv"]) || !isset($niz["opis"]) || !isset($niz["odgovor"])){
		echo '{"status":"greska", "greska":"Nisu poslati svi podaci"}';
		return;
	}
	if(!validnoPitanje($niz["naziv"],$niz["opis"],$niz["odgovor"])){
		echo '{"status":"greska", "greska":"Nisu validni svi podaci"}'; ;
		return;
	}
	$db->ExecuteQuery("insert into pitanje(naslov,tekst,odgovor,korisnik) values ('".$niz["naziv"]."','".$niz["opis"]."','".$niz["odgovor"]."',".$niz["korisnik"].")");
	if(!$db->getResult()){
		echo '{"status":"greska", "greska":"'.$db->getError().'"}';
	}else{
		echo '{"status":"ok"}';
	}
});

Flight::start();

function validnoPitanje($naziv,$opis,$odgovor){
	$naziv=trim($naziv);
	$opis=trim($opis);
	$odgovor=trim($odgovor);
	return strlen($naziv)>4 && strlen($opis)>4 && strlen($odgovor)>0;
}
?>
