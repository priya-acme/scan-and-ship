<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 
 $shop_info = $Stores->is_shop_exists($shop);
 $products = $Shopify->get_products($shop, $shop_info[0]['token']);
 //echo $shop_info[0]['token'];
print_r($products);

?>
<table border="1" width="100%">
    <tr>
        <th>Product Id</th>
        <th>Title</th>
        <th>Handle</th>
    </tr>
   <?php foreach($products->products as $product) { ?>
    <tr>
        <td>
            <?php echo $product->id; ?>
        </td>
        <td>
            <?php echo $product->title; ?>
        </td>
        <td>
            <?php echo $product->handle; ?>
        </td>
    </tr>
   <?php } ?>
 
</table>