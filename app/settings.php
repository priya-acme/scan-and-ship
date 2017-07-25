<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop =  $_SESSION['shop_name'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);
 $get_verification = $Stores->get_step_verification();
 if(isset($_POST['save_changes'])){
 	$get_verification = $Stores->get_step_verification();
 	if(empty($get_verification)){
 		$Stores->step_verification($_POST['select_veri']);
 		header('location:/scan-and-ship/app/settings.php');
 	}
 	else {
 		$Stores->update_step_verification($_POST['select_veri']);
 		header('location:/scan-and-ship/app/settings.php');
 	}
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scan and Ship</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/scan-and-ship/app/css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="/scan-and-ship/app/css/style.css" type="text/css">
  <link rel="stylesheet" href="/scan-and-ship/app/font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="/scan-and-ship/app/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
</head>
<body>
<form method="post">
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12">
<div class="right-icon">
<div class="order-btn">
<a class="order" href="/scan-and-ship/app/summary_page.php">BACK TO HOMEPAGE</a>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-6">
<span class="role2">SELECT</span>
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
<div class="col-sm-12 col-sm-12 marbot30">
<input type="submit" name="save_changes" value="Save Changes">
</div>
</div>

</div>
</div>
</form>
<?php include 'footer.php'; ?>