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
	$ful_days = $_POST['select_ful_days'];
	$unful_days = $_POST['select_unful_days'];
	$ful_date = date('Y-m-d', strtotime("-".$ful_days." days"));
	$count_total_orders = $Shopify->count_total_orders($shop, $shop_info['access_token'],$ful_date);
	$ful_order= $count_total_orders->count;
	$unful_date = date('Y-m-d', strtotime("-".$unful_days." days"));
	$count_orders = $Shopify->count_orders($shop, $shop_info['access_token'],$unful_date);
	$unful_order= $count_orders->count;
	$get_days = $Stores->get_days($shop);
	if(empty($get_days)){
		$Stores->save_days($shop,$ful_days,$unful_days,$ful_order,$unful_order);
	}else{
		$Stores->update_day($shop,$ful_days,$unful_days,$ful_order,$unful_order);
	}
	$get_verification = $Stores->get_step_verification($shop);
	if(empty($get_verification)){
		$Stores->step_verification($_POST['select_veri'],$_POST['fulfill_order'],$shop);
	}
	else {
		$Stores->update_step_verification($_POST['select_veri'],$_POST['fulfill_order'],$shop);
	}
	header("location:/double-check/app/settings.php?shop=$shop");
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
			header("location:/double-check/app/settings.php?shop=$shop");
			}
			else {
		    $Stores->update_saved_roles($store_url, $roles);
		    header("location:/double-check/app/settings.php?shop=$shop");
			}
	    }
    }
}
$get_all_stores = $Stores->get_store();
$get_all_data = $Stores->get_stores_data();
$saved_stores = $Stores->get_save_roles();
//print_r($saved_stores);
$get_single_store = $Stores->get_single_save_roles($shop);
$fetch_days = $Stores->get_days($shop);
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
  <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
  <script type="text/javascript">
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
<div class="col-sm-6 col-md-6">
<span class="role2">SELECT ROLE</span>
<span class="radio radio-primary">
<select name="select_veri">
<option value="">-select role--</option>
<option value="One"<?php if($get_verification['verification_step'] == 'One') { echo "selected"; } ?>>Picker</option>

<option value="Two"<?php if($get_verification['verification_step'] == 'Two') { echo "selected"; }?>>Shipper</option>
<?php 
$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("instore pickup", $get_single_role)){ 
?>
<option value="Three"<?php if($get_verification['verification_step'] == 'Three') { echo "selected"; }?>>In-store Pickup</option>
<?php }  ?>

<?php 
$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("ready for pickup", $get_single_role)){ 
?>
<option value="Four"<?php if($get_verification['verification_step'] == 'Four') { echo "selected"; }?>>Ready For Pickup</option>
<?php }  ?>

<option value="Five"<?php if($get_verification['verification_step'] == 'Five') { echo "selected"; }?>>Picker & Shipper</option>

<?php 
$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("instore pickup", $get_single_role)){ 
?>
<option value="Six"<?php if($get_verification['verification_step'] == 'Six') { echo "selected"; }?>>Picker & In-store Pickup</option>
<?php } ?>
<?php 

$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("ready for pickup", $get_single_role)){ 
?>
<option value="Seven"<?php if($get_verification['verification_step'] == 'Seven') { echo "selected"; }?>>Picker & Ready For Pickup</option>
<?php } ?>
<?php 
$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("instore pickup", $get_single_role)){ 
?>
<option value="Eight"<?php if($get_verification['verification_step'] == 'Eight') { echo "selected"; }?>>Shipper & In-store Pickup</option>
<?php } ?>

<?php 

$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("ready for pickup", $get_single_role)){ 
?>
<option value="Nine"<?php if($get_verification['verification_step'] == 'Nine') { echo "selected"; }?>>Shipper & Ready For Pickup</option>
<?php }  ?>

<?php 
$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("instore pickup", $get_single_role) && in_array("ready for pickup", $get_single_role)){ 
?>
<option value="Ten"<?php if($get_verification['verification_step'] == 'Ten') { echo "selected"; }?>>In-store Pickup & Ready For Pickup</option>
<?php }  ?>

<?php
$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("instore pickup", $get_single_role) && in_array("ready for pickup", $get_single_role)){ 
?>
<option value="Eleven"<?php if($get_verification['verification_step'] == 'Eleven') { echo "selected"; }?>>All</option>
<?php } ?>

</select>
</span>
</div>
</div>

<?php 
$get_single_role = explode(",",$get_single_store['roles']); 
if(in_array("ready for pickup", $get_single_role)){ 
?>
<div class="row">
<div class="col-sm-12 col-md-12">
<span class="role2">FULFILL ORDER AUTOMATICALLY</span>
<span class="radio radio-primary">
 <input type="radio" name="fulfill_order" id="on" value="On" <?php if($get_verification['fulfill_order'] == 'On') { echo "checked"; }?>>
<label for="on">On</label>
<input type="radio" name="fulfill_order" id="off" value="Off" <?php if($get_verification['fulfill_order'] == 'Off') { echo "checked"; } ?>>
<label for="off">Off</label>
</span>
</div>
</div>
<?php } ?>

