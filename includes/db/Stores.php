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
	function order_veri($sku, $barcode, $order_id, $verification){
		return $this->order_verification($sku, $barcode, $order_id, $verification);
	}
	function get_order_veri_sku($sku,$order_id){
		return $this->get_order_verification_sku($sku,$order_id);
	}
	function get_order_veri_barcode($barcode,$order_id){
		return $this->get_order_verification_barcode($barcode,$order_id);
	}
	/*public function updateData($data, $criteria)
	 {
	 return $this->update($this->table_name, $data, $criteria);
	 }*/
}
?>