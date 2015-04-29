<?php
include("php/SoundAndMix.php");
session_start();
if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
{
    $user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);
    
    print_r($_FILES['myfile']);
    if($user->UploadSoundPiece($_FILES['myfile'],'sound3',$_SESSION['uid']))
    {
        echo "true";
    }else
    {
        echo $user->getErrorMessage();
    }
}

?>
<html>
    <head>
        <title></title>
        <meta content-type="text/html"  charset="utf-8" />
    </head>
    <body style="text-align:center">
        <div name="upload-status"></div>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
            <input type="file" name="myfile" />
            <input type="submit" name="sub" value="upload"/>
        </form>
    </body>
</html>