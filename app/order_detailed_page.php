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
$get_days = $Stores->get_days($shop);
if(empty($get_days)){
	$thirty_date = date('Y-m-d', strtotime("-10 days"));
}else{
	$get_day = $get_days['unful_day'];
	$thirty_date = date('Y-m-d', strtotime("-".$get_day." days"));
}
$count_orders = $Shopify->count_orders($shop, $shop_info['access_token'],$thirty_date);
$count_val = ceil($count_orders->count / 250);
if(empty($get_days)){
	$fourty_five = date('Y-m-d', strtotime("-15 days"));
}else{
	$get_day = $get_days['ful_day'];
	$fourty_five= date('Y-m-d', strtotime("-".$get_day." days"));
}
$count_total_orders = $Shopify->fcount_total_orders($shop, $shop_info['access_token'],$fourty_five);
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
		${"get_order".$count} = $Shopify->get_orders($shop,$shop_info['access_token'],$count,$thirty_date);
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
		${"get_order".$ncount} = $Shopify->ofget_orders($shop,$shop_info['access_token'],$ncount,$fourty_five);
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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
  <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
  <script>
    var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};
	var shop_url = getUrlParameter('shop');
   ShopifyApp.init({
	  apiKey: "ed1b619b0d4433048a3fd866d1ae5f7f",
	  shopOrigin:"https://"+shop_url,
	  debug: false,
	  forceRedirect: true
	});
  </script>
</head>
<body class="order-details-page" OnLoad="document.form_submit.barcode_sku.focus();">
<form method="post" name="form_submit" id="form_submit">
<span id="done" style="display:none"></span>
<div class="margtop30 ordered-header-fixed">
<div class="container">
<div class="row">
<div class="col-sm-12">
<div class="right-icon">
<div class="order-btn">
<a class="order" href="/double-check/app/summary_page.php?shop=<?php echo $shop; ?>">BACK TO ORDER LOOKUP</a>
</div>
<a href="/double-check/app/settings.php?shop=<?php echo $shop; ?>" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
<a href="/double-check/app/support.php?shop=<?php echo $shop; ?>" class="support_link">Support</a>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-4">
 <div class="role2">
    <span class="b1">BARCODE / PRODUCT CODE </span> 
    <span class="b2">
    <input type="text" name="barcode_sku" class="txt" value=""> 
    <input type="hidden" name="pressed_button" id="pressed_button1" value="false">
     <button type="submit" class="serch" name="submit_barcode" onclick="document.getElementById('pressed_button1').value='true';document.getElementById('form_submit').submit();">
      <span class="glyphicon glyphicon-search"></span>
     </button>
     </span>
  
    </div>
    
 <?php if(isset($_POST['submit_barcode'])){ ?> <div class="error-message" style="color:red"><?php echo $error; ?></div><?php } ?>
   <?php if(isset($_POST['submit_barcode'])){ ?> <div class="qty-error-message" style="color:red"><?php echo $error_qty; ?></div><?php } ?>
   <?php if(isset($_POST['submit_id']) || isset($_POST['pressed_button']) == 'false'){ ?> <div class="qty-error-message" style="color:red"><?php echo $order_msg; ?></div><?php } ?>
</div>
<div class="col-sm-12 col-md-4">
 <div class="role2">
 <span class="b1">
  ORDER LOOKUP 
  </span>
  <span class="b2">
    <input type="text" class="txt" name="order_id" id="order_id"> 
    <input type="hidden" name="pressed_button" id="pressed_button" value="false">
     <button type="submit" class="serch" name="submit_id" id="submit_id" onclick="document.getElementById('pressed_button').value='true';document.getElementById('form_submit').submit();">
      <span class="glyphicon glyphicon-search"></span>
     </button>
     </span>
     </div>
</div>

