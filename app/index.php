<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $redirect_url = $Shopify->getAuthUrl1($shop);
 header("Location: $redirect_url");
 ?>
<?php include 'header.php' ?>
<div id="content">
<?php include 'order-search.php';  ?>

<?php include 'footer.php' ?>