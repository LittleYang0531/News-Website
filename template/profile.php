<?php 
	$user=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/user/info.php?uid=".$_GET["uid"])),true);
	$maxarticle=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/search/maxarticle.php?uid=".$_GET["uid"])),true)["max"];
	$article=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/search/article.php?uid=".$_GET["uid"]."&l=1&r=$maxarticle")),true);
	$watch=0;$like=0;
	for ($i=0;$i<$maxarticle;$i++){$watch+=$article["data"]["replies"][$i]["view"];$like+=$article["data"]["replies"][$i]["like"];}
	$login=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/check/login.php")),true)["data"]["isLogin"];
	$follow=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/check/follow.php?uid=".$_GET["uid"])),true)["data"]["follow"];
?>
<?php
	if (!$login||$_COOKIE["DedeUserId"]!=$_GET["uid"])
	{
		echo "
		<style>
			.article{height:608px}
			.follow{height:608px}
			.fans{height:608px}
		</style>
		";
	}
?>
<div class="profile-header">
	<div style="display:flex;display:-webkit-flex">
		<img class="user-header" src="<?php echo $user["data"]["header"]?>"/>
		<div class="user-info">
			<h3><?php echo $user["data"]["name"];?></h3>
			<p><?php echo ($user["data"]["sign"]=="empty"?$language["profile-empty-sign"]:$user["data"]["sign"])?></p>
		</div>	
	</div>
	<div>
		<table style="text-align:center;margin-top:45px;font-size:15px;">
			<tr>
				<td style="padding-right:10px;"><?php echo $language["profile-following"]?></td>
				<td style="padding-right:10px;"><?php echo $language["profile-fans"]?></td>
				<td style="padding-right:10px;"><?php echo $language["profile-article-number"];?></td>
				<td style="padding-right:10px;"><?php echo $language["profile-article-watch"];?></td>
				<td style="padding-right:50px;"><?php echo $language["profile-article-like"];?></td>
			</tr>
			<tr>
				<td style="padding-right:10px;"><?php echo $user["data"]["follow"]?></td>
				<td style="padding-right:10px;" id="profile-fans"><?php echo $user["data"]["fans"]?></td>
				<td style="padding-right:10px;"><?php echo $maxarticle;?></td>
				<td style="padding-right:10px;"><?php echo $watch;?></td>
				<td style="padding-right:50px;"><?php echo $like;?></td>
			</tr>
		</table>
		<div style="text-align:center;position:relative;right:20px;margin-top:10px;display:<?php echo ($_COOKIE["DedeUserId"]!=$_GET["uid"]?"block":"none")?>">
			<button id="profile-follow-button" style="display:<?php echo ($follow?"none":"inline-block");?>" class="profile-button1" onclick=follow(<?php echo $_GET["uid"]?>)><?php echo $language["profile-follow-button"]?></button>
			<button id="profile-unfollow-button" style="display:<?php echo ($follow?"inline-block":"none");?>" class="profile-button2" onclick=follow(<?php echo $_GET["uid"]?>)><?php echo $language["profile-unfollow-button"]?></button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button id="profile-report-button" class="profile-button2" onclick=report(<?php echo $_GET["uid"]?>)><?php echo $language["profile-report-button"]?></button>
			<script>
				function follow(uid) 
				{
					<?php
						if (!$login) echo "layer.msg('".$language["profile-not-login"]."');return;"
					?>
					$.ajax({
						type:"POST",
						url:<?php echo "\"".$config["api-server-address"]."/user/follow.php"."\"";?>,
						data:{uid:<?php echo $_GET["uid"]?>},
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								layer.msg(<?php echo "\"".$language["profile-follow-success"]."\""?>);
								var state=obj["data"]["state"];
								document.getElementById("profile-follow-button").style.display="none";
								document.getElementById("profile-unfollow-button").style.display="none";
								if (state) document.getElementById("profile-unfollow-button").style.display="inline-block";
								else document.getElementById("profile-follow-button").style.display="inline-block";
							}
							else 
							{
								layer.msg(obj["message"]);
							}
						},
						error:function(jqXHR,textStatus,errorThrown) 
						{
							layer.alert(<?php echo "\"".$language["profile-follow-error"]."\""?>)
						    console.log(jqXHR.responseText);
						    console.log(jqXHR.status);
						    console.log(jqXHR.readyState);
						    console.log(jqXHR.statusText);
						    console.log(textStatus);
						    console.log(errorThrown);
						}
					});
					$.ajax({
						type:"GET",
						url:<?php echo "\"".$config["api-server-address"]."/user/info.php?uid=".$_GET["uid"]."\"";?>,
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								document.getElementById("profile-fans").innerHTML=obj["data"]["fans"]
							}
							else layer.msg(obj["message"]);
						}
					});
				}
				function report() 
				{
					<?php
						if (!$login) echo "layer.msg('".$language["profile-not-login"]."');return;";
						else echo "window.location.href='".$config["server"]."/".$config["complaint-path"]."&uid=1'";
					?>
				}
			</script>
		</div>
	</div>
