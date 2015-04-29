<?php 
include("php/SoundAndMix.php");
session_start();

	$user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);

  	//$arr=$user->fetchJson();
 
  	//$arr=$user->fetchMixRand(3);
  	
  	$arr=$user->fetchBycate('cool');
  	
	
	//
	//var_dump($arr);
  	echo json_encode($arr);

?>