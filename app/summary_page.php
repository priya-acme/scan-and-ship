<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop =  $_REQUEST['shop'];
//   $code = isset($_GET["code"]) ? $_GET["code"] : false;
//  $redirect_url = $Shopify->checkAuthUrl($shop);
//  //echo $redirect_url;
//   header("Location: $redirect_url");
 $shop_info = $Stores->is_shop_exists($shop);
 $count_orders = $Shopify->count_orders($shop, $shop_info['access_token']);
 $count_val = ceil($count_orders->count / 250);
 for($count=1;$count<=$count_val;$count++){
 ${"orders".$count} = $Shopify->get_orders($shop, $shop_info['access_token'],$count);

 }
$get_verification = $Stores->get_step_verification($shop);
 if(isset($_POST['submit_id'])){
	$order_id = $_POST['order_id'];
	$_SESSION['select_role'] = $_POST['select_role'];
	$shop_info = $Stores->is_shop_exists($shop);
	$count_orders = $Shopify->count_orders($shop, $shop_info['access_token']);
	$count_val = ceil($count_orders->count / 250);
	
	
	for($count=1;$count<=$count_val;$count++){
	${"get_order".$count} = $Shopify->get_orders($shop,$shop_info['access_token'],$count);
	echo $count_orders->count;
	
	foreach(${"get_order".$count}->orders as $order) {
		echo ${"get_order".$count};
		echo $count;
		die;
		if($order_id == $order->name || $order_id == $order->id){
			//header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$order->id");
		}
	 }
	}
}


?>
<?php include 'header.php' ?>
 <form method="post">
<div class="margtop30 summary-header-fixed">
<div class="container">
<div class="row">
<div class="col-sm-12">
<div class="right-icon">
<a href="/double-check/app/settings.php?shop=<?php echo $shop; ?>" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12 col-md-6">
<span class="role2">SELECT ROLE : </span>
<span class="radio radio-primary">
<?php if($get_verification['verification_step'] == 'One') {  
	?>
<input type="radio" name="select_role" id="radio1" value="Picker ok" <?php if($_SESSION['select_role'] == 'Picker ok') { echo "checked"; } else { echo "checked"; } ?> onclick="selected_radio(this.value)">
<label for="radio1">
 PICKER
</label>
 <input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Receiver ok') { echo "checked"; } ?>>
            <label for="radio3">
                READY FOR PICKUP
            </label>
<?php 
}  if($get_verification['verification_step'] == 'Two') { ?>
           <input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Shipper ok') { echo "checked"; } ?>>
            <label for="radio2">
                SHIPPER
            </label>
            <input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Receiver ok') { echo "checked"; } ?>>
            <label for="radio3">
                READY FOR PICKUP
            </label>
            
<?php }  if($get_verification['verification_step'] == 'Three') {?>
            <input type="radio" name="select_role" id="radio1" value="Picker ok" <?php if($_SESSION['select_role'] == 'Picker ok') { echo "checked"; } else { echo "checked"; } ?> onclick="selected_radio(this.value)">
            <label for="radio1">
                PICKER
            </label>
            <input type="radio" name="select_role" id="radio2" value="Shipper ok" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Shipper ok') { echo "checked"; } ?>>
            <label for="radio2">
                SHIPPER
            </label>
             <input type="radio" name="select_role" id="radio3" value="Receiver ok" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Receiver ok') { echo "checked"; } ?>>
            <label for="radio3">
                READY FOR PICKUP
            </label>
            
<?php } ?>
 
</span>
</div>
<!-- <div class="col-sm-12 col-md-6"> -->
<!--  <div class="role2">SELECT ROLE</div> -->
<!--  <div class="role"><input type = "radio" value="Receiver" name="select_role" class="select_role">Receiver</div> -->
<!--  <div class="role"><input type = "radio" value="Shipper" name="select_role" class="select_role">Shipper</div> -->
<!--  <div class="role"><input type = "radio" value="Picker" name="select_role" class="select_role" checked>Picker</div> -->
<!-- </div> -->

