jQuery.expr[':'].Contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
	  .indexOf(m[3].toUpperCase()) >= 0;
};
jQuery.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
	  .indexOf(m[3].toUpperCase()) >= 0;
};

function bullseys(song){
	if (!$.cookie("dont")){
		$.post("save_song_played.php",{song:song},function(out){
			//alert(out);
		});
	}
};
$("document").ready(function(){
	$("#filter").keyup(function(){
		var search_text=$(this).val();
		search_array=search_text.split(" ");
		$(".song").hide();
		$(".remove_song,.rename_song").hide();
		if(search_text!=""){
			for(i=0;i<search_array.length;i++){
				toshow = $(".song:contains("+search_array[i]+")");
				toshow.show();
				toshow.addClass("yes"+i);
				if ($.cookie("fadi")){
					toshow.next().show();
					toshow.next().next().show();
				}
			}
		} else {
			$(".song").show();
			if ($.cookie("fadi")){
                       		 toshow.next().show();
                       		toshow.next().next().show();
                         }
		}
		if(search_text!=""){
			for(i=0;i<search_array.length;i++){
				$(".song").not(".yes"+i).hide();
				 if ($.cookie("fadi")){
					$(".song").not(".yes"+i).next().hide();
					$(".song").not(".yes"+i).next().next().hide();
				}
				$(".song").removeClass("yes"+i);
			}
		}
		count = $(".showen_songs").length;
	});
	/////////////////
	activate_keys();
	$("input").focus(function(){
		$("body").unbind("keydown");
	});
	$("input").blur(function(){
		activate_keys();
	});
	////////////////
	playlist_counter=1;
	$("#playlist_mode").toggle(function(){
		$(this).addClass("active_playlist");
		$("#get_playlist").show();
		playlist_counter=1;
	},function(){
		$(this).removeClass("active_playlist");
		$("#get_playlist").hide();
	});
	/////////////////////
	$("#tips_close").toggle(function(){
		$(this).parent().animate({right:"0px"});
	},function(){
		$(this).parent().animate({right:"-300px"});
	});
	////////////////////
	$(".song").click(function(){
		song=$(this).attr("file");
		$(".song").removeClass("blue");
		$(this).addClass("blue");
		alert(song+"llllll")
		if(!$("#playlist_mode").hasClass("active_playlist") || playlist_counter==1){
			console.log(song)
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
				playlist: [{ 
					url: song,
					provider: "audio",
				}]
	
			});
			$(".playlist_order").remove();
			if ($("#playlist_mode").hasClass("active_playlist")){
				$(this).append("<span class='playlist_order'>"+playlist_counter+"</span>");
				playlist_counter++;
			}
			$("#inner_header p").text(song.replace(".mp3",""));
		} else {
			song_object=new Object();
			song_object.url=song;
			$f().addClip(song_object);
			$(this).append("<span class='playlist_order'>"+playlist_counter+"</span>");
			playlist_counter++;
		}
		bullseys(song);
	});
	//////////////////
	if ($.cookie("fadi")){
		$(".song").after("<div class='remove_song' >x</div><div class='rename_song' >r</div>");
	}
	$(".remove_song").live("click",function(){
		this_=$(this);
		if(confirm("really?")){
			$.post("remove_song.php",{song:$(this).prev().attr("file")},function(out){
				if (out !== "" ){
					alert(out);
				}
				this_.remove();
				this_.prev().remove();
			});
		}
	});
	$(".rename_song").live("click",function(){
		this_=$(this);
		old_name=$(this).prev().prev().attr("file");
		new_name=old_name.replace(/_/g," ");
		new_name=prompt("new name :", old_name);
		//alert(new_name);
		$.post("rename_song.php",{old_name:old_name,new_name:new_name},function(out){
			//this_.remove();
			//this_.prev().remove();
			//alert(out);
			if (out !== "" ){
				alert(out);
			}
		});
	});
	////////////////////////
	window_width=$(window).width();
	$("#filter_div").css("left",window_width-300+"px");
	/////////////////
	$("#copy").live("paste",function(){
		setTimeout(function(){
			name=$("#song_name").val();
			$.post("get_file.php",{link:$("#copy").val(),name:name},function(out){
				alert(out);
			})
		},1000);
	});
	$("#random_song").click(function(){
		var songs_count=$(".song").length;
		var number=Math.floor(Math.random()*songs_count)+1;
		$("#song_"+number).click();
		scroll_to_active();
	});
	$("#get_playlist").click(function(){
		songs_list="";
		$(".playlist_order").each(function(){
			songs_list=songs_list+","+$(this).parent().attr("id").replace("song_","");
		});
		songs_list=songs_list.replace(",","");
		var url="http://fadi-webs.com/music?playlist="+songs_list;
		$("#inner_header p").text(url);
	});
	$("#random_playlist_10").click(function(){
		//$("#playlist_mode").click();
		var songs_count=$(".song").length;
		var number=Math.floor(Math.random()*songs_count)+1;
		file=$("#song_"+number).attr("file");
		$("#song_"+number).click();
		$(".playlist_order").remove();
		$("#song_"+number).append("<span class='playlist_order'>1</span>");
		random=1;
		playlist=new Array();
		player.onStart(function() {
			if (random==1){
				for(i=1;i<10;i++){
					object=new Object();
					var songs_count=$(".song").length;
					var number=Math.floor(Math.random()*songs_count)+1;
					file=$("#song_"+number).attr("file");
					object.url=$("#song_"+number).attr("file");
					object.provider="audio";
					object.onStart=function() {
						$("#song_1"+number).css('background-color','#4c8efc');
					};
					playlist.push(object);
					$f().addClip("music/"+$("#song_"+number).attr("file"));
					
					$("#song_"+number).append("<span class='playlist_order'>"+(i+1)+"</span>");	
				}
				$(".song").hide();
				$(".playlist_order").each(function(){
					$(this).parent().show();
				});
			}
			random=0;
			song_name=$f().getClip().completeUrl;
			song_name=song_name.replace("http://fadi-webs.com/music/music/","");
			song_name=song_name.replace(".mp3","");
			$("#inner_header p").text(song_name);
			bullseys(song_name);
		});
		$("#random_song").hide();
	});
	$("#random_playlist_20").click(function(){
		//$("#playlist_mode").click();
		var songs_count=$(".song").length;
		var number=Math.floor(Math.random()*songs_count)+1;
		file=$("#song_"+number).attr("file");
		$("#song_"+number).click();
		$(".playlist_order").remove();
		$("#song_"+number).append("<span class='playlist_order'>1</span>");
		random=1;
		player.onStart(function() {
			if (random==1){
				for(i=1;i<20;i++){
					var songs_count=$(".song").length;
					var number=Math.floor(Math.random()*songs_count)+1;
					file=$("#song_"+number).attr("file");
					$f().addClip("music/"+$("#song_"+number).attr("file"));
					$("#song_"+number).append("<span class='playlist_order'>"+(i+1)+"</span>");	
				}
				$(".song").hide();
				$(".playlist_order").each(function(){
					$(this).parent().show();
				});
			}
			random=0;
			song_name=$f().getClip().completeUrl;
			song_name=song_name.replace("http://fadi-webs.com/music/music/","");
			song_name=song_name.replace(".mp3","");
			$("#inner_header p").text(song_name);
			bullseys(song_name);
		});
		$("#random_song").hide();
	});
	$('.scroll_to_top').click(function () {
		scroll_to_top();
	});
	 $('.scroll_down').click(function () {
		scroll_down();
	});
	scroll_to_active();
});
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
function activate_keys(){
	$("body").keydown(function(e){
		if(e.which==82){
			$("#random_song").click();
			register_key("r");
		}
		if(e.which==84){
			scroll_to_top();
			register_key("t");
		}
		if(e.which==68){
			scroll_down();
			register_key("d");
		}
		if(e.which==70){
			$("#filter").focus();
			register_key("f");
			return false;
		}
		if(e.which==67){
			scroll_to_active();
			register_key("c");
		}
		if(e.which==85){
			scroll_up();
			register_key("u");
		}
		if(e.which==83){
			$(".song").show();
			register_key("s");
		}
		if(e.which==86){
			random_from_visible();
			register_key("v");
		}		
	});
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
function register_key(key){
	if (!$.cookie("dont")){
		$.post("register_key.php",{key:key},function(){

		});
	}
}