<script>
	function addCookie(cname,cvalue,extime)
	{
		var d=new Date();
		d.setTime(d.getTime()+extime);
		var expires="expires="+d.toGMTString();
		document.cookie=cname+"="+cvalue+";"+expires;
	}
</script>
<?php
	/* 当type为column时 */
	if ($_GET["type"]=="column")
	{
		$id=$_GET["id"];
		if (!isset($_COOKIE["column#".$id]))
		{
			$json=PostFromHTML($config["api-server-address"]."/column/watch.php",array("id"=>$id));
			$array=json_decode(strip_tags($json),true);
			if ($array["code"]==0) echo "<script>addCookie('column#$id','".time()."',".$config["cookie-column-time"].");</script>";
			else echo "<script>console.log('[Warning]".$array['message']."')</script>";
		}
		$json=GetFromHTML($config["api-server-address"]."/column/info.php?id=$id");
		$array=json_decode(strip_tags($json),true);
		echo "<center><h2 style=\"width:100%;\">".$language["main-column-article-title"]."</h2></center>";
		echo "<script>document.title='".$array["data"]["name"]." - ".$config["website-title"]."';</script>";
		for ($i=0;$i<count($array["data"]["article"]);$i++)
		{
			$last=time()-$array["data"]["article"][$i]["time"];
			$release=$array["data"]["article"][$i]["time"];
			echo "
			<div class='article-element' id='article-element$i'>
				<img class='picture' id='article-element-picture$i' src='".$array["data"]["article"][$i]["author"]["header"]."'/>
				<div class='info' id='article-element-info$i'>
					<a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=article&column=".$array["data"]["article"][$i]["column"]."&id=".$array["data"]["article"][$i]["id"]."')>".$array["data"]["article"][$i]["name"]."</a>
					<p>".$language["index-article-writer"].": @<location onclick=locate('".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["article"][$i]["author"]["uid"]."')>".$array["data"]["article"][$i]["author"]["name"]."</location></p>
					<p>".$language["index-article-like"].":".$array["data"]["article"][$i]["like"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["index-article-view"].":".$array["data"]["article"][$i]["view"]."</p>
					<p>".$language["index-article-release-time"].":".
					(($last<60)?$language["index-article-short-time"]:
					(($last<60*60)?round($last/60).$language["index-article-minute"]:
					(($last<60*60*24)?round($last/60/60).$language["index-article-hour"]:
					((date("Y",$release)!=date("Y",time()))?
					date("Y-m-d",$release):
					date("m-d H:i",$release)))))."</p>
				</div>
			</div>
			";
		}
		$ps=$config["main-article-number"];
		$num_array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/article/max.php?column=".$_GET["id"])),true);
		$num=$num_array["max"];
		CreatePageList($num,$ps,$config["main-path"]."&type=column&id=".$_GET["id"]);
	}
	
	
	
	/* 当type为wikis时 */
	if ($_GET["type"]=="wikis")
	{
		$id=$_GET["id"];
		// if (!isset($_COOKIE["wikis#".$id]))
		// {
		// 	$json=PostFromHTML($config["api-server-address"]."/wiki/watch.php",array("type"=>"wikis","id"=>$id));
		// 	$array=json_decode(strip_tags($json),true);
		// 	if ($array["code"]==0) echo "<script>addCookie('wikis#$id','".time()."',".$config["cookie-wikis-time"].");</script>";
		// 	else echo "<script>console.log('[Warning]".$array["message"]."');</script>";
		// }
		$json=GetFromHTML($config["api-server-address"]."/wiki/info.php?id=$id");
		$array=json_decode(strip_tags($json),true);
		echo "<center><h2 style=\"width:100%;\">".$language["main-wikis-history-title"]."</h2></center>";
		echo "<script>document.title='".$array["data"]["name"]." - ".$config["website-title"]."';</script>";
		for ($i=0;$i<count($array["data"]["history"]);$i++)
		{
			$last=time()-$array["data"]["history"][$i]["opentime"];
			$release=$array["data"]["history"][$i]["opentime"];
			echo "
			<div class='history-element' id='history-element$i'>
				<div class='title' id='history-element-title$i'><a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=wiki&id=".$array["data"]["history"][$i]["wikiid"]."&version=".$array["data"]["history"][$i]["version"]."&page=1')>".$array["data"]["name"]." v1.".$array["data"]["history"][$i]["version"]."</a></div>
				<div class='info' id='history-element-info$i'>
					<p>".$language["main-history-author"].":".$array["data"]["history"][$i]["author"]["name"]."</p>
					<p>".$language["main-history-view"].":".$array["data"]["history"][$i]["watch"]."</p>
					<p>".$language["main-history-release-time"].":".
					(($last<60)?$language["main-history-short-time"]:
					(($last<60*60)?round($last/60).$language["main-history-minute"]:
					(($last<60*60*24)?round($last/60/60).$language["main-history-hour"]:
					((date("Y",$release)!=date("Y",time()))?
					date("Y-m-d",$release):
					date("m-d H:i",$release)))))."</p>
				</div>
			</div>
			";
		}
		$ps=$config["main-history-number"];
		$num_array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/wiki/maxhistory.php?id=".$_GET["id"])),true);
		$num=$num_array["max"];
		CreatePageList($num,$ps,$config["main-path"]."&type=wikis&id=".$_GET["id"]);
	}
	
	
	
	/* 当type为article时 */
	if ($_GET["type"]=="article")
	{
		$column=$_GET["column"];
		$id=$_GET["id"];
		if (!isset($_COOKIE["article#$column-$id"]))
		{
			$json=PostFromHTML($config["api-server-address"]."/article/watch.php",array("column"=>$column,"id"=>$id));
			$array=json_decode(strip_tags($json),true);
			if ($array["code"]==0) echo "<script>addCookie('article#$column-$id','".time()."',".$config["cookie-article-time"].");</script>";
			else echo "<script>console.log('[Warning]".$array["message"]."');</script>";
		}
		$json=GetFromHTML($config["api-server-address"]."/article/info.php?id=$id&column=$column");
		$array=json_decode(strip_tags($json),true);
		$last=time()-$array["data"]["time"];
		$release=$array["data"]["time"];
		$context=GetFromHTML($array["data"]["link"]);
		$json=GetFromHTML($config["api-server-address"]."/article/checklike.php?id=$id&column=$column");
		$like_array=json_decode(strip_tags($json),true);
		$json=GetFromHTML($config["api-server-address"]."/article/checkstar.php?id=$id&column=$column");
		$star_array=json_decode(strip_tags($json),true);
		$json=GetFromHTML($config["api-server-address"]."/check/login.php");
		$login_array=json_decode(strip_tags($json),true);
		$json=GetFromHTML($config["api-server-address"]."/comment/request.php?column=$column&id=$id");
		$comment_array=json_decode(strip_tags($json),true);
		$json=GetFromHTML($config["api-server-address"]."/column/info.php?id=$column");
		$column_array=json_decode(strip_tags($json),true);
		echo "<div class='article-main'>
			<script>document.title='".$array["data"]["name"]." - ".$config["website-title"]."';</script>
			<div class='main-left' id='main-left'>
				<h2>".$array["data"]["name"]."</h2>
				<div class='article-info' id='article-info'>
					<div class='article-info-header' id='article-info-header'>
						<img src='".$array["data"]["author"]["header"]."' />
					</div>
					<div class='article-info-context' id='article-info-context'>
						<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["author"]["uid"]."'><strong>".$array["data"]["author"]["name"]."</strong></a>
						<p style='font-size:14px;margin:0px;'>".
						(($last<60)?$language["main-article-short-time"]:
						(($last<60*60)?round($last/60).$language["main-article-minute"]:
						(($last<60*60*24)?round($last/60/60).$language["main-article-hour"]:
						((date("Y",$release)!=date("Y",time()))?
						date("Y-m-d",$release):
						date("m-d H:i",$release)))))." | ".$array["data"]["view"]." ".$language["main-article-view"]."
						</p>
					</div>
				</div>
				<hr>
				<div id='article-context'>
					$context
				</div>
				<hr>
				<div id='article-oparetor-toolbar'>
					<div id='article-left-toolbar'>
						<img id='main-article-like' src='".$config["photo-article-like"]."' style='display:".(($like_array["data"]["islike"])?"block":"none")."'/>
						<img id='main-article-unlike' src='".$config["photo-article-unlike"]."' style='display:".((!$like_array["data"]["islike"])?"block":"none")."'/>
						<p id='main-article-like-number'>".$array["data"]["like"]."</p>
						<img id='main-article-star' src='".$config["photo-article-star"]."' style='display:".(($star_array["data"]["isstar"])?"block":"none")."'/>
						<img id='main-article-unstar' src='".$config["photo-article-unstar"]."' style='display:".((!$star_array["data"]["isstar"])?"block":"none")."'/>
						<p id='main-article-star-number'>".$array["data"]["star"]."</p>
						<img id='main-article-share' src='".$config["photo-article-share"]."' style='display:none'/>
						<img id='main-article-unshare' src='".$config["photo-article-unshare"]."' style='display:block'/>
						<p id='main-article-share-number'>".$language["main-article-share"]."</p>
						<div id='main-article-share-block' style='display:none;margin:3%'></div>
					</div>
					<div id='article-middle-toolbar'>
					</div>
					<div id='article-right-toolbar'>
						<a href='".$config["server"]."/".$config["complaint-path"]."&type=article&column=$column&id=$id'>".$language["main-article-complaint"]."</a>
					</div>
				</div>
				<br>
				<div class='main-article-comment-area' id='main-article-comment-area'>
					<h3>".$array["data"]["comment"]." ".$language["main-article-comment"]."</h3>
					<div class='main-article-send-comment' id='main-article-send-comment'>
						<img src='".(($login_array["data"]["isLogin"])?$login_array["data"]["user"]["header"]:$config["photo-noface"])."' />
						<textarea class='main-article-comment-content' id='main-article-send-comment-content#0' placeholder='".
							(($login_array["data"]["isLogin"])?$language["main-article-comment-placeholder"]:$language["main-article-comment-nosign"])
						."' ".(($login_array["data"]["isLogin"])?"":"disabled='disabled'")."></textarea>
						<div id='main-article-comment-button' class='main-article-comment-button'>
							<button class='main-article-send-button' id='main-article-send-button' onclick=send_comment(0,0)>".$language["main-article-send-comment"]."</button>
							<button class='main-article-emoji-button' id='main-article-emoji-button'>
								<img src='".$config["photo-emoji"]."' />
								".$language["main-article-emoji"]."
							</button>
							<script>
								var send=document.getElementById('main-article-send-button');
								var emoji=document.getElementById('main-article-emoji-button');
								emoji.onclick=function()
								{
									".
									(($login_array["data"]["isLogin"])?"
										layer.msg('".$language["main-article-emoji-unabled"]."');
									"
									:"layer.msg('".$language["main-article-nosign"]."');")."
								}
							</script>
						</div>
					</div>
					<br>
					<hr>
					<div id='main-article-comment-content'>
						";
					for ($i=0;$i<count($comment_array["data"]);$i++)
					{
						$json=GetFromHTML($config["api-server-address"]."/comment/checklike.php?column=$column&id=$id&cid=".$comment_array["data"][$i]["cid"]);
						$comment_like_array=json_decode(strip_tags($json),true);
						$last=time()-$comment_array["data"][$i]["time"];
						$release=$comment_array["data"][$i]["time"];
						echo "
						<br>
						<div class='main-article-comment' id='main-article-comment#".$comment_array["data"][$i]["cid"]."'>
							<img src='".$comment_array["data"][$i]["author"]["header"]."' />
							<div class='main-article-comment-info' id='main-article-comment-info#".$comment_array["data"][$i]["cid"]."'>
								<div class='main-article-comment-author' id='main-article-comment-author#".$comment_array["data"][$i]["cid"]."'>
									<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$comment_array["data"][$i]["author"]["uid"]."'><strong id='main-article-comment-author-name#".$comment_array["data"][$i]["cid"]."'>".$comment_array["data"][$i]["author"]["name"]."</strong></a>
									<p>
										".
										(($last<60)?$language["main-article-comment-short-time"]:
										(($last<60*60)?round($last/60).$language["main-article-comment-minute"]:
										(($last<60*60*24)?round($last/60/60).$language["main-article-comment-hour"]:
										((date("Y",$release)!=date("Y",time()))?
										date("Y-m-d",$release):
										date("m-d H:i",$release)))))
										."
									</p>
								</div>
								<div class='main-article-comment-content' id='main-article-comment-content#".$comment_array["data"][$i]["cid"]."'>
									<p>
										".str_replace("\n","<br>",$comment_array["data"][$i]["content"])."
									</p>
								</div>
								<div class='main-article-comment-toolbar' id='main-article-comment-toolbar#".$comment_array["data"][$i]["cid"]."'>
									<div class='main-article-comment-left-toolbar'>
										<img id='main-article-comment-like#".$comment_array["data"][$i]["cid"]."' src='".$config["photo-comment-like"]."' style='display:".(($comment_like_array["data"]["islike"])?"block":"none")."'/>
										<img id='main-article-comment-unlike#".$comment_array["data"][$i]["cid"]."' src='".$config["photo-comment-unlike"]."' style='display:".((!$comment_like_array["data"]["islike"])?"block":"none")."'/>
										<p id='main-article-comment-like-number#".$comment_array["data"][$i]["cid"]."'>".$comment_array["data"][$i]["like"]."</p>
										<a style='margin-left:30px;' onclick=reply(".$comment_array["data"][$i]["cid"].",".$comment_array["data"][$i]["cid"].")>".$language['main-article-comment-reply']."</a>
									</div>
									<div class='main-article-comment-middle-toolbar'></div>
									<a href='".$config["server"]."/".$config["complaint-path"]."&type=comment&column=$column&id=$id&cid=".$comment_array["data"][$i]["cid"]."'>".$language['main-article-comment-record']."</a>
									<script>
										var comment_like".$comment_array["data"][$i]["cid"]."=document.getElementById('main-article-comment-like#".$comment_array["data"][$i]["cid"]."');
										var comment_unlike".$comment_array["data"][$i]["cid"]."=document.getElementById('main-article-comment-unlike#".$comment_array["data"][$i]["cid"]."');
										var comment_liked".$comment_array["data"][$i]["cid"]."=".(($comment_like_array["data"]["islike"])?1:0).";
										comment_like".$comment_array["data"][$i]["cid"].".onclick=function()
										{";
										if ($login_array["data"]["isLogin"]) echo "$.ajax({
											type:'POST',
											url:'".$config["api-server-address"]."/comment/like.php',
											data:{id:$id,column:$column,comment:".$comment_array["data"][$i]["cid"]."},
											success:function(message)
											{
												var obj=JSON.parse(strip_tags(message));
												var code=obj['code'];
												if (code==0)
												{
													if (comment_liked".$comment_array["data"][$i]["cid"]."==0)
													{
														layer.msg('".$language["main-article-comment-like-success"]."');
														comment_liked".$comment_array["data"][$i]["cid"]."=1;
														comment_like".$comment_array["data"][$i]["cid"].".style.display='block';comment_unlike".$comment_array["data"][$i]["cid"].".style.display='none';
													}
													else
													{
														layer.msg('".$language["main-article-comment-unlike-success"]."');
														comment_liked".$comment_array["data"][$i]["cid"]."=0;
														comment_like".$comment_array["data"][$i]["cid"].".style.display='none';comment_unlike".$comment_array["data"][$i]["cid"].".style.display='block';
													}
													document.getElementById('main-article-comment-like-number#".$comment_array["data"][$i]["cid"]."').innerHTML=obj['data']['like'];
												}
												else
												{
													layer.msg('".$language["main-article-comment-like-execute-failed"]."');
													console.log(obj['message']);
												}
											},
											error:function(jqXHR,textStatus,errorThrown) 
											{
												layer.alert('".$language["main-article-comment-like-error"]."');
											    layer.alert(jqXHR.responseText);
											    layer.alert(jqXHR.status);
											    layer.alert(jqXHR.readyState);
											    layer.alert(jqXHR.statusText);
											    layer.alert(textStatus);
											    layer.alert(errorThrown);
											}
										});";
										else echo "layer.msg('".$language["main-article-comment-nosign"]."');";
										echo "
										}
										comment_unlike".$comment_array["data"][$i]["cid"].".onmouseover=function()
										{
											comment_like".$comment_array["data"][$i]["cid"].".style.display='block';comment_unlike".$comment_array["data"][$i]["cid"].".style.display='none';
										}
										comment_like".$comment_array["data"][$i]["cid"].".onmouseout=function()
										{
											if (!comment_liked".$comment_array["data"][$i]["cid"].")
											{
												comment_like".$comment_array["data"][$i]["cid"].".style.display='none';comment_unlike".$comment_array["data"][$i]["cid"].".style.display='block';
											}
										}
									</script>
								</div>
								<div id='main-article-comment-reply'>";
								for ($j=0;$j<count($comment_array["data"][$i]["comment"]);$j++)
								{
									$json=GetFromHTML($config["api-server-address"]."/comment/checklike.php?column=$column&id=$id&cid=".$comment_array["data"][$i]["comment"][$j]["cid"]);
									$comment_like_array=json_decode(strip_tags($json),true);
									$last=time()-$comment_array["data"][$i]["comment"][$j]["time"];
									$release=$comment_array["data"][$i]["comment"][$j]["time"];
									echo "
									<br>
									<div class='main-article-comment' id='main-article-comment#".$comment_array["data"][$i]["comment"][$j]["cid"]."'>
										<img src='".$comment_array["data"][$i]["comment"][$j]["author"]["header"]."' />
										<div class='main-article-comment-info' id='main-article-comment-info#".$comment_array["data"][$i]["comment"][$j]["cid"]."'>
											<div class='main-article-comment-author' id='main-article-comment-author#".$comment_array["data"][$i]["comment"][$j]["cid"]."'>
												<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$comment_array["data"][$i]["comment"][$j]["author"]["uid"]."'><strong id='main-article-comment-author-name#".$comment_array["data"][$i]["comment"][$j]["cid"]."'>".$comment_array["data"][$i]["comment"][$j]["author"]["name"]."</strong></a>
												<p>
													".
													(($last<60)?$language["main-article-comment-short-time"]:
													(($last<60*60)?round($last/60).$language["main-article-comment-minute"]:
													(($last<60*60*24)?round($last/60/60).$language["main-article-comment-hour"]:
													((date("Y",$release)!=date("Y",time()))?
													date("Y-m-d",$release):
													date("m-d H:i",$release)))))
													."
												</p>
											</div>
											<div class='main-article-comment-content' id='main-article-comment-content#".$comment_array["data"][$i]["comment"][$j]["cid"]."'>
												<p>
													<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$comment_array["data"][$i]["comment"][$j]["rootauthor"]["uid"]."'>@".$comment_array["data"][$i]["comment"][$j]["rootauthor"]["name"]."</a>
													".str_replace("\n","<br>",$comment_array["data"][$i]["comment"][$j]["content"])."
												</p>
											</div>
											<div class='main-article-comment-toolbar' id='main-article-comment-toolbar#".$comment_array["data"][$i]["comment"][$j]["cid"]."'>
												<div class='main-article-comment-left-toolbar'>
													<img id='main-article-comment-like#".$comment_array["data"][$i]["comment"][$j]["cid"]."' src='".$config["photo-comment-like"]."' style='display:".(($comment_like_array["data"]["islike"])?"block":"none")."'/>
													<img id='main-article-comment-unlike#".$comment_array["data"][$i]["comment"][$j]["cid"]."' src='".$config["photo-comment-unlike"]."' style='display:".((!$comment_like_array["data"]["islike"])?"block":"none")."'/>
													<p id='main-article-comment-like-number#".$comment_array["data"][$i]["comment"][$j]["cid"]."'>".$comment_array["data"][$i]["comment"][$j]["like"]."</p>
													<a style='margin-left:30px;' onclick=reply(".$comment_array["data"][$i]["cid"].",".$comment_array["data"][$i]["comment"][$j]["cid"].")>".$language['main-article-comment-reply']."</a>
												</div>
												<div class='main-article-comment-middle-toolbar'></div>
												<a href='".$config["server"]."/".$config["complaint-path"]."&type=comment&column=$column&id=$id&cid=".$comment_array["data"][$i]["comment"][$j]["cid"]."'>".$language['main-article-comment-record']."</a>
												<script>
													var comment_like".$comment_array["data"][$i]["comment"][$j]["cid"]."=document.getElementById('main-article-comment-like#".$comment_array["data"][$i]["comment"][$j]["cid"]."');
													var comment_unlike".$comment_array["data"][$i]["comment"][$j]["cid"]."=document.getElementById('main-article-comment-unlike#".$comment_array["data"][$i]["comment"][$j]["cid"]."');
													var comment_liked".$comment_array["data"][$i]["comment"][$j]["cid"]."=".(($comment_like_array["data"]["islike"])?1:0).";
													comment_like".$comment_array["data"][$i]["comment"][$j]["cid"].".onclick=function()
													{";
													if ($login_array["data"]["isLogin"]) echo "$.ajax({
														type:'POST',
														url:'".$config["api-server-address"]."/comment/like.php',
														data:{id:$id,column:$column,comment:".$comment_array["data"][$i]["comment"][$j]["cid"]."},
														success:function(message)
														{
															var obj=JSON.parse(strip_tags(message));
															var code=obj['code'];
															if (code==0)
															{
																if (comment_liked".$comment_array["data"][$i]["comment"][$j]["cid"]."==0)
																{
																	layer.msg('".$language["main-article-comment-like-success"]."');
																	comment_liked".$comment_array["data"][$i]["comment"][$j]["cid"]."=1;
																	comment_like".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='block';comment_unlike".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='none';
																}
																else
																{
																	layer.msg('".$language["main-article-comment-unlike-success"]."');
																	comment_liked".$comment_array["data"][$i]["comment"][$j]["cid"]."=0;
																	comment_like".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='none';comment_unlike".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='block';
																}
																document.getElementById('main-article-comment-like-number#".$comment_array["data"][$i]["comment"][$j]["cid"]."').innerHTML=obj['data']['like'];
															}
															else
															{
																layer.msg('".$language["main-article-comment-like-execute-failed"]."');
																console.log(obj['message']);
															}
														},
														error:function(jqXHR,textStatus,errorThrown) 
														{
															layer.alert('".$language["main-article-comment-like-error"]."');
														    layer.alert(jqXHR.responseText);
														    layer.alert(jqXHR.status);
														    layer.alert(jqXHR.readyState);
														    layer.alert(jqXHR.statusText);
														    layer.alert(textStatus);
														    layer.alert(errorThrown);
														}
													});";
													else echo "layer.msg('".$language["main-article-comment-nosign"]."');";
													echo "
													}
													comment_unlike".$comment_array["data"][$i]["comment"][$j]["cid"].".onmouseover=function()
													{
														comment_like".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='block';comment_unlike".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='none';
													}
													comment_like".$comment_array["data"][$i]["comment"][$j]["cid"].".onmouseout=function()
													{
														if (!comment_liked".$comment_array["data"][$i]["comment"][$j]["cid"].")
														{
															comment_like".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='none';comment_unlike".$comment_array["data"][$i]["comment"][$j]["cid"].".style.display='block';
														}
													}
												</script>
											</div>
										</div>
									</div>";
								}
								echo "</div>
								<div class='main-article-send-comment' id='main-article-send-comment#".$comment_array["data"][$i]["cid"]."' style='display:none;margin-top:30px;'>
									<img src='".(($login_array["data"]["isLogin"])?$login_array["data"]["user"]["header"]:$config["photo-noface"])."' />
									<textarea class='main-article-comment-content' id='main-article-send-comment-content#".$comment_array["data"][$i]["cid"]."' placeholder='".
										(($login_array["data"]["isLogin"])?$language["main-article-comment-placeholder"]:$language["main-article-comment-nosign"])
									."' ".(($login_array["data"]["isLogin"])?"":"disabled='disabled'")."></textarea>
									<div class='main-article-comment-button' id='main-article-comment-button#".$comment_array["data"][$i]["cid"]."'>
										<button class='main-article-send-button' id='main-article-send-button#".$comment_array["data"][$i]["cid"]."' onclick=send_comment(".$comment_array["data"][$i]["root"].",".$comment_array["data"][$i]["cid"].")>".$language["main-article-send-comment"]."</button>
										<button class='main-article-emoji-button' id='main-article-emoji-button#".$comment_array["data"][$i]["cid"]."'>
											<img src='".$config["photo-emoji"]."' />
											".$language["main-article-emoji"]."
										</button>
										<script>
											var send=document.getElementById('main-article-send-button#".$comment_array["data"][$i]["cid"]."');
											var emoji=document.getElementById('main-article-emoji-button#".$comment_array["data"][$i]["cid"]."');
											emoji.onclick=function()
											{
												".
												(($login_array["data"]["isLogin"])?"
													layer.msg('".$language["main-article-emoji-unabled"]."');
												"
												:"layer.msg('".$language["main-article-nosign"]."');")."
											}
										</script>
									</div>
								</div>
								<script>
									function reply(root1,cid)
									{
										document.getElementById('main-article-send-button#'+root1).setAttribute('onclick','send_comment('+root1+','+cid+')');
										document.getElementById('main-article-send-comment#'+root1).style.display='flex';
										// console.log(document.getElementById('main-article-comment-content#'+root1));
										document.getElementById('main-article-send-comment-content#'+root1).setAttribute('placeholder',".(!$login_array["data"]["isLogin"]?"'".$language["main-article-comment-nosign"]."'":"'".$language["main-article-comment-reply-textarea"]." @'+document.getElementById('main-article-comment-author-name#'+cid).innerHTML").");
									}
								</script>
								<br>
								<hr>
							</div>
						</div>
						";
					}
					echo "
					</div>
				</div>
				<script>
					function send_comment(root,cid)
					{
						".
						(($login_array["data"]["isLogin"])?"
							var content=document.getElementById('main-article-send-comment-content#'+root).value;
							if (content=='')
							{
								layer.msg('".$language["main-article-comment-empty"]."');
								return false;
							}
							$.ajax({
								type:'POST',
								url:'".$config["api-server-address"]."/comment/send.php',
								data:{id:$id,column:$column,content:content,root:cid},
								success:function(message)
								{
									var obj=JSON.parse(strip_tags(message));
									var code=obj['code'];
									if (code==0) window.location.href='".$config["server"]."/".$config["main-path"]."&type=article&column=$column&id=$id';
									else
									{
										layer.msg('".$language["main-article-comment-execute-failed"]."');
										console.log(obj['message']);
									}
								},
								error:function(jqXHR,textStatus,errorThrown) 
								{
									layer.alert('".$language["main-article-comment-error"]."');
									layer.alert(jqXHR.responseText);
									layer.alert(jqXHR.status);
									layer.alert(jqXHR.readyState);
									layer.alert(jqXHR.statusText);
									layer.alert(textStatus);
									layer.alert(errorThrown);
								}
							});
						"
						:"layer.msg('".$language["main-article-nosign"]."');")."
					}
					function strip_tags(html) 
					{
						var div=document.createElement('div');
						div.innerHTML=html;
						return (div.textContent||div.innerText||'');
					}
					var like=document.getElementById('main-article-like');
					var unlike=document.getElementById('main-article-unlike');
					var liked=".(($like_array["data"]["islike"])?1:0)."
					var star=document.getElementById('main-article-star');
					var unstar=document.getElementById('main-article-unstar');
					var stared=".(($star_array["data"]["isstar"])?1:0)."
					var share=document.getElementById('main-article-share');
					var unshare=document.getElementById('main-article-unshare');
					like.onclick=function()
					{";
					if ($login_array["data"]["isLogin"]) echo "$.ajax({
							type:'POST',
							url:'".$config["api-server-address"]."/article/like.php',
							data:{id:$id,column:$column},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj['code'];
								if (code==0)
								{
									if (liked==0)
									{
										layer.msg('".$language["main-article-like-success"]."');
										liked=1;
										like.style.display='block';unlike.style.display='none';
									}
									else
									{
										layer.msg('".$language["main-article-unlike-success"]."');
										liked=0;
										like.style.display='none';unlike.style.display='block';
									}
									document.getElementById('main-article-like-number').innerHTML=obj['data']['like'];
								}
								else
								{
									layer.msg('".$language["main-article-like-execute-failed"]."');
									console.log(obj['message']);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert('".$language["main-article-like-error"]."');
							    layer.alert(jqXHR.responseText);
							    layer.alert(jqXHR.status);
							    layer.alert(jqXHR.readyState);
							    layer.alert(jqXHR.statusText);
							    layer.alert(textStatus);
							    layer.alert(errorThrown);
							}
						});";
						else echo "layer.msg('".$language["main-article-nosign"]."');";
					echo "
					}
					unlike.onmouseover=function()
					{
						like.style.display='block';unlike.style.display='none';
					}
					like.onmouseout=function()
					{
						if (!liked)
						{
							like.style.display='none';unlike.style.display='block';
						}
					}
					
					star.onclick=function()
					{";
					if ($login_array["data"]["isLogin"]) echo "$.ajax({
							type:'POST',
							url:'".$config["api-server-address"]."/article/star.php',
							data:{id:$id,column:$column},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								var code=obj['code'];
								if (code==0)
								{
									if (stared==0)
									{
										layer.msg('".$language["main-article-star-success"]."');
										stared=1;
										star.style.display='block';unstar.style.display='none';
									}
									else
									{
										layer.msg('".$language["main-article-unstar-success"]."');
										stared=0;
										star.style.display='none';unstar.style.display='block';
									}
									document.getElementById('main-article-star-number').innerHTML=obj['data']['star'];
								}
								else
								{
									layer.msg('".$language["main-article-star-execute-failed"]."');
									console.log(obj['message']);
								}
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert('".$language["main-article-star-error"]."');
							    layer.alert(jqXHR.responseText);
							    layer.alert(jqXHR.status);
							    layer.alert(jqXHR.readyState);
							    layer.alert(jqXHR.statusText);
							    layer.alert(textStatus);
							    layer.alert(errorThrown);
							}
						});";
						else echo "layer.msg('".$language["main-article-nosign"]."');";
						echo "
					}
					unstar.onmouseover=function()
					{
						star.style.display='block';unstar.style.display='none';
					}
					star.onmouseout=function()
					{
						if (!stared)
						{
							star.style.display='none';unstar.style.display='block';
						}
					}
					
					
					unshare.onmouseover=function()
					{
						share.style.display='block';unshare.style.display='none';
					}
					share.onmouseout=function()
					{
						share.style.display='none';unshare.style.display='block';
					}
					
					share.onclick=function()
					{
						document.getElementById('main-article-share-block').innerHTML='分享链接:<br>';
						var config={
						    title               : '".$array["data"]["name"]." - ".$config["website-title"]."',
						    description         : '',
						};
						$('#main-article-share-block').share(config);
						layer.open({
							type: 1,
							shade: false,
							title: false, //不显示标题
							content: $('#main-article-share-block'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
						});
					}
				</script>
			</div>
			<div class='main-right' id='main-right'>
				<h3>".$language["main-article-others"]."</h3>";
			for ($i=0;$i<min($config["main-column-article-number"]+1,count($column_array["data"]["article"]));$i++)
			{				$last=time()-$column_array["data"]["article"][$i]["time"];
				$release=$column_array["data"]["article"][$i]["time"];
				echo "					<div class='article-element' id='article-element$i' style='width:250px;padding-left:6px;'>						<div class='info' id='article-element-info$i' style='padding-top:10px'>
							<a style='width:230px' onclick=locate('".$config["server"]."/".$config["main-path"]."&type=article&column=".$column_array["data"]["article"][$i]["column"]."&id=".$column_array["data"]["article"][$i]["id"]."')>".$column_array["data"]["article"][$i]["name"]."</a>
							<p style='width:230px'>".$language["index-article-writer"].": @<location onclick=locate('".$config["server"]."/".$config["profile-path"]."&uid=".$column_array["data"]["article"][$i]["author"]["uid"]."')>".$column_array["data"]["article"][$i]["author"]["name"]."</location></p>
							<p style='width:230px'>".$language["index-article-like"].":".$column_array["data"]["article"][$i]["like"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["index-article-view"].":".$column_array["data"]["article"][$i]["view"]."</p>
							<p style='width:230px'>".$language["index-article-release-time"].":".
							(($last<60)?$language["index-article-short-time"]:
							(($last<60*60)?round($last/60).$language["index-article-minute"]:
							(($last<60*60*24)?round($last/60/60).$language["index-article-hour"]:
							((date("Y",$release)!=date("Y",time()))?
							date("Y-m-d",$release):
							date("m-d H:i",$release)))))."</p>
						</div>
					</div>				";			}
			echo "
				<br>
				<br>
				<a style='margin-left:15px;' href='".$config["server"]."/".$config["main-path"]."&type=column&id=$column'>".$language["main-article-more"]."</a>
			</div>
		</div>
		";
	}
	
	
	
	/* 当type为wiki时 */
	if ($_GET['type']=="wiki")
	{
		$id=$_GET["id"];
		$version=$_GET["version"];
		if (!isset($_COOKIE["wikis#$id"]))
		{
			$json=PostFromHTML($config["api-server-address"]."/wiki/watch.php",array("type"=>"wikis","id"=>$id));
			$array=json_decode(strip_tags($json),true);
			if ($array["code"]==0) echo "<script>addCookie('wikis#$id','".time()."',".$config["cookie-wikis-time"].");</script>";
			else echo "<script>console.log('[Warning]".$array["message"]."');</script>";
		}
		$json=GetFromHTML($config["api-server-address"]."/wiki/history.php?id=$id&version=$version");
		$array=json_decode(strip_tags($json),true);
		$json=GetFromHTML($config["api-server-address"]."/wiki/info.php?id=$id");
		$wikis_array=json_decode(strip_tags($json),true);
		$context=GetFromHTML($array["data"]["link"]);
		$json=GetFromHTML($config["api-server-address"]."/wiki/checklike.php?id=$id");
		$like_array=json_decode(strip_tags($json),true);
		$json=GetFromHTML($config["api-server-address"]."/wiki/checkstar.php?id=$id");
		$star_array=json_decode(strip_tags($json),true);
		$json=GetFromHTML($config["api-server-address"]."/check/login.php");
		$login_array=json_decode(strip_tags($json),true);
		$last=time()-$array["data"]["opentime"];
		$release=$array["data"]["opentime"];
		echo "<div class='wiki-main'>
			<script>document.title='".$wikis_array["data"]["name"]." - ".$config["website-title"]."';</script>
			<div class='main-left' id='main-left'>
				<h2>".$wikis_array["data"]["name"]." <strong>v1.".$array["data"]["version"]."</strong></h2>
				<div class='wiki-info' id='wiki-info'>
					<div class='wiki-info-header' id='wiki-info-header'>
						<img src='".$array["data"]["author"]["header"]."' />
					</div>
					<div class='wiki-info-context' id='wiki-info-context'>
						<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["author"]["uid"]."'><strong>".$array["data"]["author"]["name"]."</strong></a>
						<p style='font-size:14px;margin:0px;'>".
						(($last<60)?$language["main-wiki-short-time"]:
						(($last<60*60)?round($last/60).$language["main-wiki-minute"]:
						(($last<60*60*24)?round($last/60/60).$language["main-wiki-hour"]:
						((date("Y",$release)!=date("Y",time()))?
						date("Y-m-d",$release):
						date("m-d H:i",$release)))))." | ".$array["data"]["watch"]." ".$language["main-wiki-view"]." | ".$language["main-wiki-update-reason"].":".$array["data"]["reason"]."
						</p>
					</div>
				</div>
				<hr>
				<div id='wiki-context'>
					$context
				</div>
				<hr>
				<div id='wiki-oparetor-toolbar'>
					<div id='wiki-left-toolbar'>
						<img id='main-wiki-like' src='".$config["photo-wiki-like"]."' style='display:".(($like_array["data"]["islike"])?"block":"none")."'/>
						<img id='main-wiki-unlike' src='".$config["photo-wiki-unlike"]."' style='display:".((!$like_array["data"]["islike"])?"block":"none")."'/>
						<p id='main-wiki-like-number'>".$wikis_array["data"]["like"]."</p>
						<img id='main-wiki-star' src='".$config["photo-wiki-star"]."' style='display:".(($star_array["data"]["isstar"])?"block":"none")."'/>
						<img id='main-wiki-unstar' src='".$config["photo-wiki-unstar"]."' style='display:".((!$star_array["data"]["isstar"])?"block":"none")."'/>
						<p id='main-wiki-star-number'>".$wikis_array["data"]["star"]."</p>
						<img id='main-wiki-share' src='".$config["photo-wiki-share"]."' style='display:none'/>
						<img id='main-wiki-unshare' src='".$config["photo-wiki-unshare"]."' style='display:block'/>
						<p id='main-wiki-share-number'>".$language["main-wiki-share"]."</p>
						<div id='main-wiki-share-block' style='display:none;margin:3%'></div>
					</div>
					<div id='wiki-middle-toolbar'>
					</div>
					<div id='wiki-right-toolbar'>
						<a href='".$config["server"]."/".$config["complaint-path"]."&type=wiki&id=$id&version=$version'>".$language["main-wiki-complaint"]."</a>
					</div>
					<script>
					function strip_tags(html)
						{
							var div=document.createElement('div');
							div.innerHTML=html;
							return (div.textContent||div.innerText||'');
						}
						var like=document.getElementById('main-wiki-like');
						var unlike=document.getElementById('main-wiki-unlike');
						var liked=".(($like_array["data"]["islike"])?1:0)."
						var star=document.getElementById('main-wiki-star');
						var unstar=document.getElementById('main-wiki-unstar');
						var stared=".(($star_array["data"]["isstar"])?1:0)."
						var share=document.getElementById('main-wiki-share');
						var unshare=document.getElementById('main-wiki-unshare');
						like.onclick=function()
						{";
						if ($login_array["data"]["isLogin"]) echo "$.ajax({
								type:'POST',
								url:'".$config["api-server-address"]."/wiki/like.php',
								data:{id:$id},
								success:function(message)
								{
									var obj=JSON.parse(strip_tags(message));
									var code=obj['code'];
									if (code==0)
									{
										if (liked==0)
										{
											layer.msg('".$language["main-wiki-like-success"]."');
											liked=1;
											like.style.display='block';unlike.style.display='none';
										}
										else
										{
											layer.msg('".$language["main-wiki-unlike-success"]."');
											liked=0;
											like.style.display='none';unlike.style.display='block';
										}
										document.getElementById('main-wiki-like-number').innerHTML=obj['data']['like'];
									}
									else
									{
										layer.msg('".$language["main-wiki-like-execute-failed"]."');
										console.log(obj['message']);
									}
								},
								error:function(jqXHR,textStatus,errorThrown) 
								{
									layer.alert('".$language["main-wiki-like-error"]."');
								    layer.alert(jqXHR.responseText);
								    layer.alert(jqXHR.status);
								    layer.alert(jqXHR.readyState);
								    layer.alert(jqXHR.statusText);
								    layer.alert(textStatus);
								    layer.alert(errorThrown);
								}
							});";
							else echo "layer.msg('".$language["main-wiki-nosign"]."');";
						echo "
						}
						unlike.onmouseover=function()
						{
							like.style.display='block';unlike.style.display='none';
						}
						like.onmouseout=function()
						{
							if (!liked)
							{
								like.style.display='none';unlike.style.display='block';
							}
						}
						
						star.onclick=function()
						{";
						if ($login_array["data"]["isLogin"]) echo "$.ajax({
								type:'POST',
								url:'".$config["api-server-address"]."/wiki/star.php',
								data:{id:$id},
								success:function(message)
								{
									var obj=JSON.parse(strip_tags(message));
									var code=obj['code'];
									if (code==0)
									{
										if (stared==0)
										{
											layer.msg('".$language["main-wiki-star-success"]."');
											stared=1;
											star.style.display='block';unstar.style.display='none';
										}
										else
										{
											layer.msg('".$language["main-wiki-unstar-success"]."');
											stared=0;
											star.style.display='none';unstar.style.display='block';
										}
										document.getElementById('main-wiki-star-number').innerHTML=obj['data']['star'];
									}
									else
									{
										layer.msg('".$language["main-wiki-star-execute-failed"]."');
										console.log(obj['message']);
									}
								},
								error:function(jqXHR,textStatus,errorThrown) 
								{
									layer.alert('".$language["main-wiki-star-error"]."');
								    layer.alert(jqXHR.responseText);
								    layer.alert(jqXHR.status);
								    layer.alert(jqXHR.readyState);
								    layer.alert(jqXHR.statusText);
								    layer.alert(textStatus);
								    layer.alert(errorThrown);
								}
							});";
							else echo "layer.msg('".$language["main-wiki-nosign"]."');";
							echo "
						}
						unstar.onmouseover=function()
						{
							star.style.display='block';unstar.style.display='none';
						}
						star.onmouseout=function()
						{
							if (!stared)
							{
								star.style.display='none';unstar.style.display='block';
							}
						}
						
						
						unshare.onmouseover=function()
						{
							share.style.display='block';unshare.style.display='none';
						}
						share.onmouseout=function()
						{
							share.style.display='none';unshare.style.display='block';
						}
						
						share.onclick=function()
						{
							document.getElementById('main-wiki-share-block').innerHTML='分享链接:<br>';
							var config={
							    title               : '".$wikis_array["data"]["name"]." - ".$config["website-title"]."',
							    description         : '',
							};
							$('#main-wiki-share-block').share(config);
							layer.open({
								type: 1,
								shade: false,
								title: false, //不显示标题
								content: $('#main-wiki-share-block'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
							});
						}
					</script>
				</div>
			</div>
			<div class='main-right' id='main-right'>
				<h3>".$language["main-wiki-history"]."</h3>";
			for ($i=0;$i<min($config["main-wiki-version-number"]+1,count($wikis_array["data"]["history"]));$i++)
			{
				$last=time()-$wikis_array["data"]["history"][$i]["opentime"];
				$release=$wikis_array["data"]["history"][$i]["opentime"];
				echo "
					<div class='wiki-element' id='wiki-element$i' style='width:250px;'>
						<div class='info' id='wiki-element-info$i' style='padding-top:10px'>
							<a style='width:230px' onclick=locate('".$config["server"]."/".$config["main-path"]."&type=wiki&id=".$wikis_array["data"]["history"][$i]["wikiid"]."&version=".$wikis_array["data"]["history"][$i]["version"]."')>".$wikis_array["data"]["name"]." v1.".$wikis_array["data"]["history"][$i]["version"]."</a>
							<p style='width:230px'>".$language["main-history-author"].": @<location onclick=locate('".$config["server"]."/".$config["profile-path"]."&uid=".$wikis_array["data"]["history"][$i]["author"]["uid"]."')>".$wikis_array["data"]["history"][$i]["author"]["name"]."</location></p>
							<p style='width:230px'>".$language["main-history-view"].":".$wikis_array["data"]["history"][$i]["watch"]."</p>
							<p style='width:230px'>".$language["main-history-release-time"].":".
							(($last<60)?$language["main-history-short-time"]:
							(($last<60*60)?round($last/60).$language["main-history-minute"]:
							(($last<60*60*24)?round($last/60/60).$language["main-history-hour"]:
							((date("Y",$release)!=date("Y",time()))?
							date("Y-m-d",$release):
							date("m-d H:i",$release)))))."</p>
						</div>
					</div>
				";
			}
			echo "
				<br>
				<br>
				<a style='margin-left:15px;' href='".$config["server"]."/".$config["main-path"]."&type=wikis&id=$id'>".$language["main-wiki-more-version"]."</a>
			</div>
		</div>
		";
	}
	
	
	
	/* 当type为notice时 */
	if ($_GET['type']=="notice")
	{
		$id=$_GET["id"];
		if (!isset($_COOKIE["notice#$id"]))
		{
			$json=PostFromHTML($config["api-server-address"]."/notice/watch.php",array("id"=>$id));
			$array=json_decode(strip_tags($json),true);
			if ($array["code"]==0) echo "<script>addCookie('notice#$id','".time()."',".$config["cookie-notice-time"].");</script>";
			else echo "<script>console.log('[Warning]".$array["message"]."');</script>";
		}
		$json=GetFromHTML($config["api-server-address"]."/notice/info.php?id=$id");
		$array=json_decode(strip_tags($json),true);
		$context=GetFromHTML($array["data"]["link"]);
		$last=time()-$array["data"]["release"];
		$release=$array["data"]["release"];
		echo "<div class='notice-main'>
			<script>document.title='".$array["data"]["name"]." - ".$config["website-title"]."';</script>
			<div class='main-left' id='main-left'>
				<h2>".$array["data"]["name"]."</h2>
				<div class='notice-info' id='notice-info'>
					<div class='notice-info-header' id='notice-info-header'>
						<img src='".$array["data"]["author"]["header"]."' />
					</div>
					<div class='notice-info-context' id='notice-info-context'>
						<a href='".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["author"]["uid"]."'><strong>".$array["data"]["author"]["name"]."</strong></a>
						<p style='font-size:14px;margin:0px;'>".
						(($last<60)?$language["main-notice-short-time"]:
						(($last<60*60)?round($last/60).$language["main-notice-minute"]:
						(($last<60*60*24)?round($last/60/60).$language["main-notice-hour"]:
						((date("Y",$release)!=date("Y",time()))?
						date("Y-m-d",$release):
						date("m-d H:i",$release)))))." | ".$array["data"]["view"]." ".$language["main-notice-view"]."
						</p>
					</div>
				</div>
				<hr>
				<div id='notice-context'>
					$context
				</div>
			</div>
			</div>
		</div>
		";
	}
?>