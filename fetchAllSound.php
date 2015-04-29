<?php 
include("php/SoundAndMix.php");
session_start();
if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
{
	$user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);

  	$arr=$user->fetchAllSounds();
  	// $arr['msg'] = 'success';	
  	// var_dump($arr);
  	//$array=array("allmixes"=>$arr);
  	echo json_encode($arr);
  	//var_dump($arr);
}else
{
	echo json_encode(array('msg' => "error"));
}
?>