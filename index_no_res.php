<?php
$output="";
//$dir = "/var/www/mus/music";
//$dir = "C:/wamp/www/mus/music";
$dir = "/home/slimfadi/webapps/music/music";
$dh = opendir($dir);
$count=0;
$scan=scandir($dir);
$title="My Music";
$script="";
foreach($scan as $file) {
	if ($file!="." && $file!=".." && strpos($file,".mp3")>0){
		$count++;
		//$tag=id3_get_tag($dir.$file);
		$file1=str_replace(".mp3","",$file);
		//$file1=substr($file1,0,23);
		$tooltip="";
		if (strlen($file)>40) {
			$tooltip=str_replace(".mp3","",$file);
		}
		
		if (isset($_GET['song']) && (int)$_GET['song']==$count){
			$title=$file1;
			$script= '<script>
						$("document").ready(function(){
							setTimeout(function(){
								$("#song_'.$count.'").click();
								scroll_to_active();
							},1000);
						})
					</script>';
		}
		//$like_button='<div class="fb-like" data-href="http://music.fadi-webs.com?song='.$count.'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>';
		$output.='<a href="javascript:;" file="'.$file.'" title="'.$tooltip.'" class="song song_grid" id="song_'.$count.'" ><p>'.$file1.'</p><span class="song_number">'.$count.'</span><span class="download_link"><img src="http://www.cs.umd.edu/hcil/counterpoint/download/tutorial/down_arrow.gif" style="margin-top: 3px;"></span></a>';
	}
}
if (isset($_GET['playlist'])) {
	$playlist=explode(",",$_GET['playlist']);
	$script="
		<script>
		$('document').ready(function(){
			setTimeout(function(){
				list=new Array();
				$('#hide_playlist').show();
				";
				foreach($playlist as $song) {
					$script.= "	song=$('#song_".$song."').attr('file');
							song_name=song.replace('.mp3','');
							object= new Object();
							object.title=song_name;
							object.mp3='music/'+song;
							list.push(object);
					";
				}
	$script.= " playlist.setPlaylist(list);
				setTimeout(function(){
					$('.jp-play').click();
					$('.jp-playlist ul').sortable();
				},500);
			},1000);
		});
		</script>
	";
}
if(isset($_GET['filter']) && !isset($_GET['song'])){
	$title=$_GET['filter'];
}
closedir($dh);
?>


<!DOCTYPE html>
<html>
	<head>
	<meta charset=utf-8 />
	
	<!-- Website Design By: www.happyworm.com -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<link href="style.css" rel="stylesheet" type="text/css" />
		<link href="jquery-ui/css/jquery-ui-1.8.24.custom.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="jquery-ui/js/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="jquery-ui/js/jquery-ui-1.8.24.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
		<script type="text/javascript" src="js/jplayer.playlist.min.js"></script>
		<script type="text/javascript" src="jquery.cookie.js"></script>
		<script type="text/javascript" src="html5uploader.js"></script>
		<script type="text/javascript" src="js.js"></script>
		<title><?php echo $title ?></title>
		<?php 
		echo $script; 
		?>
	</head>
	<body>
		<iframe id="download_frame" style="display:none"></iframe>
		<div id="modal" style="position:absolute;top:0;left:0;width:100%;height:900px;background:#f5f5f5;z-index:99;"></div>
		<div id="history">
		<p style="margin: 5px 0;font-family: arial;text-align: center;">History</p>
			<ul>
				<?php 
					if (isset($_COOKIE['history'])) {
						$history=$_COOKIE['history'];
						$history_array=explode("||",$history);
						foreach($history_array as $song) {
							echo "<li><a href='javascript:;' class='song' file='".$song."'>".str_replace(".mp3","",$song)."</a></li>";
						}
					}
				?>
			</ul>
			<div id="history_close"></div>
		</div>
		
		<div id="header" style="position:fixed;top:0px; width:100%;left:0;z-index:10;background:#EEE;height:75px;">
			<div id="jquery_jplayer_2" class="jp-jplayer"></div>
			<div id="jp_container_2" class="jp-audio">
				<div class="jp-type-playlist">
					<div class="jp-gui jp-interface">
						<ul class="jp-controls">
							<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
							<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
							<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
							<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
							<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
							<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
							<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
							<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
						</ul>
						<div class="jp-progress">
							<div class="jp-seek-bar">
								<div class="jp-play-bar"></div>
							</div>
						</div>
						<div class="jp-volume-bar">
							<div class="jp-volume-bar-value"></div>
						</div>
						<div class="jp-time-holder">
							<div class="jp-current-time"></div>
							<div class="jp-duration"></div>
						</div>
						<ul class="jp-toggles">
							<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>
							<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off">shuffle off</a></li>
							<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
							<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
						</ul>
					</div>
					<div class="jp-playlist">
						<div id="hide_playlist" style="display:none;float:right;margin: 0 4px; font-size: 9px; line-height: 32px;cursor:pointer;">hide</div>
						<ul>
							<li></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="mode" class="mode0" id="mode">
				Playlist
			</div>
			<input class="filter" id="big_filter" placeholder="Filter" />
			<div id="inputs" style="float: right;top: 0;position: absolute;right:0;display:none;">
				<input class="filter" placeholder="this does something"  />
				<input id="copy" placeholder="this does nothing"  />
				<input id="song_name" placeholder="this does nothing"  />
			</div>
			<div id="grid-mobile"><img src="list-button.gif" /></div>
		</div>
		<div style="z-index:-10; margin-top:90px;margin-left:20px;">
		<?php echo $output; ?>
		</div>
		<script type="text/javascript">
			var sc_project=7970449; 
			var sc_invisible=1; 
			var sc_security="7b59d1fc"; 
		</script>
		<script type="text/javascript" src="http://www.statcounter.com/counter/counter.js"></script>		
	</body>
</html>