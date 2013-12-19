<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="application/json;text/html;charset=UTF-8">
<title>Self-contained PHP example for Thinglink "user authentication"</title>
<script type="text/javascript" src="//cdn.thinglink.me/jse/thinglink.js"></script>
<script type="text/javascript" src="//cdn.thinglink.me/jse/embed.js"></script>

<script type="text/javascript" src="//www.thinglink.com/jse/tlconnect.js"></script>

</head>
<body>
<script type="text/javascript">
function initAuth(acc_token)
{
	var access_token=acc_token;
	// Your credentials
	TLC.init({
		client:'469422445300809730',
		user: 'EgoApp',
		isOwner: true,
		redirectUri: 'http://staging.emantras.com/emantrasvss/thinglink/redirect.html'
	});
	
	function jsonparser1() {
		$.ajax({
            type: "GET",
            url: "https://api.thinglink.com/api/me?callback=callbackJSON&pretty=true&access_token="+access_token,
            dataType: "jsonp"           
        });
	}
	TLC.login( function() {
		alert("User just logged in!");
		jsonparser1()		
	});
}
window.callbackJSON = function(actualJsonpData) {
	actualJsonpData=JSON.stringify(actualJsonpData);
    alert("actualJsonpData"+actualJsonpData);
}
</script>
<?php
 session_start();
//returns session token for calls to API using oauth 2.0
function get_oauth2_token() {
    $client_id='469422445300809730';
    $username='beulahgnanam.rm@emantras.com';
	$password='beatific';
	$client_secret='Ndk8qvRHAmTm5cxuyA2kXaUZNyM';
    $oauth2token_url = "https://www.thinglink.com/auth/token";
    $clienttoken_post = array(
		"username" => $username,
		"client_id" => $client_id,
		"client_secret" => $client_secret,
		"password" => $password,
		"grant_type" => "password"
    );

    $curl = curl_init($oauth2token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $clienttoken_post);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $json_response = curl_exec($curl);
    curl_close($curl);
    $authObj = json_decode($json_response);

    if (isset($authObj->refresh_token)){
        global $refreshToken;
        $refreshToken = $authObj->refresh_token;
    }
    $accessToken = $authObj->access_token;
    return $accessToken;
}
//Oauth 2.0: exchange token for session token so multiple calls can be made to api
 
  $_SESSION['accessToken'] = get_oauth2_token();
 
 echo '<script type="text/javascript">initAuth("'.$_SESSION['accessToken'].'");</script>';
 ?>
<div class="thinglinkConnect">
</div>
<div class="thinglinkContainer">
</div>
</body>
</html>

