<?php
//echo "stores";
include 'db_connection.php';
class Stores extends DB_Connection{
	
	private $table_name = 'stores';
	
	public function __construct() {
		parent :: __construct();
	}
	function is_shop_exists($shop){
		return $this->select($this->table_name , "*" ,"store_url='$shop'");
	}
	function addData($data){
		//print_r($data);
		return $this->add($this->table_name , $data);
	}
	function check_preference($shop){
		$query  = "select * from preference where related_url='$shop'";
		echo $query;
		$result = pg_query($this->connection , $query);
		return pg_fetch_all($result);
	}
	
	/*public function updateData($data, $criteria)
	 {
	 return $this->update($this->table_name, $data, $criteria);
	 }*/
}
?>