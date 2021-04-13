<?php

//start session on web page
session_start();

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/Social%20Media%20Integration/index.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');






// Facebook code





require_once(__DIR__.'/Facebook/autoload.php');

define('APP_ID', 'XXXXXXXXXXXXXXXX');
define('APP_SECRET', 'XXXXXXXXXXXXXXXX');
define('API_VERSION', 'v2.5');
define('FB_BASE_URL', 'http://localhost/Social%20Media%20Integration/index.php');

define('BASE_URL', 'YOUR_WEBSITE_URL');

if(!session_id()){
    session_start();
}


// Call Facebook API
$fb = new Facebook\Facebook([
 'app_id' => APP_ID,
 'app_secret' => APP_SECRET,
 'default_graph_version' => API_VERSION,
]);


// Get redirect login helper
$fb_helper = $fb->getRedirectLoginHelper();


// Try to get access token
try {
    if(isset($_SESSION['facebook_access_token']))
		{$accessToken = $_SESSION['facebook_access_token'];}
	else
		{$accessToken = $fb_helper->getAccessToken();}
} catch(FacebookResponseException $e) {
     echo 'Facebook API Error: ' . $e->getMessage();
      exit;
} catch(FacebookSDKException $e) {
    echo 'Facebook SDK Error: ' . $e->getMessage();
      exit;
}































?>