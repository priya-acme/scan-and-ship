<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $_SESSION['shop_name'] = $shop;
 
 ?>
<?php include 'header.php' ?>
<!-- <div id="content"> -->
<?php //header('location:summary_page.php'); ?>

<?php include 'footer.php' ?>