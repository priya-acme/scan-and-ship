<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =   $_SESSION[$shop];
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
					header("location:/scan-and-ship/app/order_detailed_page.php/?id=$get_order_id");
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
						header("location:/scan-and-ship/app/order_detailed_page.php/?id=$get_order_id");
					}
				}
			}
			
			// shipper
			
			if($selected_role == 'Shipper ok' || $selected_role== 'Shipper' ){
	        	$s_check_order_veri = $Stores->s_check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
	        	if(empty($s_check_order_veri)){
	        		$Stores->s_order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role);
	        	}
	        }
	        if($selected_role == 'Receiver ok' || $selected_role== 'Receiver' ){
	        	$r_check_order_veri = $Stores->r_check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
	        	if(empty($r_check_order_veri)){
	        		$Stores->r_order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role);
	        	}
	        }
	        //break;
		}
		
		
	}
	//echo $k;
	if($k == 1){
		$error_qty = "All item quantities are scanned";
	}
	if($j == 1){
		$error == '';
	}
	else {
		$error = "Product scanned doesn't match" ;
	}
}
if(isset($_POST['save_notes'])){
	$order_notes = $_POST['order_note'];
	$order_id=$_REQUEST['id'];
	if(!empty($order_notes)){
		$Stores->add_order_note($_REQUEST['id'], $order_notes);
		header("location:/scan-and-ship/app/order_detailed_page.php/?id=$order_id");
	}
}
if(isset($_POST['update_notes'])){
	$uorder_notes = $_POST['update_order_note'];
	$uorder_id=$_REQUEST['id'];
		$Stores->update_order_note($_REQUEST['id'], $uorder_notes);
		header("location:/scan-and-ship/app/order_detailed_page.php/?id=$uorder_id");

}
$get_order_note = $Stores->get_order_note($_REQUEST['id']);
$get_instore_pickup = $Stores->gett_instore_pickup($_REQUEST['id']);
if(isset($_POST['submit_id']) || isset($_POST['pressed_button']) == 'false'){
	$j = 0;
	//echo $_POST['pressed_button'];
	$order_id = $_POST['order_id'];
	$_SESSION['select_role'] = $_POST['select_role'];
	$shop_info = $Stores->is_shop_exists($shop);
	$get_order = $Shopify->get_orders($shop,$shop_info['access_token']);
	foreach($get_order->orders as $order) {
		if($order_id == $order->name || $order_id == $order->id){
			$j = 1;
			header("location:/scan-and-ship/app/order_detailed_page.php/?id=$order->id");
		}
	}
// 	if($j == 1){
		
// 	}
// 	else {
// 		$error = "Order number doesn't match" ;
// 	}
	//echo "<script>$('.qty-error-message').hide();</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Double Check - <?php echo $_GET['id']?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="../css/style.css" type="text/css">
  <link rel="stylesheet" href="../font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

</head>
<body class="order-details-page" OnLoad="document.form_submit.barcode_sku.focus();">
<form method="post" name="form_submit" id="form_submit">
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12">
<div class="right-icon">
<div class="order-btn">
<a class="order" href="/scan-and-ship/app/summary_page.php">BACK TO ORDER LOOKUP</a>
</div>
<a href="/scan-and-ship/app/settings.php" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-4">
 <div class="role2">
    BARCODE / PRODUCT CODE  
    <input type="text" name="barcode_sku" class="txt" value=""> 
    <input type="hidden" name="pressed_button" id="pressed_button1" value="false">
     <button type="submit" class="serch" name="submit_barcode" onclick="document.getElementById('pressed_button1').value='true';document.getElementById('form_submit').submit();">
      <span class="glyphicon glyphicon-search"></span>
     </button>
  
    </div>
    
 <?php if(isset($_POST['submit_barcode'])){ ?> <div class="error-message" style="color:red"><?php echo $error; ?></div><?php } ?>
   <?php if(isset($_POST['submit_barcode'])){ ?> <div class="qty-error-message" style="color:red"><?php echo $error_qty; ?></div><?php } ?>
</div>
<div class="col-sm-12 col-md-4">
 <div class="role2">
  ORDER LOOKUP 
    <input type="text" class="txt" name="order_id" id="order_id"> 
    <input type="hidden" name="pressed_button" id="pressed_button" value="false">
     <button type="submit" class="serch" name="submit_id" id="submit_id" onclick="document.getElementById('pressed_button').value='true';document.getElementById('form_submit').submit();">
      <span class="glyphicon glyphicon-search"></span>
     </button>
     </div>
</div>

<div class="col-sm-12 col-md-4 no-wrap">

<span class="role2">SELECT ROLE : </span>
<span class="radio radio-primary">
<?php if($get_verification['verification_step'] == 'One') {  
	?>
<input type="radio" name="select_role" id="radio1" value="Picker ok" onclick="selected_radio(this.value,'<?php echo $pget_order_id ?>')" <?php if($_SESSION['select_role'] == 'Picker ok') { echo "checked"; } else { echo "checked"; } ?>>
<label for="radio1">
 PICKER
</label>
<?php 
}  if($get_verification['verification_step'] == 'Two') { ?>
            <input type="radio" name="select_role" id="radio1" value="Picker ok" onclick="selected_radio(this.value,'<?php echo $pget_order_id ?>')" <?php if($_SESSION['select_role'] == 'Picker ok') { echo "checked"; } else { echo "checked"; }?>>
            <label for="radio1">
                PICKER
            </label>
            <input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value,'<?php echo $pget_order_id ?>')" <?php if($_SESSION['select_role'] == 'Shipper ok') { echo "checked"; } ?>>
            <label for="radio2">
                SHIPPER
            </label>
<?php } if($get_verification['verification_step'] == 'Three') {?>
            <input type="radio" name="select_role" id="radio1" value="Picker ok" onclick="selected_radio(this.value,'<?php echo $pget_order_id ?>')" <?php if($_SESSION['select_role'] == 'Picker ok') { echo "checked"; }else { echo "checked"; }?>>
            <label for="radio1">
                PICKER
            </label>
            <input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value,'<?php echo $pget_order_id ?>')" <?php if($_SESSION['select_role'] == 'Shipper ok') { echo "checked"; } ?>>
            <label for="radio2">
                SHIPPER
            </label>
            <input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value,'<?php echo $pget_order_id ?>')" <?php if($_SESSION['select_role'] == 'Receiver ok') { echo "checked"; } ?>>
            <label for="radio3">
                READY FOR PICKUP
            </label>
