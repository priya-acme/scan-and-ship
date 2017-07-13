<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop =  $_SESSION['shop_name'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);
 $get_verification = $Stores->get_step_verification();
if(isset($_POST['submit_id'])){
	$order_id = $_POST['order_id'];
	$_SESSION['select_role'] = $_POST['select_role'];
	$shop_info = $Stores->is_shop_exists($shop);
	$get_order = $Shopify->get_orders($shop,$shop_info['access_token']);
	foreach($get_order->orders as $order) {
		if($order_id == $order->name || $order_id == $order->id){
			header("location:/scan-and-ship/app/order_detailed_page.php/?id=$order->id");
		}
	}
}
?>
<?php include 'header.php' ?>
 <form method="post">
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12 col-md-6">
<span class="role2">SELECT ROLE : </span>
<span class="radio radio-primary">
<?php if($get_verification['verification_step'] == 'One') {  
	?>
<input type="radio" name="select_role" id="radio1" value="Picker" <?php if($_SESSION['select_role'] == 'Picker') { echo "checked"; } else { echo "checked"; } ?> onclick="selected_radio(this.value)">
<label for="radio1">
 PICKER
</label>
<?php 
} if($get_verification['verification_step'] == 'Two') { ?>
            <input type="radio" name="select_role" id="radio1" value="Picker" <?php if($_SESSION['select_role'] == 'Picker') { echo "checked"; } else { echo "checked"; } ?> onclick="selected_radio(this.value)">
            <label for="radio1">
                PICKER
            </label>
            <input type="radio" name="select_role" id="radio2" value="Shipper" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Shipper') { echo "checked"; } ?>>
            <label for="radio2">
                SHIPPER
            </label>
<?php } if($get_verification['verification_step'] == 'Three') {?>
            <input type="radio" name="select_role" id="radio1" value="Picker" <?php if($_SESSION['select_role'] == 'Picker') { echo "checked"; } else { echo "checked"; } ?> onclick="selected_radio(this.value)">
            <label for="radio1">
                PICKER
            </label>
            <input type="radio" name="select_role" id="radio2" value="Shipper" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Shipper') { echo "checked"; } ?>>
            <label for="radio2">
                SHIPPER
            </label>
            <input type="radio" name="select_role" id="radio3" value="Receiver" onclick="selected_radio(this.value)" <?php if($_SESSION['select_role'] == 'Receiver') { echo "checked"; } ?>>
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
<div class="col-sm-12 col-md-6">
<div class="right-icon">
<a href="/scan-and-ship/app/settings.php" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="tbl">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td colspan="3" class="hed">ORDER LOOKUP <input type="text" class="txt" name="order_id"> <button type="submit" class="serch" name="submit_id">
      <span class="glyphicon glyphicon-search"></span>
    </button></td>
    <td width="6%" class="hed">PICKED</td>
    <td width="7%" class="hed">SHIPPED</td>
    <td width="7%" class="hed">READY FOR PICKUP</td>
    <td width="7%" class="hed">IN-STORE PICKUP</td>
    <td width="31%" class="hed">NOTES</td>
  </tr>
  <?php foreach($orders->orders as $order) { ?>
  <?php //echo "<pre>";
  //print_r($order);
  ?>
  <tr>
    <td width="7%" valign="middle"><strong><a class="order_detail" href="/scan-and-ship/app/order_detailed_page.php/?id=<?php echo $order->id; ?>"><?php echo $order->name; ?></a></strong></td>
    <td width="12%"><strong><?php echo $order->updated_at; ?></strong></td>
    <td width="12%"><strong><?php echo $order->shipping_address->first_name." ".$order->shipping_address->last_name; ?></strong></td>
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
     <?php $get_instore_pickup= $Stores->gett_instore_pickup($order->id); if(!empty($get_instore_pickup) ){ ?>
     <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
     <?php } else { ?>
     <td><input type="checkbox" name="in_store_pickup" value="yes" onclick="sendvalue(this.value,'<?php echo $order->id ?>')"></td>
     <?php } ?>
     <?php $get_order_note = $Stores->get_order_note($order->id); 
         if(!empty($get_order_note) ){ ?>
            <td><div class="last-text"><?php  echo $get_order_note['order_note']; ?></div></td>
     <?php } else { ?>
            <td> - </td>
     <?php } ?>
   </tr>
  <?php } ?>
 </table>
</div>
</div>
</div>
</div>
</div>
</form>
<script>
function sendvalue(a,b){
	var chckbx_val = a;
	var order_id = b;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
         //document.getElementById('done').innerHTML = this.responseText;
       
	    }
	  };
	  xhttp.open("GET", "ajax_call.php?chkbx_val="+chckbx_val+"&order_id="+order_id, true);
	  xhttp.send();
	  setTimeout(function(){ window.location.href = 'http://67.207.82.1/scan-and-ship/app/summary_page.php'; }, 500);
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
</script>
<?php include 'footer.php' ?>