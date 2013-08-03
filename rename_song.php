<?php
	$old_file="/home/slimfadi/webapps/music/music/".$_POST['old_name'];
	$new_file="/home/slimfadi/webapps/music/music/".$_POST['new_name'];
	rename($old_file, $new_file);
?>
