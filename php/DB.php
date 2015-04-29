
<?php 


/////////////////////////////////////
//DO NOT NEED TO USE THIS CLASS, THIS IS A HELPER CLASS INCLUDER IN User.php
/////////////////////////////////////



class DB
{
	const MYSQLHOSTNAME = "localhost";  
    const MYSQLUSERNAME = "root";  
    const MYSQLPASSWORD = "";  
    const MYSQLDBNAME = "wavpool";  
    const MYSQLCHARSET = "utf8";
    protected $connection;
	private function __construct()
	{
		$this->connection=new PDO('mysql:host='.self::MYSQLHOSTNAME.';dbname='.self::MYSQLDBNAME,self::MYSQLUSERNAME,self::MYSQLPASSWORD);
		if(!$this)
		{
			echo "unable to connect to the database";
		}
	}

	public static function getInstance()
	{
		static $instance=null;
		if($instance==null)
		{
			$instance=new self();
		}
		return $instance;
	}

	public function InsertRow($tableName,$ColumnArr,$valueArr)
	{
		$colString='';
		$colStringPre='';
		$valueArray=[];
		foreach($ColumnArr as $key=>$val)
		{
			$colString.=$val.",";
			$colStringPre.=":".$val.",";

		}
		$colString=substr($colString,0,-1);
		$colStringPre=substr($colStringPre,0,-1);
		$stmt="insert into {$tableName}($colString) values($colStringPre)";
		$preparedStmt=$this->connection->prepare($stmt);
		foreach ($valueArr as $key=>$val) 
		{
			$valueArray[':'.$ColumnArr[$key]]=$val;
		}
		$preparedStmt->execute($valueArray);
		return $this->connection->lastInsertId();
	}

	public function SearchForRow($tableName,$columnDisplay='',$searchRowArr='',$searchRowValueArr='')
	{
		if($columnDisplay=='')
		{
			$column="*";
		}else
		{
			$column=$columnDisplay;
		}
		$searchrowPre='';
		foreach ($searchRowArr as $key => $value) {
			$searchrowPre.=$value."=:".$value." AND ";
		}
		$stmt="SELECT {$column} FROM $tableName";
		if(count($searchRowArr)!=0)
		{
			$stmt.=" WHERE ".$searchrowPre;
		}
		$stmt=substr($stmt,0,-4);
		//echo $stmt;
		$preparedStmt=$this->connection->prepare($stmt);
		$valueArray=[];
		foreach ($searchRowValueArr as $key=>$val) 
		{
			$valueArray[':'.$searchRowArr[$key]]=$val;
		}
		$preparedStmt->execute($valueArray);
		$preparedStmt->setFetchMode(PDO::FETCH_ASSOC);
		return $preparedStmt->fetchAll();
	}


	public function deleteRow($tableName,$colArr,$valArr)
	{
		$stmt="DELETE FROM {$tableName} WHERE ";
		$colstmt='';
		foreach ($colArr as $key => $value) {
			$colstmt.=$value."=:".$value." AND ";
		}
		$colstmt=substr($colstmt,0,-4);
		$stmt=$stmt.$colstmt;
		$preparedStmt=$this->connection->prepare($stmt);
		$valueArray=[];
		foreach ($valArr as $key => $value) {
			$valueArray[':'.$colArr[$key]]=$value;
		}
		$preparedStmt->execute($valueArray);
	}
}

$user1=DB::getInstance();
//$user1->InsertRow("users",['username','password'],['Chee','123456']);
//$arr=$user1->SearchForRow("users",'*',['username'],['haoqihua']);
//$user1->deleteRow("users",['username'],['haoqihua']);
//var_dump($arr);
?>