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
	function get_store(){
		return $this->get_all_stores($this->table_name , "*" );
	}
	function addData($data){
		//print_r($data);
		return $this->add($this->table_name , $data);
	}
	public function updateData($data, $criteria)
	{
		return $this->update($this->table_name, $data, $criteria);
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
	
	function s_order_veri($sku, $barcode, $order_id, $verification,$qty){
		return $this->s_order_verification($sku, $barcode, $order_id, $verification,$qty);
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
	function s_update_qty_order($sku,$barcode,$order_id){
		return $this->shipper_update_qty($sku,$barcode,$order_id);
	}
	// receiver
	
	function r_order_veri($sku, $barcode, $order_id, $verification,$qty){
		return $this->r_order_verification($sku, $barcode, $order_id, $verification,$qty);
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
	function r_update_qty_order($sku,$barcode,$order_id){
		return $this->receiver_update_qty($sku,$barcode,$order_id);
	}
	function r_verified_orders($order_id){
		return $this->verified_orders($order_id);
	}
	// step verification
	
	function step_verification($step,$fulfill,$shop){
		return $this->steps_verification($step,$fulfill,$shop);
	}
	function update_step_verification($step,$fulfill,$shop){
		return $this->update_steps_verification($step,$fulfill,$shop);
	}
	function get_step_verification($shop){
		return $this->get_steps_verification($shop);
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
	function delete_instore_pickup_order($order_id){
		return $this->delete_instore_pickup($order_id);
	}
	
	// save role
	
	function saved_roles($store_url,$role){
		return $this->save_roles($store_url,$role);
	}
	function get_save_roles(){
		return $this->get_saved_role();
	}
	function update_saved_roles($store_url,$role){
		return $this->update_saved_role($store_url,$role);
	}
	function get_single_save_roles($store_url){
		return $this->get_single_saved_role($store_url);
	}
	function update_select_role($store_url,$role){
		return $this->update_selected_role($store_url,$role);
	}
	function save_select_role($store_url,$role){
		return $this->save_selected_role($store_url,$role);
	}
	
	// days allotment 
	
	function get_days($store_url){
		return $this->select_days($store_url);
	}
	function save_days($store_url,$fdays,$undays){
		return $this->insert_days($store_url,$fdays,$undays);
	}
	function update_day($store_url,$fdays,$undays){
		return $this->update_days($store_url,$fdays,$undays);
	}
	function get_stores_data(){
		return $this->get_all_data();
	}
	
}
?>