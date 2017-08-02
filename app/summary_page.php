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
	for($count=1;$count<=$count_val;$count++){
	${"get_order".$count} = $Shopify->get_orders($shop,$shop_info['access_token'],$count);
	foreach(${"get_order".$count}->orders as $order) {
		if($order_id == $order->name || $order_id == $order->id){
			header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$order->id");
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

/*! Copyright (c) 2011 by Jonas Mosbech - https://github.com/jmosbech/StickyTableHeaders
	MIT license info: https://github.com/jmosbech/StickyTableHeaders/blob/master/license.txt */

;
(function ($, window, undefined) {
    'use strict';

    var name = 'stickyTableHeaders',
        id = 0,
        defaults = {
            fixedOffset: 150,
            leftOffset: 0,
            marginTop: 0,
            scrollableArea: window
        };

    function Plugin(el, options) {
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;
        base.id = id++;
        base.$window = $(window);
        base.$document = $(document);

        // Listen for destroyed, call teardown
        base.$el.bind('destroyed',
        $.proxy(base.teardown, base));

        // Cache DOM refs for performance reasons
        base.$clonedHeader = null;
        base.$originalHeader = null;

        // Keep track of state
        base.isSticky = false;
        base.hasBeenSticky = false;
        base.leftOffset = null;
        base.topOffset = null;

        base.init = function () {
            base.$el.each(function () {
                var $this = $(this);

                // remove padding on <table> to fix issue #7
                $this.css('padding', 0);

                base.$originalHeader = $('thead:first', this);
                base.$clonedHeader = base.$originalHeader.clone();
                $this.trigger('clonedHeader.' + name, [base.$clonedHeader]);

                base.$clonedHeader.addClass('tableFloatingHeader');
                base.$clonedHeader.css('display', 'none');

                base.$originalHeader.addClass('tableFloatingHeaderOriginal');

                base.$originalHeader.after(base.$clonedHeader);

                base.$printStyle = $('<style type="text/css" media="print">' +
                    '.tableFloatingHeader{display:none !important;}' +
                    '.tableFloatingHeaderOriginal{position:static !important;}' +
                    '</style>');
                $('head').append(base.$printStyle);
            });

            base.setOptions(options);
            base.updateWidth();
            base.toggleHeaders();
            base.bind();
        };

        base.destroy = function () {
            base.$el.unbind('destroyed', base.teardown);
            base.teardown();
        };

        base.teardown = function () {
            if (base.isSticky) {
                base.$originalHeader.css('position', 'static');
            }
            $.removeData(base.el, 'plugin_' + name);
            base.unbind();

            base.$clonedHeader.remove();
            base.$originalHeader.removeClass('tableFloatingHeaderOriginal');
            base.$originalHeader.css('visibility', 'visible');
            base.$printStyle.remove();

            base.el = null;
            base.$el = null;
        };

        base.bind = function () {
            base.$scrollableArea.on('scroll.' + name, base.toggleHeaders);
            if (!base.isWindowScrolling) {
                base.$window.on('scroll.' + name + base.id, base.setPositionValues);
                base.$window.on('resize.' + name + base.id, base.toggleHeaders);
            }
            base.$scrollableArea.on('resize.' + name, base.toggleHeaders);
            base.$scrollableArea.on('resize.' + name, base.updateWidth);
        };

        base.unbind = function () {
            // unbind window events by specifying handle so we don't remove too much
            base.$scrollableArea.off('.' + name, base.toggleHeaders);
            if (!base.isWindowScrolling) {
                base.$window.off('.' + name + base.id, base.setPositionValues);
                base.$window.off('.' + name + base.id, base.toggleHeaders);
            }
            base.$scrollableArea.off('.' + name, base.updateWidth);
        };

        base.toggleHeaders = function () {
            if (base.$el) {
                base.$el.each(function () {
                    var $this = $(this),
                        newLeft,
                        newTopOffset = base.isWindowScrolling ? (
                        isNaN(base.options.fixedOffset) ? base.options.fixedOffset.outerHeight() : base.options.fixedOffset) : base.$scrollableArea.offset().top + (!isNaN(base.options.fixedOffset) ? base.options.fixedOffset : 0),
                        offset = $this.offset(),

                        scrollTop = base.$scrollableArea.scrollTop() + newTopOffset,
                        scrollLeft = base.$scrollableArea.scrollLeft(),

                        scrolledPastTop = base.isWindowScrolling ? scrollTop > offset.top : newTopOffset > offset.top,
                        notScrolledPastBottom = (base.isWindowScrolling ? scrollTop : 0) < (offset.top + $this.height() - base.$clonedHeader.height() - (base.isWindowScrolling ? 0 : newTopOffset));

                    if (scrolledPastTop && notScrolledPastBottom) {
                        newLeft = offset.left - scrollLeft + base.options.leftOffset;
                        base.$originalHeader.css({
                            'position': 'fixed',
                                'margin-top': base.options.marginTop,
                                'left': newLeft,
                                'z-index': 3,
                                'background' : '#fff' // #18: opacity bug
                        });
                        base.leftOffset = newLeft;
                        base.topOffset = newTopOffset;
                        base.$clonedHeader.css('display', '');
                        if (!base.isSticky) {
                            base.isSticky = true;
                            // make sure the width is correct: the user might have resized the browser while in static mode
                            base.updateWidth();
                        }
                        base.setPositionValues();
                    } else if (base.isSticky) {
                        base.$originalHeader.css('position', 'static');
                        base.$clonedHeader.css('display', 'none');
                        base.isSticky = false;
                        base.resetWidth($('td,th', base.$clonedHeader), $('td,th', base.$originalHeader));
                    }
                });
            }
        };

        base.setPositionValues = function () {
            var winScrollTop = base.$window.scrollTop(),
                winScrollLeft = base.$window.scrollLeft();
            if (!base.isSticky || winScrollTop < 0 || winScrollTop + base.$window.height() > base.$document.height() || winScrollLeft < 0 || winScrollLeft + base.$window.width() > base.$document.width()) {
                return;
            }
            base.$originalHeader.css({
                'top': base.topOffset - (base.isWindowScrolling ? 0 : winScrollTop),
                    'left': base.leftOffset - (base.isWindowScrolling ? 0 : winScrollLeft)
            });
        };

        base.updateWidth = function () {
            if (!base.isSticky) {
                return;
            }
            // Copy cell widths from clone
            if (!base.$originalHeaderCells) {
                base.$originalHeaderCells = $('th,td', base.$originalHeader);
            }
            if (!base.$clonedHeaderCells) {
                base.$clonedHeaderCells = $('th,td', base.$clonedHeader);
            }
            var cellWidths = base.getWidth(base.$clonedHeaderCells);
            base.setWidth(cellWidths, base.$clonedHeaderCells, base.$originalHeaderCells);

            // Copy row width from whole table
            base.$originalHeader.css('width', base.$clonedHeader.width());
        };

        base.getWidth = function ($clonedHeaders) {
            var widths = [];
            $clonedHeaders.each(function (index) {
                var width, $this = $(this);

                if ($this.css('box-sizing') === 'border-box') {
                    width = $this[0].getBoundingClientRect().width; // #39: border-box bug
                } else {
                    var $origTh = $('th', base.$originalHeader);
                    if ($origTh.css('border-collapse') === 'collapse') {
                        if (window.getComputedStyle) {
                            width = parseFloat(window.getComputedStyle(this, null).width);
                        } else {
                            // ie8 only
                            var leftPadding = parseFloat($this.css('padding-left'));
                            var rightPadding = parseFloat($this.css('padding-right'));
                            // Needs more investigation - this is assuming constant border around this cell and it's neighbours.
                            var border = parseFloat($this.css('border-width'));
                            width = $this.outerWidth() - leftPadding - rightPadding - border;
                        }
                    } else {
                        width = $this.width();
                    }
                }

                widths[index] = width;
            });
            return widths;
        };

        base.setWidth = function (widths, $clonedHeaders, $origHeaders) {
            $clonedHeaders.each(function (index) {
                var width = widths[index];
                $origHeaders.eq(index).css({
                    'min-width': width,
                        'max-width': width
                });
            });
        };

        base.resetWidth = function ($clonedHeaders, $origHeaders) {
            $clonedHeaders.each(function (index) {
                var $this = $(this);
                $origHeaders.eq(index).css({
                    'min-width': $this.css('min-width'),
                        'max-width': $this.css('max-width')
                });
            });
        };

        base.setOptions = function (options) {
            base.options = $.extend({}, defaults, options);
            base.$scrollableArea = $(base.options.scrollableArea);
            base.isWindowScrolling = base.$scrollableArea[0] === window;
        };

        base.updateOptions = function (options) {
            base.setOptions(options);
            // scrollableArea might have changed
            base.unbind();
            base.bind();
            base.updateWidth();
            base.toggleHeaders();
        };

        // Run initializer
        base.init();
    }

    // A plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[name] = function (options) {
        return this.each(function () {
            var instance = $.data(this, 'plugin_' + name);
            if (instance) {
                if (typeof options === 'string') {
                    instance[options].apply(instance);
                } else {
                    instance.updateOptions(options);
                }
            } else if (options !== 'destroy') {
                $.data(this, 'plugin_' + name, new Plugin(this, options));
            }
        });
    };

})(jQuery, window);

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