<?php
require getcwd().'/Dropbox/music/metaData.php';
$output="";
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

echo '<script>var a='.json_encode($songs).'; var data='.json_encode($data).'</script>';
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset=utf-8 />
	<meta name="viewport" content="width=480, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<link href="public/css/style.css" rel="stylesheet" type="text/css" />
		<link href="public/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<title><?php echo $data['title'] ?></title>
	</head>
	<body>
		<div id="history">
			<p style="margin: 5px 0;font-family: arial;text-align: center;">History</p>
			<ul>
				<?php echo $data['history']; ?>
			</ul>
			<div id="history_close"></div>
		</div>
		
		<div id="header" style="position:fixed;top:0px; width:100%;left:0;z-index:10;background:#EEE;height:75px;">
			<div class="row-fluid" style="width: 425px;">
				<div class="span5">
				<div id="jquery_jplayer_2" class="jp-jplayer"></div>
				<div id="jp_container_2" class="jp-audio">
					<div class="jp-type-playlist">
						<div class="jp-gui jp-interface">
							<ul class="jp-controls" style="width:100px;">
								<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
								<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
								<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
								<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
							</ul>
							<div class="jp-progress" style="left:125px; width:295px;">
								<div class="jp-seek-bar">
									<div class="jp-play-bar"></div>
								</div>
							</div>
							<div class="jp-volume-bar" style="left:125px; width:295px; top:20px;">
								<div class="jp-volume-bar-value"></div>
							</div>
							<div class="jp-time-holder" style="left:125px; width:295px;">
								<div class="jp-current-time"></div>
								<div class="jp-duration"></div>
							</div>
						</div>
						<div class="jp-playlist">
							<div id="hide_playlist" style="display:none;float:right;margin: 0 4px; font-size: 9px; line-height: 32px;cursor:pointer;">hide</div>
							<ul>
								<li></li>
							</ul>
						</div>
					</div>
				</div>
				</div>
				<div id="mode" class="mode0 span1" id="mode">Playlist</div>
				<input class="filter span4" id="big_filter" placeholder="Filter" />
			</div>
		</div>
		<div id="all_songs">
			<?php echo $output; ?>
		</div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="public/js/plugins.js"></script>
		<script type="text/javascript" src="public/js/js.js"></script>
		<script type="text/javascript">
			$("document").ready(function(){
				var counter=0;
				for (key in a) {
					if (counter%4 == 0) {
						$("#all_songs").append('<div class="row-fluid show-grid songs_row"></div>');
					}
					var song_number=counter+1;
					$(".songs_row").last().append('<a href="javascript:;" file="'+a[song_number]['path']+'" title="'+a[song_number]['name']+'" class="song span3" id="song_'+song_number+'" ><p>'+a[song_number]['name']+'</p><span class="song_number">'+song_number+'</span></a>');
					counter++;
				}
				if(data['song_requested']) {
					setTimeout(function(){
						$("#song_"+data['song_requested']).click();
						scroll_to_active();
					},1000);
				}

				$("#mode").click();
				setTimeout(function(){
					list=new Array();
					$('#hide_playlist').show();

					for(var i =0 ;i<data['playlist_requested'].length;i++) {
						song=$('#song_'+data['playlist_requested'][i]).click();
					}
				},2000,function(){
					console.log(1)
					$('.jp-play').click();
				});
			});
		</script>
	</body>
</html>