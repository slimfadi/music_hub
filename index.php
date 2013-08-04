<?php
require getcwd().'/Dropbox/music/metaData.php';
$output="";
$count=0;
$title="My Music Hub";
$script="";
$column_count=1;
foreach($content as $file) {
	$file=(array) $file;

	$count++;
	$file1=str_replace(".mp3","",$file['path']);
	$file1=str_replace("/","",$file['path']);
	$tooltip=$file1;
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
	if ($column_count==1) {
		$output.='<div class="row-fluid show-grid">';
	}
	$output.='<a href="javascript:;" file="'.$file['path'].'" title="'.$tooltip.'" class="song span3" id="song_'.$count.'" ><p>'.$file1.'</p><span class="song_number">'.$count.'</span><span class="download_link"><img src="http://www.cs.umd.edu/hcil/counterpoint/download/tutorial/down_arrow.gif" style="margin-top: 3px;"></span></a>';
	if ($column_count==4) {
		$output.='</div>';
		$column_count=1;
	} else {
		$column_count++;
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
		<title><?php echo $title ?></title>
	</head>
	<body>
		<div id="history">
			<p style="margin: 5px 0;font-family: arial;text-align: center;">History</p>
			<ul>
				<?php 
					if (isset($_COOKIE['history'])) {
						$history=$_COOKIE['history'];
						$history_array=explode("||",$history);
						foreach($history_array as $song) {
							$song_info=explode("&&",$song);
							$song_name=$song_info[0];
							$song_path=$song_info[1];
							echo "<li><a href='javascript:;' class='song' file='".$song_path."'>".str_replace(".mp3","",$song_name)."</a></li>";
						}
					}
				?>
			</ul>
			<div id="history_close"></div>
		</div>
		
		<div id="header" style="position:fixed;top:0px; width:100%;left:0;z-index:10;background:#EEE;height:75px;">
			<div class="row-fluid">
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
				<div id="mode" class="mode0 span1" id="mode">
					Playlist
				</div>
				<input class="filter span4" id="big_filter" placeholder="Filter" />
			</div>
		</div>
		<div id="all_songs">
			<?php echo $output; ?>
		</div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="public/js/plugins.js"></script>
		<script type="text/javascript" src="public/js/js.js"></script>
		<?php 
			echo $script; 
		?>
	</body>
</html>