<?php 
session_start();
include("php/SoundAndMix.php");

if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
{
	$user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);

  	$arr=$user->fetchJson();
	// $arr=$user->fetchMixRand(3);
	//$arr=$user->fetchBycate('cool');
	// var_dump($arr);
  	echo json_encode($arr);
}else
{
	echo json_encode(array('msg' => "error"));
}
?>