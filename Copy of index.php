<?php
$output="";
$dir = "/var/www/music/music";
$dh = opendir($dir);
$count=0;
$scan=scandir($dir);
//print_r($scan);
//while (($file = readdir($dh)) !== false) {
	$title="My Music";
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
			}
			$output.='<div file="'.$file.'" title="'.$tooltip.'" class="song" id="song_'.$count.'" >'.$file1.'<span class="song_number">'.$count.'</span></div>';
		}
	}
	if(isset($_GET['filter']) && !isset($_GET['song'])){
		$title=$_GET['filter'];
	}
closedir($dh);
?>
<html>
<head>
<title><?php echo $title; ?></title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="jquery.cookie.js"></script>
<script src="flowplayer-3.2.6.min.js"></script>
<script src="script.js"></script>
<link rel="icon" type="image/png" href="favicon.ico">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class='scroll_to_top' ></div>
<div class='scroll_down' ></div>
<div id="tips">
	<ul>
		<li>Search box: try typing "sp emi" and figure it out!</li>
		<li>Hot key: "r" - plays a random song</li>
		<li>Hot key: "v" - play a random song from the visible songs only</li>
		<li>Hot key: "d" - scrolls the page down a bit</li>
		<li>Hot key: "u" - scrolls the page up a bit</li>
		<li>Hot key: "t" - scrolls the page to top</li>
		<li>Hot key: "c" - scrolls to the current song</li>
		<li>Hot key: "f" - activates the search box</li>
		<li>Hot key: "s" - show all songs</li>
		<li>The input boxes that say "this does nothing" do something!</li>
	</ul>
	<div id="tips_close"></div>
</div>


<div id="header">
	<div id="audio" style="display:block;width:750px;height:30px; position:fixed; top:0px"></div>
	<div id="inner_header">
		<div id="filter_div" style="position:fixed; top:0px;">
			<input id="filter" placeholder="this does something" style="width:300px; height:35px;"  />
			<input id="copy" placeholder="this does nothing" style="width:300px; height:35px;"  />
			<input id="song_name" placeholder="this does nothing" style="width:300px; height:35px;"  />
		</div>
		
		<div id="playlist_mode" class="button">Playlist Mode</div>
		<div id="random_song" class="button">Random Song</div>
		<div style="display:none;" id="get_playlist" class="button">Get Playlist URL</div>
		<div id="random_playlist_10" class="button">Random Playlist (10)</div>
		<div id="random_playlist_20" class="button">Random Playlist (20)</div>
		<p></p>
	</div>
</div>
<script>
$("document").ready(function(){
	playlist=new Array();
	object1=new Object();
	<?php
	if (isset($_GET['playlist'])) {
		if(count(explode("-",$_GET['playlist']))>1){
			$playlist_range=explode("-",$_GET['playlist']);
			$playlist=Array();
			array_push($playlist,$playlist_range[0]);
		} else {
			$playlist=explode(",",$_GET['playlist']);
		}
		
		echo "object1.url='music/'+$('#song_".$playlist[0]."').attr('file');";
		echo "object1.provider='audio';";
		echo "object1.onBegin=function(){\$('#song_".$playlist[0]."').css('background-color','#4c8efc');};";
		echo "playlist.push(object1);";
		echo '$("#playlist_mode").click();';
	}
	?>
	
	player=flowplayer("audio", "flowplayer-3.2.7.swf", {
		plugins: {
			audio: {
				url: 'flowplayer.audio-3.2.2.swf'
			},
			controls: {
				fullscreen: false,
				height: 30,
				autoHide: false,
				playlist:true
			}
		},
		playlist: playlist
	});
	player.onStart(function() {
		<?php
		if (isset($_GET['playlist'])) {
			if(count(explode("-",$_GET['playlist']))>1){
				$playlist= Array();
				$play_list_rang=explode("-",$_GET['playlist']);
				for($i=$play_list_rang[0];$i<=$play_list_rang[1];$i++){
					array_push($playlist,$i);
				}
			} else {
				$playlist=explode(",",$_GET['playlist']);
			}
			
			echo '$(".song").hide();';
			$count=1;
			foreach ($playlist as $song) {
				echo '$("#song_'.$song.'").show();';
				echo '$("#song_'.$song.'").append("<span class=\'playlist_order\'>'.$count.'</span>");';
				$count++;
				if($song!=$playlist[0]) {
					echo '$f().addClip("music/"+$("#song_'.$song.'").attr("file"));';
				}
			}
			echo "playlist_counter=".$count;
		}
		?>
	});
	<?php
	if (isset($_GET['song'])) {
		echo "$('#song_".$_GET['song']."').click();";
	}
	if (isset($_GET['filter'])) {
		echo "$('#filter').val('".$_GET['filter']."');$('#filter').keyup();";
	}
	?>
});
</script>
<div>
<?php echo $output; ?>
</div>
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=7970449; 
var sc_invisible=1; 
var sc_security="7b59d1fc"; 
</script>
<script type="text/javascript"
src="http://www.statcounter.com/counter/counter.js"></script>
<noscript><div class="statcounter"><a title="godaddy stats"
href="http://statcounter.com/godaddy_website_tonight/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/7970449/0/7b59d1fc/1/"
alt="godaddy stats"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
</body>
</html>
