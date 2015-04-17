<?php 
include('php/DB.php');
class SoundAndMixes
{
	protected $username;
	protected $uid;
	protected $fullname;
	protected $soundLocations;
	protected $connection;
	protected $erroMessage;
	protected $allowTypes=['mp3','ogg'];
	const USERTABLENAME="users";
	const MIXTABLENAME="mixes";
	const MIXOWNERTABLENAME="mixesowner";
	const MAXSIZE=1000000;




	public function __construct($userid,$username,$fullname)
	{
		$this->connection=DB::getInstance();
		$UserInfo=$this->connection->SearchForRow(
			self::USERTABLENAME,'*',['id','username','fullname'],[$userid,$username,$fullname]);
		if(count($UserInfo)==1)
		{
			$this->username=$UserInfo[0]['username'];
			$this->uid=$UserInfo[0]['id'];
			$this->fullname=$UserInfo[0]['fullname'];
		}else
		{
			echo "illegal usage!";
		}

	}

	public function UploadSoundMixes($JsonString,$name,$category,$public)
	{
		$JsonArray=json_decode($JsonString);
		/*if(is_string($JsonString))
		{
			$this->connection->InsertRow(self::MIXTABLENAME,
				['name','categories','public','mix'],[$JsonArray['name'],$JsonArray['categories'],
				$JsonArray['private'],$JsonString]);
		}*/
		if(is_string($JsonString))
		{
			$mid=$this->connection->InsertRow(self::MIXTABLENAME,
				['name','categories','public','mix'],
				[$name,$category,$public,$JsonString]);
			$this->connection->InsertRow(self::MIXOWNERTABLENAME,
				['uid','mid'],
				[$this->uid,$mid]);
		}
		
		
	}

	public function fetchJson()
	{
		$JsonArray=$this->connection->SearchForRow(self::MIXOWNERTABLENAME,'*',['uid'],[$this->uid]);
		//print_r($JsonArray);
		//Array ( [0] => Array ( [id] => 1 [uid] => 2 [mid] => 1 ) )
		$Allmixes=[];
		for($i=0;$i<count($JsonArray);$i++)
		{
			$mix=$this->connection->SearchForRow(self::MIXTABLENAME,'*',['id'],[$JsonArray[$i]['mid']]);
			array_push($Allmixes,$mix);

		}
		//var_dump($Allmixes[0][0]['mix']);
		//echo json_encode($Allmixes[0][0]['mix']);
		$allMix=[];
		for($i=0;$i<count($Allmixes);$i++)
		{
			array_push($allMix, json_decode($Allmixes[$i][0]['mix']));
		}
		//echo json_encode($allMix);
		return $allMix;
	}	

	public function UploadSoundPiece($file)
	{
		$filename=$file['name'];
		$type=$file['type'];
		$path=$file['tmp_name'];
		$error=$file['error'];
		$size=$file['size'];
		if($error>0)
		{
			if($error==1)
				$erroMessage="The uploaded file exceeds the upload_max_filesize";
			else if($error==2)
				$erroMessage="The uploaded file exceeds the MAX_FILE_SIZE";
			else if($error==3)
				$erroMessage="The uploaded file was only partially uploaded";
			else if($error==4)
				$erroMessage="No file was uploaded";
			else if($error==6)
				$erroMessage="Missing a temporary folder";
			else if($error==7)
				$erroMessage="Failed to write file to disk";

			return false;
		}else if(!self::checkSize($size))
		{
			return false;
		}else if(!self::checkType($filename))
		{
			return false;
		}

	}

	public function getErrorMessage()
	{
		return $this->erroMessage;
	}

	//HELPER FUNCTIONS
	private static function checkSize($size)
	{
		if($size>self::MAXSIZE)
		{
			$this->erroMessage="Upload File size limit!";
			return false;
		}else
		{
			return true;
		}
	}

	private static function checkType($type)
	{
		$nameArr=explode(".",$type);
		$type=$nameArr[count($nameArr)-1];
		for($i=0;$i<count($this->allowTypes);$i++)
		{
			if($type==$this->allowTypes[$i])
			{
				return true;
			}
		}
		$this->erroMessage="Types not allowed!";
		return false;
	}

	private static function generateRandFilename($filename)
	{
		list($name,$type) = explode(".",$fileName);
		$str="QWERTYUIOPASDFGHJKLZXCVBNMzxcvbnmasdfghjklqwertyuiop1234567890";
		$str=str_shuffle($str);
		$salt=substr($str,0,4);
		$newname=str_shuffle($name).$salt;
		return $newname.".".$type;
	}


}
/*$json='{"mixes": [ 
{
		"sid": 1,
		"volume": 2,
		"loop": 3,
		"pan": "left",
		"delay": 2
	},
	{
		"sid": 1,
		"volume": 2,
		"loop": 3,
		"pan": "left",
		"delay": 2
	}
	
]}';
$json2='{
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
}';*/
//$result=json_decode($json2,true);
//var_dump($result);

?>