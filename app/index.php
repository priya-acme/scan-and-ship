<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $_SESSION[$shop] = $shop;
 //echo $_SESSION[$shop];
 $get_verification = $Stores->get_step_verification($shop);
 if(empty($get_verification)){
 	$Stores->step_verification('Three','On',$shop);
 	//header('location:/scan-and-ship/app/settings.php');
 }
 ?>
<?php include 'header.php' ?>
<!-- <div id="content"> -->
<?php header('location:summary_page.php'); ?>

<?php include 'footer.php' ?>