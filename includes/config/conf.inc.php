<?php 
define('APP_ENV','heroku');
if(APP_ENV == 'heroku'){
 define('DB_HOST','ec2-54-243-185-123.compute-1.amazonaws.com');
define('DB_NAME','dftadju73ao8b');
define('DB_USER','wavptfphsawxvl');
define('DB_PASS','f197258ef0d018d06137e62f49428d88807a2a3b1aca06ec0419276f40eb51bf');
define('SHOPIFY_API','');   
} else if(APP_ENV == 'local'){
define('DB_HOST','localhost');
define('DB_NAME','my_first_app');
define('DB_USER','root');
define('DB_PASS','');
}
define('SHOPIFY_API_KEY','c1724b2cc0298491681659e56dd89cff');
define('SHOPIFY_API_SECRET','a548c92cefb9c742aac11b86f226b322');
define('CALLBACK_URL','https://my-first-demo-app.herokuapp.com/install');
define("APP_URL", "https://my-first-demo-app.herokuapp.com/app");

?>