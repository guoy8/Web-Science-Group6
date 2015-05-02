<?php
include("php/SoundAndMix.php");
session_start();
if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
{
    $user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);
    
    print_r($_FILES['myfile']);
    $date = new DateTime('2000-01-01');
    $filename = $date->format('Y-m-d');
    if (isset($_POST['soundTitle'])) {
        $filename = $_POST['soundTitle'];
    }
    print_r($filename);
    if($user->UploadSoundPiece($_FILES['myfile'],$filename,$_SESSION['uid']))
    {
        echo "true";
    }else
    {
        echo $user->getErrorMessage();
    }
}

?>