<?php session_start();
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $_SESSION['shop'] = $shop;
 if($_SESSION['shop']!=null) { ?>
<?php include 'header.php' ?>
<div id="content">
<?php include 'order-search.php';  ?>

<?php include 'footer.php' ?>
<?php } else {
	$redirect_url = $Shopify->getAuthUrl($shop);
	header("Location: $redirect_url");
}?>