<?php session_start();
require '/var/www/html/scan-and-ship/includes/config/conf.inc.php';
class DB_Connection{
    protected $connection;
    
    // connection string 
    
    function __construct()
	{
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// 		if($this->connection)
// 		{
// 			echo 'Connected';
// 		}
// 		else {
// 			echo 'Not Connected'. mysqli_connect_error();
// 		}
	}
	
	
	// insert data into database
	
	function add($table,$data){
		$query = "INSERT INTO $table (store_url,access_key,access_token,created_at) VALUES (";
		$columns = [];
		foreach($data as $column => $value){
			$columns[] = "'$value'";
		}
		$query .= implode(",",$columns).")";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
		return mysqli_insert_id($result);
	}
	
	// validate shop exists or not
	
	function select($table,$columns = "*" ,$criteria = null){
		$query = "SELECT $columns from $table ";
		if(!empty($criteria)){
			$query .= "WHERE $criteria";
			//echo $query;
			$result = mysqli_query($this->connection, $query);
		    return mysqli_fetch_assoc($result);
		}
	}
	function order_verification($sku,$barcode,$order_id,$verification){
		$query = "insert into order_verification(sku,barcode,order_id,verification) values('$sku','$barcode','$order_id','$verification')";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	function get_order_verification_sku($sku,$order_id){
		$query = "select * from order_verification where order_id='$order_id' and sku='$sku'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function get_order_verification_barcode($barcode,$order_id){
		$query = "select * from order_verification where order_id='$order_id' and barcode='$barcode'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
}

