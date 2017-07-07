<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop =  $_SESSION['shop_name'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scan and Ship</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="/css/style.css" type="text/css">
  <link rel="stylesheet" href="/font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
</head>
<body>
<form method="post">
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12 col-md-6">
<span class="role2">SELECT</span>
<span class="radio radio-primary">
 <input type="radio" name="select_role" id="radio1" value="One">
<label for="radio1">One step verification</label>
<input type="radio" name="select_role" id="radio2" value="Two">
<label for="radio1">Two steps verification</label>
<input type="radio" name="select_role" id="radio2" value="Three">
<label for="radio1">Three steps verification</label>
</span>
</div>
<div class="col-sm-12 col-md-6">
<input type="submit" name="save_changes" value="Save Changes">
</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12 marbot30">
<a class="order" href="/scan-and-ship/app/summary_page.php">BACK TO HOMEPAGE</a>
</div>
</div>
</div>
</div>
</form>
<?php include 'footer.php'; ?>