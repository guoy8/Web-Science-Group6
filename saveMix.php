<?php 
include("php/SoundAndMix.php");
session_start();
if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
{
	$user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);

	$s = $user->UploadSoundMixes($_POST['mixes'],$_POST['title'],$_POST['categories'],$_POST['share']);
  //$user->fetchJson();	
  echo json_encode(array('msg' => "saved"));
} else
{
  echo json_encode(array('msg' => "error"));
}
?>