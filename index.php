<?php

$app_id = "551590664908056";
$app_secret = "e3b02355ca192eea751ba392810b3068";
$post_login_url = "https://apps.facebook.com/ahmdelemam/";
$album_name = 'YOUR_ALBUM_NAME';//get it from our form
$album_description = 'YOUR_ALBUM_DESCRIPTION';//get it from our form

$code = $_REQUEST["code"];

//Obtain the access_token with publish_stream permission 
if (empty($code)) {
    $dialog_url = "http://www.facebook.com/dialog/oauth?"
            . "client_id=" . $app_id
            . "&redirect_uri=" . urlencode($post_login_url)
            . "&scope=publish_stream";
    echo("<script>top.location.href='" . $dialog_url .
    "'</script>");
} else {
    $token_url = "https://graph.facebook.com/oauth/"
            . "access_token?"
            . "client_id=" . $app_id
            . "&redirect_uri=" . urlencode($post_login_url)
            . "&client_secret=" . $app_secret
            . "&code=" . $code;
    $response = file_get_contents($token_url);
    $params = null;
    parse_str($response, $params);
    $access_token = $params['access_token'];

    // Create a new album
    $graph_url = "https://graph.facebook.com/me/albums?"
            . "access_token=" . $access_token;

    $postdata = http_build_query(
            array(
                'name' => $album_name,
                'message' => $album_description
            )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' =>
            'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context = stream_context_create($opts);
    $result = json_decode(file_get_contents($graph_url, false, $context));

    // Get the new album ID
    $album_id = $result->id;

    //Show photo upload form and post to the Graph URL
    $graph_url = "https://graph.facebook.com/" . $album_id
            . "/photos?access_token=" . $access_token;
    echo '<html><body>';
    echo '<form enctype="multipart/form-data" action="'
    . $graph_url . ' "method="POST">';
    echo 'Adding photo to album: ' . $album_name . '<br/><br/>';
    echo 'Please choose a photo: ';
    echo '<input name="source" type="file"><br/><br/>';
    echo 'Say something about this photo: ';
    echo '<input name="message" type="text"
            value=""><br/><br/>';
    echo '<input type="submit" value="Upload" /><br/>';
    echo '</form>';
    echo '</body></html>';
}
?>