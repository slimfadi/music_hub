$(document).ready(function(){
	var playlist = new jPlayerPlaylist(
		{
			jPlayer: "#jquery_jplayer_2",
			cssSelectorAncestor: "#jp_container_2"
		}, [], {
			supplied: "oga, mp3",
			wmode: "window",
		}
	);
	$(".song").live("click",function(){
		$(".song").removeClass("blue");
		$(this).addClass("blue");
		var path=$(this).attr("file");
		
		$.post("Dropbox/music/getlink.php",{path:path},function(song){

			var song_array=song.split("/");
			var song_name=decodeURIComponent(song_array[song_array.length-1].replace(".mp3",""));
			
			if ($(this).parents("#history").length>0) {
				song_name=$(this).text();
			}

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
				/*$(".jp-playlist ul").sortable({
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
				});*/
			}
			add_to_history(song,song_name,path);
		});
		
	});
	$(".jp-playlist-item-remove").live("click",function(){
		$('#show_playlist').attr("id","hide_playlist").text("hide");
		$('.jp-playlist li').show();
		if($(".jp-playlist li").length === 2) {
			$('#hide_playlist').hide();
		}
	});
	
	$("#hide_playlist").live("click",function(){
		$(".jp-playlist li").hide();
		$(".jp-playlist-current").show();
		$("#jquery_jplayer_2").bind($.jPlayer.event.play, function() {
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
		var search_array=search_text.split(" ");
		$(".song").hide();
		if(search_text!==""){
			for(var i=0;i<search_array.length;i++){
				var toshow = $(".song:contains("+search_array[i]+")");
				toshow.show();
				toshow.addClass("yes"+i);
			}
		} else {
			$(".song").show();
		}
		if(search_text!==""){
			for(var j=0;j<search_array.length;j++){
				$(".song").not(".yes"+j).hide();
				$(".song").removeClass("yes"+j);
			}
		}
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
	});
	$(".jp-next").click(function(){
		if ($("#mode").hasClass("mode0")){
			play_random_song();
		}
	});
	$(".jp-previous").click(function(){
		if ($("#mode").hasClass("mode0")){
			var current=$(".jp-playlist-item").text();
			$("#history .song:contains("+current+")").parent().next().children(".song").click();
		}
	});
});
function activate_keys(){
	$("body").keydown(function(e){
		var current=$(".jp-volume-bar-value").width();
		var new_vol=0;
		switch (e.which) {
			case 82 :
				var songs_count=$(".song").length;
				var number=Math.floor(Math.random()*songs_count)+1;
				$("#song_"+number).click();
				scroll_to_active();
			break;
			case 84 :
				scroll_to_top();
			break;
			case 68 :
				scroll_down();
			break;
			case 70 :
				$(".filter:visible").first().focus();
				return false;
			case 67 :
				scroll_to_active();
			break;
			case 85 :
				scroll_up();
			break;
			case 83 :
				$(".song").show();
			break;
			case 86 :
				random_from_visible();
			break;
			case 72 :
				hide_header();
			break;
			case 73 :
				$("#inputs").toggle();
			break;
			case 80 :
				$(".jp-play:visible,.jp-pause:visible").click();
			break;
			case 78 :
				$(".jp-next").click();
			break;
			case 66 :
				$(".jp-previous").click();
			break;
			case 187 :
				new_vol=(current/295)+0.05;
				$("#jquery_jplayer_2").jPlayer("volume",new_vol);
			break;
			case 189 :
				new_vol=(current/295)-0.05;
				$("#jquery_jplayer_2").jPlayer("volume",new_vol);
			break;
			case 76 :
				$("#mode").click();
			break;
			case 68 :
				$(".jp-playlist-item-remove").first().click();
			break;

		}
	});
}
function scroll_to_active(){
	if($(".blue").length>0){
		var topp=$(".blue").offset().top-100;
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
	var cur=$(window).scrollTop();
	$('body,html').stop().animate({
		scrollTop: cur+500
	}, 800);
	return false;
}
function scroll_up(){
	var cur=$(window).scrollTop();
	$('body,html').stop().animate({
		scrollTop: cur-500
	}, 800);
	return false;
}
function hide_header() {
	$("#header").slideToggle();
}
function add_to_history(song,song_name,path) {
	var history=$.cookie("history");
	var dont_add=0;
	if ( history === null ) {
		history=song_name+"&&"+path;
	} else {
		var history_array=history.split("||");
		for(var i=0;i<history_array.length;i++){
			if ( song === history_array[i] ) {
				dont_add=1;
			}
		}
		history=song_name+"&&"+path+"||"+history;
	}
	if(dont_add===0) {
		$.cookie("history",history);
		$("#history ul").prepend("<li><a href='javascript:;' class='song' file='"+path+"'>"+song_name.replace(".mp3","")+"</a></li>");
	}
}
