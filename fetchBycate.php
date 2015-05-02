<?php 
include("php/SoundAndMix.php");
session_start();

  if (isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname'])) {
    $user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);
  } else {
    $user=new SoundAndMixes('', '', '');
  }
  	if (isset($_POST['category'])) {
      $arr=$user->fetchBycate($_POST['category']);
    } else {
      $arr=$user->fetchBycate('cool');
    }

	//
	//var_dump($arr);
  	echo json_encode($arr);

?>