<?php }?>
          
 
</span>

</div>
<!-- <div class="col-sm-12 col-md-7"> -->
<!-- <div class="role4">SELECT ROLE</div> -->
<!-- <div class="role3"><input type = "radio" value="Picker" name="select_role" class="select_role" <?php if($_SESSION['select_role'] == 'Picker' ){ echo "checked"; } else { echo "checked"; } ?>>Picker</div>
 <div class="role3"><input type = "radio" value="Shipper" name="select_role" class="select_role" <?php if($_SESSION['select_role'] == 'Shipper' ){ echo "checked"; }?>>Shipper</div>
<div class="role3"><input type = "radio" value="Receiver" name="select_role" class="select_role" <?php if($_SESSION['select_role'] == 'Receiver' ){ echo "checked"; }?>>Receiver</div> 
<!-- <div class="right-icon"> -->
<!-- <a href="" class="seting-icon"> -->
<!-- <i class="fa fa-cog" aria-hidden="true"></i> -->
<!-- </a> -->
<!-- </div> -->
<!-- </div> -->
</div>
</div>
</div>

<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-5 col-sm-12">
<div class="hdd">ORDER DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
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
   <td><textarea name="update_order_note" class="text-area" placeholder="Internal order notes"><?php echo $get_order_note['order_note'];  ?></textarea></td>
   
  </tr>
  <tr>
  <td colspan="2"><input class="btn btn-primary" type="submit" name="update_notes" value="Update Notes"></td>
  </tr>
  <?php }  else {
  	?>
  <tr>
   <td><strong>Internal Notes</strong></td>
   <td><textarea name="order_note" class="text-area" placeholder="Internal order notes"></textarea>
   
   </td>
  </tr>
  <tr>
  <td colspan="2"><input class="btn btn-primary" type="submit" name="save_notes" value="Save Notes"></td>
  </tr>
  <?php 
  }?>
</table>