<div class="col-sm-12 col-md-4 no-wrap">
<div class="role2">
<?php if($get_verification['verification_step'] != 'Three') {  ?> <span class="b1">SELECT ROLE :</span><?php } ?>
<span class="radio radio-primary b2">
<?php if($get_verification['verification_step'] == 'One' || $get_verification['verification_step'] == 'Six') {  
	?>
<input type="radio" name="select_role" id="radio1" value="Picker ok" <?php if($get_single_store['selected_role'] == 'Picker ok') { echo "checked"; }  ?> onclick="selected_radio(this.value,'<?php echo $shop; ?>')">
<label for="radio1">
 PICKER
</label>
<?php 
} 
?>
<?php if($get_verification['verification_step'] == 'Two' || $get_verification['verification_step'] == 'Eight') {  
	?>
<input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role']== 'Shipper ok') { echo "checked"; } ?>>
<label for="radio2">
SHIPPER
</label>
<?php 
} 
?>
<?php if(in_array("ready for pickup", $get_single_role)){
if($get_verification['verification_step'] == 'Four' || $get_verification['verification_step'] == 'Ten' ) {  
	?>
<input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role']== 'Receiver ok') { echo "checked"; } ?>>
<label for="radio3">
READY FOR PICKUP
</label>
<?php 
} }
?>

<?php 
if($get_verification['verification_step'] == 'Five') {  
	?>
<input type="radio" name="select_role" id="radio1" value="Picker ok" <?php if($get_single_store['selected_role']== 'Picker ok') { echo "checked"; } else { echo "checked"; } ?> onclick="selected_radio(this.value,'<?php echo $shop; ?>')">
<label for="radio1">
 PICKER
</label>
<input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role']== 'Shipper ok') { echo "checked"; } ?>>
<label for="radio2">
SHIPPER
</label>
<?php 
} 
?>
<?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Seven') {  
	?>
<input type="radio" name="select_role" id="radio1" value="Picker ok" <?php if($get_single_store['selected_role'] == 'Picker ok') { echo "checked"; } ?> onclick="selected_radio(this.value,'<?php echo $shop; ?>')">
<label for="radio1">
 PICKER
</label>
<input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role'] == 'Receiver ok') { echo "checked"; } ?>>
<label for="radio3">
READY FOR PICKUP
</label>
<?php 
} }
?>
<?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Nine') {  
	?>
<input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role']== 'Shipper ok') { echo "checked"; } ?>>
<label for="radio2">
SHIPPER
</label>
<input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role']== 'Receiver ok') { echo "checked"; } ?>>
<label for="radio3">
READY FOR PICKUP
</label>
<?php 
} }
?>

            
<?php if(in_array("ready for pickup", $get_single_role)){ 
	if($get_verification['verification_step'] == 'Eleven') {
?>
<input type="radio" name="select_role" id="radio1" value="Picker ok" <?php if($get_single_store['selected_role']== 'Picker ok') { echo "checked"; }  ?> onclick="selected_radio(this.value,'<?php echo $shop; ?>')">
<label for="radio1">
PICKER
</label>
<input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role'] == 'Shipper ok') { echo "checked"; } ?>>
<label for="radio2">
SHIPPER
</label>
<input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value,'<?php echo $shop; ?>')" <?php if($get_single_store['selected_role'] == 'Receiver ok') { echo "checked"; } ?>>
<label for="radio3">
READY FOR PICKUP
</label>
            
<?php } } ?>
 
</span>
</div>
</div>
</div>
</div>
</div>

<div class="margtop30 ordered-content">
<div class="container">
<div class="row">
<div class="col-md-5 col-sm-12">
<div class="hdd">ORDER DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-responsive mytable">
  <tr>
    <td width="29%"><strong>Order No.</strong></td>
    <td width="71%"><?php echo $orders->order->name; ?></td>
  </tr>
  <tr>
    <td><strong>Order ID</strong></td>
    <td><?php echo $orders->order->id; ?></td>
  </tr>
  <tr>
    <td><strong>Dates</strong></td>
    <td><?php echo $orders->order->updated_at; ?></td>
  </tr>
  <?php if(!empty($get_order_note)) { ?>
  <tr>
    <td><strong>Internal Notes</strong></td>
   <td><textarea style="resize:none" name="update_order_note" class="text-area" placeholder="Internal order notes"><?php echo $get_order_note['order_note'];  ?></textarea></td>
   
  </tr>
  <tr>
  <td></td>
  <td class="save_notes"><input class="btn btn-primary" type="submit" name="update_notes" value="Update Notes"></td>
  </tr>
  <?php }  else {
  	?>
  <tr>
   <td><strong>Internal Notes</strong></td>
   <td><textarea style="resize:none" name="order_note" class="text-area" placeholder="Internal order notes"></textarea>
   
   </td>
  </tr>
  <tr>
  <td></td>
  <td class="save_notes"><input class="btn btn-primary" type="submit" name="save_notes" value="Save Notes"></td>
  </tr>
  <?php 
  }?>
