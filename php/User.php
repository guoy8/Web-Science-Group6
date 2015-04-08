
<?php 
/////////////////////////////////////////////////
//USE THIS CLASS FOR USER LOG IN AND REGISTRATION
/////////////////////////////////////////////////




require_once('DB.php');
class User
{
	protected $connection;
	protected $username;
	protected $validationErr='';
	const TABLENAME="users";



	//This function is to log a user in
	//usage:
	//$user=new User();   //create a User class object instance //Instantiate a instance only once
	//$user->login($username,$password); 


	//This function returns true if the user pass the authetication and set up the session 
	public function login($username,$password)
	{
		if(!$this->isEmpty($username) and !$this->isEmpty($password) and $this->usernameValidation($username) and $this->passwordValidation($password))
		{
			$resultArr=$this->connection->SearchForRow(self::TABLENAME,'*',['username'],[$username]);
			if(count($resultArr)==1)
			{
				$salt=$resultArr[0]['salt'];
				$passwordSecure=sha1($password.$salt);
				if($passwordSecure===$resultArr[0]['password'])
				{
					session_start();
					$_SESSION['uid'] = $resultArr[0]['id'];
					$_SESSION['username'] = $resultArr[0]['username'];
					$_SESSION['premium'] = $resultArr[0]['premium'];
					$this->username=$username;
					return true;
				}
				/*
				*/
			}
		}
		return false;
	}


	//This function is to register a new user
	//usage:
	//$user=new User();//Instantiate a instance only once
	//$user->register($registrationArr)  ex:$user->register(['haoqihua','abc123','abc123']);
	//$registrationArr is an ordered Array 
	//first element in the array is the username.
	//second element in the array is the password.
	//third element is the re-entered password.


	//The function returns true if the new user was created in the database
	public function register($registrationArr)
	{
		if(!$this->isEmpty($registrationArr[0]) and !$this->isEmpty($registrationArr[1]) and 
			$this->usernameValidation($registrationArr[0]) 
			and $this->passwordValidation($registrationArr[1]) 
			and $this->passwordMatch($registrationArr[1],$registrationArr[2]))
		{
			if($this->checkUnique($registrationArr[0]))
			{
				//$user1->InsertRow("users",['username','password'],['Chee','123456']);
				$salt=self::randomGenerateSalt();
				$passwordSecure=sha1($registrationArr[1].$salt);
				$this->connection->InsertRow(self::TABLENAME,['username','password','salt'],
					[$registrationArr[0],$passwordSecure,$salt]);
				return true;
			}
		}
		return false;
	}




	//If the login or register is not succesful 
	// if($user->register($registrationArr)) // register function or log in function return false
	//use this function to get error message
	//


	//if(!($user->register($registrationArr)))
	//{
		//echo $user->getErrMessage();
	//}
	public function getErrMessage()
	{
		return $this->validationErr;
	}




/////////////////////////////////////////////
//DO NOT NEED TO USE THESE MEMBER FUNCTIONS
//HELPER FUNCTIONS	
////////////////////////////////////////////
	public function __construct()
	{
		$this->connection=DB::getInstance();
	}

	public function isEmpty($value)
	{
		$str=trim($value);
		return empty($str);
	}

	public function usernameValidation($username)
	{
		if($this->isEmpty($username))
		{
			$this->validationErr="username cannot be empty";
			return false;
		}else
		{
			$match='/^[a-zA-Z0-9_]{3,16}$/'; 
			if(!preg_match($match,$username))
			{
				$this->validationErr="Please enter a valid username";
				return false;
			}
		}
		return true;
	}

	public function passwordValidation($password)
	{
		$match='/^[a-zA-Z0-9_]{3,16}$/';
		if($this->isEmpty($password))
		{
			$this->validationErr="password cannot be empty";
			return false;
		}else if(strlen($password)<6 or strlen($password)>15)
		{
			$this->validationErr="password length must be greater than 5 and less than 15";
			return false;
		}else if(!preg_match($match,$password))
		{
			$this->validationErr="please enter valid password";
			return false;
		}
		return true;
	}

	public function passwordMatch($password,$reenterPassword)
	{
		if($password!==$reenterPassword)
		{
			$this->validationErr="Passwords didn't match";
			return false;
		}
		return true;
	}

	/*public function emailValidation($email)
	{

	}*/

	public function checkUnique($username)
	{
		$searchResult=$this->connection->SearchForRow(self::TABLENAME,"*",['username'],[$username]);
		if(count($searchResult)>0)
		{
			$this->validationErr="can not have repeat username";
			return false;
		}
		return true;
	}

	

	public function getUsername()
	{
		return $this->username;
	}

	static private function randomGenerateSalt()
	{
		$str="QWERTYUIOPASDFGHJKLZXCVBNMzxcvbnmasdfghjklqwertyuiop1234567890";
		$str=str_shuffle($str);
		$salt=substr($str,0,10);
		return $salt;
	}




}

//user=new User();
//$result=$user->login("haoqihua","abc123");
//var_dump($result);
//$result=$user->register(['haoqihua','abc123','abc123']);
//var_dump($result);
//echo $user->getErrMessage();
?>
