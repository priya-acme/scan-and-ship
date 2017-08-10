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
<div class="col-sm-12 col-md-4">
<span class="role2">SELECT ROLE</span>
<span class="radio radio-primary">
 <input type="radio" name="select_veri" id="radio1" value="One" <?php if($get_verification['verification_step'] == 'One') { echo "checked"; }?>>
<label for="radio1">Picker Verification</label>
<input type="radio" name="select_veri" id="radio2" value="Two" <?php if($get_verification['verification_step'] == 'Two') { echo "checked"; }?>>
<label for="radio2">Shipper Verification</label>
<input type="radio" name="select_veri" id="radio3" value="Three" <?php if($get_verification['verification_step'] == 'Three') { echo "checked"; }?>>
<label for="radio3">In-store Pickup Verification</label>
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