</table>

</div>
<div class="col-md-5 col-sm-12">
<DIV class="hdd">SHIPPING DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-responsive mytable">
  <tr>
    <td width="29%"><strong>Name</strong></td>
    <td width="71%"><?php echo $orders->order->shipping_address->first_name." ".$orders->order->shipping_address->last_name; ?></td>
  </tr>
  <tr>
    <td><strong>Order ID</strong></td>
    <td><?php echo $orders->order->id; ?></td>
  </tr>
  <tr>
    <td><strong>Address</strong></td>
    <td><?php if($orders->order->shipping_address->address1 != '' || $orders->order->shipping_address->address2 != '' ) { echo $orders->order->shipping_address->address1." ".$orders->order->shipping_address->address2.","; }
    if($orders->order->shipping_address->city!= '' ) { echo $orders->order->shipping_address->city." ".$orders->order->shipping_address->zip.","; } if($orders->order->shipping_address->country!= '' ) { echo $orders->order->shipping_address->country." "."."; } ?></td>
  </tr>
  <?php if($orders->order->shipping_address->phone != '' ){ ?>
  <tr>
    <td><strong>Phone No.</strong></td>
    <td><?php echo $orders->order->shipping_address->phone; ?></td>
  </tr>
  <?php } ?>
</table>
</div>
<?php if(in_array("instore pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Three' || $get_verification['verification_step'] == 'Six' || $get_verification['verification_step'] == 'Eight' || $get_verification['verification_step'] == 'Ten' || $get_verification['verification_step'] == 'Eleven' ) {  	?>
<div class="col-md-2 col-sm-12">
<div class="hdd">IN-STORE PICKUP</div>
<?php if(empty($get_instore_pickup)) { ?>
<div class="instore radio">
<input type="checkbox" name="in_store_pickup" value="yes" onclick="sendvalue(this.value,'<?php echo $_REQUEST['id']?>','<?php echo $shop; ?>')">
<label>
In Store Pickup
</label>
<!-- <center><input class="btn btn-primary btn-sm" type="submit" value="Submit"></center> -->
</div>
<?php } else { ?>
<div class="green green-checked"><a href="" onclick="delete_instore_picker('<?php echo $_REQUEST['id'];?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div>
<?php }?>
</div>
<?php } }
?>

<div class="col-md-2 col-sm-12" style="margin-top:10px">
<?php if($orders->order->tags == 'Double-Check' ){ ?>
<div class="hdd">FULFILL ORDER</div>
<div class="green green-checked"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div>
<?php } else {  ?>
<div class="hdd">FULFILL ORDER</div>
<div class="instore radio">
<input type="checkbox" name="fulfilled_order" value="yes" onclick="create_fulfilled_order('<?php echo $_REQUEST['id']?>','<?php echo $shop; ?>')">
<label>
FulFill Order
</label>
<?php } ?>
</div>
</div>

</div>
</div>
</div>


<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<DIV class="hdd">PRODUCT DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-responsive mytable main-design">
  <tr class="heading_tr">
    <td width="25%" class="hed" style="text-align:left">PRODUCT TITLE</td>
    <td width="8%" class="hed">QUANTITY</td>
    <?php if($orders->order->tags == 'Double-Check'){ ?>
    <td width="8%" class="hed">SCANNED QUANTITY</td>
    <?php } else { ?>
    <td width="8%" class="hed">SCANNED QUANTITY
    </td>
    <?php } ?>
    <td width="14%" class="hed">PRICE</td>
    <td width="15%" class="hed">SKU</td>
    <?php if($get_verification['verification_step'] == 'One' || $get_verification['verification_step'] == 'Six') {  
?>
    <td width="8%" class="hed">PICKED</td>
    <?php } ?>
    <?php  if($get_verification['verification_step'] == 'Two' || $get_verification['verification_step'] == 'Eight') {  
?>
     <td width="9%" class="hed">SHIPPED</td>
    <?php } ?>
    <?php  if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Four' || $get_verification['verification_step'] == 'Ten') {  
