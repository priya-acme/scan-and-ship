<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $_SESSION[$shop] = $shop;
 //echo $_SESSION[$shop];
 ?>
<?php include 'header.php' ?>
<!-- <div id="content"> -->
<?php echo $_SERVER['PHP_SELF']; //header("location:summary_page.php?shop=$_SESSION[$shop]"); ?>

<?php include 'footer.php' ?>