<div class="row">
<div class="col-sm-6 col-md-6">
<span class="role2">SELECT DAYS FOR FULFILLED ORDERS</span>
<span class="radio radio-primary">
<select name="select_ful_days">
<option value="15"<?php if($fetch_days['ful_day'] == '15' ) echo "selected"; ?>>15 Days</option>
<option value="10"<?php if($fetch_days['ful_day'] == '10' ) echo "selected"; ?>>10 Days</option>
<option value="30"<?php if($fetch_days['ful_day'] == '30' ) echo "selected"; ?>>30 Days</option>
<option value="45"<?php if($fetch_days['ful_day'] == '45' ) echo "selected"; ?>>45 Days</option>
<option value="60"<?php if($fetch_days['ful_day'] == '60' ) echo "selected"; ?>>60 Days</option>
</select>
</span>
</div>
<div class="col-sm-6 col-md-6">
<span class="role2">SELECT DAYS FOR UNFULFILLED ORDERS</span>
<span class="radio radio-primary">
<select name="select_unful_days">
<option value="10"<?php if($fetch_days['unful_day'] == '10' ) echo "selected"; ?>>10 Days</option>
<option value="3"<?php if($fetch_days['unful_day'] == '3' ) echo "selected"; ?>>3 Days</option>
<option value="5"<?php if($fetch_days['unful_day'] == '5' ) echo "selected"; ?>>5 Days</option>
<option value="7"<?php if($fetch_days['unful_day'] == '7' ) echo "selected"; ?>>7 Days</option>
<option value="12"<?php if($fetch_days['unful_day'] == '12' ) echo "selected"; ?>>12 Days</option>
<option value="15"<?php if($fetch_days['unful_day'] == '15' ) echo "selected"; ?>>15 Days</option>
<option value="30"<?php if($fetch_days['unful_day'] == '30' ) echo "selected"; ?>>30 Days</option>
<option value="45"<?php if($fetch_days['unful_day'] == '45' ) echo "selected"; ?>>45 Days</option>
<option value="60"<?php if($fetch_days['unful_day'] == '60' ) echo "selected"; ?>>60 Days</option>
</select>
</span>
</div>
<div class="col-sm-12 col-md-12">
<span class="role2"><strong><u>NOTE</u>:</strong>&nbsp;&nbsp;"Lowering the number of active days will speed up load times. It is not recommend to go over 15days for Open Orders."</span>
</div>
</div>
<br>
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
<td class="hed">STORE URL</td>
<td class="hed">ASSIGNED ROLES</td>
</tr>
<?php $i = 0; 
foreach($get_all_stores as $get_stores){ 

if($get_stores['store_url'] != 'livestock-5.myshopify.com'){ ?>
<tr>
<td style="display:none"><input type="checkbox" name="selected_checkbox[]" value="<?php echo $i?>" checked/></td>
<td class="hed"><?php  echo $get_stores['store_url'];  ?>
<input type="hidden" name="store_url<?php echo $i; ?>" value="<?php  echo $get_stores['store_url'];  ?>" /></td>
<td class="hed">
<input type="checkbox" name="selected_role<?php echo $i; ?>[]" value="ready for pickup" 
<?php foreach($saved_stores as $saved_store){ if($saved_store['store_urls'] == $get_stores['store_url'] ){
	$get_roles = $Stores->get_single_save_roles($saved_store['store_urls']);
	$saved_role = explode(",",$get_roles['roles']);
	if(in_array("ready for pickup", $saved_role)){ echo "checked"; }
}
} 
?>>
	Ready For Pickup &nbsp;&nbsp;
<input type="checkbox" name="selected_role<?php echo $i; ?>[]" value="instore pickup" <?php foreach($saved_stores as $saved_store){ if($saved_store['store_urls'] == $get_stores['store_url'] ){
	$get_roles = $Stores->get_single_save_roles($saved_store['store_urls']);
	$saved_role = explode(",",$get_roles['roles']);
	if(in_array("instore pickup", $saved_role)){ echo "checked"; }
} }
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
<br>
<div class="row">
<div class="col-sm-12 col-sm-12">
<span class="role2">NUMBER OF DAYS</span>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
<tr>
<td class="hed">Store Name</td>
<td class="hed">No of days(Fulfilled Orders)</td>
<td class="hed">No of Fulfilled Orders</td>
<td class="hed">NO of days(Unfulfilled Orders)</td>
<td class="hed">No of Unfulfilled Orders</td>
</tr>
<?php 	foreach($get_all_data as $get_data){
	if($get_data['store'] != 'livestock-5.myshopify.com'){
?>
<tr>
<td><?php echo $get_data['store']; ?></td>
<td><?php echo $get_data['ful_day']; ?></td>
<td><?php echo $get_data['ful_order']; ?></td>
<td><?php echo $get_data['unful_day']; ?></td>
<td><?php echo $get_data['unful_order']; ?></td>
</tr>
<?php 
	} }
?>
</table>
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