?>
     <td width="9%" class="hed">READY FOR PICKUP</td>
   <?php } } ?>
   <?php  if($get_verification['verification_step'] == 'Five') {  
?>
     <td width="8%" class="hed">PICKED</td>
     <td width="9%" class="hed">SHIPPED</td>
   <?php } ?>
   <?php  if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Seven') {  
?>
     <td width="8%" class="hed">PICKED</td>
     <td width="15%" class="hed">READY FOR PICKUP</td>
   <?php } } ?>
   <?php  if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Nine') {  
?>
     <td width="9%" class="hed">SHIPPED</td>
     <td width="15%" class="hed">READY FOR PICKUP</td>
   <?php } } ?>
   <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Eleven') {  
?>
     <td width="8%" class="hed">PICKED</td>
     <td width="9%" class="hed">SHIPPED</td>
     <td width="15%" class="hed">READY FOR PICKUP</td>
   <?php } } ?>
    
  </tr>
  <tr>
  <td></td>
  <td></td>
  <td class="scanned_table">
 <table class="table table-responsive mytable scanned_qty_table" style="margin-bottom: 0">
   <tr>
    <?php if($get_verification['verification_step'] == 'One' || $get_verification['verification_step'] == 'Six') {  
?>

<td width="8%" class="hed step_hed"> <span class="sroles">Picker</span></td>

<?php } ?>
    <?php if($get_verification['verification_step'] == 'Two' || $get_verification['verification_step'] == 'Eight') {  
?>
 <td width="8%" class="hed step_hed"> <span class="sroles">Shipper</span></td>

<?php } ?>
<?php if(in_array("ready for pickup", $get_single_role)){  if($get_verification['verification_step'] == 'Four' || $get_verification['verification_step'] == 'Ten') {  
?>
 <td width="8%" class="hed step_hed"> <span class="sroles">Ready</span></td>

<?php } }  ?>
<?php if($get_verification['verification_step'] == 'Five') {  
?>
  <td width="8%" class="hed step_hed"> <span class="sroles">Picker</span></td>
 <td width="8%" class="hed step_hed"> <span class="sroles">Shipper</span></td>

<?php } ?>
<?php if(in_array("ready for pickup", $get_single_role)){  if($get_verification['verification_step'] == 'Seven') {  
?>
  <td width="8%" class="hed step_hed"> <span class="sroles">Picker</span></td>
  <td width="8%" class="hed step_hed"> <span class="sroles">Ready</span></td>

<?php } } ?>
<?php if(in_array("ready for pickup", $get_single_role)){  if($get_verification['verification_step'] == 'Nine') {  
?>
  <td width="8%" class="hed step_hed"> <span class="sroles">Shipper</span></td>
  <td width="8%" class="hed step_hed"> <span class="sroles">Ready</span></td>

<?php } } ?>
<?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Eleven') {  
?>
 <td width="8%" class="hed step_hed"> <span class="sroles">Picker</span></td>
 <td width="8%" class="hed step_hed"> <span class="sroles">Shipper</span></td>
 <td width="8%" class="hed step_hed"> <span class="sroles">Ready</span></td>

<?php } } ?>

 </tr>
    </table>
	</td>
	<td></td>
	<td></td>
	 <?php if($get_verification['verification_step'] == 'One' || $get_verification['verification_step'] == 'Six') {  
?>
    <td></td>
    <?php } ?>
    <?php  if($get_verification['verification_step'] == 'Two' || $get_verification['verification_step'] == 'Eight') {  
?>
     <td></td>
    <?php } ?>
    <?php  if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Four' || $get_verification['verification_step'] == 'Ten') {  
?>
     <td></td>
   <?php } } ?>
   <?php  if($get_verification['verification_step'] == 'Five') {  
?>
     <td></td>
     <td></td>
   <?php } ?>
   <?php  if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Seven') {  
