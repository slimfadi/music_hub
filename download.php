<?php 
	header("Content-disposition: attachment;");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$_GET['path']);
	readfile($_GET['path']);
?>