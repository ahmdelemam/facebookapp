<?php
//git@heroku.com:cubeadv.git
//App URL http://cubeadv.herokuapp.com/

require_once('php-sdk/facebook.php');

$config = array(
    'appId' => '551590664908056',
    'secret' => 'e3b02355ca192eea751ba392810b3068',
    'fileUpload' => true,
);

$facebook = new Facebook($config);
$user_id = $facebook->getUser();

$photo = './mypic.jpg'; // Path to the photo on the local filesystem
$message = 'Photo upload via the PHP SDK!';
?>
<html>
    <head></head>
    <body>

        <?php
        if ($user_id) {

            // We have a user ID, so probably a logged in user.
            // If not, we'll get an exception, which we handle below.
            try {
                //Create an album
                $album_details = array(
                    'message' => 'The description of the album', // The description of the album from our form
                    'name' => 'Album name', // any Album name from our form
                );
                $create_album = $facebook->api('/me/albums', 'post', $album_details);

                //Get album ID of the album you've just created
                $album_uid = $create_album['id'];

                //Upload a photo to album of ID...
                $photo_details = array(
                    'message' => 'Photo message',
                    'source' => 'multipart/form-data'
                );
                $file = 'app.jpg'; //Example image file
                $photo_details['image'] = '@' . realpath($file);

                $upload_photo = $facebook->api('/' . $album_uid . '/photos', 'post', $photo_details);

                $ret_obj = $facebook->api('/me/photos', 'POST', array(
                    'source' => '@' . $photo,
                    'message' => $message,
                        )
                );
                echo '<br /><a href="' . $facebook->getLogoutUrl() . '">logout</a>';
            } catch (FacebookApiException $e) {
                // If the user is logged out, you can have a 
                // user ID even though the access token is invalid.
                // In this case, we'll get an exception, so we'll
                // just ask the user to login again here.
                $login_url = $facebook->getLoginUrl(array(
                    'scope' => 'photo_upload, access_token, user_photos, friends_photos'
                ));
                echo 'Please <a href="' . $login_url . '">login.</a>';
                error_log($e->getType());
                error_log($e->getMessage());
            }
        } else {

            // To upload a photo to a user's wall, we need photo_upload  permission
            // We'll use the current URL as the redirect_uri, so we don't
            $login_url = $facebook->getLoginUrl(array('scope' => 'photo_upload, access_token, user_photos, friends_photos'));
            echo 'Please <a href="' . $login_url . '">login.</a>';
        }
        ?>

    </body>
</html>