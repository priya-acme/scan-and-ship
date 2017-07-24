<?php
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop = $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$get_collections = $Shopify->get_collections($shop, $shop_info['access_token']);

?>
<select name="collection">
<option value="">-select collection-</option>
<?php foreach($get_collections->custom_collections as $get_collection) { ?>
<option value="<?php echo $get_collection->id; ?>"><?php echo $get_collection->title; ?></option>

<?php } ?>
</select>