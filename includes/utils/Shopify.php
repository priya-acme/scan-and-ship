<?php
class Shopify {
	
	public $_APP_KEY;
	public $_APP_SECRET;
	
	public function __construct()
	{
		$this->initializeKeys();
	}
	
	public function initializeKeys()
	{
		$this->_APP_KEY = SHOPIFY_API_KEY;
		$this->_APP_SECRET = SHOPIFY_API_SECRET;
	}
	
	public function exchangeTempTokenForPermanentToken($ShopifyURL, $TempCode)
	{
		// encode the data
		$data = json_encode(array("client_id" => $this->_APP_KEY, "client_secret" => $this->_APP_SECRET, "code" => $TempCode));
		
		// the curl url
		$curl_url = "https://$ShopifyURL/admin/oauth/access_token";
		
		return $this->curlRequest($curl_url, null, $data);
	}
	
	private function curlRequest($url, $access_token = NULL, $data = NULL)
	{
		// set curl options
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		
		$http_headers = array("Content-Type:application/json");
		if ($access_token) {
			$http_headers = array("Content-Type:application/json", "X-Shopify-Access-Token: $access_token");
		}
		
		curl_setopt($ch, CURLOPT_HEADER, false); // Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$output = curl_exec($ch); // Download the given URL, and return output
		echo "output>>".$output;
		if ($output === false) {
			return 'Curl error: ' . curl_error($ch);
		}
		
		curl_close($ch); // Close the cURL resource, and free system resources
		
		return json_decode($output);
		
	}
	
	public function validateMyShopifyName($shop) {
		$subject = $shop;
		$pattern = '/^(.*)?(\.myshopify\.com)$/';
		preg_match($pattern, $subject, $matches);
		
		return $matches[2] == '.myshopify.com';
	}
	
	public function validateRequestOriginIsShopify($code, $shop, $timestamp, $signature) {
		$get_params_string = 'code=' . $code . 'shop=' . $shop . 'timestamp=' . $timestamp . '';
		$calculated_signature = md5(SHOPIFY_APP_PASSWORD . $calculated_signature);
		
		if ($calculated_signature == $signature) {
			return true;
		} else if ($_GET["origin"] == 'shopify') {
			return true;
		} else {
			return false;
		}
	}
	
	public function getAuthUrl($shop)
	{
		//echo 'inside getAuth';
		$scopes = ["read_products", "read_orders"];
		return 'https://' . $shop . '/admin/oauth/authorize?'
				. 'scope=' . implode("%2C", $scopes)
				. '&client_id=' . SHOPIFY_API_KEY
				. '&redirect_uri=' . CALLBACK_URL;
	}
	
}
?>
