<?php 
include("php/SoundAndMix.php");
session_start();
if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
{
	$user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);
  $arr=$user->fetchJson();	
  //var_dump($arr);
  //$array=array("allmixes"=>$arr);
  echo json_encode($arr);
}else
{
	echo "login first, I get user information from session!";
}
?>