</div>
<div class="col-md-5 col-sm-12">
<DIV class="hdd">SHIPPING DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
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
<?php if($get_verification['verification_step'] == 'Three') {  	?>
<div class="col-md-2 col-sm-12">
<div class="hdd">IN-STORE PICKUP</div>
<?php if(empty($get_instore_pickup)) { ?>
<div class="instore">
<input type="checkbox" name="in_store_pickup" value="yes" onclick="sendvalue(this.value,'<?php echo $_REQUEST['id']?>')">
<label>
In Store Pickup
</label>
<!-- <center><input class="btn btn-primary btn-sm" type="submit" value="Submit"></center> -->
</div>
<?php } else { ?>
<div class="green green-checked"><a href="" onclick="delete_instore_picker('<?php echo $_REQUEST['id'];?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div>
<?php }?>
</div>
<?php }
?>
<?php if($get_verification['fulfill_order'] == 'On') { ?>
<div class="col-md-2 col-sm-12" style="margin-top:10px">
<?php if($orders->order->fulfillment_status == 'fulfilled' ){ ?>
<div class="hdd">FULFILL ORDER</div>
<div class="green green-checked"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div>
<?php } else {  ?>
<div class="hdd">FULFILL ORDER</div>
<div class="instore">
<input type="checkbox" name="fulfilled_order" value="yes" onclick="create_fulfilled_order('<?php echo $_REQUEST['id']?>')">
<label>
FulFill Order
</label>
<?php } ?>
</div>
</div>
<?php } ?>
</div>
</div>
</div>


<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<DIV class="hdd">PRODUCT DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td width="29%" class="hed" style="text-align:left">PRODUCT TITLE</td>
    <td width="8%" class="hed">ORDERED QUANTITY</td>
    <td width="8%" class="hed">SCANNED QUANTITY</td>
    <td width="8%" class="hed">PRICE</td>
    <td width="17%" class="hed">SKU</td>
    <?php if($get_verification['verification_step'] == 'One') {  
	?>
    <td width="8%" class="hed">PICKED</td>
    <?php } ?>
    <?php  if($get_verification['verification_step'] == 'Two') {  
	?>
    <td width="8%" class="hed">PICKED</td>
     <td width="9%" class="hed">SHIPPED</td>
    <?php } ?>
   <?php  if($get_verification['verification_step'] == 'Three') {  
	?>
     <td width="8%" class="hed">PICKED</td>
     <td width="9%" class="hed">SHIPPED</td>
     <td width="9%" class="hed">READY FOR PICKUP</td>
   <?php } ?>
    
  </tr>
   <?php  $arrayobj = new ArrayObject($orders->order->line_items);
       $line_item_count = $arrayobj->count();
       for($i=0;$i<$line_item_count;$i++)
       {
     ?>
  <tr>
    <td align="left"><?php echo $orders->order->line_items[$i]->name; ?></td>
    <td><?php echo $orders->order->line_items[$i]->quantity; ?></td>
  
         <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
         $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
         if(empty($get_order_veri_sku)){
    	?>
    	 <td>0</td>
        <?php 
        } else {
        	if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){
    	?>
    	 <td style="background-color:green"><?php echo $orders->order->line_items[$i]->quantity ?></td>
        <?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity){ 
    	?>
    	 <td style="background-color:#e8f400"><?php echo $get_order_veri_sku['quantity']; ?></td>
        <?php } } 
     ?>
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
    <!-- one step verification starts -->
    <?php if($get_verification['verification_step'] == 'One') {  
	?>
    <?php 
    // Picker verification
    $get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok' || $get_order_veri_sku['verification']== 'Picker') {
       if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){  ?>
               <td><div class="green"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $get_order_veri_sku['quantity'] != 0){ ?>
    	       <td><div class="yellow"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
       <?php } else {  
    	     	?>
    	 <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /></td>
    	<?php }
    	}  else { ?>
               <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /></td>
        <?php }  ?>
      
      <?php } ?>
      
      <!-- one step verification end -->
      
      <!-- two step verification starts -->
      
      <?php  if($get_verification['verification_step'] == 'Two') {  
	  ?>
	  
	  <?php 
        // Picker verification
       $get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
       $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
       if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok' || $get_order_veri_sku['verification']== 'Picker') {
       if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){  ?>
               <td><div class="green"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $get_order_veri_sku['quantity'] != 0){ ?>
    	       <td><div class="yellow"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
       <?php } else {  
    	     	?>
    	 <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /></td>
    	<?php }
    	}  else { ?>
               <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /></td>
        <?php }  ?>
        
      <?php
      
      // Shipper verification  
      
        if(isset($_POST['submit_barcode'])){ 
    	$barcode_sku = $_POST['barcode_sku'];
    	$s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') { ?>
    	<td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else { ?>
        <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>')" /></td>
        <?php } ?>
        <?php } else { 
        	$s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        	if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') { ?>
               <td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        	<?php } else { ?>
               <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>')" /></td>
        <?php } } ?>
    
    <?php } ?>
    <!-- two step verification end -->
    
    <!-- three step verification starts -->
    
     <?php if($get_verification['verification_step'] == 'Three') {  
	  ?>
    	  <?php 
        // Picker verification
       $get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
       $get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
       if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok' || $get_order_veri_sku['verification']== 'Picker') {
       if($get_order_veri_sku['quantity'] == $orders->order->line_items[$i]->quantity){  ?>
               <td><div class="green"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else if($get_order_veri_sku['quantity'] != $orders->order->line_items[$i]->quantity && $get_order_veri_sku['quantity'] != 0){ ?>
    	       <td><div class="yellow"><a href="" onclick="delete_picker_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
       <?php } else {  
    	     	?>
    	 <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /></td>
    	<?php }
    	}  else { ?>
               <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_picker_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>','<?php echo $orders->order->line_items[$i]->quantity?>')" /></td>
        <?php }  ?>
        
      <?php
      
      // Shipper verification  
      
        if(isset($_POST['submit_barcode'])){ 
    	$barcode_sku = $_POST['barcode_sku'];
    	$s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') { ?>
    	<td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else { ?>
        <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>')" /></td>
        <?php } ?>
        <?php } else { 
        	$s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        	if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok' || $s_get_order_veri_sku['verification']== 'Shipper') { ?>
               <td><div class="green"><a href="" onclick="delete_shipper_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        	<?php } else { ?>
               <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_shipper_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>')" /></td>
        <?php } } ?>
 
    
     <?php
      
      // Receiver verification  
      
         if(isset($_POST['submit_barcode'])){ 
    	$barcode_sku = $_POST['barcode_sku'];
    	$r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok' || $r_get_order_veri_sku['verification']== 'Receiver') { ?>
    	<td><div class="green"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else { ?>
        <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>')" /></td>
        <?php } ?>
        <?php } else { 
        	$r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        	$r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        	if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok' || $r_get_order_veri_sku['verification']== 'Receiver') { ?>
               <td><div class="green"><a href="" onclick="delete_receiver_order('<?php echo $pget_order_id?>','<?php echo $variants->variant->sku ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        	<?php } else { ?>
               <td><input type="checkbox" value="<?php echo $variants->variant->sku ?>" onclick="send_receiver_value('<?php echo $pget_order_id ?>',this.value,'<?php echo $pselect_role ?>')" /></td>
        <?php } }  ?>
        <?php }  ?>
 <!-- three step verification end -->
  </tr>
  <?php } ?>

  
