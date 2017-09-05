<?php
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_REQUEST['shop'];
$sum = 0 ;
$shop_info = $Stores->is_shop_exists($shop);
$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$_REQUEST['id']);
//echo $_SESSION['select_role'];
$pget_order_id = $_REQUEST['id'];
$pselect_role = $_SESSION['select_role'];
$get_verification = $Stores->get_step_verification($shop);
if(isset($_POST['submit_barcode']) || isset($_POST['pressed_button1']) == 'false'){
	
	$get_order_id = $_REQUEST['id'];
	$barcode_sku = $_POST['barcode_sku'];
	//echo $barcode_sku;
	$select_role = $_SESSION['select_role'];
	$select_role = $_POST['select_role'];
	$_SESSION['select_role'] = $_POST['select_role'];
	//echo $select_role;
	if($select_role == 'Picker ok' || $select_role == 'Shipper ok' || $select_role == 'Receiver ok' ){
		$selected_role = $select_role;
	}
	//echo $selected_role;
	$arrayobj = new ArrayObject($orders->order->line_items);
	$line_item_count = $arrayobj->count();
	$j = 0;
	$k = 0;
	for($i=0;$i<$line_item_count;$i++)
	{
		ob_start();
		$variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id);
		if($variants->variant->sku == $barcode_sku || $variants->variant->barcode == $barcode_sku)
		{
			//break;
			$j = 1;
			//$check_order_veri = $Stores->check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
			//print_r($check_order_veri);
			// picker
			if($selected_role == 'Picker ok' || $selected_role== 'Picker' ){
				$check_order_veri = $Stores->check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
				if(empty($check_order_veri)){
					$Stores->order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role,"1");
					header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$get_order_id");
				}
				else {
					//echo $orders->order->line_items[$i]->quantity;
					//echo "equal qty";
					if($orders->order->line_items[$i]->quantity == $check_order_veri['quantity']){
						$k = 1;
						//header("location:http://67.207.82.1/scan-and-ship/app/order_test.php/?id=$get_order_id");
					}
					else {
						//echo "not equal";
						$Stores->update_qty_order($variants->variant->sku,$variants->variant->barcode,$get_order_id);
						header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$get_order_id");
					}
				}
			}
			
			// shipper
			
			if($selected_role == 'Shipper ok' || $selected_role== 'Shipper' ){
				$s_check_order_veri = $Stores->s_check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
				if(empty($s_check_order_veri)){
					$Stores->s_order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role,"1");
					header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$get_order_id");
				}
				else {
					//echo $orders->order->line_items[$i]->quantity;
					//echo "equal qty";
					if($orders->order->line_items[$i]->quantity == $s_check_order_veri['quantity']){
						$k = 1;
						//header("location:http://67.207.82.1/scan-and-ship/app/order_test.php/?id=$get_order_id");
					}
					else {
						//echo "not equal";
						$Stores->s_update_qty_order($variants->variant->sku,$variants->variant->barcode,$get_order_id);
						header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$get_order_id");
					}
				}
			}
			
			// ready for pickup
			
			if($selected_role == 'Receiver ok' || $selected_role== 'Receiver' ){
				$r_check_order_veri = $Stores->r_check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
				if(empty($r_check_order_veri)){
					$Stores->r_order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role,"1");
					header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$get_order_id");
				}
				else {
					//echo $orders->order->line_items[$i]->quantity;
					//echo "equal qty";
					if($orders->order->line_items[$i]->quantity == $r_check_order_veri['quantity']){
						$k = 1;
						//header("location:http://67.207.82.1/scan-and-ship/app/order_test.php/?id=$get_order_id");
					}
					else {
						//echo "not equal";
						$Stores->r_update_qty_order($variants->variant->sku,$variants->variant->barcode,$get_order_id);
						header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$get_order_id");
					}
				}
			}
			//break;
		}
		ob_end_flush();
	}
	//echo $k;
	if($k == 1){
		$error_qty = "All item quantities are scanned";
	}
	if($j == 1){
		$error == '';
	}
	else {
		$error = "" ;
	}
}
if(isset($_POST['save_notes'])){
	$order_notes = $_POST['order_note'];
	$order_id=$_REQUEST['id'];
	if(!empty($order_notes)){
		$Stores->add_order_note($_REQUEST['id'], $order_notes);
		header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$order_id");
	}
}
if(isset($_POST['update_notes'])){
	$uorder_notes = $_POST['update_order_note'];
	$uorder_id=$_REQUEST['id'];
	$Stores->update_order_note($_REQUEST['id'], $uorder_notes);
	header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$uorder_id");
	
}
$shop_info = $Stores->is_shop_exists($shop);
$date = new DateTime("-6 months");
$date->modify("-" . ($date->format('j')-1) . " days");
$six_date = $date->format('Y-m-j');
$count_orders = $Shopify->count_orders($shop, $shop_info['access_token'],$six_date);
$count_val = ceil($count_orders->count / 250);
$ndate = new DateTime("-1 months");
$ndate->modify("-" . ($ndate->format('j')-1) . " days");
$two_date = $ndate->format('Y-m-j');
$count_total_orders = $Shopify->count_total_orders($shop, $shop_info['access_token'],$two_date);
$ncount_val = ceil($count_total_orders->count / 250);
$get_order_note = $Stores->get_order_note($_REQUEST['id']);
$get_instore_pickup = $Stores->gett_instore_pickup($_REQUEST['id']);
if(isset($_POST['submit_id']) || isset($_POST['pressed_button']) == 'false'){
	$z = 0;
	//echo $_POST['pressed_button'];
	$order_id = $_POST['order_id'];
	$_SESSION['select_role'] = $_POST['select_role'];
	$shop_info = $Stores->is_shop_exists($shop);
	for($count=1;$count<=$count_val;$count++){
		ob_start();
		${"get_order".$count} = $Shopify->get_orders($shop,$shop_info['access_token'],$count);
		foreach(${"get_order".$count}->orders as $order) {
			ob_start();
			if($order_id == $order->name || $order_id == $order->id){
				header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$order->id");
			} else {
				$z = 1;
			}
		   ob_end_flush();
		}
		ob_end_flush();
	}
	for($ncount=1;$ncount<=$ncount_val;$ncount++){
		ob_start();
		${"get_order".$ncount} = $Shopify->get_orders($shop,$shop_info['access_token'],$ncount);
		foreach(${"get_order".$ncount}->orders as $order) {
			ob_start();
			if($order_id == $order->name || $order_id == $order->id){
				header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$order->id");
			} else{
				$z = 1;
			}
			ob_end_flush();
		}
		ob_end_flush();
	}
}
if($z == 1){
	$order_msg = "Not Found";
}
$get_single_store = $Stores->get_single_save_roles($shop);
$get_single_role = explode(",",$get_single_store['roles']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Double Check - <?php echo $_GET['id']?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/double-check/app/css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="/double-check/app/css/style.css" type="text/css">
  <link rel="stylesheet" href="/double-check/app/font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="/double-check/app/js/bootstrap.min.js"></script>
  <script src="/double-check/app/js/script.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
  <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
  
</head>