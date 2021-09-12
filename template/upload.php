<h2 style="width:100%;"><center><?php echo $language["upload-intitle"];?></center></h2>
<div id="hehe">
	<div class="main-left">
		<h4 style="padding-left:30px;"><?php echo $language["upload-admin-title"];?></h4>
		<?php
			$array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/check/login.php")),true);
			// var_dump($array);
			if ($config["enable-article"])
				echo '
					<p id="upload-article-unshow" onclick="show(\'article\')" style="display:none;">●&nbsp;&nbsp;'.$language["upload-article"].'</p>
					<p id="upload-article-show" onclick="show(\'article\')" style="color:rgb(47,174,227);">●&nbsp;&nbsp;'.$language["upload-article"].'</p>
				';
			if ($config["enable-wikis"])
				echo '
					<p id="upload-wikis-unshow" onclick="show(\'wikis\')">●&nbsp;&nbsp;'.$language["upload-wikis"].'</p>
					<p id="upload-wikis-show" onclick="show(\'wikis\')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;'.$language["upload-wikis"].'</p>
				';
			if ($config["enable-notice"]&&$array["data"]["isLogin"]&&$array["data"]["user"]["authority"])
				echo '
					<p id="upload-notice-unshow" onclick="show(\'notice\')">●&nbsp;&nbsp;'.$language["upload-notice"].'</p>
					<p id="upload-notice-show" onclick="show(\'notice\')" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;'.$language["upload-notice"].'</p>
				';
		?>
		<p id="upload-writer-unshow" onclick="attention()">●&nbsp;&nbsp;<?php echo $language["upload-writer"];?></p>
		<p id="upload-writer-show" onclick="attention()" style="display:none;color:rgb(47,174,227);">●&nbsp;&nbsp;<?php echo $language["upload-writer"];?></p>
	</div>
	<div class="main-right">
		<div class="article" id="article" style="display:block">
			<h3><?php echo $language["upload-article-title"];?></h3>
			<div class="upload-article-element" id="upload-article-element0">
				<h4 onclick=create_article()><a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language["upload-article-create"];?>&nbsp;&nbsp;&nbsp;&nbsp;<img style="height:15px;position:relative;top:2px;" src="<?php echo $config["photo-black-right"];?>"></img></a></h4>
			</div>
			<hr>
			<?php
				$json=strip_tags(GetFromHTML($config["api-server-address"]."/search/article.php?l=1&r=".$config["upload-article-number"]."&uid=".$_COOKIE["DedeUserId"]."&sort=time"));
				$obj=json_decode($json,true);
				for ($i=0;$i<count($obj["data"]["replies"]);$i++)
				{
					echo "<div class='upload-article-element' id='upload-article-element$i'>
						<div class='upload-article-element-left' id='upload-article-element-left$i'>
							<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["main-path"]."&type=article&column=".$obj["data"]["replies"][$i]["columnid"]."&id=".$obj["data"]["replies"][$i]["id"]."'>".$obj["data"]["replies"][$i]["name"]."</a></h4>
							<p>".$language["upload-article-time"].":&nbsp;".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["time"])."<p>
							<div class='upload-article-element-left-toolbar' id='upload-article-element-left-toolbar$i'>
								".$language["upload-article-data"].":&nbsp;
								<img class='img1' src='".$config["photo-upload-watch-path"]."'/> ".$obj["data"]["replies"][$i]["view"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<img class='img1' src='".$config["photo-upload-comment-path"]."'/> ".$obj["data"]["replies"][$i]["comment"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<img class='img2' src='".$config["photo-upload-star-path"]."'/> ".$obj["data"]["replies"][$i]["star"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<img class='img2' src='".$config["photo-upload-like-path"]."'/> ".$obj["data"]["replies"][$i]["like"]."
							</div>
						</div>
						<div class='upload-article-element-right' id='upload-article-element-right$i'>
							<button onclick=delete_article(".$obj["data"]["replies"][$i]["columnid"].",".$obj["data"]["replies"][$i]["id"].")>".$language["upload-article-delete"]."</button>&nbsp;&nbsp;
							<button onclick=write_article(".$obj["data"]["replies"][$i]["columnid"].",".$obj["data"]["replies"][$i]["id"].")>".$language["upload-article-change"]."</button>
						</div>
					</div>
					<hr>";
				}
			?>
			<script>
				function write_article(column,id)
				{
					$.ajax({
						type:"GET",
						url:<?php echo "\"".$config["api-server-address"]."/article/info.php?column=\"+column+\"&id=\"+id";?>,
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								$.ajax({
									type:"GET",
									url:obj["data"]["link"],
									cache:false,
									success:function(message)
									{
										document.getElementById("upload-writer-title").value=obj["data"]["name"];
										editor.txt.html(message);
										document.getElementById("upload-writer-column").value=obj["data"]["columnid"];
										document.getElementById("upload-writer-column").setAttribute("disabled","disabled");
										submit_type="update-article";
										article_id=obj["data"]["id"];
										document.getElementById("upload-writer-info-title").style.display="flex";
										document.getElementById("upload-writer-info-column").style.display="flex";	
										document.getElementById("upload-writer-info-wiki").style.display="none";
										document.getElementById("upload-writer-info-reason").style.display="none";	
										show('writer');
									},
									error:function(jqXHR,textStatus,errorThrown) 
									{
										layer.alert(<?php echo "\"".$language["upload-writer-request-error"]."\""?>);
									    console.log(jqXHR.responseText);
									    console.log(jqXHR.status);
									    console.log(jqXHR.readyState);
										console.log(jqXHR.statusText);
									    console.log(textStatus);
									    console.log(errorThrown);
									}
								});
							}
							else 
							{
								layer.msg(<?php echo "\"".$language["upload-writer-request-failed"]."\""?>)
								console.log(obj["message"]);
							}
						},
						error:function(jqXHR,textStatus,errorThrown) 
						{
							layer.alert(<?php echo "\"".$language["upload-writer-request-error"]."\""?>);
						    console.log(jqXHR.responseText);
						    console.log(jqXHR.status);
						    console.log(jqXHR.readyState);
							console.log(jqXHR.statusText);
						    console.log(textStatus);
						    console.log(errorThrown);
						}
					});
				}
				function create_article()
				{
					document.getElementById("upload-writer-title").value="";
					editor.txt.html("");
					document.getElementById("upload-writer-column").value=0;	
					document.getElementById("upload-writer-column").removeAttribute("disabled");
					document.getElementById("upload-writer-info-title").style.display="flex";
					document.getElementById("upload-writer-info-column").style.display="flex";	
					document.getElementById("upload-writer-info-wiki").style.display="none";
					document.getElementById("upload-writer-info-reason").style.display="none";	
					submit_type="create-article";
					show("writer");
				}
				function delete_article(column,id)
				{
					layer.confirm(<?php echo "\"".$language["upload-article-delete-content"]."\""?>, {
						btn: [<?php echo "\"".$language["upload-article-delete-sure"]."\""?>,<?php echo "\"".$language["upload-article-delete-cancle"]."\""?>] //按钮
					}, function(){
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/article/delete.php\"";?>,
							data:{column:column,id:id},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["upload-article-delete-succeed"]."\""?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["upload-path"]."\"";?>
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-article-delete-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-article-delete-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
								console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}, function(){});
				}
			</script>
		</div>
		<script>
			var data_num_article=<?php echo $config["upload-article-number"];?>;
			var data_each_time_article=<?php echo $config["upload-article-number"];?>;
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
					url:<?php echo "\"".$config["api-server-address"]."/search/article.php?l=\"+(data_num_article+1)+\"&r=\"+(data_num_article+data_each_time_article)+\"&uid=".$_COOKIE["DedeUserId"]."&sort=time\"";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							for (i=0;i<obj["data"]["replies"].length;i++)
							{
								article_element.innerHTML+="<div class='upload-article-element' id='upload-article-element"+i+"'>"+
															"	<div class='upload-article-element-left' id='upload-article-element-left"+i+"'>"+
															"		<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["main-path"]."\"";?>+"&type=article&column="+obj["data"]["replies"][i]["columnid"]+"&id="+obj["data"]["replies"][i]["id"]+"'>"+obj["data"]["replies"][i]["name"]+"</a></h4>"+
															"		<p>"+<?php echo "\"".$language["upload-article-time"]."\"";?>+":&nbsp;"+TimeToString(obj["data"]["replies"][i]["time"])+"<p>"+
															"		<div class='upload-article-element-left-toolbar' id='upload-article-element-left-toolbar"+i+"'>"+
															"			"+<?php echo "\"".$language["upload-article-data"]."\"";?>+":&nbsp;"+
															"			<img class='img1' src='"+<?php echo "\"".$config["photo-upload-watch-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["view"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img1' src='"+<?php echo "\"".$config["photo-upload-comment-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["comment"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img2' src='"+<?php echo "\"".$config["photo-upload-star-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["star"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img2' src='"+<?php echo "\"".$config["photo-upload-like-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["like"]+
															"		</div>"+
															"	</div>"+
															"	<div class='upload-article-element-right' id='upload-article-element-right"+i+"'>"+
															"		<button onclick=delete_article("+obj["data"]["replies"][i]["columnid"]+","+obj["data"]["replies"][i]["id"]+")>"+<?php echo "\"".$language["upload-article-delete"]."\"";?>+"</button>&nbsp;&nbsp;"+
															"		<button onclick=write_article("+obj["data"]["replies"][i]["columnid"]+","+obj["data"]["replies"][i]["id"]+")>"+<?php echo "\"".$language["upload-article-change"]."\"";?>+"</button>"+
															"	</div>"+
															"</div>"+
															"<hr>";
							}
							if (obj["data"]["replies"].length<data_each_time_article)
							{
								if (!article_end)
								{
									article_element.innerHTML+=<?php echo "\"<br><div><center>".$language["upload-article-ending"]."</center></div>\""?>;
									data_num_article+=data_each_time_article;
								}
								article_end=1;
								return;
							}
							data_num_article+=data_each_time_article;
						}
						else
						{
							layer.msg(<?php echo "\"".$language["upload-article-request-failed"]."\""?>)
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["upload-article-request-error"]."\""?>);
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
			})
		</script>
		<div class="wikis" id="wikis" style="display:none">
			<h3><?php echo $language["upload-wiki-title"];?></h3>
				<div class="upload-wiki-element" id="upload-wiki-element0">
					<h4 onclick=create_wiki()><a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language["upload-wiki-create"];?>&nbsp;&nbsp;&nbsp;&nbsp;<img style="height:15px;position:relative;top:2px;" src="<?php echo $config["photo-black-right"];?>"></img></a></h4>
				</div>
				<hr>
				<?php
					$json=strip_tags(GetFromHTML($config["api-server-address"]."/wiki/request.php?l=1&r=".$config["upload-wiki-number"]."&sort=createtime"));
					$obj=json_decode($json,true);
					for ($i=0;$i<count($obj["data"]["replies"]);$i++)
					{
						echo "<div class='upload-wiki-element' id='upload-wiki-element$i'>
							<div class='upload-wiki-element-left' id='upload-wiki-element-left$i'>
								<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["main-path"]."&type=wikis&id=".$obj["data"]["replies"][$i]["id"]."'>".$obj["data"]["replies"][$i]["name"]."</a></h4>
								<p>".$language["upload-wiki-time"].":&nbsp;".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["opentime"])."&nbsp;&nbsp;|&nbsp;&nbsp;".$language["upload-wiki-latest"].":&nbsp;".($obj["data"]["replies"][$i]["latest"]?date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["latest"]):"---")."<p>
								<div class='upload-wiki-element-left-toolbar' id='upload-wiki-element-left-toolbar$i'>
									".$language["upload-wiki-data"].":&nbsp;
									<img class='img1' src='".$config["photo-upload-watch-path"]."'/> ".$obj["data"]["replies"][$i]["view"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<img class='img2' src='".$config["photo-upload-star-path"]."'/> ".$obj["data"]["replies"][$i]["star"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<img class='img2' src='".$config["photo-upload-like-path"]."'/> ".$obj["data"]["replies"][$i]["like"]."
								</div>
							</div>
							<div class='upload-wiki-element-right' id='upload-wiki-element-right$i'>
								<button onclick=write_wiki(".$obj["data"]["replies"][$i]["id"].")>".$language["upload-wiki-update"]."</button>
							</div>
						</div>
						<hr>";
					}
				?>
				<script>
					function write_wiki(id)
					{
						$.ajax({
							type:"GET",
							url:<?php echo "\"".$config["api-server-address"]."/wiki/info.php?id=\"+id";?>,
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								document.getElementById("upload-writer-wiki").value=obj["data"]["id"];
								document.getElementById("upload-writer-wiki").setAttribute("disabled","disabled");
								if (code==0)
								{
									editor.txt.html("");
									document.getElementById("upload-writer-reason").removeAttribute("disabled");
									document.getElementById("upload-writer-reason").value="";
									submit_type="wiki";
									document.getElementById("upload-writer-info-title").style.display="none";
									document.getElementById("upload-writer-info-column").style.display="none";	
									document.getElementById("upload-writer-info-wiki").style.display="flex";
									document.getElementById("upload-writer-info-reason").style.display="flex";
									if (obj["data"]["history"].length) 
										$.ajax({
											type:"GET",
											url:obj["data"]["history"][0]["link"],
											cache:false,
											success:function(message)
											{
												editor.txt.html(message);
											},
											error:function(jqXHR,textStatus,errorThrown) 
											{
												layer.alert(<?php echo "\"".$language["upload-writer-request-error"]."\""?>);
												console.log(jqXHR.responseText);
												console.log(jqXHR.status);
												console.log(jqXHR.readyState);
												console.log(jqXHR.statusText);
												console.log(textStatus);
												console.log(errorThrown);
												return;
											}
										});
									show('writer');
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-writer-request-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-writer-request-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
								console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}
					function create_wiki()
					{
						var id;
						layer.prompt({title:<?php echo "\"".$language["upload-wiki-input-title"]."\"";?>},function(val,index){
							$.ajax({
								type:"POST",
								url:<?php echo "\"".$config["api-server-address"]."/wiki/create.php\"";?>,
								data:{title:val},
								success:function(message)
								{
									var obj=JSON.parse(strip_tags(message));
									var code=obj["code"];
									if (code==0)
									{
										alert(<?php echo "\"".$language["upload-wiki-create-succeed"]."\""?>);
										document.getElementById("upload-writer-wiki").innerHTML+="<option value='"+obj["data"]["id"]+"'>"+val+"</option>";
										document.getElementById("upload-writer-wiki").value=obj["data"]["id"];
										document.getElementById("upload-writer-wiki").setAttribute("disabled","disabled");
										editor.txt.html("");
										document.getElementById("upload-writer-reason").value="";
										document.getElementById("upload-writer-info-title").style.display="none";
										document.getElementById("upload-writer-info-column").style.display="none";	
										document.getElementById("upload-writer-info-wiki").style.display="flex";
										document.getElementById("upload-writer-info-reason").style.display="flex";	
										submit_type="wiki";
										show("writer");
									}
									else 
									{
										layer.msg(<?php echo "\"".$language["upload-wiki-create-failed"]."\""?>)
										console.log(obj["message"]);
										return;
									}
								},
								error:function(jqXHR,textStatus,errorThrown) 
								{
									layer.alert(<?php echo "\"".$language["upload-wiki-create-error"]."\""?>);
								    console.log(jqXHR.responseText);
								    console.log(jqXHR.status);
								    console.log(jqXHR.readyState);
									console.log(jqXHR.statusText);
								    console.log(textStatus);
								    console.log(errorThrown);
									return;
								}
							});
							layer.close(index);
						});
					}
				</script>
			<script>
				var data_num_wiki=<?php echo $config["upload-wiki-number"];?>;
				var data_each_time_wiki=<?php echo $config["upload-wiki-number"];?>;
				var wiki_element=document.getElementById("wikis");
				var wiki_end=0;
				function TimeToString(datetime)
				{
					if (datetime==0) return '---';
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
				function update_wiki()
				{
					$.ajax({
						type:"GET",
						url:<?php echo "\"".$config["api-server-address"]."/wiki/request.php?l=\"+(data_num_wiki+1)+\"&r=\"+(data_num_wiki+data_each_time_wiki)+\"&sort=createtime\"";?>,
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								for (i=0;i<obj["data"]["replies"].length;i++)
								{
									wiki_element.innerHTML+="<div class='upload-wiki-element' id='upload-wiki-element"+i+"'>"+
															"	<div class='upload-wiki-element-left' id='upload-wiki-element-left"+i+"'>"+
															"		<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["main-path"]."\"";?>+"&type=wiki&id="+obj["data"]["replies"][i]["id"]+"'>"+obj["data"]["replies"][i]["name"]+"</a></h4>"+
															"		<p>"+<?php echo "\"".$language["upload-wiki-time"]."\"";?>+":&nbsp;"+TimeToString(obj["data"]["replies"][i]["opentime"])+"&nbsp;&nbsp;|&nbsp;&nbsp;"+<?php echo "\"".$language["upload-wiki-latest"]."\"";?>+":&nbsp;"+TimeToString(obj["data"]["replies"][i]["latest"])+"<p>"+
															"		<div class='upload-wiki-element-left-toolbar' id='upload-wiki-element-left-toolbar"+i+"'>"+
															"			"+<?php echo "\"".$language["upload-wiki-data"]."\"";?>+":&nbsp;"+
															"			<img class='img1' src='"+<?php echo "\"".$config["photo-upload-watch-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["view"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img2' src='"+<?php echo "\"".$config["photo-upload-star-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["star"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"			<img class='img2' src='"+<?php echo "\"".$config["photo-upload-like-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["like"]+
															"		</div>"+
															"	</div>"+
															"	<div class='upload-wiki-element-right' id='upload-wiki-element-right"+i+"'>"+
															"		<button onclick=write_wiki("+obj["data"]["replies"][i]["id"]+")>"+<?php echo "\"".$language["upload-wiki-update"]."\"";?>+"</button>"+
															"	</div>"+
															"</div>"+
															"<hr>";
								}
								if (obj["data"]["replies"].length<data_each_time_wiki)
								{
									if (!wiki_end)
									{
										wiki_element.innerHTML+=<?php echo "\"<br><div><center>".$language["upload-wiki-ending"]."</center></div>\""?>;
										data_num_wiki+=data_each_time_wiki;
									}
									wiki_end=1;
									return;
								}
								data_num_wiki+=data_each_time_wiki;
							}
							else
							{
								layer.msg(<?php echo "\"".$language["upload-wiki-request-failed"]."\""?>)
								console.log(obj["message"]);
							}
						},
						error:function(jqXHR,textStatus,errorThrown) 
						{
							layer.alert(<?php echo "\"".$language["upload-wiki-request-error"]."\""?>);
						    console.log(jqXHR.responseText);
						    console.log(jqXHR.status);
						    console.log(jqXHR.readyState);
						    console.log(jqXHR.statusText);
						    console.log(textStatus);
						    console.log(errorThrown);
						}
					});
				}
				var div1=document.getElementById('wikis');
				if (!((div1.scrollHeight>div1.clientHeight)||(div1.offsetHeight>div1.clientHeight))) update_wiki();
				$('#wikis').scroll(function(event)
				{
					var t=event.currentTarget.scrollTop;
					var s=event.currentTarget.scrollHeight;
					var c=event.currentTarget.clientHeight;
					// alert(t+" "+s+" "+c);
					if(t+c>=s) update_wiki();
				})
			</script>
		</div>
		<div class="notice" id="notice" style="display:none">
			<h3><?php echo $language["upload-notice-title"];?></h3>
			<div class="upload-notice-element" id="upload-notice-element0">
				<h4 onclick=create_notice()><a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language["upload-notice-create"];?>&nbsp;&nbsp;&nbsp;&nbsp;<img style="height:15px;position:relative;top:2px;" src="<?php echo $config["photo-black-right"];?>"></img></a></h4>
			</div>
			<hr>
			<?php
				$json=strip_tags(GetFromHTML($config["api-server-address"]."/search/notice.php?l=1&r=".$config["upload-notice-number"]."&uid=".$_COOKIE["DedeUserId"]."&sort=time"));
				$obj=json_decode($json,true);
				for ($i=0;$i<count($obj["data"]["replies"]);$i++)
				{
					echo "<div class='upload-notice-element' id='upload-notice-element$i'>
						<div class='upload-notice-element-left' id='upload-notice-element-left$i'>
							<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$config["server"]."/".$config["main-path"]."&type=notice&id=".$obj["data"]["replies"][$i]["id"]."'>".$obj["data"]["replies"][$i]["name"]."</a></h4>
							<p>".$language["upload-notice-time"].":&nbsp;".date("Y-m-d H:i:s",$obj["data"]["replies"][$i]["release"])."<p>
							<div class='upload-notice-element-left-toolbar' id='upload-notice-element-left-toolbar$i'>
								".$language["upload-notice-data"].":&nbsp;
								<img class='img1' src='".$config["photo-upload-watch-path"]."'/> ".$obj["data"]["replies"][$i]["view"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</div>
						</div>
						<div class='upload-notice-element-right' id='upload-notice-element-right$i'>
							<button onclick=delete_notice(".$obj["data"]["replies"][$i]["id"].")>".$language["upload-notice-delete"]."</button>&nbsp;&nbsp;
							<button onclick=write_notice(".$obj["data"]["replies"][$i]["id"].")>".$language["upload-notice-change"]."</button>
						</div>
					</div>
					<hr>";
				}
			?>
			<script>
				function write_notice(id)
				{
					$.ajax({
						type:"GET",
						url:<?php echo "\"".$config["api-server-address"]."/notice/info.php?id=\"+id";?>,
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								$.ajax({
									type:"GET",
									url:obj["data"]["link"],
									cache:false,
									success:function(message)
									{
										document.getElementById("upload-writer-title").value=obj["data"]["name"];
										editor.txt.html(message);
										submit_type="update-notice";
										notice_id=obj["data"]["id"];
										document.getElementById("upload-writer-info-title").style.display="flex";
										document.getElementById("upload-writer-info-column").style.display="none";	
										document.getElementById("upload-writer-info-wiki").style.display="none";
										document.getElementById("upload-writer-info-reason").style.display="none";	
										show('writer');
									},
									error:function(jqXHR,textStatus,errorThrown) 
									{
										layer.alert(<?php echo "\"".$language["upload-writer-request-error"]."\""?>);
									    console.log(jqXHR.responseText);
									    console.log(jqXHR.status);
									    console.log(jqXHR.readyState);
										console.log(jqXHR.statusText);
									    console.log(textStatus);
									    console.log(errorThrown);
									}
								});
							}
							else 
							{
								layer.msg(<?php echo "\"".$language["upload-writer-request-failed"]."\""?>)
								console.log(obj["message"]);
							}
						},
						error:function(jqXHR,textStatus,errorThrown) 
						{
							layer.alert(<?php echo "\"".$language["upload-writer-request-error"]."\""?>);
						    console.log(jqXHR.responseText);
						    console.log(jqXHR.status);
						    console.log(jqXHR.readyState);
							console.log(jqXHR.statusText);
						    console.log(textStatus);
						    console.log(errorThrown);
						}
					});
				}
				function create_notice()
				{
					document.getElementById("upload-writer-title").value="";
					editor.txt.html("");
					document.getElementById("upload-writer-info-title").style.display="flex";
					document.getElementById("upload-writer-info-column").style.display="none";	
					document.getElementById("upload-writer-info-wiki").style.display="none";
					document.getElementById("upload-writer-info-reason").style.display="none";	
					submit_type="create-notice";
					show("writer");
				}
				function delete_notice(id)
				{
					layer.confirm(<?php echo "\"".$language["upload-notice-delete-content"]."\""?>, {
						btn: [<?php echo "\"".$language["upload-notice-delete-sure"]."\""?>,<?php echo "\"".$language["upload-notice-delete-cancle"]."\""?>] //按钮
					}, function(){
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/notice/delete.php\"";?>,
							data:{id:id},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["upload-notice-delete-succeed"]."\""?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["upload-path"]."\"";?>
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-notice-delete-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-notice-delete-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
								console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}, function(){});
				}
			</script>
		</div>
		<script>
			var data_num_notice=<?php echo $config["upload-notice-number"];?>;
			var data_each_time_notice=<?php echo $config["upload-notice-number"];?>;
			var notice_element=document.getElementById("notice");
			var notice_end=0;
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
			function update_notice()
			{
				$.ajax({
					type:"GET",
					url:<?php echo "\"".$config["api-server-address"]."/search/notice.php?l=\"+(data_num_notice+1)+\"&r=\"+(data_num_notice+data_each_time_notice)";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							for (i=0;i<obj["data"]["replies"].length;i++)
							{
								notice_element.innerHTML+="<div class='upload-notice-element' id='upload-notice-element"+i+"'>"+
															"	<div class='upload-notice-element-left' id='upload-notice-element-left"+i+"'>"+
															"		<h4>&nbsp;&nbsp;&nbsp;&nbsp;<a href='"+<?php echo "\"".$config["server"]."\"";?>+"/"+<?php echo "\"".$config["main-path"]."\"";?>+"&type=notice&id="+obj["data"]["replies"][i]["id"]+"'>"+obj["data"]["replies"][i]["name"]+"</a></h4>"+
															"		<p>"+<?php echo "\"".$language["upload-notice-time"]."\"";?>+":&nbsp;"+TimeToString(obj["data"]["replies"][i]["release"])+"<p>"+
															"		<div class='upload-notice-element-left-toolbar' id='upload-notice-element-left-toolbar"+i+"'>"+
															"			"+<?php echo "\"".$language["upload-notice-data"]."\"";?>+":&nbsp;"+
															"			<img class='img1' src='"+<?php echo "\"".$config["photo-upload-watch-path"]."\"";?>+"'/> "+obj["data"]["replies"][i]["view"]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
															"		</div>"+
															"	</div>"+
															"	<div class='upload-notice-element-right' id='upload-notice-element-right"+i+"'>"+
															"		<button onclick=delete_notice("+obj["data"]["replies"][i]["id"]+")>"+<?php echo "\"".$language["upload-notice-delete"]."\"";?>+"</button>&nbsp;&nbsp;"+
															"		<button onclick=write_notice("+obj["data"]["replies"][i]["id"]+")>"+<?php echo "\"".$language["upload-notice-change"]."\"";?>+"</button>"+
															"	</div>"+
															"</div>"+
															"<hr>";
							}
							if (obj["data"]["replies"].length<data_each_time_notice)
							{
								if (!notice_end)
								{
									notice_element.innerHTML+=<?php echo "\"<br><div><center>".$language["upload-notice-ending"]."</center></div>\""?>;
									data_num_notice+=data_each_time_notice;
								}
								notice_end=1;
								return;
							}
							data_num_notice+=data_each_time_notice;
						}
						else
						{
							layer.msg(<?php echo "\"".$language["upload-notice-request-failed"]."\""?>)
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["upload-notice-request-error"]."\""?>);
					    console.log(jqXHR.responseText);
					    console.log(jqXHR.status);
					    console.log(jqXHR.readyState);
					    console.log(jqXHR.statusText);
					    console.log(textStatus);
					    console.log(errorThrown);
					}
				});
			}
			var div=document.getElementById('notice');
			if (!((div.scrollHeight>div.clientHeight)||(div.offsetHeight>div.clientHeight))) update_notice();
			$('#notice').scroll(function(event)
			{
				var t=event.currentTarget.scrollTop;
				var s=event.currentTarget.scrollHeight;
				var c=event.currentTarget.clientHeight;
				// alert(t+" "+s+" "+c);
				if(t+c>=s) update_notice();
			})
		</script>
		<div class="writer" id="writer" style="display:none">
			<h3><?php echo $language["upload-writer-title"];?></h3>
			<p class="upload-writer-info" id="upload-writer-info-title"><?php echo $language["upload-writer-before-title"]?>:&nbsp;<input id="upload-writer-title"/></p>
			<p class="upload-writer-info" id="upload-writer-info-column"><?php echo $language["upload-writer-before-column"]?>:&nbsp;<select id="upload-writer-column">
				<option value="0"><?php echo $language["upload-writer-default-option"];?></option>
				<?php
					$array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/column/max.php")),true);
					$array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/column/request.php?l=1&r=".$array["max"]."&sort=intime")),true);
					for ($i=0;$i<count($array["data"]["replies"]);$i++) echo "<option value='".$array["data"]["replies"][$i]["id"]."'>".$array["data"]["replies"][$i]["name"]."</option>"
				?>
			</select></p>
			<p class="upload-writer-info" id="upload-writer-info-wiki" style="display:none"><?php echo $language["upload-writer-before-wiki"]?>:&nbsp;<select id="upload-writer-wiki">
				<option value="0"><?php echo $language["upload-writer-default-option"];?></option>
				<?php
					$array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/wiki/maxwiki.php")),true);
					$array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/wiki/request.php?l=1&r=".$array["max"])),true);
					for ($i=0;$i<count($array["data"]["replies"]);$i++) echo "<option value='".$array["data"]["replies"][$i]["id"]."'>".$array["data"]["replies"][$i]["name"]."</option>"
				?>
			</select></p>
			<p class="upload-writer-info" id="upload-writer-info-reason" style="display:none"><?php echo $language["upload-writer-before-reason"]?>:&nbsp;<input id="upload-writer-reason"/></p>
			<div id="upload-writer-content">
			</div>
			<br>
			<center><button class="upload-writer-submit" onclick=submit()><?php echo $language["upload-writer-submit"];?></button></center>
			<script>
				var submit_type="";
				var article_id="";
				var notice_id="";
			</script>
			<script>
				const E = window.wangEditor;
				var editor = new E('#upload-writer-content');
				editor.config.focus = false;
				editor.highlight = hljs;
				editor.config.height = 450;
				editor.config.placeholder = <?php echo "\"".$language["upload-writer-placeholder"]."\"";?>;
				editor.config.languageType = ['Bash','C','C#','C++','CSS','Java','JavaScript','JSON','TypeScript','Plain text','Html','XML','SQL','Go','Kotlin','Lua','Markdown','PHP','Python','Shell Session','Ruby',];
				editor.config.languageTab = '    ';
				editor.config.showFullScreen = true;
				editor.config.pasteFilterStyle = false;
				editor.create();
				function submit()
				{
					var title=document.getElementById("upload-writer-title").value;
					var column=document.getElementById("upload-writer-column").value;
					var wiki=document.getElementById("upload-writer-wiki").value;
					var reason=document.getElementById("upload-writer-reason").value;
					var content=editor.txt.html();
					if (submit_type=="update-article")
					{
						if (title=="")
						{
							layer.msg(<?php echo "\"".$language["upload-writer-title-empty"]."\"";?>)
							return;
						}
						if (editor.txt.text().length==0)
						{
							layer.msg(<?php echo "\"".$language["upload-writer-content-empty"]."\"";?>)
							return;
						}
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/article/update.php\"";?>,
							data:{title:title,column:column,id:article_id,content:content},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["upload-update-article-succeed"]."\"";?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["upload-path"]."\"";?>
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-update-article-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-update-article-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
							    console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}
					else if (submit_type=="create-article")
					{
						if (title=="")
						{
							layer.msg(<?php echo "\"".$language["upload-writer-title-empty"]."\"";?>)
							return;
						}
						if (column==0)
						{
							layer.msg(<?php echo "\"".$language["upload-writer-column-empty"]."\"";?>)
							return;
						}
						if (editor.txt.text().length==0)
						{
							layer.msg(<?php echo "\"".$language["upload-writer-content-empty"]."\"";?>)
							return;
						}
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/article/create.php\"";?>,
							data:{title:title,column:column,content:content},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["upload-create-article-succeed"]."\"";?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["upload-path"]."\"";?>
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-create-article-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-create-article-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
							    console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}
					if (submit_type=="update-notice")
					{
						if (title=="")
						{
							layer.msg(<?php echo "\"".$language["upload-writer-title-empty"]."\"";?>)
							return;
						}
						if (editor.txt.text().length==0)
						{
							layer.msg(<?php echo "\"".$language["upload-writer-content-empty"]."\"";?>)
							return;
						}
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/notice/update.php\"";?>,
							data:{title:title,id:notice_id,content:content},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["upload-update-notice-succeed"]."\"";?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["upload-path"]."\"";?>
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-update-notice-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-update-notice-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
							    console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}
					else if (submit_type=="create-notice")
					{
						if (title=="")
						{
							layer.msg(<?php echo "\"".$language["upload-writer-title-empty"]."\"";?>)
							return;
						}
						if (editor.txt.text().length==0)
						{
							layer.msg(<?php echo "\"".$language["upload-writer-content-empty"]."\"";?>)
							return;
						}
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/notice/create.php\"";?>,
							data:{title:title,content:content},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["upload-create-notice-succeed"]."\"";?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["upload-path"]."\"";?>
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-create-notice-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-create-notice-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
							    console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}
					else if (submit_type=="wiki")
					{
						if (wiki==0)
						{
							layer.msg(<?php echo "\"".$language["upload-writer-wiki-empty"]."\"";?>)
							return;
						}
						if (reason=="")
						{
							layer.msg(<?php echo "\"".$language["upload-writer-reason-empty"]."\"";?>)
							return;
						}
						if (editor.txt.text().length==0)
						{
							layer.msg(<?php echo "\"".$language["upload-writer-content-empty"]."\"";?>)
							return;
						}
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/wiki/update.php\"";?>,
							data:{wiki:wiki,content:content,reason:reason},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["upload-update-wiki-succeed"]."\"";?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["upload-path"]."\"";?>
								}
								else 
								{
									layer.msg(<?php echo "\"".$language["upload-update-wiki-failed"]."\""?>)
									console.log(obj["message"]);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["upload-update-wiki-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
							    console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}
				}
			</script>
		</div>
	</div>
	<script>
			function strip_tags(html) 
			{
				var div=document.createElement("div");
				div.innerHTML=html;
				return (div.textContent||div.innerText||"");
			}
			function attention()
			{
				layer.msg(<?php echo "\"".$language["upload-request"]."\"";?>);
			}
			function show(id)
			{
				<?php
					if ($config["enable-article"]) 
						echo '
							var article=document.getElementById("article");
							var article_show=document.getElementById("upload-article-show");
							var article_unshow=document.getElementById("upload-article-unshow");
							article.style.display="none";
							article_show.style.display="none";
							article_unshow.style.display="block";
						';
					if ($config["enable-wikis"]) 
						echo '
							var wikis=document.getElementById("wikis");
							var wikis_show=document.getElementById("upload-wikis-show");
							var wikis_unshow=document.getElementById("upload-wikis-unshow");
							wikis.style.display="none";
							wikis_show.style.display="none";
							wikis_unshow.style.display="block";
						';
					if ($config["enable-notice"]) 
						echo '
							var notice=document.getElementById("notice");
							var notice_show=document.getElementById("upload-notice-show");
							var notice_unshow=document.getElementById("upload-notice-unshow");
							notice.style.display="none";
							notice_show.style.display="none";
							notice_unshow.style.display="block";
						';
				?>
				var writer=document.getElementById("writer");
				// var friends=document.getElementById("friends");
				// var settings=document.getElementById("settings");
				var writer_show=document.getElementById("upload-writer-show");
				// var friends_show=document.getElementById("message-friends-show");
				// var settings_show=document.getElementById("message-settings-show");
				var writer_unshow=document.getElementById("upload-writer-unshow");
				// var friends_unshow=document.getElementById("message-friends-unshow");
				// var settings_unshow=document.getElementById("message-settings-unshow");
				writer.style.display="none";
				writer_show.style.display="none";
				writer_unshow.style.display="block";
				var goal=document.getElementById(id);
				var goal_show=document.getElementById("upload-"+id+"-show");
				var goal_unshow=document.getElementById("upload-"+id+"-unshow");
				goal.style.display="block";goal_show.style.display="block";goal_unshow.style.display="none";
			}
		</script>
	</div>
</div>