</table>


</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12">
<textarea  class="txtarea" placeholder="Customer Notes" readonly><?php if($orders->order->note != '' ){ echo $orders->order->note; } ?></textarea>

</div>

</div>
</div>
</div>
</form>
<script>

function create_fulfilled_order(forder_id){
 var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "../fulfilled_order.php?order_id="+forder_id, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+forder_id; }, 1000);
}
function delete_instore_picker(in_order){
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "../delete_instore_pickup.php?order_id="+in_order, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+in_order; }, 1000);
}
function sendvalue(a,b){
	var chckbx_val = a;
	var order_id = b;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "../ajax_call.php?chkbx_val="+chckbx_val+"&order_id="+order_id, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+b; }, 1000);
}
function send_picker_value(o,s,ro,qty){
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
	  xhttp.open("GET", "../picker_ajax_call.php?sku="+sku+"&order_id="+porder_id+"&role="+prole+"&qty="+qty, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+porder_id; }, 1000);
}
function send_shipper_value(so,ss,sro){
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
	  xhttp.open("GET", "../shipper_ajax_call.php?sku="+ssku+"&order_id="+sorder_id+"&role="+srole, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+so; }, 1000);
}
function send_receiver_value(ro,rs,rro){
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
	  xhttp.open("GET", "../receiver_ajax_call.php?sku="+rsku+"&order_id="+rorder_id+"&role="+rrole, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+ro; }, 1000);
}
function selected_radio(r,order){
	var selected_rval = r;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "../role.php?selected_rval="+selected_rval, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+order; }, 1000);
}
function delete_picker_order(dorder , dsku){
	var dorder = dorder;
	var dsku = dsku;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "../delete_ajax.php?dorder="+dorder+"&dsku="+dsku, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+dorder; }, 1000);
}
function delete_shipper_order(dsorder , dssku){
	var dsorder = dsorder;
	var dssku = dssku;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "../delete_ajax.php?dsorder="+dsorder+"&dssku="+dssku, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+dsorder; }, 1000);
}
function delete_receiver_order(drorder , drsku){
	var drorder = drorder;
	var drsku = drsku;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "../delete_ajax.php?drorder="+drorder+"&drsku="+drsku, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://aviaapps.co/scan-and-ship/app/order_detailed_page.php/?id='+drorder; }, 1000);
}
</script>
<?php include 'footer.php'; ?>