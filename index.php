<?php 
	require 'src/facebook.php';
	$facebook = new Facebook(array(
		'appId'  => '551590664908056',
		'secret' => 'e3b02355ca192eea751ba392810b3068'
	));
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Facebook PHP</title>
</head>
<body>
<h1>Hello World</h1>
<?php
	//get user from facebook object
	$user = $facebook->getUser();
	
	if ($user): //check for existing user id
		echo '<p>User ID: ', $user, '</p>';
	else: //user doesn't exist
		$loginUrl = $facebook->getLoginUrl(array(
			'diplay'=>'popup',
			'redirect_uri' => 'http://apps.facebook.com/ahmdelemam'
		));
		echo '<p><a href="', $loginUrl, '" target="_top">login</a></p>';
	endif; //check for user id
?>
</body>
</html>