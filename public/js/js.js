jQuery.expr[':'].Contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
	  .indexOf(m[3].toUpperCase()) >= 0;
};
jQuery.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
	  .indexOf(m[3].toUpperCase()) >= 0;
};

$(document).ready(function(){
	window_height=$(document).height();
	$("#modal").css("height",window_height+"px");
	$(window).load(function(){
		$("#modal").fadeOut();
		$(".jp-shuffle,.jp-repeat").hide();
	});
	playlist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_2",
		cssSelectorAncestor: "#jp_container_2"
	}, [
		
	], {
		swfPath: "js",
		supplied: "oga, mp3",
		wmode: "window",
		playlistOptions: {
			enableRemoveControls: true
		}
	});
	$(".song").live("click",function(){
		$(".song").removeClass("blue");
		$(this).addClass("blue");
		path=$(this).attr("file");
		this_song=$(this);
		song_name=this_song.children("p").text().replace(".mp3","");
		if ($(this).parents("#history").length>0) {
		  song_name=$(this).text();
		}
		$.post("Dropbox/music/getlink.php",{path:path},function(song){
			
			if ($("#mode").hasClass("mode0")) {
				$("#inner_header p").text(song_name);
				playlist.setPlaylist([
					{
						title:song_name,
						mp3:song,
					}
				]);
				setTimeout(function(){
					playlist.play();
					if($(".jp-playlist li").length < 2 ) {
						$('#hide_playlist,#show_playlist').hide();
					}
				},1001);
			} else {
				playlist.add({
					title:song_name,
					mp3:song,
				});
				if($(".jp-playlist li").length > 1 ) {
					$('#hide_playlist').show();
				}
				$(".jp-playlist ul").sortable({
					placeholder: "ui-state-highlight",
					update:function(event,ui){
						list=new Array();
						$(".jp-playlist li").each(function(){
							song_name=this_song.children("div").children(".jp-playlist-item").text();
							song=song_name+".mp3";
							object=new Object();
							object.title=song_name;
							object.mp3='music/'+song;
							list.push(object);
						});
						playlist.setPlaylist(list);
					}
				});
			}
			add_to_history(song,song_name,path);
		})
		
	});
	$("#grid-mobile").click(function(){
		$(".song").toggleClass("song_grid");
		$(".song").toggleClass("song_stack");
		if ($(this).children("img").attr("src")=="grid-button.gif") {
			$(this).children("img").attr("src","list-button.gif");
		} else {
			$(this).children("img").attr("src","grid-button.gif")
		}
	});
	$(".jp-playlist-item-remove").live("click",function(){
		$('#show_playlist').attr("id","hide_playlist").text("hide");
		$('.jp-playlist li').show();
		if($(".jp-playlist li").length == 2) {
			$('#hide_playlist').hide();
		}
	});
	
	$("#hide_playlist").live("click",function(){
		$(".jp-playlist li").hide();
		$(".jp-playlist-current").show();
		$("#jquery_jplayer_2").bind($.jPlayer.event.play, function(event) {
		 	$(".jp-playlist li").hide();
			$(".jp-playlist-current").show();
		});
		$(this).text("show");
		$(this).attr("id","show_playlist");
	});
	$("#show_playlist").live("click",function(){
		$(".jp-playlist li").fadeIn();
		$("#jquery_jplayer_2").unbind($.jPlayer.event.play);
		$(this).text("hide");
		$(this).attr("id","hide_playlist");
	});
	
	
	$(".filter").keyup(function(){
		var search_text=$(this).val();
		search_array=search_text.split(" ");
		$(".song").hide();
		if(search_text!=""){
			for(i=0;i<search_array.length;i++){
				toshow = $(".song:contains("+search_array[i]+")");
				toshow.show();
				toshow.addClass("yes"+i);
			}
		} else {
			$(".song").show();
		}
		if(search_text!=""){
			for(i=0;i<search_array.length;i++){
				$(".song").not(".yes"+i).hide();
				$(".song").removeClass("yes"+i);
			}
		}
		count = $(".showen_songs").length;
	});
	
	$("#history_close").toggle(function(){
		$(this).parent().animate({right:"0px"});
	},function(){
		$(this).parent().animate({right:"-300px"});
	});
	
	
	
	activate_keys();
	$("input").focus(function(){
		$("body").unbind("keydown");
	});
	$("input").blur(function(){
		activate_keys();
	});
	
	$("#mode").click(function(){
		$(this).toggleClass("mode0");
		$(this).toggleClass("mode1");
		if ($(this).hasClass("mode0")){
			$(".jp-shuffle,.jp-repeat").hide();
		} else {
			$(".jp-shuffle,.jp-repeat").show();
		}
	});
	$(".jp-next").click(function(){
		if ($("#mode").hasClass("mode0")){
			play_random_song();
		}
	});
	$(".jp-previous").click(function(){
		console.log(1);
		if ($("#mode").hasClass("mode0")){
			current=$(".jp-playlist-item").text();
			$("#history .song:contains("+current+")").parent().next().children(".song").click();
		}
	});
	$("#jquery_jplayer_2").bind($.jPlayer.event.ended,function(){
		if ($("#mode").hasClass("mode0")){
			setTimeout(function(){
				play_random_song();
			},3000);
		}
	});
});
function activate_keys(){
	$("body").keydown(function(e){
		if(e.which==82){
			var songs_count=$(".song").length;
			var number=Math.floor(Math.random()*songs_count)+1;
			$("#song_"+number).click();
			scroll_to_active();
		}
		if(e.which==84){
			scroll_to_top();
		}
		if(e.which==68){
			scroll_down();
		}
		if(e.which==70){
			$(".filter:visible").first().focus();
			return false;
		}
		if(e.which==67){
			scroll_to_active();
		}
		if(e.which==85){
			scroll_up();
		}
		if(e.which==83){
			$(".song").show();
		}
		if(e.which==86){
			random_from_visible();
		}
		if(e.which==72){
			hide_header();
		}
		if(e.which==73){
			$("#inputs").toggle();
		}
		if(e.which==80){
			$(".jp-play:visible,.jp-pause:visible").click();
		}
		if(e.which==78){
			$(".jp-next").click();
		}
		if(e.which==66){
			$(".jp-previous").click();
		}
		if(e.which==187){
			current=$(".jp-volume-bar-value").width();
			new_vol=(current/46)+0.05;
			$("#jquery_jplayer_2").jPlayer("volume",new_vol);
		}
		if(e.which==189){
			current=$(".jp-volume-bar-value").width();
			new_vol=(current/46)-0.05;
			$("#jquery_jplayer_2").jPlayer("volume",new_vol);
		}
		if(e.which==76){
			$("#mode").click();
		}
		if(e.which==68){
			$(".jp-playlist-item-remove").first().click();
		}
	});
	uploader = new uploader('big_filter', null, 'upload_file.php', null);
}
function scroll_to_active(){
	if($(".blue").length>0){
		topp=$(".blue").offset().top-100;
		$("body").animate({scrollTop:topp});
	}
}
function random_from_visible(){
	var songs_count=$(".song").length;
	var number=Math.floor(Math.random()*songs_count)+1;
	if($("#song_"+number+":visible").length>0){			
		$("#song_"+number).click();
		scroll_to_active();
	} else {
		random_from_visible();			
	}
}
function play_random_song(){
	var songs_count=$(".song").length;
	var number=Math.floor(Math.random()*songs_count)+1;
	$("#song_"+number).click();
}
function scroll_to_top (){
	$('body,html').stop().animate({
              scrollTop: 0
         }, 800);
         return false;
}
function scroll_down(){
	cur=$(window).scrollTop();
	$('body,html').stop().animate({
		scrollTop: cur+500
	}, 800);
	return false;
}
function scroll_up(){
	cur=$(window).scrollTop();
	$('body,html').stop().animate({
		scrollTop: cur-500
	}, 800);
	return false;
}
function hide_header() {
	$("#header").slideToggle();
}
function add_to_history(song,song_name,path) {
	history=$.cookie("history");
	dont_add=0;
	if ( history == null ) {
		history=song_name+"&&"+path;
	} else {
		history_array=history.split("||");
		for(i=0;i<history_array.length;i++){
			if ( song == history_array[i] ) {
				dont_add=1;
			}
		}
		history=song_name+"&&"+path+"||"+history;
	}
	if(dont_add==0) {
		$.cookie("history",history);
		$("#history ul").prepend("<li><a href='javascript:;' class='song' file='"+path+"'>"+song_name.replace(".mp3","")+"</a></li>");
	}
}

