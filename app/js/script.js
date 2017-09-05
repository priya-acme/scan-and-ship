 function selected_radio(rad,shop){
		//alert('hi');
		var selected_rval = rad;
		var xhttp = new XMLHttpRequest();
		 
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         document.getElementById('done').innerHTML = this.responseText;
	       }
		  };
		  xhttp.open("GET", "../role.php?selected_rval="+rad+"&shop="+shop, true);
		  xhttp.send();
		 setTimeout(function(){ window.location.reload(); }, 1000);
	}
	function create_fulfilled_order(forder_id,shop){
	 var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       
		    }
		  };
		  xhttp.open("GET", "../fulfilled_order.php?shop="+shop+"&order_id="+forder_id, true);
		  xhttp.send();
		  setTimeout(function(){ window.location.reload(); }, 2000);
	}
	function delete_instore_picker(in_order,shop){
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       
		    }
		  };
		  xhttp.open("GET", "../delete_instore_pickup.php?shop="+shop+"&order_id="+in_order, true);
		  xhttp.send();
		  setTimeout(function(){ window.location.reload(); }, 2000);
	}
	function sendvalue(a,b,shop){
		var chckbx_val = a;
		var order_id = b;
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       
		    }
		  };
		  xhttp.open("GET", "../ajax_call.php?shop="+shop+"&chkbx_val="+chckbx_val+"&order_id="+order_id, true);
		  xhttp.send();
		  setTimeout(function(){ window.location.reload(); }, 2000);
	}
	function send_picker_value(o,s,ro,qty,shop){
		var select_role = $("input[type='radio'].select_role:checked").val();
		//alert(select_role);
		if(select_role != 'Picker ok'){
	     alert('Please select correct role !!');
			}
		else {
		var porder_id = o;
		var sku = s;
		if(ro == '' ){ 
			var prole = 'Picker ok';
	    }
		else {
			var prole = ro;
			}
		
		
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       
		    }
		  };
		  xhttp.open("GET", "../picker_ajax_call.php?shop="+shop+"&sku="+sku+"&order_id="+porder_id+"&role="+prole+"&qty="+qty, true);
		  xhttp.send();
		  setTimeout(function(){  window.location.reload(); }, 2000);
		}
	}
	function send_shipper_value(so,ss,sro,sqty,shop){
		var select_role = $("input[type='radio'].select_role:checked").val();
		//alert(select_role);
		if(select_role != 'Shipper ok'){
	     alert('Please select correct role !!');
			} else {
		var sorder_id = so;
		var ssku = ss;
		if(sro == '' ){ 
			var srole = 'Shipper ok';
	    }
		else {
			var srole = sro;
			}
		
		
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       
		    }
		  };
		  xhttp.open("GET", "../shipper_ajax_call.php?shop="+shop+"&sku="+ssku+"&order_id="+sorder_id+"&role="+srole+"&qty="+sqty, true);
		  xhttp.send();
			
		  setTimeout(function(){ window.location.reload(); }, 2000);
			}
	}
	function send_receiver_value(ro,rs,rro,shop,rqty){
		var select_role = $("input[type='radio'].select_role:checked").val();
		//alert(select_role);
		if(select_role != 'Receiver ok'){
	     alert('Please select correct role !!');
			} else {
		var rorder_id = ro;
		var rsku = rs;
		if(rro == '' ){ 
			var rrole = 'Receiver ok';
	    }
		else {
			var rrole = rro;
			}
		
		
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       
		    }
		  };
		  xhttp.open("GET", "../receiver_ajax_call.php?shop="+shop+"&sku="+rsku+"&order_id="+rorder_id+"&role="+rrole+"&qty="+rqty, true);
		  xhttp.send();
		    setTimeout(function(){ window.location = "http://aviaapps.co/double-check/app/order_test.php/?shop="+shop+"&order_id="+rorder_id; }, 2000);
	     	}
	}

	function delete_picker_order(dorder , dsku,shop){
		var dorder = dorder;
		var dsku = dsku;
		var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       }
		  };
		  xhttp.open("GET", "../delete_ajax.php?shop="+shop+"&dorder="+dorder+"&dsku="+dsku, true);
		  xhttp.send();
		  setTimeout(function(){ window.location.reload(); },2000);
	}
	function delete_shipper_order(dsorder , dssku,shop){
		var dsorder = dsorder;
		var dssku = dssku;
		var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       }
		  };
		  xhttp.open("GET", "../delete_ajax.php?shop="+shop+"&dsorder="+dsorder+"&dssku="+dssku, true);
		  xhttp.send();
		  setTimeout(function(){ window.location.reload(); }, 2000);
	}
	function delete_receiver_order(drorder , drsku,shop){
		var drorder = drorder;
		var drsku = drsku;
		var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
	         //document.getElementById('done').innerHTML = this.responseText;
	       }
		  };
		  xhttp.open("GET", "../delete_ajax.php?shop="+shop+"&drorder="+drorder+"&drsku="+drsku, true);
		  xhttp.send();
		  setTimeout(function(){ window.location.reload(); }, 2000);
	} 