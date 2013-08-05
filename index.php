<?php
require getcwd().'/Dropbox/music/metaData.php';
$count=0;
$column_count=1;
$data=array();
$songs=array();
$data['title']="My Music Hub";
$data['song_requested']="";
$data['playlist_requested']=array();
$data['history']="";
foreach($content as $file) {
	$file=(array) $file;
	$count++;
	$songs[$count]['name']=str_replace(array(".mp3","/"),"",$file['path']);
	$songs[$count]['path']=$file['path'];
	if (isset($_GET['song']) && (int)$_GET['song']==$count){
		$data['title']=$songs[$count]['name'];
		$data['song_requested']=$count;
	}
}

if (isset($_GET['playlist'])) {
	$playlist=explode(",",$_GET['playlist']);
	foreach($playlist as $song) {
		array_push($data['playlist_requested'], $song);
	}
}

if (isset($_COOKIE['history'])) {
	$history=$_COOKIE['history'];
	$history_array=explode("||",$history);
	foreach($history_array as $song) {
		$song_info=explode("&&",$song);
		$song_name=$song_info[0];
		$song_path=$song_info[1];
		$data['history'] .= "<li><a href='javascript:;' class='song' file='".$song_path."'>".str_replace(".mp3","",$song_name)."</a></li>";
	}
}

echo '<script>var songs='.json_encode($songs).'; var data='.json_encode($data).'</script>';
include('view.php');
?>