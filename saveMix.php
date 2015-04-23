<?php 
include("php/SoundAndMix.php");
session_start();
if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
{
	$user=new SoundAndMixes($_SESSION['uid'],$_SESSION['username'],$_SESSION['fullname']);
	$json1='{
  "mixes": [
    {
      "name": "Evening in the Forest",
      "loop": "-1",
      "volume": 0.5,
      "pan": 0
    },
    {
      "name": "Thunderstorm",
      "loop": "-1",
      "volume": 0.5,
      "pan": 0
    },
    {
      "name": "Storm Rain",
      "loop": "-1",
      "volume": 0.5,
      "pan": 0
    }
  ]
}';
$json2='{
  "mixes": [
    {
      "name": "Evening in the Forest1",
      "loop": "-1",
      "volume": 0.5,
      "pan": 0
    },
    {
      "name": "Thunderstorm1",
      "loop": "-1",
      "volume": 0.5,
      "pan": 0
    },
    {
      "name": "Storm Rain1",
      "loop": "-1",
      "volume": 0.5,
      "pan": 0
    }
  ]
}';
	$user->UploadSoundMixes($json1,'storm','cool',0);
	$user->UploadSoundMixes($json2,'storm1','cool1',0);
  //$user->fetchJson();	
}else
{
	echo "login first, I get user information from session!";
}
?>