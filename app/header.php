<!DOCTYPE html>
<html lang="en">
<head>
  <title>Double Check</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="css/style.css" type="text/css">
  <link rel="stylesheet" href="font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
  <link href="css/paging.css" rel="stylesheet" type="text/css" />
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/jquery.table.hpaging.min.js"></script>
  <script src="js/jquery.stickytableheaders.js"></script>
  <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
  <script type="text/javascript">
  $(function() {
		$("#table1").simplePagination({
			previousButtonClass: "btn btn-danger",
			nextButtonClass: "btn btn-danger"
		});
	});

  var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};
	var shop_url = getUrlParameter('shop');
  ShopifyApp.init({
	  apiKey: "ed1b619b0d4433048a3fd866d1ae5f7f",
	  shopOrigin:"https://"+shop_url,
	  debug: false,
	  forceRedirect: true
	});
 </script>
</head>

<body>
