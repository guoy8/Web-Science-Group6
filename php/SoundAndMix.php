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
	protected $allowTypes;
	protected $publicuser = true;
	const USERTABLENAME="users";
	const MIXTABLENAME="mixes";
	const MIXOWNERTABLENAME="mixesOwner";
	const SOUNDTABLE="sound";
	const SOUNDOWNERTABLE="soundOwner";
	const MAXSIZE=100000000;
	const PATH="sounds/";

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
			$this->publicuser = false;
		}else
		{
			$this->publicuser = true;
		}
		$this->allowTypes=['ogg','mp3'];
	}

	public function UploadSoundMixes($JsonString,$name,$category,$public)
	{
		if (!$this->publicuser) {
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
	}

	public function fetchJson()
	{
		if (!$this->publicuser) {
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
				$temp = json_decode($Allmixes[$i][0]['mix'], true);
				$temp['name'] = $Allmixes[$i][0]['name'];
				array_push($allMix, $temp);
			}
			//echo json_encode($allMix);
			return $allMix;
		}
	}	

	public function UploadSoundPiece($file,$soundName,$uid)
	{
		if (!$this->publicuser) {
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
			}else if(!$this->checkSize($size))
			{
				return false;
			}else if(!$this->checkType($filename))
			{
				return false;
			}
			$randomName=$this->generateRandFilename($filename);
			if(move_uploaded_file($path,self::PATH.$randomName))
			{
				$this->saveIndatabase($soundName,self::PATH.$randomName,$uid);
			}
		}
	}

	public function saveIndatabase($soundName,$path,$uid)
	{
		if(!$this->publicuser) {
			$sid=$this->connection->InsertRow(self::SOUNDTABLE,['soundname','url'],[$soundName,$path]);
			$this->connection->InsertRow(self::SOUNDOWNERTABLE,['uid','sid'],[$uid,$sid]);
		}
	}

	public function fetchAllSounds()
	{
		$Array=$this->connection->SearchForRow(self::SOUNDTABLE,'*',['count'],[0]);
		$finalArr=[];
		for($i=0;$i<count($Array);$i++)
		{
			$arr['id']=$Array[$i]['soundname'];
			$arr['src']=$Array[$i]['url'];
			array_push($finalArr,$arr);
		}
		return $finalArr;
	}

	public function fetchMixRand($number)
	{
		$Array=$this->connection->SearchForRow(self::MIXTABLENAME,'*',['public'],[0]);
		shuffle($Array);
		$finalArr=[];
		for($i=0;$i<$number;$i++)
		{
			$temp = json_decode($Array[$i]['mix'], true);
			$temp['name'] = $Array[$i]['name'];
			array_push($finalArr, $temp);
			// array_push($finalArr, json_decode($Array[$i]['mix']));
		}
		return $finalArr;
	}

	public function fetchBycate($category)
	{
		$Array=$this->connection->SearchForRow(self::MIXTABLENAME,'*',['categories'],[$category]);
		$finalArr=[];
		for($i=0;$i<count($Array);$i++)
		{
			$temp = json_decode($Array[$i]['mix'], true);
			$temp['name'] = $Array[$i]['name'];
			array_push($finalArr, $temp);
			// array_push($finalArr, json_decode($Array[$i]['mix']));
		}
		return $finalArr;
	}

	public function getErrorMessage()
	{
		return $this->erroMessage;
	}

	//HELPER FUNCTIONS
	public function checkSize($size)
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

	public function checkType($type)
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

	public function generateRandFilename($filename)
	{
		list($name,$type) = explode(".",$filename);
		$str="QWERTYUIOPASDFGHJKLZXCVBNMzxcvbnmasdfghjklqwertyuiop1234567890";
		$str=str_shuffle($str);
		$salt=substr($str,0,4);
		$newname=str_shuffle($name).$salt;
		return time().$newname.".".$type;
	}

}

?>