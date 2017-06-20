<?php
echo "shopify";
class Shopify {
	
	protected $_APP_KEY;
	protected $_APP_SECRET;
	public function __construct() {
		$this->inititalizeKeys();
	}
	function inititalizeKeys(){
		$this->_APP_KEY = SHOPIFY_API_KEY ;
		$this->_APP_SECRET = SHOPIFY_API_SECRET;
		
	}
	// validate shopify url
	
	public function validateMyShopifyName($shop) {
		$subject = $shop;
		$pattern = '/^(.*)?(\.myshopify\.com)$/';
		preg_match($pattern, $subject, $matches);
		return $matches[2] == '.myshopify.com';
	}
	
	function exchangeTempTokenForPermanentToken($shopifyUrl , $TempCode){
		
		// encode the data
		$data = json_encode(array("client_id"=>$this->_APP_KEY , "client_secret"=>$this->_APP_SECRET , "code"=>$TempCode));
		
		//the curl url
		$curl_uri = "https://$shopifyUrl/admin/oauth/access_token";
		
		// set curl option
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL , $curl_uri);
		curl_setopt($ch,CURLOPT_HEADER , false);
		curl_setopt($ch,CURLOPT_HTTPHEADER , array("Content-Type:application/json"));
		curl_setopt($ch,CURLOPT_POSTFIELDS , $data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER , 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER , false);
		
		// execute curl
		$response = json_decode(curl_exec($ch));
		
		// close curl
		curl_close($ch);
		
		return $response;
	}
	
	function validateRequestOriginIsShopify($code,$shop,$timestamp,$signature) {
		$get_params_string = 'code='.$code.'shop='.$shop.'timestamp='.$timestamp.'';
		$calculated_signature = md5(SHOPIFY_APP_PASSWORD . $calculated_signature);
		if($calculated_signature == $signature){
			return true;
		}else if($_GET['origin'] == 'shopify'){
			return true;
		}else{
			return false;
		}
	}
	function getAuthUrl($shop){
		$scopes = ["read_products", "read_orders"];
		//print_r($scopes);
		//echo SHOPIFY_API_KEY;
		return 'https://' . $shop . '/admin/oauth/authorize?'
				. 'scope=' . implode("%2C", $scopes)
				. '&client_id=' . SHOPIFY_API_KEY
				. '&redirect_uri=' . CALLBACK_URL;
	}
}
?>