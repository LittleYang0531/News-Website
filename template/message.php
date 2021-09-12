<h2 style="width:100%;"><center><?php echo $language["message-intitle"];?></center></h2>
<div id="hehe">
	<div class="main-left">
		<h4 style="padding-left:30px;"><?php echo $language["message-center-title"];?></h4>
		<p id="message-comment-unshow" onclick="show('comment')" style="display:none">●&nbsp;&nbsp;<?php echo $language["message-comment"];?></p>
		<p id="message-comment-show" onclick="show('comment')" style="color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["message-comment"];?></p>
		<p id="message-like-unshow" onclick="show('like')">●&nbsp;&nbsp;<?php echo $language["message-like"];?></p>
		<p id="message-like-show" onclick="show('like')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["message-like"];?></p>
		<p id="message-system-unshow" onclick="show('system')">●&nbsp;&nbsp;<?php echo $language["message-system"];?></p>
		<p id="message-system-show" onclick="show('system')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["message-system"];?></p>
		<!-- <p id="message-friends-unshow" onclick="show('friends')">●&nbsp;&nbsp;<?php echo $language["message-friends"];?></p> -->
		<!-- <p id="message-friends-show" onclick="show('friends')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["message-friends"];?></p> -->
		<!-- <br> -->
		<!-- <p id="message-settings-unshow" onclick="show('settings')">●&nbsp;&nbsp;<?php echo $language["message-setting"];?></p> -->
		<!-- <p id="message-settings-show" onclick="show('settings')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["message-setting"];?></p> -->
	</div>
	<div class="main-right">
		<div class="comment" id="comment">
			<h3><?php echo $language["message-comment-title"];?></h3>
			<?php
				$json=strip_tags(GetFromHTML($config["api-server-address"]."/message/comment.php?l=1&r=".$config["message-comment-number"]));
				$obj=json_decode($json,true);
				for ($i=0;$i<count($obj["data"]["replies"]);$i++)
				{
					echo "<div class='message-comment-element' id='message-comment-element$i'>
						<div id='message-comment-element-header$i'>
							<img class='message-comment-header' id='message-comment-header$i' src='".$obj["data"]["replies"][$i]["author"]["header"]."'/>
						</div>
						<div class='message-comment-element-info' id='message-comment-element-info$i'>
							<p><strong><a href='".$config["server"]."/".$config["profile-path"]."&uid=".$obj["data"]["replies"][$i]["author"]["uid"]."'>".$obj["data"]["replies"][$i]["author"]["name"]."</a></strong>&nbsp;&nbsp;".($obj["data"]["replies"][$i]["root"]==0?$language["message-comment-send"]:$language["message-comment-reply"])."</p>
							<p>".str_replace("\n","<br>",$obj["data"]["replies"][$i]["content"])."</p>
							<p style='margin-top:3px;'>".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["time"])."&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["main-path"]."&type=article&column=".$obj["data"]["replies"][$i]["column"]."&id=".$obj["data"]["replies"][$i]["id"]."'>".$language["message-comment-href"]."</a></p>
						</div>
					</div>";
				}
			?>
		</div>
		<script>
			var data_num_comment=<?php echo $config["message-comment-number"];?>;
			var data_each_time_comment=<?php echo $config["message-comment-number"];?>;
			var comment_element=document.getElementById("comment");
			var comment_end=0;
			function TimeToString(datetime)
			{
			    var date=new Date(datetime*1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
			    var year=date.getFullYear(),
			    month=("0"+(date.getMonth() + 1)).slice(-2),
			    sdate=("0"+date.getDate()).slice(-2),
			    hour=("0"+date.getHours()).slice(-2),
			    minute=("0"+date.getMinutes()).slice(-2),
			    second=("0"+date.getSeconds()).slice(-2);
			    var result=year+"-"+month+"-"+sdate+" "+hour+":"+minute+":"+second;
			    return result;
			}
			function update_comment()
			{
				// alert(data_num_comment+" "+data_each_time_comment);
				$.ajax({
					type:"GET",
					url:<?php echo "\"".$config["api-server-address"]."/message/comment.php?l=\"+(data_num_comment+1)+\"&r=\"+(data_num_comment+data_each_time_comment)";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							for (i=0;i<obj["data"]["replies"].length;i++)
							{
								comment_element.innerHTML+="<div class='message-comment-element' id='message-comment-element"+i+"'>"+
														   "    <div id='message-comment-element-header"+i+"'>"+
														   "        <img class='message-comment-header' id='message-comment-header"+i+"' src='"+obj["data"]["replies"][i]["author"]["header"]+"'/>"+
														   "	</div>"+
														   "	<div class='message-comment-element-info' id='message-comment-element-info"+i+"'>"+
														   "		<p><strong><a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["profile-path"]."\"";?>+"&uid="+obj["data"]["replies"][i]["author"]["uid"]+"'>"+obj["data"]["replies"][i]["author"]["name"]+"</a></strong>&nbsp;&nbsp;"+(obj["data"]["replies"][i]["root"]==0?<?php echo "\"".$language["message-comment-send"]."\"";?>:<?php echo "\"".$language["message-comment-reply"]."\"";?>)+"</p>"+
														   "		<p>"+obj["data"]["replies"][i]["content"].replace("\n","<br>")+"</p>"+
														   "		<p style='margin-top:3px;'>"+TimeToString(obj["data"]["replies"][i]["time"])+"&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["main-path"]."\"";?>+"&type=article&column="+obj["data"]["replies"][i]["column"]+"&id="+obj["data"]["replies"][i]["id"]+"'>"+<?php echo "\"".$language["message-comment-href"]."\"";?>+"</a></p>"+
														   "	</div>"+
														   "</div>";
							}
							if (obj["data"]["replies"].length<data_each_time_comment)
							{
								if (!comment_end)
								{
									comment_element.innerHTML+=<?php echo "\"<br><div><center>".$language["message-comment-ending"]."</center></div>\""?>;
									data_num_comment+=data_each_time_comment;
								}
								comment_end=1;
								return;
							}
							data_num_comment+=data_each_time_comment;
						}
						else
						{
							layer.msg(<?php echo "\"".$language["message-comment-request-failed"]."\""?>)
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["message-comment-request-error"]."\""?>);
					    console.log(jqXHR.responseText);
					    console.log(jqXHR.status);
					    console.log(jqXHR.readyState);
					    console.log(jqXHR.statusText);
					    console.log(textStatus);
					    console.log(errorThrown);
					}
				});
			}
			var div=document.getElementById('comment');
			if (!((div.scrollHeight>div.clientHeight)||(div.offsetHeight>div.clientHeight))) update_comment();
			$('#comment').scroll(function(event)
			{
				var t=event.currentTarget.scrollTop;
				var s=event.currentTarget.scrollHeight;
				var c=event.currentTarget.clientHeight;
				if(t+c>=s) update_comment();
			})
		</script>
		<div class="like" id="like" style="display:none">
			<h3><?php echo $language["message-like-title"];?></h3>
			<?php
				$json=strip_tags(GetFromHTML($config["api-server-address"]."/message/like.php?l=1&r=".$config["message-like-number"]));
				$obj=json_decode($json,true);
				for ($i=0;$i<count($obj["data"]["replies"]);$i++)
				{
					echo "<div class='message-like-element' id='message-like-element$i'>
						<div id='message-like-element-header$i'>
							<img class='message-like-header' id='message-like-header$i' src='".$obj["data"]["replies"][$i]["user"]["header"]."'/>
						</div>
						<div class='message-like-element-info' id='message-like-element-info$i'>
							<p><strong><a href='".$config["server"]."/".$config["profile-path"]."&uid=".$obj["data"]["replies"][$i]["user"]["uid"]."'>".$obj["data"]["replies"][$i]["user"]["name"]."</a></strong>&nbsp;&nbsp;".
							($obj["data"]["replies"][$i]["type"]=="article"?$language["message-like-article"]:$language["message-like-comment"])."</p>
							<p class='message-like-element-content'>".($obj["data"]["replies"][$i]["type"]=="article"?$language["message-like-article-title"]." \"".$obj["data"]["replies"][$i]["article"]["title"]."\"":str_replace("\n","<br>",$obj["data"]["replies"][$i]["content"]))."</p>
							<p style='margin-top:3px;'>".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["time"])."&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["main-path"]."&type=article&column=".$obj["data"]["replies"][$i]["column"]."&id=".$obj["data"]["replies"][$i]["id"]."'>".$language["message-like-href"]."</a></p>
						</div>
					</div>";
				}
			?>
		</div>
		<script>
			var data_num_like=<?php echo $config["message-like-number"];?>;
			var data_each_time_like=<?php echo $config["message-like-number"];?>;
			var like_element=document.getElementById("like");
			var like_end=0;
			console.log(like_element);
			function update_like()
			{
				$.ajax({
					type:"GET",
					url:<?php echo "\"".$config["api-server-address"]."/message/like.php?l=\"+(data_num_like+1)+\"&r=\"+(data_num_like+data_each_time_like)";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							for (i=0;i<obj["data"]["replies"].length;i++)
							{
								like_element.innerHTML+="<div class='message-like-element' id='message-like-element"+i+"'>"+
														"	<div id='message-like-element-header"+i+"'>"+
														"		<img class='message-like-header' id='message-like-header"+i+"' src='"+obj["data"]["replies"][i]["user"]["header"]+"'/>"+
														"	</div>"+
														"	<div class='message-like-element-info' id='message-like-element-info"+i+"'>"+
														"		<p><strong><a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["profile-path"]."\"";?>+"&uid="+obj["data"]["replies"][i]["user"]["uid"]+"'>"+obj["data"]["replies"][i]["user"]["name"]+"</a></strong>&nbsp;&nbsp;"+
														(obj["data"]["replies"][i]["type"]=="article"?<?php echo "\"".$language["message-like-article"]."\"";?>:<?php echo "\"".$language["message-like-comment"]."\"";?>)+"</p>"+
														"		<p class='message-like-element-content'>"+(obj["data"]["replies"][i]["type"]=="article"?<?php echo "\"".$language["message-like-article-title"]."\"";?>+" \""+obj["data"]["replies"][i]["article"]["title"]+"\"":obj["data"]["replies"][i]["content"].replace("\n","<br>"))+"</p>"+
														"		<p style='margin-top:3px;'>"+TimeToString(obj["data"]["replies"][i]["time"])+"&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."/".$config["main-path"]."\"";?>+"&type=article&column="+obj["data"]["replies"][i]["column"]+"&id="+obj["data"]["replies"][i]["id"]+"'>"+<?php echo "\"".$language["message-like-href"]."\"";?>+"</a></p>"+
														"	</div>"+
														"</div>";
							}
							if (obj["data"]["replies"].length<data_each_time_like)
							{
								if (!like_end)
								{
									like_element.innerHTML+=<?php echo "\"<br><div><center>".$language["message-like-ending"]."</center></div>\""?>;
									data_num_like+=data_each_time_like;
								}
								like_end=1;
								return;
							}
							data_num_like+=data_each_time_like;
						}
						else
						{
							layer.msg(<?php echo "\"".$language["message-like-request-failed"]."\""?>)
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["message-like-request-error"]."\""?>);
					    console.log(jqXHR.responseText);
					    console.log(jqXHR.status);
					    console.log(jqXHR.readyState);
					    console.log(jqXHR.statusText);
					    console.log(textStatus);
					    console.log(errorThrown);
					}
				});
			}
			var div1=document.getElementById('like');
			if (!((div1.scrollHeight>div1.clientHeight)||(div1.offsetHeight>div1.clientHeight))) update_like();
			$('#like').scroll(function(event)
			{
				var t=event.currentTarget.scrollTop;
				var s=event.currentTarget.scrollHeight;
				var c=event.currentTarget.clientHeight;
				if(t+c>=s) update_like();
			})
		</script>
		<div class="system" id="system" style="display:none">
			<h3><?php echo $language["message-system-title"];?></h3>
			<?php
				$json=strip_tags(GetFromHTML($config["api-server-address"]."/message/system.php?l=1&r=".$config["message-system-number"]));
				$obj=json_decode($json,true);
				for ($i=0;$i<count($obj["data"]["replies"]);$i++)
				{
					echo "<div class='message-system-element' id='message-system-element$i'>
						<p style='font-size:15px'><strong style='color:black;'>".$obj["data"]["replies"][$i]["title"]."</strong>&nbsp;&nbsp;".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["time"])."</p>
						<p style='margin-left:10px;margin-top:5px;font-size:15px;'>".$obj["data"]["replies"][$i]["content"]."</p>
					</div>";
				}
			?>
		</div>
		<script>
			var data_num_system=<?php echo $config["message-system-number"];?>;
			var data_each_time_system=<?php echo $config["message-system-number"];?>;
			var system_element=document.getElementById("system");
			var system_end=0;
			console.log(system_element);
			function update_system()
			{
				$.ajax({
					type:"GET",
					url:<?php echo "\"".$config["api-server-address"]."/message/system.php?l=\"+(data_num_system+1)+\"&r=\"+(data_num_system+data_each_time_system)";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							for (i=0;i<obj["data"]["replies"].length;i++)
							{
								system_element.innerHTML+="<div class='message-system-element' id='message-system-element"+i+"'>"+
														  "    <p><strong>"+obj["data"]["replies"][i]["title"]+"</strong>&nbsp;&nbsp;&nbsp;&nbsp;"+TimeToString(obj["data"]["replies"][i]["time"])+"</p>"+
														  "    <p>"+obj["data"]["replies"][i]["content"]+"</p>"+
														  "</div>";
							}
							if (obj["data"]["replies"].length<data_each_time_system)
							{
								if (!system_end)
								{
									system_element.innerHTML+=<?php echo "\"<br><div><center>".$language["message-system-ending"]."</center></div>\""?>;
									data_num_system+=data_each_time_system;
								}
								system_end=1;
								return;
							}
							data_num_system+=data_each_time_system;
						}
						else
						{
							layer.msg(<?php echo "\"".$language["message-system-request-failed"]."\""?>)
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["message-system-request-error"]."\""?>);
					    console.log(jqXHR.responseText);
					    console.log(jqXHR.status);
					    console.log(jqXHR.readyState);
					    console.log(jqXHR.statusText);
					    console.log(textStatus);
					    console.log(errorThrown);
					}
				});
			}
			var div2=document.getElementById('system');
			if (!((div2.scrollHeight>div2.clientHeight)||(div2.offsetHeight>div2.clientHeight))) update_system();
			$('#system').scroll(function(event)
			{
				var t=event.currentTarget.scrollTop;
				var s=event.currentTarget.scrollHeight;
				var c=event.currentTarget.clientHeight;
				if(t+c>=s) update_system();
			})
		</script>