</div>
<hr>
<div id="hehe">
	<div class="main-left">
		<h4 style="padding-left:30px;"><?php echo $language["profile-user-title"];?></h4>
		<?php
			if ($config["enable-article"])
				echo '
					<p id="profile-article-unshow" onclick="show(\'article\')" style="display:none;">●&nbsp;&nbsp;'.$language["profile-article"].'</p>
					<p id="profile-article-show" onclick="show(\'article\')" style="color:rgb(47,174,227);">●&nbsp;&nbsp;'.$language["profile-article"].'</p>
				';
		?>
		<p id="profile-follow-unshow" onclick="show('follow')">●&nbsp;&nbsp;<?php echo $language["profile-follow"]?></p>
		<p id="profile-follow-show" onclick="show('follow')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["profile-follow"]?></p>
		<p id="profile-fans-unshow" onclick="show('fans')">●&nbsp;&nbsp;<?php echo $language["profile-fans"]?></p>
		<p id="profile-fans-show" onclick="show('fans')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["profile-fans"]?></p>
	</div>
	<div class="main-right">
		<div class="article" id="article" style="display:block">
			<h3><?php echo $language["profile-article-title"];?></h3>
			<?php
				$json=strip_tags(GetFromHTML($config["api-server-address"]."/search/article.php?l=1&r=".$config["profile-article-number"]."&uid=".$_GET["uid"]."&sort=time"));
				$obj=json_decode($json,true);
				for ($i=0;$i<count($obj["data"]["replies"]);$i++)
				{
					echo "<div class='profile-article-element' id='profile-article-element$i'>
						<div class='profile-article-element-left' id='profile-article-element-left$i'>
							<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["main-path"]."&type=article&column=".$obj["data"]["replies"][$i]["columnid"]."&id=".$obj["data"]["replies"][$i]["id"]."'>".$obj["data"]["replies"][$i]["name"]."</a></h4>
							<p>".$language["profile-article-time"].":&nbsp;".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["time"])."<p>
							<div class='profile-article-element-left-toolbar' id='profile-article-element-left-toolbar$i'>
								".$language["profile-article-data"].":&nbsp;
								<img class='img1' src='".$config["photo-profile-watch-path"]."'/> ".$obj["data"]["replies"][$i]["view"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<img class='img1' src='".$config["photo-profile-comment-path"]."'/> ".$obj["data"]["replies"][$i]["comment"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<img class='img2' src='".$config["photo-profile-star-path"]."'/> ".$obj["data"]["replies"][$i]["star"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<img class='img2' src='".$config["photo-profile-like-path"]."'/> ".$obj["data"]["replies"][$i]["like"]."
							</div>
						</div>
					</div>
					<hr>";
				}
			?>
		</div>
		<script>
			function strip_tags(html) 
			{
				var div=document.createElement("div");
				div.innerHTML=html;
				return (div.textContent||div.innerText||"");
			}
			var data_num_article=<?php echo $config["profile-article-number"];?>;
			var data_each_time_article=<?php echo $config["profile-article-number"];?>;
			var article_element=document.getElementById("article");
			var article_end=0;
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
			function update_article()
			{
				$.ajax({
					type:"GET",
					url:<?php echo "\"".$config["api-server-address"]."/search/article.php?l=\"+(data_num_article+1)+\"&r=\"+(data_num_article+data_each_time_article)+\"&uid=".$_GET["uid"]."&sort=time\"";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							for (i=0;i<obj["data"]["replies"].length;i++)
							{
								article_element.innerHTML+="<div class='profile-article-element' id='profile-article-element"+i+"'>"+
															"	<div class='profile-article-element-left' id='profile-article-element-left"+i+"'>"+
															"		<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["main-path"]."\"";?>+"&type=article&column="+obj["data"]["replies"][i]["columnid"]+"&id="+obj["data"]["replies"][i]["id"]+"'>"+obj["data"]["replies"][i]["name"]+"</a></h4>"+
															"		<p>"+<?php echo "\"".$language["profile-article-time"]."\"";?>+":&nbsp;"+TimeToString(obj["data"]["replies"][i]["time"])+"<p>"+
															"		<div class='profile-article-element-left-toolbar' id='profile-article-element-left-toolbar"+i+"'>"+
															"			"+<?php echo "\"".$language["profile-article-data"]."\"";?>+":&nbsp;"+
															"			<img class='img1' src='"+<?php echo "\"".$config["photo-profile-watch-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["view"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img1' src='"+<?php echo "\"".$config["photo-profile-comment-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["comment"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img2' src='"+<?php echo "\"".$config["photo-profile-star-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["star"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img2' src='"+<?php echo "\"".$config["photo-profile-like-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["like"]+
															"		</div>"+
															"	</div>"+
															"</div>"+
															"<hr>";
							}
							if (obj["data"]["replies"].length<data_each_time_article)
							{
								if (!article_end)
								{
									article_element.innerHTML+=<?php echo "\"<br><div><center>".$language["profile-article-ending"]."</center></div>\""?>;
									data_num_article+=data_each_time_article;
								}
								article_end=1;
								return;
							}
							data_num_article+=data_each_time_article;
						}
						else
						{
							layer.msg(<?php echo "\"".$language["profile-article-request-failed"]."\""?>)
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["profile-article-request-error"]."\""?>);
					    console.log(jqXHR.responseText);
					    console.log(jqXHR.status);
					    console.log(jqXHR.readyState);
					    console.log(jqXHR.statusText);
					    console.log(textStatus);
					    console.log(errorThrown);
					}
				});
			}
			var div=document.getElementById('article');
			if (!((div.scrollHeight>div.clientHeight)||(div.offsetHeight>div.clientHeight))) update_article();
			$('#article').scroll(function(event)
			{
				var t=event.currentTarget.scrollTop;
				var s=event.currentTarget.scrollHeight;
				var c=event.currentTarget.clientHeight;
				// alert(t+" "+s+" "+c);
				if(t+c>=s) update_article();
			});
		</script>
		<div class="follow" id="follow" style="display:none">
			<h3><?php echo $language["profile-follow-title"];?></h3>
			<?php
				$json=strip_tags(GetFromHTML($config["api-server-address"]."/user/relation.php?l=1&r=".$config["profile-follow-number"]."&uid=".$_GET["uid"]."&type=follow"));
				$obj=json_decode($json,true);
				for ($i=0;$i<count($obj["data"]["replies"]);$i++)
				{
					echo "<div class='profile-follow-element' id='profile-follow-element$i'>
						<img class='profile-follow-header' src='".$obj["data"]["replies"][$i]["user"]["header"]."'></img>
						<div class='profile-follow-element-left' id='profile-follow-element-left$i'>
							<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$obj["data"]["replies"][$i]["user"]["uid"]."'>".$obj["data"]["replies"][$i]["user"]["name"]."</a></h4>
							<p>".$language["profile-follow-sign"].":&nbsp;".($obj["data"]["replies"][$i]["user"]["sign"]=="empty"?$language["profile-empty-sign"]:$obj["data"]["replies"][$i]["user"]["sign"])."</p>
							<p>".$language["profile-follow-time"].":&nbsp;".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["time"])."<p>
						</div>
					</div>
					<hr>";
				}
			?>
		</div>
		<script>
			function strip_tags(html) 
			{
				var div=document.createElement("div");
				div.innerHTML=html;
				return (div.textContent||div.innerText||"");
			}
			var data_num_follow=<?php echo $config["profile-follow-number"];?>;
			var data_each_time_follow=<?php echo $config["profile-follow-number"];?>;
			var follow_element=document.getElementById("follow");
			var follow_end=0;
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
			function update_follow()
			{
				$.ajax({
					type:"GET",
					url:<?php echo "\"".$config["api-server-address"]."/user/relation.php?l=\"+(data_num_follow+1)+\"&r=\"+(data_num_follow+data_each_time_follow)+\"&uid=".$_GET["uid"]."&type=follow\"";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							for (i=0;i<obj["data"]["replies"].length;i++)
							{
								follow_element.innerHTML+="<div class='profile-follow-element' id='profile-follow-element"+i+"'>"+
															"	<img class='profile-follow-header' src='"+obj["data"]["replies"][i]["user"]["header"]+"'></img>"+
															"	<div class='profile-follow-element-left' id='profile-follow-element-left"+i+"'>"+
															"		<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["profile-path"]."\"";?>+"&uid="+obj["data"]["replies"][i]["user"]["uid"]+"'>"+obj["data"]["replies"][i]["user"]["name"]+"</a></h4>"+
															"		<p>"+<?php echo "\"".$language["profile-follow-sign"]."\"";?>+":&nbsp;"+(obj["data"]["replies"][i]["user"]["sign"]=="empty"?<?php echo "\"".$language["profile-empty-sign"]."\""?>:obj["data"]["replies"][i]["user"]["sign"])+"</p>"+
															"		<p>"+<?php echo "\"".$language["profile-follow-time"]."\"";?>+":&nbsp;"+TimeToString(obj["data"]["replies"][i]["time"])+"<p>"+
															"	</div>"+
															"</div>"+
															"<hr>";
							}
							if (obj["data"]["replies"].length<data_each_time_follow)
							{
								if (!follow_end)
								{
									follow_element.innerHTML+=<?php echo "\"<br><div><center>".$language["profile-follow-ending"]."</center></div>\""?>;
									data_num_follow+=data_each_time_follow;
								}
								follow_end=1;
								return;
							}
							data_num_follow+=data_each_time_follow;
						}
						else
						{
							layer.msg(<?php echo "\"".$language["profile-follow-request-failed"]."\""?>)
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["profile-follow-request-error"]."\""?>);
					    console.log(jqXHR.responseText);
					    console.log(jqXHR.status);
					    console.log(jqXHR.readyState);
					    console.log(jqXHR.statusText);
					    console.log(textStatus);
					    console.log(errorThrown);
					}
				});
			}
			var div=document.getElementById('follow');
			if (!((div.scrollHeight>div.clientHeight)||(div.offsetHeight>div.clientHeight))) update_follow();
			$('#follow').scroll(function(event)
			{
				var t=event.currentTarget.scrollTop;
				var s=event.currentTarget.scrollHeight;
				var c=event.currentTarget.clientHeight;
				// alert(t+" "+s+" "+c);
				if(t+c>=s) update_follow();
			});
		</script>
		<div class="fans" id="fans" style="display:none">
				<h3><?php echo $language["profile-fans-title"];?></h3>
				<?php
					$json=strip_tags(GetFromHTML($config["api-server-address"]."/user/relation.php?l=1&r=".$config["profile-fans-number"]."&uid=".$_GET["uid"]."&type=fans"));
					$obj=json_decode($json,true);
					for ($i=0;$i<count($obj["data"]["replies"]);$i++)
					{
						echo "<div class='profile-fans-element' id='profile-fans-element$i'>
							<img class='profile-fans-header' src='".$obj["data"]["replies"][$i]["user"]["header"]."'></img>
							<div class='profile-fans-element-left' id='profile-fans-element-left$i'>
								<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$obj["data"]["replies"][$i]["user"]["uid"]."'>".$obj["data"]["replies"][$i]["user"]["name"]."</a></h4>
								<p>".$language["profile-fans-sign"].":&nbsp;".($obj["data"]["replies"][$i]["user"]["sign"]=="empty"?$language["profile-empty-sign"]:$obj["data"]["replies"][$i]["user"]["sign"])."</p>
								<p>".$language["profile-fans-time"].":&nbsp;".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["time"])."<p>
							</div>
						</div>
						<hr>";
					}
				?>
			</div>
			<script>
				function strip_tags(html) 
				{
					var div=document.createElement("div");
					div.innerHTML=html;
					return (div.textContent||div.innerText||"");
				}
				var data_num_fans=<?php echo $config["profile-fans-number"];?>;
				var data_each_time_fans=<?php echo $config["profile-fans-number"];?>;
				var fans_element=document.getElementById("fans");
				var fans_end=0;
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
				function update_fans()
				{
					$.ajax({
						type:"GET",
						url:<?php echo "\"".$config["api-server-address"]."/user/relation.php?l=\"+(data_num_fans+1)+\"&r=\"+(data_num_fans+data_each_time_fans)+\"&uid=".$_GET["uid"]."&type=fans\"";?>,
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								for (i=0;i<obj["data"]["replies"].length;i++)
								{
									fans_element.innerHTML+="<div class='profile-fans-element' id='profile-fans-element"+i+"'>"+
															"	<img class='profile-fans-header' src='"+obj["data"]["replies"][i]["user"]["header"]+"'></img>"+
															"	<div class='profile-fans-element-left' id='profile-fans-element-left"+i+"'>"+
															"		<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["profile-path"]."\"";?>+"&uid="+obj["data"]["replies"][i]["user"]["uid"]+"'>"+obj["data"]["replies"][i]["user"]["name"]+"</a></h4>"+
															"		<p>"+<?php echo "\"".$language["profile-fans-sign"]."\"";?>+":&nbsp;"+(obj["data"]["replies"][i]["user"]["sign"]=="empty"?<?php echo "\"".$language["profile-empty-sign"]."\""?>:obj["data"]["replies"][i]["user"]["sign"])+"</p>"+
															"		<p>"+<?php echo "\"".$language["profile-fans-time"]."\"";?>+":&nbsp;"+TimeToString(obj["data"]["replies"][i]["time"])+"<p>"+
															"	</div>"+
															"</div>"+
															"<hr>";
								}
								if (obj["data"]["replies"].length<data_each_time_fans)
								{
									if (!fans_end)
									{
										fans_element.innerHTML+=<?php echo "\"<br><div><center>".$language["profile-fans-ending"]."</center></div>\""?>;
										data_num_fans+=data_each_time_fans;
									}
									fans_end=1;
									return;
								}
								data_num_fans+=data_each_time_fans;
							}
							else
							{
								layer.msg(<?php echo "\"".$language["profile-fans-request-failed"]."\""?>)
								console.log(obj["message"]);
							}
						},
						error:function(jqXHR,textStatus,errorThrown) 
						{
							layer.alert(<?php echo "\"".$language["profile-fans-request-error"]."\""?>);
						    console.log(jqXHR.responseText);
						    console.log(jqXHR.status);
						    console.log(jqXHR.readyState);
						    console.log(jqXHR.statusText);
						    console.log(textStatus);
						    console.log(errorThrown);
						}
					});
				}
				var div=document.getElementById('fans');
				if (!((div.scrollHeight>div.clientHeight)||(div.offsetHeight>div.clientHeight))) update_fans();
				$('#fans').scroll(function(event)
				{
					var t=event.currentTarget.scrollTop;
					var s=event.currentTarget.scrollHeight;
					var c=event.currentTarget.clientHeight;
					// alert(t+" "+s+" "+c);
					if(t+c>=s) update_fans();
				});
			</script>
	</div>
	<script>
		function show(id)
		{
			<?php
				if ($config["enable-article"]) 
					echo '
						var article=document.getElementById("article");
						var article_show=document.getElementById("profile-article-show");
						var article_unshow=document.getElementById("profile-article-unshow");
						article.style.display="none";
						article_show.style.display="none";
						article_unshow.style.display="block";
					';
			?>
			var follow=document.getElementById("follow");
			var follow_show=document.getElementById("profile-follow-show");
			var follow_unshow=document.getElementById("profile-follow-unshow");
			follow.style.display="none";
			follow_show.style.display="none";
			follow_unshow.style.display="block";
			var fans=document.getElementById("fans");
			var fans_show=document.getElementById("profile-fans-show");
			var fans_unshow=document.getElementById("profile-fans-unshow");
			fans.style.display="none";
			fans_show.style.display="none";
			fans_unshow.style.display="block";
			var goal=document.getElementById(id);
			var goal_show=document.getElementById("profile-"+id+"-show");
			var goal_unshow=document.getElementById("profile-"+id+"-unshow");
			goal.style.display="block";goal_show.style.display="block";goal_unshow.style.display="none";
		}
	</script>
</div>