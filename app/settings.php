<?php
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_REQUEST['shop'];
$shop_info = $Stores->is_shop_exists($shop);
$orders = $Shopify->get_orders($shop, $shop_info['access_token']);
$get_verification = $Stores->get_step_verification($shop);
if(isset($_POST['save_changes'])){
	$get_verification = $Stores->get_step_verification($shop);
	if(empty($get_verification)){
		$Stores->step_verification($_POST['select_veri'],$_POST['fulfill_order'],$shop);
		header("location:/double-check/app/settings.php?shop=$shop");
	}
	else {
		$Stores->update_step_verification($_POST['select_veri'],$_POST['fulfill_order'],$shop);
		header("location:/double-check/app/settings.php?shop=$shop");
	}
}


if(isset($_POST['save_roles'])){
	$checkbox = $_POST["selected_checkbox"];
	if (is_array($checkbox))
	{
		foreach ($checkbox as $key => $your_slected_id){
			$store_url = $_POST['store_url'.$your_slected_id];
			$roles =  implode(',', $_POST['selected_role'.$your_slected_id]);
			$saved_stores = $Stores->get_single_save_roles($store_url);
			if(empty($saved_stores)){
			$Stores->saved_roles($store_url, $roles);
			}
			else {
		    $Stores->update_saved_roles($store_url, $roles);
			}
	    }
    }
}
$get_all_stores = $Stores->get_store();
$saved_stores = $Stores->get_save_roles();
//print_r($saved_stores);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Double Check</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/double-check/app/css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="/double-check/app/css/style.css" type="text/css">
  <link rel="stylesheet" href="/double-check/app/font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="/double-check/app/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
</head>
<body>
<form method="post">
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12">
<div class="right-icon" style="padding:6px 12px">
<div class="order-btn">
<a class="order" href="/double-check/app/summary_page.php?shop=<?php echo $shop ?>">BACK TO HOMEPAGE</a>

</div>
<a href="/double-check/app/support.php?shop=<?php echo $shop; ?>" class="support_link">Support</a>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-2">
<span class="role2">SELECT ROLE</span>
<span class="radio radio-primary">
<select name="select_veri">
<option value="">-select role--</option>
<option value="One"<?php if($get_verification['verification_step'] == 'One') { echo "selected"; }?>>Picker</option>
<option value="Two"<?php if($get_verification['verification_step'] == 'Two') { echo "selected"; }?>>Shipper</option>
<option value="Three"<?php if($get_verification['verification_step'] == 'Three') { echo "selected"; }?>>In-store Pickup</option>
<option value="Four"<?php if($get_verification['verification_step'] == 'Four') { echo "selected"; }?>>Ready For Pickup</option>
<option value="Five"<?php if($get_verification['verification_step'] == 'Five') { echo "selected"; }?>>Picker & Shipper</option>
<option value="Six"<?php if($get_verification['verification_step'] == 'Six') { echo "selected"; }?>>Picker & In-store Pickup</option>
<option value="Seven"<?php if($get_verification['verification_step'] == 'Seven') { echo "selected"; }?>>Picker & Ready For Pickup</option>
<option value="Eight"<?php if($get_verification['verification_step'] == 'Eight') { echo "selected"; }?>>Shipper & In-store Pickup</option>
<option value="Nine"<?php if($get_verification['verification_step'] == 'Nine') { echo "selected"; }?>>Shipper & Ready For Pickup</option>
<option value="Ten"<?php if($get_verification['verification_step'] == 'Ten') { echo "selected"; }?>>In-store Pickup & Ready For Pickup</option>
<option value="Eleven"<?php if($get_verification['verification_step'] == 'Eleven') { echo "selected"; }?>>All</option>
</select>
</span>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-12">
<span class="role2">FULFILL ORDER AUTOMATICALLY</span>
<span class="radio radio-primary">
 <input type="radio" name="fulfill_order" id="on" value="On" <?php if($get_verification['fulfill_order'] == 'On') { echo "checked"; }?>>
<label for="on">On</label>
<input type="radio" name="fulfill_order" id="off" value="Off" <?php if($get_verification['fulfill_order'] == 'Off') { echo "checked"; }?>>
<label for="off">Off</label>
</span>
</div>
</div>

<div class="row">
<div class="col-sm-12 col-sm-12 marbot30">
<input type="submit" name="save_changes" value="Save Changes">
</div>
</div>
<?php
if($shop == 'livestock-5.myshopify.com'){
?>
<div class="row">
<div class="col-sm-12 col-sm-12 marbot30">
<span class="role2">ROLE DEFINITION FOR STORES</span>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
<thead>
<tr>
<td>SELECT</td>
<td class="hed">STORE URL</td>
<td class="hed">ASSIGNED ROLES</td>
</tr>
<?php $i = 0; 
foreach($get_all_stores as $get_stores){ 

if($get_stores['store_url'] != 'livestock-5.myshopify.com'){ ?>
<tr>
<td><input type="checkbox" name="selected_checkbox[]" value="<?php echo $i?>" <?php foreach($saved_stores as $saved_store){  if($saved_store['store_urls'] == $get_stores['store_url'] ){ echo "checked"; } } ?>/></td>
<td class="hed"><?php  echo $get_stores['store_url'];  ?>
<input type="hidden" name="store_url<?php echo $i; ?>" value="<?php  echo $get_stores['store_url'];  ?>" /></td>
<td class="hed">
<input type="checkbox" name="selected_role<?php echo $i; ?>[]" value="ready for pickup" 
<?php foreach($saved_stores as $saved_store){ 
	$get_roles = $Stores->get_single_save_roles($saved_store['store_urls']);
	$saved_role = explode(",",$get_roles['roles']);
	if(in_array("ready for pickup", $saved_role)){ echo "checked"; }
} 
?>>
	Ready For Pickup &nbsp;&nbsp;
<input type="checkbox" name="selected_role<?php echo $i; ?>[]" value="instore pickup" <?php foreach($saved_stores as $saved_store){ 
	$get_roles = $Stores->get_single_save_roles($saved_store['store_urls']);
	$saved_role = explode(",",$get_roles['roles']);
	if(in_array("instore pickup", $saved_role)){ echo "checked"; }
} 
?>>Instore Pickup
 </td>
</tr>
<?php $i++; 
} } 
?>
</thead>
</table>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-sm-12">
<input type="submit" name="save_roles" value="Save Roles">
</div>
</div>
<?php 	
}
?>
</div>
</div>
</form>
<script>
$(document).bind("contextmenu",function(e){
	  //return false;
	 });
</script>
<?php include 'footer.php'; ?>