<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $_SESSION['shop_name'] = $shop;
 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
  <title>Mobile App</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="css/style.css" type="text/css">
  <link rel="stylesheet" href="font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
</head>
<body>

<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12 col-md-6">
 <div class="role2">SELECT ROLE</div>
  <div class="role"><a href="">Picker</a></div>
   <div class="role"><a href="">Shipper</a></div>
    <div class="role"><a href="">Receiver</a></div>
</div>
<div class="col-sm-12 col-md-6">
<div class="right-icon">
<a href="" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
</div>
</div>
</div>
</div>
</div>

<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="tbl">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td colspan="3" class="hed">ORDER LOOKUP <input type="text" class="txt"> <button type="button" class="serch">
      <span class="glyphicon glyphicon-search"></span>
    </button></td>
    <td width="6%" class="hed">PICKED</td>
    <td width="7%" class="hed">SHIPPED</td>
    <td width="11%" class="hed">INSTORE PICKUP</td>
    <td width="14%" class="hed">READY FOR PICKUP</td>
    <td width="31%" class="hed">NOTES</td>
  </tr>
  <tr>
    <td width="7%" valign="middle"><strong>#1004</strong></td>
    <td width="12%"><strong>June - 28- 2017</strong></td>
    <td width="12%"><strong>Christian Balk</strong></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="last-text">Here is some text about this mobile app. Here is some text about this mobile app.</div></td>
  </tr>
   <tr>
     <td><strong>#1004</strong></td>
     <td><strong>June - 28- 2017</strong></td>
    <td><strong>Christian Balk</strong></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="last-text">Here is some text about this mobile app. Here is some text about this mobile app.</div></td>
  </tr>
   <tr>
     <td><strong>#1004</strong></td>
     <td><strong>June - 28- 2017</strong></td>
    <td><strong>Christian Balk</strong></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="last-text">Here is some text about this mobile app. Here is some text about this mobile app.</div></td>
  </tr>
   <tr>
     <td><strong>#1004</strong></td>
     <td><strong>June - 28- 2017</strong></td>
    <td><strong>Christian Balk</strong></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="last-text">Here is some text about this mobile app. Here is some text about this mobile app.</div></td>
  </tr>
   <tr>
     <td><strong>#1004</strong></td>
     <td><strong>June - 28- 2017</strong></td>
    <td><strong>Christian Balk</strong></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="last-text">Here is some text about this mobile app. Here is some text about this mobile app.</div></td>
  </tr>
   </tr>
</table>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
 
<?php //include 'header.php' ?>
<!-- <div id="content"> -->
<?php //header('location:order-search.php'); ?>

<?php //include 'footer.php' ?>