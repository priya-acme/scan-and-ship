<?php
require '/var/www/html/scan-and-ship/includes/config/conf.inc.php';
class DB_Connection{
    protected $connection;
    
    // connection string 
    
    public function connect()
	{
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if($this->connection)
		{
			echo 'Connected';
		}
		else {
			echo 'Not Connected'. mysqli_connect_error();
		}
		
		return $this->connection;
	}
	
	// validate shop url 
	
	function validate_shop_url($shop){
		$subject = $shop;
		$pattern = '/^(.*)?(\.myshopify\.com)$/';
		preg_match($pattern, $subject, $matches);
		return $matches[2] == '.myshopify.com';
	}
	
	// insert data into database
	
	function add($table,$data){
		$query = "INSERT INTO $table (store_url,access_key,access_token,created_at) VALUES (";
		$columns = [];
		foreach($data as $column => $value){
			$columns[] = "'$value'";
		}
		$query .= implode(",",$columns).")";
		echo $query;
		$result = mysqli_query($this->connection, $query);
		return mysqli_insert_id($result);
	}
	
	// validate shop exists or not
	
	function select($table,$columns = "*" ,$criteria = null){
		$query = "SELECT $columns from $table ";
		if(!empty($criteria)){
			$query .= "WHERE $criteria";
			$result = mysqli_query($this->connection, $query);
		    return mysqli_insert_id($result);
		}
	}
	
}