<!-- 		<div class="friends" id="friends" style="display:none">
			
		</div> -->
<!-- 		<div class="settings" id="settings" style="display:none"> 
			
		</div> -->
		<script>
			function strip_tags(html) 
			{
				var div=document.createElement("div");
				div.innerHTML=html;
				return (div.textContent||div.innerText||"");
			}
			function show(id)
			{
				var comment=document.getElementById("comment");
				var like=document.getElementById("like");
				var system=document.getElementById("system");
				// var friends=document.getElementById("friends");
				// var settings=document.getElementById("settings");
				var comment_show=document.getElementById("message-comment-show");
				var like_show=document.getElementById("message-like-show");
				var system_show=document.getElementById("message-system-show");
				// var friends_show=document.getElementById("message-friends-show");
				// var settings_show=document.getElementById("message-settings-show");
				var comment_unshow=document.getElementById("message-comment-unshow");
				var like_unshow=document.getElementById("message-like-unshow");
				var system_unshow=document.getElementById("message-system-unshow");
				// var friends_unshow=document.getElementById("message-friends-unshow");
				// var settings_unshow=document.getElementById("message-settings-unshow");
				var goal=document.getElementById(id);
				var goal_show=document.getElementById("message-"+id+"-show");
				var goal_unshow=document.getElementById("message-"+id+"-unshow");
				comment.style.display=like.style.display=system.style.display="none";
				comment_show.style.display=like_show.style.display=system_show.style.display="none";
				comment_unshow.style.display=like_unshow.style.display=system_unshow.style.display="block";
				goal.style.display="block";goal_show.style.display="block";goal_unshow.style.display="none";
			}
		</script>
	</div>
</div>