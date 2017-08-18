<?php session_start();
require '/var/www/html/double-check/includes/config/conf.inc.php';
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
	
	public function update($table, $data, $criteria)
	{
		$query = "UPDATE $table SET ";
		
		$columns = [];
		foreach($data as $column => $value) {
			$columns[] = "$column = '$value'";
		}
		
		$query .= implode(", ", $columns);
		
		if (!empty($criteria)) {
			$query .= " WHERE $criteria";
		}
		 //echo $query;
			mysqli_query($this->connection, $query);
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
	
	function get_all_stores($table,$columns = "*" ){
		$query = "SELECT $columns from $table ";
	        $result = mysqli_query($this->connection, $query);
			while($row = mysqli_fetch_assoc($result)){
				$rows[] = $row;
			}
			return $rows;
	}
	// picker
	
	function order_verification($sku,$barcode,$order_id,$verification,$qty){
		$query = "insert into order_verification(sku,barcode,order_id,verification,quantity) values('$sku','$barcode','$order_id','$verification','$qty')";
		$result = mysqli_query($this->connection, $query);
	}
	function get_order_verification_sku($sku,$order_id){
		$query = "select * from order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
		//echo mysqli_num_rows($result);
		if(mysqli_num_rows($result) > 0 ){
			//echo mysqli_num_rows($result);
		return mysqli_fetch_assoc($result);
		}
	}
	function get_order_verification_barcode($barcode,$order_id){
		$query = "select * from order_verification where order_id='$order_id' and barcode='$barcode'";
		$result = mysqli_query($this->connection, $query);
		
		if(mysqli_num_rows($result) > 0 ){
			//echo mysqli_num_rows($result);
		return mysqli_fetch_assoc($result);
		}
	}
	function check_order_verification($sku,$order_id,$verification){
		$query = "select * from order_verification where order_id='$order_id' and sku='$sku' and verification='$verification'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
		if(mysqli_num_rows($result) > 0 ){
		return mysqli_fetch_assoc($result);
		}
	}
	function count_picker_order($order_id){
		$query = "select count(*) from order_verification where order_id='$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function delete_picker_orders($order_id,$sku){
		$query = "delete from order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
	}
	function update_qty($sku,$barcode,$order_id){
		$query = "update order_verification set quantity = `quantity`+1 where order_id='$order_id' and sku='$sku' or barcode ='$barcode'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	
	
	// shipper
	
	function s_order_verification($sku,$barcode,$order_id,$verification,$qty){
		$query = "insert into shipper_order_verification(sku,barcode,order_id,verification,quantity) values('$sku','$barcode','$order_id','$verification','$qty')";
		$result = mysqli_query($this->connection, $query);
	}
	function s_get_order_verification_sku($sku,$order_id){
		$query = "select * from shipper_order_verification where order_id='$order_id' and sku='$sku'";
		
		$result = mysqli_query($this->connection, $query);
		//echo mysqli_num_rows($result);
		if(mysqli_num_rows($result) > 0 ){
			
		return mysqli_fetch_assoc($result);
		}
	}
	function s_get_order_verification_barcode($barcode,$order_id){
		$query = "select * from shipper_order_verification where order_id='$order_id' and barcode='$barcode'";
		$result = mysqli_query($this->connection, $query);
		if(mysqli_num_rows($result) > 0 ){
		return mysqli_fetch_assoc($result);
		}
	}
	function s_check_order_verification($sku,$order_id,$verification){
		$query = "select * from shipper_order_verification where order_id='$order_id' and sku='$sku' and verification='$verification'";
		$result = mysqli_query($this->connection, $query);
		if(mysqli_num_rows($result) > 0 ){
		return mysqli_fetch_assoc($result);
		}
	}
	function count_shipper_order($order_id){
		$query = "select count(*) from shipper_order_verification where order_id='$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function delete_shipper_orders($order_id,$sku){
		$query = "delete from shipper_order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
	}
	function shipper_update_qty($sku,$barcode,$order_id){
		$query = "update shipper_order_verification set quantity = `quantity`+1 where order_id='$order_id' and sku='$sku' or barcode ='$barcode'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}

	// receiver
	
	function r_order_verification($sku,$barcode,$order_id,$verification,$qty){
		$query = "insert into receiver_order_verification(sku,barcode,order_id,verification,quantity) values('$sku','$barcode','$order_id','$verification','$qty')";
		$result = mysqli_query($this->connection, $query);
	}
	function r_get_order_verification_sku($sku,$order_id){
		$query = "select * from receiver_order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
		if(mysqli_num_rows($result) > 0 ){
		return mysqli_fetch_assoc($result);
		}
	}
	function r_get_order_verification_barcode($barcode,$order_id){
		$query = "select * from receiver_order_verification where order_id='$order_id' and barcode='$barcode'";
		$result = mysqli_query($this->connection, $query);
		if(mysqli_num_rows($result) > 0 ){
		return mysqli_fetch_assoc($result);
		}
	}
	function r_check_order_verification($sku,$order_id,$verification){
		$query = "select * from receiver_order_verification where order_id='$order_id' and sku='$sku' and verification='$verification'";
		$result = mysqli_query($this->connection, $query);
		if(mysqli_num_rows($result) > 0 ){
		return mysqli_fetch_assoc($result);
		}
	}
	function verified_orders($order_id){
		$query = "select sum(quantity) from receiver_order_verification where order_id='$order_id'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
		if(mysqli_num_rows($result) > 0 ){
			return mysqli_fetch_assoc($result);
		}
	}
	function count_receiver_order($order_id){
		$query = "select count(*) from receiver_order_verification where order_id='$order_id'";
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function delete_receiver_orders($order_id,$sku){
		$query = "delete from receiver_order_verification where order_id='$order_id' and sku='$sku'";
		$result = mysqli_query($this->connection, $query);
	}
	function receiver_update_qty($sku,$barcode,$order_id){
		$query = "update receiver_order_verification set quantity = `quantity`+1 where order_id='$order_id' and sku='$sku' or barcode ='$barcode'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	
	// step verification
	
	function steps_verification($step,$fulfill,$shop){
		$query = "insert into `settings_table` (verification_step,fulfill_order,store_name) values('$step','$fulfill','$shop')";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	
	function update_steps_verification($step,$fulfill,$shop){
		$query = "update `settings_table` set verification_step='$step' , fulfill_order='$fulfill' WHERE store_name='$shop'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	
	function get_steps_verification($shop){
		$query = "select * from `settings_table` where store_name = '$shop'";
		//echo $query;
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	
	// add order notes
	
	function add_order_notes($order_id , $note){
		$query = "insert into `order_notes` (order_id,order_note) values('$order_id','$note')";
		$result = mysqli_query($this->connection, $query);
	}
	
	function update_order_notes($order_id , $note){
		$query = "update `order_notes` set order_note='$note' where order_id='$order_id'";
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
	function delete_instore_pickup($order_id){
		$query = "delete from `order_instore_pickup` where order_id = '$order_id'";
		$result = mysqli_query($this->connection, $query);
	}
	
	// save roles
	function save_roles($store_url,$role){
		$qry="INSERT INTO `assigned_roles` (store_urls,roles) VALUES ('$store_url','$roles')";
		$result = mysqli_query($this->connection, $qry);
	}
	function get_saved_role($store_url){
		$query = "select * from `assigned_roles` where store_urls = '$store_url'";
		echo $query;
		$result = mysqli_query($this->connection, $query);
		return mysqli_fetch_assoc($result);
	}
	function update_saved_role($store_url,$role){
		$query = "update `assigned_roles` set roles='$role' where store_urls='$store_url'";
		echo $query;
		$result = mysqli_query($this->connection, $query);
	}
	
}

