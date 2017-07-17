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
	
	// picker
	
	function order_veri($sku, $barcode, $order_id, $verification,$qty){
		return $this->order_verification($sku, $barcode, $order_id, $verification,$qty);
	}
	function get_order_veri_sku($sku,$order_id){
		return $this->get_order_verification_sku($sku,$order_id);
	}
	function get_order_veri_barcode($barcode,$order_id){
		return $this->get_order_verification_barcode($barcode,$order_id);
	}
	function check_order_veri($sku,$order_id,$verification){
		return $this->check_order_verification($sku,$order_id,$verification);
	}
	function p_count_order($order_id){
		return $this->count_picker_order($order_id);
	}
	function delete_picker_order($order_id,$sku){
		return $this->delete_picker_orders($order_id,$sku);
	}
	function update_qty_order($sku,$barcode,$order_id){
		return $this->update_qty($sku,$barcode,$order_id);
	}
	// shipper
	
	function s_order_veri($sku, $barcode, $order_id, $verification){
		return $this->s_order_verification($sku, $barcode, $order_id, $verification);
	}
	function s_get_order_veri_sku($sku,$order_id){
		return $this->s_get_order_verification_sku($sku,$order_id);
	}
	function s_get_order_veri_barcode($barcode,$order_id){
		return $this->s_get_order_verification_barcode($barcode,$order_id);
	}
	function s_check_order_veri($sku,$order_id,$verification){
		return $this->s_check_order_verification($sku,$order_id,$verification);
	}
	function s_count_order($order_id){
		return $this->count_shipper_order($order_id);
	}
	function delete_shipper_order($order_id,$sku){
		return $this->delete_shipper_orders($order_id,$sku);
	}
	
	// receiver
	
	function r_order_veri($sku, $barcode, $order_id, $verification){
		return $this->r_order_verification($sku, $barcode, $order_id, $verification);
	}
	function r_get_order_veri_sku($sku,$order_id){
		return $this->r_get_order_verification_sku($sku,$order_id);
	}
	function r_get_order_veri_barcode($barcode,$order_id){
		return $this->r_get_order_verification_barcode($barcode,$order_id);
	}
	function r_check_order_veri($sku,$order_id,$verification){
		return $this->r_check_order_verification($sku,$order_id,$verification);
	}
	function r_count_order($order_id){
		return $this->count_receiver_order($order_id);
	}
	function delete_receiver_order($order_id,$sku){
		
		return $this->delete_receiver_orders($order_id,$sku);
	}
	
	// step verification
	
	function step_verification($step){
		return $this->steps_verification($step);
	}
	function update_step_verification($step){
		return $this->update_steps_verification($step);
	}
	function get_step_verification(){
		return $this->get_steps_verification();
	}
	
	// order note
	
	function add_order_note($order_id , $note){
		return $this->add_order_notes($order_id,$note);
	}
	
	function update_order_note($order_id , $note){
		return $this->update_order_notes($order_id,$note);
	}
	
	function get_order_note($order_id){
		return $this->get_order_notes($order_id);
	}
	
	// instore pickup
	
	
	function addd_instore_pickup($order_id , $val){
		return $this->add_instore_pickup($order_id,$val);
	}
	
	function gett_instore_pickup($order_id){
		return $this->get_instore_pickup($order_id);
	}
}
?>