</div>
</div>
</div>
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="tbl summary-table">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable" id="table1">
<thead>
  <tr>
    <td colspan="3" class="hed">ORDER LOOKUP <input type="text" class="txt" name="order_id"> <button type="submit" class="serch" name="submit_id">
      <span class="glyphicon glyphicon-search"></span>
    </button></td>
    <?php if($get_verification['verification_step'] == 'One') {  
	?>
    <td width="6%" class="hed">PICKED</td>
    <td width="7%" class="hed">READY FOR PICKUP</td>
   
    <?php } ?>
    <?php if($get_verification['verification_step'] == 'Two') {  
	?>
	<td width="7%" class="hed">SHIPPED</td>
	<td width="7%" class="hed">READY FOR PICKUP</td>
	<?php } ?>
	<?php if($get_verification['verification_step'] == 'Three') {  
	?>
	<td width="6%" class="hed">PICKED</td>
	<td width="7%" class="hed">SHIPPED</td>
	<td width="7%" class="hed">READY FOR PICKUP</td>
	<td width="7%" class="hed">IN-STORE PICKUP</td>
	
	<?php } ?>
    
    <td width="7%" class="hed">ORDER STATUS</td>
   
    <td width="31%" class="hed">NOTES</td>
  </tr>
  </thead>
  <tbody>
   
  <?php for($count=1;$count<=$count_val;$count++){ foreach(${"orders".$count}->orders as $order) {   ?>
  <?php //echo "<pre>";
    $now = date("Y-m-d");
    $input = $order->updated_at; 
	$result = explode('T',$input);
	$total_days = round(abs(strtotime($now)-strtotime($result[0]))/86400);
	if($total_days >= 0 && $total_days <= 365){
 ?>
  <tr>
    <td width="7%" valign="middle"><strong><a class="order_detail" href="/double-check/app/order_detailed_page.php/?shop=<?php echo $shop; ?>&&id=<?php echo $order->id; ?>"><?php echo $order->name; ?></a></strong></td>
    <td width="12%"><strong><?php  echo $result[0]; //echo " ".$total_days ?></strong></td>
    <td width="12%"><strong><?php echo $order->shipping_address->first_name." ".$order->shipping_address->last_name; ?></strong></td>
    
    <!--  one step verification starts -->
    <?php if($get_verification['verification_step'] == 'One') {  
	?>
	<?php  if($order->fulfillment_status == 'fulfilled' ) { ?>
	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
	<?php }  else { ?>
     <!-- picker -->
    
    <?php $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
       $pcount = $Stores->p_count_order($order->id);
       
       if($line_item_count == $pcount['count(*)']){
       	?>
       <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else if($line_item_count > $pcount['count(*)'] && $pcount['count(*)'] != 0 ) { 
     	?>
     	<td><div class="yellow"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php
     } else { ?>
       <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
     
     <!--  receiver  -->
     <?php $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
       $rcount = $Stores->r_count_order($order->id);
       if($line_item_count == $rcount['count(*)']){
       	?>
       <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else if($line_item_count > $rcount['count(*)'] && $rcount['count(*)'] != 0 ) {
     	?>
     	<td><div class="yellow"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php 
     } else { ?>
       <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
     
    <?php }  } ?>
    
    <!--  one step verification end -->
    
    <!--  two step verification starts -->
    
    <?php if($get_verification['verification_step'] == 'Two') {  
	?>
	<?php  if($order->fulfillment_status == 'fulfilled' ) { ?>
	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
	<?php }  else { ?>
	 <!-- shipper  -->
    
     <?php $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
       $scount = $Stores->s_count_order($order->id);
       if($line_item_count == $scount['count(*)']){
       	?>
       <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else if($line_item_count > $scount['count(*)'] && $scount['count(*)'] != 0 ) {
     	?>
     	<td><div class="yellow"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php 
     } else { ?>
       <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
  
     <!--  receiver  -->
     <?php $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
       $rcount = $Stores->r_count_order($order->id);
       if($line_item_count == $rcount['count(*)']){
       	?>
       <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else if($line_item_count > $rcount['count(*)'] && $rcount['count(*)'] != 0 ) {
     	?>
     	<td><div class="yellow"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php 
     } else { ?>
       <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
  
	<?php } } ?>
	
	<!--  two step verification end -->
	
	<!--  three step verification starts -->
	
	<?php if($get_verification['verification_step'] == 'Three') {  
	?>
	<?php  if($order->fulfillment_status == 'fulfilled' ) { ?>
	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
	<?php }  else { ?> 
	 <!-- picker -->
    
    <?php $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
       $pcount = $Stores->p_count_order($order->id);
       
       if($line_item_count == $pcount['count(*)']){
       	?>
       <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else if($line_item_count > $pcount['count(*)'] && $pcount['count(*)'] != 0 ) { 
     	?>
     	<td><div class="yellow"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php
     } else { ?>
       <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
     
     <!-- shipper  -->
    
     <?php $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
       $scount = $Stores->s_count_order($order->id);
       if($line_item_count == $scount['count(*)']){
       	?>
       <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else if($line_item_count > $scount['count(*)'] && $scount['count(*)'] != 0 ) {
     	?>
     	<td><div class="yellow"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php 
     } else { ?>
       <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
     
    <!--  receiver  -->
     <?php $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
       $rcount = $Stores->r_count_order($order->id);
       if($line_item_count == $rcount['count(*)']){
       	?>
       <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else if($line_item_count > $rcount['count(*)'] && $rcount['count(*)'] != 0 ) {
     	?>
     	<td><div class="yellow"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php 
     } else { ?>
       <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
	
	<?php } 
	?>
	 <!-- in store pickup  -->
     <?php $get_instore_pickup= $Stores->gett_instore_pickup($order->id); if(!empty($get_instore_pickup) ){ ?>
     <td><div class="green"><a href="" onclick="delete_instore_picker('<?php echo $order->id ?>','<?php echo $shop; ?>')"><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else { ?>
     <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
     <?php } ?>
	<?php 
	} ?>
    
    <!--  three step verification end -->
    <?php  if($order->fulfillment_status == 'fulfilled' ) { ?>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <?php } else { ?>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <?php } ?>
     <?php $get_order_note = $Stores->get_order_note($order->id); 
         if(!empty($get_order_note) ){ ?>
            <td><div class="last-text"><?php  echo $get_order_note['order_note']; ?></div></td>
     <?php } else { ?>
            <td> - </td>
     <?php } ?>
   </tr>
  <?php } } }  ?>
  </tbody>
 </table>
</div>
</div>
</div>
</div>
</div>
</form>
<script>
$(function () {
    $("#table1").stickyTableHeaders();
   
});
function delete_instore_picker(in_order,shop){
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
       //document.getElementById('done').innerHTML = this.responseText;
     
	    }
	  };
	  xhttp.open("GET", "delete_instore_pickup.php?shop="+shop+"&order_id="+in_order, true);
	  xhttp.send();
	  
	  setTimeout(function(){ window.location.reload();; }, 500);
}

function selected_radio(r){
	var selected_rval = r;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       }
	  };
	  xhttp.open("GET", "role.php?selected_rval="+selected_rval, true);
	  xhttp.send();
}
$(function () {
    $("#table1").hpaging({ "limit": 50 });
});

$("#btnApply").click(function () {
    var lmt = $("#pglmt").val();
    $("#table1").hpaging("newLimit", lmt);
});
</script>
<?php include 'footer.php' ?>