?>
     <td></td>
     <td></td>
   <?php } } ?>
   <?php  if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Nine') {  
?>
     <td></td>
     <td></td>
   <?php } } ?>
   <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Eleven') {  
?>
     <td></td>
     <td></td>
     <td></td>
   <?php } } ?>
  </tr>
   <?php  $arrayobj = new ArrayObject($orders->order->line_items);
       $line_item_count = $arrayobj->count();
       for($i=0;$i<$line_item_count;$i++)
       {
       	ob_start();
       	$qty =  $orders->order->line_items[$i]->quantity;
       	$sum += $qty;
     ?>
  <tr>
    <td align="left"><?php echo $orders->order->line_items[$i]->name; ?></td>
    <td><?php echo $orders->order->line_items[$i]->quantity; ?></td>
    <?php if($orders->order->tags == 'Double-Check'){ ?>
    <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
    <?php } else { ?>
    <td class="scaaned_table">
         <table class="table table-responsive mytable remove-border" style="margin-bottom: 0;border:0">
         <tr class="">
         <!--  one & six step verifictaion starts -->
          <?php if($get_verification['verification_step'] == 'One' || $get_verification['verification_step'] == 'Six') {  
          ?>
          <!--  picker qty check starts -->
         <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
         $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  picker qty check ends-->
               
          <?php } ?>
        <!--  one & six step verifictaion ends -->
          
        <!--  two & eight step verifictaion starts -->
          
          <?php if($get_verification['verification_step'] == 'Two' || $get_verification['verification_step'] == 'Eight') {  
          ?>
          <!--  shipper qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($s_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $s_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  shipper qty check ends -->
        <?php } ?>
         
         <!--  two & eight step verifictaion ends -->
         
         <!--  four & ten step verifictaion starts -->
          
        <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Four' || $get_verification['verification_step'] == 'Ten' ) {  
        ?>
        <!--  ready for pickup qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($r_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $r_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  ready for pickup qty check ends -->
        <?php } } ?>
         
         <!--  four & ten step verifictaion ends -->
         
         
        <!--  five step verifictaion starts -->
          
        <?php if($get_verification['verification_step'] == 'Five') {  
        ?>
         <!--  picker qty check starts -->
         <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
         $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  picker qty check ends-->
        
          <!--  shipper qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($s_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $s_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  shipper qty check ends -->
        <?php } ?>
         
         <!--  five step verifictaion ends -->
         
         <!--  seven step verifictaion starts -->
          
        <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Seven') {  
        ?>
         <!--  picker qty check starts -->
         <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
         $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  picker qty check ends-->
        <!--  ready for pickup qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($r_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $r_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  ready for pickup qty check ends -->
        <?php } } ?>
         
         <!--  seven step verifictaion ends -->
         
          <!--  nine step verifictaion starts -->
          
        <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Nine') {  
        ?>
           <!--  shipper qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($s_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $s_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  shipper qty check ends -->
        
        <!--  ready for pickup qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($r_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $r_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  ready for pickup qty check ends -->
        <?php } } ?>
         
         <!--  nine step verifictaion ends -->
         
         
         <!--  eleven step verifictaion starts -->
         
         <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Eleven') {  
          ?>
            <!--  picker qty check starts -->
         <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
         $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  picker qty check ends-->
        
          <!--  shipper qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($s_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $s_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  shipper qty check ends -->
        
        <!--  ready for pickup qty check starts -->
        <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
        $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($r_get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td><?php echo $r_get_order_veri_sku['quantity']; ?></td>
        <?php } }  ?>
        <!--  ready for pickup qty check ends -->
          <?php } } ?>
          
          <!--  eleven step verifictaion ends -->
        
        </tr>
        </table>
        </td>
        <?php } ?>
    <td><?php echo $orders->order->line_items[$i]->price; ?></td>
    <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
    	if($variants->variant->sku != '' ){ 
    ?>
    <td><?php echo $variants->variant->sku; ?></td>
    
    
    <?php } else { 
    	?>
    	<td>-</td>
    <?php 
    } 
    ?>
    <!-- one & six step verification starts -->
    <?php if($get_verification['verification_step'] == 'One' || $get_verification['verification_step'] == 'Six') {  
?>
<?php if($orders->order->tags == 'Double-Check'){ ?>
<td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
<?php }  else { ?>
    <?php 
    // Picker verification starts
    
    $get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok' || $get_order_veri_sku['verification']== 'Picker') {
       if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){  ?>
               <td><div class="green"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $get_order_veri_sku['quantity'] != 0){ ?>
    	       <td><div class="yellow"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
       <?php } else {  
    	     	?>
    	 <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
    	<?php }
    	}  else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php }  
        
        // picker verification ends
        
        ?>
      <?php } } ?>
      
      <!-- one & six step verification end -->
      
      <!-- two & eight step verification starts -->
      
      <?php  if($get_verification['verification_step'] == 'Two' || $get_verification['verification_step'] == 'Eight') {  
  ?>
      <?php if($orders->order->tags == 'Double-Check'){ ?>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <?php }  else { ?>
        
      <?php
      
      // Shipper verification starts
      
        $s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') { 
    		if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){ ?>
    	<td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $s_get_order_veri_sku['quantity'] != 0) {
    	?>
    	<td><div class="yellow"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php  
    	} else { 
    		?>
    		<td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php 
    	}
    	} else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php }
        
        // shipper verification ends
        
        ?>
        
     <?php } } ?>
    <!-- two & eight step verification end -->
    
    <!-- four &  ten verification starts -->
      
      <?php  if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Four' || $get_verification['verification_step'] == 'Ten') {  
  ?>
      <?php if($orders->order->tags == 'Double-Check'){ ?>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <?php }  else { ?>
        
      <?php
      
      // Receiver verification starts
      
      $r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
      $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
      if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok' || $r_get_order_veri_sku['verification']== 'Receiver') {
      	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
      		?>
         		<td><div class="green"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
            <?php 
         	} else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $r_get_order_veri_sku['quantity'] != 0){
	        ?>
        <td><div class="yellow"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        <?php } else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php } ?>
            <?php } else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php } 
        
        // Receiver verification ends
        
        ?>
    
      <?php } } } ?>
    <!-- four & ten step verification end -->
    
     <!-- five verification starts -->
      
      <?php  if($get_verification['verification_step'] == 'Five') {  
  ?>
      <?php if($orders->order->tags == 'Double-Check'){ ?>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <?php }  else { ?>
        
      <?php
      
      // Picker verification starts
      
      $get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
      $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
      if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok' || $get_order_veri_sku['verification']== 'Picker') {
       if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){  ?>
               <td><div class="green"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $get_order_veri_sku['quantity'] != 0){ ?>
    	       <td><div class="yellow"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
       <?php } else {  
    	     	?>
    	 <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
    	<?php }
    	}  else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php }  
        
        // picker verification ends
        
        // Shipper verification starts
        
        $s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        $s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') {
    		if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){ ?>
    	<td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $s_get_order_veri_sku['quantity'] != 0) {
    	?>
    	<td><div class="yellow"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php  
    	} else { 
    		?>
    		<td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php 
    	}
    	} else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php }
        
        // shipper verification ends
        
        ?>
    
      <?php } } ?>
    <!-- five step verification end -->
    
     <!-- seven verification starts -->
      
      <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Seven') {  
  ?>
      <?php if($orders->order->tags == 'Double-Check'){ ?>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <?php }  else { ?>
        
      <?php
      
      // Picker verification starts
      
      $get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
      $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
      if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok' || $get_order_veri_sku['verification']== 'Picker') {
       if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){  ?>
               <td><div class="green"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $get_order_veri_sku['quantity'] != 0){ ?>
    	       <td><div class="yellow"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
       <?php } else {  
    	     	?>
    	 <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
    	<?php }
    	}  else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php }  
        
        // picker verification ends
        
        // Receiver verification starts
        
        $r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok' || $r_get_order_veri_sku['verification']== 'Receiver') {
        	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
        		?>
         		<td><div class="green"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
            <?php 
         	} else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $r_get_order_veri_sku['quantity'] != 0){
	        ?>
        <td><div class="yellow"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        <?php } else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php } ?>
            <?php } else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php } 
        
        // Receiver verification ends
        
        ?>
    
      <?php } } } ?>
    <!-- seven step verification end -->
    
    <!-- nine verification starts -->
      
      <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Nine') {  
  ?>
      <?php if($orders->order->tags == 'Double-Check'){ ?>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
      <?php }  else { ?>
        
      <?php
      
      // Shipper verification starts
      
      $s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
      $s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
      if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') {
    		if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){ ?>
    	<td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $s_get_order_veri_sku['quantity'] != 0) {
    	?>
    	<td><div class="yellow"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php  
    	} else { 
    		?>
    		<td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php 
    	}
    	} else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php }
        
        // shipper verification ends
        
        // Receiver verification starts
        
        $r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok' || $r_get_order_veri_sku['verification']== 'Receiver') {
        	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
        		?>
         		<td><div class="green"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
            <?php 
         	} else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $r_get_order_veri_sku['quantity'] != 0){
	        ?>
        <td><div class="yellow"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        <?php } else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php } ?>
            <?php } else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php } 
        
        // Receiver verification ends
        
        ?>
    
      <?php } } } ?>
    <!-- nine step verification end -->
    
    
    <!-- eleven step verification starts -->
    
     <?php if(in_array("ready for pickup", $get_single_role)){ if($get_verification['verification_step'] == 'Eleven') {  
  ?>
  <?php if($orders->order->tags == 'Double-Check'){ ?>
  <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
  <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
  <td><div class="green"><a href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
<?php } else { ?>
    	  <?php 
        // Picker verification
       $get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
       $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
       if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok' || $get_order_veri_sku['verification']== 'Picker') {
       if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){  ?>
               <td><div class="green"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $get_order_veri_sku['quantity'] != 0){ ?>
    	       <td><div class="yellow"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
       <?php } else {  
    	     	?>
    	 <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
    	<?php }
    	}  else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" /><label style="padding: 0;">&nbsp;</label></span></td>
        <?php }  ?>
        
      <?php
      
      // Shipper verification  
      
        $s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') { 
    		if($s_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){ ?>
    	<td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($s_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $s_get_order_veri_sku['quantity'] != 0) {
    	?>
    	<td><div class="yellow"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php  
    	} else { 
    		?>
    		<td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" />
    		<label style="padding: 0;">&nbsp;</label>
    		</span></td>
        <?php 
    	}
    	} else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>','<?php echo $shop; ?>')" />
        <label style="padding: 0;">&nbsp;</label>
        </span></td>
        <?php } ?>
    
    <?php
      
      // Receiver verification  
         $r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
         $r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok' || $r_get_order_veri_sku['verification']== 'Receiver') { 
         	if($r_get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){ 
         		?>
         		<td><div class="green"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
            <?php 
         	} else if($r_get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $r_get_order_veri_sku['quantity'] != 0){
	        ?>
        <td><div class="yellow"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        <?php } else { ?>
        <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" />
        <label style="padding: 0;">&nbsp;</label>
        </span></td>
        <?php } ?>
            <?php } else { ?>
               <td><span class="radio"><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $shop; ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" />
               <label style="padding: 0;">&nbsp;</label>
               </span></td>
        <?php }  ?>
        
        <?php } } } ?>
   <!-- eleven step verification end -->
  </tr>
  <?php ob_end_flush(); 
    } ?>

  
