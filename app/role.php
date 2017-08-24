 <?php 
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_REQUEST['shop'];
 $selected_role = $_REQUEST['selected_rval'];
 $get_single_store = $Stores->get_single_save_roles($shop);
 if(empty($get_single_store)){
 	$Stores->save_select_role($shop,$selected_role);
 }else{
 	$Stores->update_select_role($shop,$selected_role);
 }
 ?>