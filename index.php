<?php

include('config.php');

$login_button = '';


if(isset($_GET["code"]))
{

 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);


 if(!isset($token['error']))
 {
 
  $google_client->setAccessToken($token['access_token']);

 
  $_SESSION['access_token'] = $token['access_token'];


  $google_service = new Google_Service_Oauth2($google_client);

 
  $data = $google_service->userinfo->get();

 
  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }
 }
}

// '<a href="'.$google_client->createAuthUrl().'">Login With Google</a>';
if(!isset($_SESSION['access_token']))
{

 $login_button ='<a style="width:500px;" href="'.$google_client->createAuthUrl().'" class="btn btn-danger btn-block"><i class="fa fa-google"></i> Sign in with <b>Google</b></a>';
}



// Facebook



$permissions = ['email']; //optional

if (isset($accessToken))
{
  if (!isset($_SESSION['facebook_access_token'])) 
  {
    //get short-lived access token
    $_SESSION['facebook_access_token'] = (string) $accessToken;
    
    //OAuth 2.0 client handler
    $oAuth2Client = $fb->getOAuth2Client();
    
    //Exchanges a short-lived access token for a long-lived one
    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
    
    //setting default access token to be used in script
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  } 
  else 
  {
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  }
  
  
  //redirect the user to the index page if it has $_GET['code']
  if (isset($_GET['code'])) 
  {
    header('Location: ./');
  }
  
  
  try {
    $fb_response = $fb->get('/me?fields=name,first_name,last_name,email');
    $fb_response_picture = $fb->get('/me/picture?redirect=false&height=200');
    
    $fb_user = $fb_response->getGraphUser();
    $picture = $fb_response_picture->getGraphUser();
    
    $_SESSION['fb_user_id'] = $fb_user->getProperty('id');
    $_SESSION['fb_user_name'] = $fb_user->getProperty('name');
    $_SESSION['fb_user_email'] = $fb_user->getProperty('email');
    $_SESSION['fb_user_pic'] = $picture['url'];
    
    
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Facebook API Error: ' . $e->getMessage();
    session_destroy();
    // redirecting user back to app login page
    header("Location: ./");
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK Error: ' . $e->getMessage();
    exit;
  }
} 
else 
{ 
  // replace your website URL same as added in the developers.Facebook.com/apps e.g. if you used http instead of https and you used
  $fb_login_url = $fb_helper->getLoginUrl('http://localhost/facebook1/', $permissions);
}









?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PHP Login using Social Media Account</title>
  <meta charset="utf-8">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  
 </head>
 <body>
  <div class="container">
   <br />
   <h2 align="center">PHP Login using Google Account</h2>
   <br />
   <a style="width:500px; margin-left: 299px;" href="<?php echo $fb_login_url;?>" class="btn btn-primary btn-block"><i class="fa fa-facebook"></i> Sign in with <b>Facebook</b></a><br><br>
   <div class="panel panel-default">
   <?php
   if($login_button == '')
   {
    echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
    echo '<img src="'.$_SESSION["user_image"].'" class="img-responsive img-circle img-thumbnail" />';
    echo '<h3><b>Name :</b> '.$_SESSION['user_first_name'].' '.$_SESSION['user_last_name'].'</h3>';
    echo '<h3><b>Email :</b> '.$_SESSION['user_email_address'].'</h3>';
    echo '<h3><a href="logout.php">Logout</h3></div>';
   }
   else
   {
    echo '<div align="center">'.$login_button . '</div>';
   }
   ?>
   </div>
  </div>
 </body>
</html>


