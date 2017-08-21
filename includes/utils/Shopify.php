<?php
//echo "shopify";
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
	public function validateMyShopifyName($shop) {
		$subject = $shop;
		$pattern = '/^(.*)?(\.myshopify\.com)$/';
		preg_match($pattern, $subject, $matches);
		return $matches[2] == '.myshopify.com';
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
		$scopes = ["read_products", "read_orders","write_orders","write_products"];
		//print_r($scopes);
		//echo SHOPIFY_API_KEY;
		return 'https://' . $shop . '/admin/oauth/authorize?'
				. 'scope=' . implode("%2C", $scopes)
				. '&client_id=' . SHOPIFY_API_KEY
				. '&redirect_uri=' . CALLBACK_URL;
	}

	
	private function curlRequest($url, $access_token = NULL, $data = NULL)
	{
		// set curl options
		//echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		
		$http_headers = array("Content-Type:application/json");
		if ($access_token) {
			$http_headers = array("Content-Type:application/json", "X-Shopify-Access-Token: $access_token");
		}
		
		curl_setopt($ch, CURLOPT_HEADER, false); // Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
		//curl_setopt($ch,CURLOPT_POST, 1);    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$output = curl_exec($ch); // Download the given URL, and return output
		
		if ($output === false) {
			return 'Curl error: ' . curl_error($ch);
		}
		
		curl_close($ch); // Close the cURL resource, and free system resources
		
		return json_decode($output);
		
	}
	
	
	// curl put request
	
	public function curlPutRequest($url, $access_token= false, $data = false) {
		$ch = curl_init(); //create a new cURL resource handle
		curl_setopt($ch, CURLOPT_URL, $url); // Set URL to download
		
		$http_headers = array("Content-Type:application/json");
		if ($access_token) {
			$http_headers = array("Content-Type:application/json", "X-Shopify-Access-Token: $access_token");
		}
		
		curl_setopt($ch, CURLOPT_HEADER, false); // Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$output = curl_exec($ch); // Download the given URL, and return output
		
		if ($output === false) {
			return 'Curl error: ' . curl_error($ch);
		}
		
		curl_close($ch); // Close the cURL resource, and free system resources
		
		return json_decode($output);
	}
	
	// update order
	
	public function updateOrderInfo($shop, $access_token, $order_id , $order) {
			
		$curl_url = "https://$shop/admin/orders/$order_id.json";
		$data = json_encode($order);
		
		return $this->curlPutRequest($curl_url, $access_token,$data);
	}
	
	// get orders 
	
	public function get_orders($shop, $access_token,$count)
	{
	 $curl_url = "https://$shop/admin/orders.json?limit=250&page=$count&status=any";
	 //echo $curl_url;
	   return $this->curlRequest($curl_url, $access_token);
	}
	 
	function count_orders($shop, $access_token){
		$curl_url = "https://$shop/admin/orders/count.json";
		return $this->curlRequest($curl_url, $access_token);
	}
	
	public function get_single_order($shop, $access_token,$order_id)
	{
		$curl_url = "https://$shop/admin/orders/$order_id.json";
		return $this->curlRequest($curl_url, $access_token);
	}
	
	public function get_variants($shop, $access_token,$variant_id)
	{
		$curl_url = "https://$shop/admin/variants/$variant_id.json";
		return $this->curlRequest($curl_url, $access_token);
	}
	
	public function create_fulfillment_order($shop,$access_token,$order_id,$forder){
		//echo $curl_url;
		$curl_url = "https://$shop/admin/orders/$order_id/fulfillments.json";
		$data = json_encode($forder);
		return $this->curlRequest($curl_url, $access_token,$data);
	}
	public function get_collections($shop, $access_token)
	{
		$curl_url = "https://$shop/admin/custom_collections.json";
		return $this->curlRequest($curl_url, $access_token);
	}
	
	public function update_collections($shop, $access_token,$cdata)
	{
		$curl_url = "https://$shop/admin/collects.json?collection_id=41320513";
		$data = json_encode($cdata);
		return $this->curlPutRequest($curl_url, $access_token,$data);
	}
	
}
?>
