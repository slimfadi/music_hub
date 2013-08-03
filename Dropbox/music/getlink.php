<?php 
require_once('bootstrap.php');
$media= $dropbox->media($_POST['path']);
$url=(array) $media['body'];
$url=$url['url'];
echo $url;
?>
