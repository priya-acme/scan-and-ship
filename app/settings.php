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
<a href="/double-check/app/support.php?shop=<?php echo $shop; ?>" style="text-align:right;color:#fff;float:right">Support</a>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-2">
<span class="role2">SELECT ROLE</span>
<span class="radio radio-primary">
<select name="select_veri">
<option value="One"<?php if($get_verification['verification_step'] == 'One') { echo "selected"; }?>>Picker</option>
<option value="Two"<?php if($get_verification['verification_step'] == 'Two') { echo "selected"; }?>>Picker</option>
<option value="Three"<?php if($get_verification['verification_step'] == 'Three') { echo "selected"; }?>>Picker</option>
<option value="Four"<?php if($get_verification['verification_step'] == 'Four') { echo "selected"; }?>>Picker</option>
<option value="Five"<?php if($get_verification['verification_step'] == 'Five') { echo "selected"; }?>>Picker</option>
<option value="Six"<?php if($get_verification['verification_step'] == 'Six') { echo "selected"; }?>>Picker</option>
<option value="Seven"<?php if($get_verification['verification_step'] == 'Seven') { echo "selected"; }?>>Picker</option>
<option value="Eight"<?php if($get_verification['verification_step'] == 'Eight') { echo "selected"; }?>>Picker</option>
<option value="Nine"<?php if($get_verification['verification_step'] == 'Nine') { echo "selected"; }?>>Picker</option>
<option value="Ten"<?php if($get_verification['verification_step'] == 'Ten') { echo "selected"; }?>>Picker</option>
<option value="Eleven"<?php if($get_verification['verification_step'] == 'Eleven') { echo "selected"; }?>>Picker</option>
</select>
</span>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-6">
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

</div>
</div>
</form>
<?php include 'footer.php'; ?>