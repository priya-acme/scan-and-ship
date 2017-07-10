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
	
	// picker
	
	function order_verification($sku,$barcode,$order_id,$verification){
		$query = "insert into order_verification(sku,barcode,order_id,verification) values('$sku','$barcode','$order_id','$verification')";
		$result = mysqli_query($this->connection, $query);
	}
	function get_order_verification_sku($sku,$order_id){
		$query = "select * from order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function get_order_verification_barcode($barcode,$order_id){
		$query = "select * from order_verification where order_id='$order_id' and barcode='$barcode'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function check_order_verification($sku,$order_id,$verification){
		$query = "select * from order_verification where order_id='$order_id' and sku='$sku' and verification='$verification'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function count_picker_order($order_id){
		$query = "select count(*) from order_verification where order_id='$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	
	// shipper
	
	function s_order_verification($sku,$barcode,$order_id,$verification){
		$query = "insert into shipper_order_verification(sku,barcode,order_id,verification) values('$sku','$barcode','$order_id','$verification')";
		$result = mysqli_query($this->connection, $query);
	}
	function s_get_order_verification_sku($sku,$order_id){
		$query = "select * from shipper_order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function s_get_order_verification_barcode($barcode,$order_id){
		$query = "select * from shipper_order_verification where order_id='$order_id' and barcode='$barcode'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function s_check_order_verification($sku,$order_id,$verification){
		$query = "select * from shipper_order_verification where order_id='$order_id' and sku='$sku' and verification='$verification'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function count_shipper_order($order_id){
		$query = "select count(*) from shipper_order_verification where order_id='$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	
	// receiver
	
	function r_order_verification($sku,$barcode,$order_id,$verification){
		$query = "insert into receiver_order_verification(sku,barcode,order_id,verification) values('$sku','$barcode','$order_id','$verification')";
		$result = mysqli_query($this->connection, $query);
	}
	function r_get_order_verification_sku($sku,$order_id){
		$query = "select * from receiver_order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function r_get_order_verification_barcode($barcode,$order_id){
		$query = "select * from receiver_order_verification where order_id='$order_id' and barcode='$barcode'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function r_check_order_verification($sku,$order_id,$verification){
		$query = "select * from receiver_order_verification where order_id='$order_id' and sku='$sku' and verification='$verification'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function count_receiver_order($order_id){
		$query = "select count(*) from receiver_order_verification where order_id='$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}

	// step verification
	
	function steps_verification($step){
		$query = "insert into `settings_table` (verification_step) values('$step')";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	
	function update_steps_verification($step){
		$query = "update `settings_table` set verification_step='$step' WHERE 1";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	
	function get_steps_verification(){
		$query = "select * from `settings_table` where id='1'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	
	// add order notes
	
	function add_order_notes($order_id , $note){
		$query = "insert into `order_notes` (order_id,order_note) values('$order_id','$note')";
		$result = mysqli_query($this->connection, $query);
	}
	
	function get_order_notes($order_id){
		$query = "select * from `order_notes` where order_id = '$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	
	// in store pickup
	
	function add_instore_pickup($order_id , $val){
		$query = "insert into `order_instore_pickup` (order_id,instore_pickup) values('$order_id','$val')";
		$result = mysqli_query($this->connection, $query);
	}
	
	function get_instore_pickup($order_id){
		$query = "select * from `order_instore_pickup` where order_id = '$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	
	
}

