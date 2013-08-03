<?php 
################ 
## NOT TESTED ## 
################ 

// create a new CURL resource 
$ch = curl_init(); 

// set URL and other appropriate options 
curl_setopt($ch, CURLOPT_URL, $_POST['link']); 
curl_setopt($ch, CURLOPT_HEADER, false); 
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

set_time_limit(300); # 5 minutes for PHP 
curl_setopt($ch, CURLOPT_TIMEOUT, 300); # and also for CURL 
if (isset($_POST['name']) && strlen($_POST['name'])>1 ){
	$file_name=$_POST['name'].".mp3";
} else {
	$file_name=explode("/",$_POST['link']);
	$last_element=count($file_name)-1;
	$file_name=$file_name[$last_element];
	$file_name=str_replace("%20"," ",$file_name);
	$file_name=explode("mp3",$file_name);
	$file_name=$file_name[0]."mp3";
}
$file_name=str_replace("\'","",$file_name);
$file="/home/slimfadi/webapps/music/music/".$file_name;
echo $file;
$outfile = fopen($file, 'wb'); 
curl_setopt($ch, CURLOPT_FILE, $outfile); 

// grab file from URL 
curl_exec($ch); 
fclose($outfile); 

// close CURL resource, and free up system resources 
curl_close($ch); 

### As this is meant to be run from cron, 
### it doesn't need to output anything. 
### It might be a good idea to mail yourself 
### at the end though (or in case of errors) 
//mail('yourself@example.com', 'cron job with CURL completed', '(empty body)'); 
?>