</table>


</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12">
<textarea  style="resize:none" class="txtarea" placeholder="Customer Notes" readonly><?php if($orders->order->note != '' ){ echo $orders->order->note; } ?></textarea>

</div>

</div>
</div>
</div>
</form>
<?php 
if($get_verification['fulfill_order'] == 'On') {
	$rcount= $Stores->r_verified_orders($_REQUEST['id']);
	if(!empty($rcount)){
		
		if($rcount['sum(quantity)'] == $sum ){
			
				//echo "hi";
			$create_fulfillment = $Shopify->create_fulfillment_order($shop, $shop_info['access_token'],$_REQUEST['id'],array("fulfillment"=>array("id"=>"","order_id"=>$_REQUEST['id'],"status"=>"success","service"=>"manual")));
			$updateorder = $Shopify->updateOrderInfo($shop, $shop_info['access_token'],$_REQUEST['id'],array("order" =>array("tags" => "Double-Check")));
			//header("location:/scan-and-ship/app/order_detailed_page.php/?shop=$shop&&id=$get_order_id");
		
		}
	}
}
?>
<script>
function selected_radio(rad,shop){
	//alert('hi');
	var selected_rval = rad;
	var xhttp = new XMLHttpRequest();
	 
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "role.php?selected_rval="+rad+"&shop="+shop, true);
	  xhttp.send();
	 setTimeout(function(){ window.location.reload(); }, 1000);
}
function create_fulfilled_order(forder_id,shop){
 var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "fulfilled_order.php?shop="+shop+"&order_id="+forder_id, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.reload(); }, 2000);
}
function delete_instore_picker(in_order,shop){
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "delete_instore_pickup.php?shop="+shop+"&order_id="+in_order, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.reload(); }, 2000);
}
function sendvalue(a,b,shop){
	var chckbx_val = a;
	var order_id = b;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "ajax_call.php?shop="+shop+"&chkbx_val="+chckbx_val+"&order_id="+order_id, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.reload(); }, 2000);
}
function send_picker_value(o,s,ro,qty,shop){
	var select_role = $("input[type='radio']:checked").val();
	//alert(select_role);
	if(select_role != 'Picker ok'){
     alert('Please select correct role !!');
		}
	else {
	var porder_id = o;
	var sku = s;
	if(ro == '' ){ 
		var prole = 'Picker ok';
    }
	else {
		var prole = ro;
		}
	
	
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "picker_ajax_call.php?shop="+shop+"&sku="+sku+"&order_id="+porder_id+"&role="+prole+"&qty="+qty, true);
	  xhttp.send();
	  setTimeout(function(){  window.location.reload(); }, 2000);
	}
}
function send_shipper_value(so,ss,sro,sqty,shop){
	var select_role = $("input[type='radio']:checked").val();
	//alert(select_role);
	if(select_role != 'Shipper ok'){
     alert('Please select correct role !!');
		} else {
	var sorder_id = so;
	var ssku = ss;
	if(sro == '' ){ 
		var srole = 'Shipper ok';
    }
	else {
		var srole = sro;
		}
	
	
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "shipper_ajax_call.php?shop="+shop+"&sku="+ssku+"&order_id="+sorder_id+"&role="+srole+"&qty="+sqty, true);
	  xhttp.send();
		
	  setTimeout(function(){ window.location.reload(); }, 2000);
		}
}
function send_receiver_value(ro,rs,rro,shop,rqty){
	var select_role = $("input[type='radio']:checked").val();
	//alert(select_role);
	if(select_role != 'Receiver ok'){
     alert('Please select correct role !!');
		} else {
	var rorder_id = ro;
	var rsku = rs;
	if(rro == '' ){ 
		var rrole = 'Receiver ok';
    }
	else {
		var rrole = rro;
		}
	
	
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "receiver_ajax_call.php?shop="+shop+"&sku="+rsku+"&order_id="+rorder_id+"&role="+rrole+"&qty="+rqty, true);
	  xhttp.send();
	    setTimeout(function(){ window.location = "http://aviaapps.co/double-check/app/order_test.php/?shop="+shop+"&order_id="+rorder_id; }, 2000);
     	}
}

function delete_picker_order(dorder , dsku,shop){
	var dorder = dorder;
	var dsku = dsku;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "delete_ajax.php?shop="+shop+"&dorder="+dorder+"&dsku="+dsku, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.reload(); },2000);
}
function delete_shipper_order(dsorder , dssku,shop){
	var dsorder = dsorder;
	var dssku = dssku;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "delete_ajax.php?shop="+shop+"&dsorder="+dsorder+"&dssku="+dssku, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.reload(); }, 2000);
}
function delete_receiver_order(drorder , drsku,shop){
	var drorder = drorder;
	var drsku = drsku;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "delete_ajax.php?shop="+shop+"&drorder="+drorder+"&drsku="+drsku, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.reload(); }, 2000);
} 
</script>
<?php include 'footer.php'; ?>