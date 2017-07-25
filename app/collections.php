<?php
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop = $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$get_collections = $Shopify->get_collections($shop, $shop_info['access_token']);
$modify_col = $Shopify->update_collections($shop, $shop_info['access_token'],array("custom_collections"=>array("collects"=>array("product_id"=>"656607169","position"=>"2"))));
echo "<pre>";
print_r($create_collections);
?>
<select name="collection">
<option value="">-select collection-</option>
<?php foreach($get_collections->custom_collections as $get_collection) { ?>
<option value="<?php echo $get_collection->id; ?>"><?php echo $get_collection->title; ?></option>

<?php } ?>
</select>