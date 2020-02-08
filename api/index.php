<?php
require 'flight/Flight.php';
require 'jsonindent.php';
//registracija baze Database
Flight::register('db', 'Database', array('matematika'));

Flight::route('/', function(){
	die("Izabereti neku od ruta...");
	
});
Flight::route('GET /kviz.json',function(){
	header("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->ExecuteQuery("select * from kviz ");
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
Flight::route('GET /kvizSaPitanjima.json',function(){
	header("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->ExecuteQuery("select distinct k.* from kviz k inner join kviz_pitanje kp on(k.id=kp.kviz) ");
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
Flight::route('GET /kviz.xml',function(){
	
	header("Content-Type: application/xml");
	$db = Flight::db();
	
	$db->ExecuteQuery("select * from kviz");
	$dom = new DomDocument('1.0','utf-8');
	if(!$db->getResult()){
		$greska = $dom->appendChild($dom->createElement('greska'));
	}else{
		$kvizovi = $dom->appendChild($dom->createElement('kvizovi'));
		while ($red = $db->getResult()->fetch_object()){
			$kviz = $kvizovi->appendChild($dom->createElement('kviz'));
			$id = $kviz->appendChild($dom->createElement('id'));
			
			$id->appendChild($dom->createTextNode($red->id));
			$naziv = $kviz->appendChild($dom->createElement('naziv'));
			
			$naziv->appendChild($dom->createTextNode($red->naziv));
			
		}
		
	}
	$xml_string = $dom->saveXML(); 
		echo $xml_string;
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
Flight::route('PUT /pitanje',function(){
	header("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci = file_get_contents('php://input');
	$niz = json_decode($podaci,true);
	if(!isset($niz["naziv"]) || !isset($niz["opis"]) || !isset($niz["odgovor"]) || !isset($niz["id"])){
		echo '{"status":"greska", "greska":"Nisu poslati svi podaci"}';
		return;
	}
	if(!validnoPitanje($niz["naziv"],$niz["opis"],$niz["odgovor"])){
		echo '{"status":"greska", "greska":"Nisu validni svi podaci"}'; ;
		return;
	}
	$db->ExecuteQuery("update pitanje set naslov='".$niz["naziv"]."',tekst='".$niz["opis"]."',odgovor='".$niz["odgovor"]."' where id=".$niz["id"]);
	if(!$db->getResult()){
		echo '{"status":"greska", "greska":"'.$db->getError().'"}';
	}else{
		echo '{"status":"ok"}';
	}
});
Flight::route("delete /pitanje",function(){
	header("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci = file_get_contents('php://input');
	$niz = json_decode($podaci,true);
	if(!isset($niz["id"]) || !intval($niz["id"])){
		echo '{"status":"greska", "greska":"Id nije dobar"}';
		return;
	}
	$db->ExecuteQuery("delete from pitanje where id=".$niz["id"]);
	if(!$db->getResult()){
		echo '{"status":"greska", "greska":"'.$db->getError().'"}';
	}else{
		echo '{"status":"ok"}';
	}
});




function validnoPitanje($naziv,$opis,$odgovor){
	$naziv=trim($naziv);     
	$opis=trim($opis);
	$odgovor=trim($odgovor);
	return strlen($naziv)>4 && strlen($opis)>4 && strlen($odgovor)>0;
}
Flight::start();


?>
