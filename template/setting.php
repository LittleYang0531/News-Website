<h2 style="width:100%;"><center><?php echo $language["setting-intitle"];?></center></h2>
<div id="setting-toolbar">
	<script>
		function addCookie(cname,cvalue,exdays)
		{
			var d=new Date();
			d.setTime(d.getTime()+(exdays*24*60*60*1000));
			var expires="expires="+d.toGMTString();
			document.cookie=cname+"="+cvalue+";"+expires;
		}
		function clearCookie(name)
		{ 
		    addCookie(name,"",-1); 
		}
	</script>
	<?php
		$authority=PostFromHTML($config["api-server-address"]."/check/login2.php",NULL);
		$info=json_decode(strip_tags($authority),true);
		if (!$info["data"]["isLogin"])
		{
			echo "<script>alert('".$language["setting-nologin"]."');window.location.href='".$config["server"]."/".$config["index-path"]."';</script>";
			exit;
		}
		$type=($_GET["type"]=="")?"base":$_GET["type"];
	?>
	<button style="display:inline-block" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["setting-path"]."&type=base\"";?>)><?php echo $language["setting-base"];?></button>
	<button style="display:inline-block" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["setting-path"]."&type=password\"";?>)><?php echo $language["setting-password"];?></button>
	<button style="display:<?php echo $info["data"]["user"]["authority"]==1?"inline-block":"none";?>" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["admin-path"]."\"";?>)><?php echo $language["setting-admin"];?></button>
	<button style="display:inline-block" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["profile-path"]."&uid=".$info["data"]["user"]["uid"]."\"";?>)><?php echo $language["setting-profile"];?></button>
	<button style="display:inline-block" onclick=exit()><?php echo $language["setting-exit"];?></button>
	<script>
		function strip_tags(html)
		{
		    var div=document.createElement("div");
		    div.innerHTML=html;
		    return (div.textContent||div.innerText||"");
		}
		function exit()
		{
			clearCookie('DedeUserId');clearCookie('DedeUserId__ckMd5');clearCookie('CSRF');clearCookie('SESSDATA');
			alert(<?php echo "\"".$language["setting-exit-success"]."\""?>);window.location.href=<?php echo "\"".$config["server"]."/".$config["index-path"]."\""?>;
		}
	</script>
</div>
<div id="setting-main">
	<div id="setting-main-base" style="display:<?php echo $type=="base"?"block":"none"?>">
		<h3><?php echo $language["setting-favorite"];?></h3>
		<div id="setting-main-base-favorite">
			<form class="setting-upload-picture" action="<?php echo $config["api-server-address"]."/account/header.php"?>" method="POST" enctype='multipart/form-data'>
				<img src="<?php echo $info["data"]["user"]["header"]?>"/>
				<div class="setting-upload-operator"> 
					<label for="setting-header"><?php echo $language["setting-change-header"]?></label>
					<input type="file" class="setting-upload-file" name="setting-header" id="setting-header" accept="image/jpeg"><br/>
					<input class="submit" type="submit" name="setting-header-submit" id="setting-header-submit" value="<?php echo $language["setting-submit"]?>" >
				</div>
			</form>
			<form class="setting-upload-picture" action="<?php echo $config["api-server-address"]."/account/background.php"?>" method="POST" enctype='multipart/form-data'>
				<img src="<?php echo $info["data"]["user"]["background"]?>"/>
				<div class="setting-upload-operator"> 
					<label for="setting-background"><?php echo $language["setting-change-background"]?></label>
					<input type="file" class="setting-upload-file" name="setting-background" id="setting-background" accept="image/jpeg"><br/>
					<input class="submit" type="submit" name="setting-background-submit" id="setting-background-submit" value="<?php echo $language["setting-submit"]?>" >
				</div>
			</form>
		</div>
		<p class="input-p"><?php echo $language["setting-main-sign"]?>:&nbsp;<input id="setting-sign" type="text" class="input" placeholder="<?php echo $language["setting-main-sign-placeholder"]?>" value="<?php echo ($info["data"]["user"]["sign"]=="empty")?"":$info["data"]["user"]["sign"]?>"/></p>
		<h3><?php echo $language["setting-user-info"]?></h3>
		<p class="input-p"><?php echo $language["setting-main-realname"]?>:&nbsp;<input id="setting-realname" type="text" class="input" placeholder="<?php echo $language["setting-main-realname-placeholder"]?>" value="<?php echo $info["data"]["user"]["realname"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-main-school"]?>:&nbsp;<input id="setting-school" type="text" class="input" placeholder="<?php echo $language["setting-main-school-placeholder"]?>" value="<?php echo $info["data"]["user"]["school"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-main-grade"]?>:&nbsp;<input id="setting-grade" type="text" class="input" placeholder="<?php echo $language["setting-main-grade-placeholder"]?>" value="<?php echo $info["data"]["user"]["grade"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-main-class"]?>:&nbsp;<input id="setting-class" type="text" class="input" placeholder="<?php echo $language["setting-main-class-placeholder"]?>" value="<?php echo $info["data"]["user"]["class"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-main-birth"]?>:&nbsp;<input id="setting-birth" type="text" class="input" placeholder="<?php echo $language["setting-main-birth-placeholder"]?>" value="<?php echo $info["data"]["user"]["birth"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-main-bilibili"]?>:&nbsp;<input id="setting-bilibili" type="text" class="input" placeholder="<?php echo $language["setting-main-bilibili-placeholder"]?>" value="<?php echo $info["data"]["user"]["bili"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-main-qq"]?>:&nbsp;<input id="setting-qq" type="text" class="input" placeholder="<?php echo $language["setting-main-qq-placeholder"]?>" value="<?php echo $info["data"]["user"]["QQ"]?>"/></p>
		<center><button class="submit" style="margin-top:20px;" onclick=submit_base()><?php echo $language["setting-submit"]?></button></center>
	</div>
	<div id="setting-main-password" style="display:<?php echo $type=="password"?"block":"none"?>">
		<h3><?php echo $language["setting-password-check"];?></h3>
		<p class="input-p"><?php echo $language["setting-password-old"]?>:&nbsp;<input id="setting-password-old" type="password" class="input" placeholder="<?php echo $language["setting-password-old-placeholder"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-password-old-email"]?>:&nbsp;<input id="setting-password-old-email" type="text" class="input" placeholder="<?php echo $language["setting-password-old-email-placeholder"]?>"/>
			&nbsp;<select id="setting-password-old-email-suffix" class="select"><?php for ($i=0;$i<count($config["email-suffix"]);$i++) echo "<option value='@".$config["email-suffix"][$i]."'>@".$config["email-suffix"][$i]."</option>"?></select>
		</p>
		<p class="input-p"><?php echo $language["setting-password-old-email-check"]?>:&nbsp;<input id="setting-password-old-email-check" type="text" class="input" placeholder="<?php echo $language["setting-password-old-email-check-placeholder"]?>"/>&nbsp;<a onclick=send_captcha1()><?php echo $language["setting-send-captcha"];?></a></p>
		<h3><?php echo $language["setting-password-change"];?></h3>
		<p class="input-p"><?php echo $language["setting-password-new"]?>:&nbsp;<input id="setting-password-new" type="password" class="input" placeholder="<?php echo $language["setting-password-new-placeholder"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-password-repeat"]?>:&nbsp;<input id="setting-password-repeat" type="password" class="input" placeholder="<?php echo $language["setting-password-repeat-placeholder"]?>"/></p>
		<p class="input-p"><?php echo $language["setting-password-new-email"]?>:&nbsp;<input id="setting-password-new-email" type="text" class="input" placeholder="<?php echo $language["setting-password-new-email-placeholder"]?>"/>
			&nbsp;<select id="setting-password-new-email-suffix" class="select"><?php for ($i=0;$i<count($config["email-suffix"]);$i++) echo "<option value='@".$config["email-suffix"][$i]."'>@".$config["email-suffix"][$i]."</option>"?></select>
		</p>
		<p class="input-p"><?php echo $language["setting-password-new-email-check"]?>:&nbsp;<input id="setting-password-new-email-check" type="text" class="input" placeholder="<?php echo $language["setting-password-new-email-check-placeholder"]?>"/>&nbsp;<a onclick=send_captcha2()><?php echo $language["setting-send-captcha"];?></a></p>
		<center><button class="submit" style="margin-top:20px;" onclick=submit_password()><?php echo $language["setting-submit"]?></button></center>
	</div>
	<script>
		var challenge1="",challenge2="";
		function send_captcha1() 
		{
			var email=document.getElementById("setting-password-old-email").value;
			var suffix=document.getElementById("setting-password-old-email-suffix").value;
			if (email=="") {
				layer.msg(<?php echo "\"".$language["setting-password-email-empty"]."\""?>);
				return false;
			}
			$.ajax({
				type:"POST",
				url:<?php echo "\"".$config["api-server-address"]."/account/verify.php"."\"";?>,
				data:{email:email+suffix,check:1},
				success:function(message)
				{
					var obj=JSON.parse(strip_tags(message));
					var code=obj["code"];
					if (code==0)
					{
						alert(<?php echo "\"".$language["setting-password-send-captcha-success"]."\""?>);
						challenge1=obj["data"]["challenge"];
					}
					else
					{
						// alert(<?php echo "\"".$language["setting-password-send-captcha-failed"]."\""?>);
						layer.msg(obj["message"])
					}
				},
				error:function(jqXHR,textStatus,errorThrown) 
				{
					layer.alert(<?php echo "\"".$language["setting-password-send-captcha-error"]."\""?>)
				    console.log(jqXHR.responseText);
				    console.log(jqXHR.status);
					console.log(jqXHR.readyState);
				    console.log(jqXHR.statusText);
				    console.log(textStatus);
				    console.log(errorThrown);
				}
			});
		}
		function send_captcha2()
		{
			var email=document.getElementById("setting-password-new-email").value;
			var suffix=document.getElementById("setting-password-new-email-suffix").value;
			if (email=="") {
				layer.msg(<?php echo "\"".$language["setting-password-email-empty"]."\""?>);
				return false;
			}
			$.ajax({
				type:"POST",
				url:<?php echo "\"".$config["api-server-address"]."/account/verify.php"."\"";?>,
				data:{email:email+suffix,check:0},
				success:function(message)
				{
					var obj=JSON.parse(strip_tags(message));
					var code=obj["code"];
					if (code==0)
					{
						alert(<?php echo "\"".$language["setting-password-send-captcha-success"]."\""?>);
						challenge2=obj["data"]["challenge"];
					}
					else
					{
						// alert(<?php echo "\"".$language["setting-password-send-captcha-failed"]."\""?>);
						layer.msg(obj["message"])
					}
				},
				error:function(jqXHR,textStatus,errorThrown) 
				{
					layer.alert(<?php echo "\"".$language["setting-password-send-captcha-error"]."\""?>)
				    console.log(jqXHR.responseText);
				    console.log(jqXHR.status);
					console.log(jqXHR.readyState);
				    console.log(jqXHR.statusText);
				    console.log(textStatus);
				    console.log(errorThrown);
				}
			});
		}
		function submit_password()
		{
			var old_password=document.getElementById("setting-password-old").value;
			var old_email=document.getElementById("setting-password-old-email").value;
			if (old_email!="") old_email+=document.getElementById("setting-password-old-email-suffix").value;
			var new_password=document.getElementById("setting-password-new").value;
			var new_password_repeat=document.getElementById("setting-password-repeat").value;
			var new_email=document.getElementById("setting-password-new-email").value;
			if (new_email!="") new_email+=document.getElementById("setting-password-new-email-suffix").value;
			var old_email_code=document.getElementById("setting-password-old-email-check").value;
			var new_email_code=document.getElementById("setting-password-new-email-check").value;
			var public_key="";
			if (old_password=="") layer.alert(<?php echo "\"".$language["setting-password-old-empty"]."\""?>);
			else if (old_email=="") layer.alert(<?php echo "\"".$language["setting-password-old-email-empty"]."\""?>);
			else if (old_email_code=="") layer.alert(<?php echo "\"".$language["setting-password-old-email-check-empty"]."\""?>);
			else if (challenge1=="") layer.alert(<?php echo "\"".$language["setting-password-not-verify-old-email"]."\""?>);
			else if (new_password!=new_password_repeat) layer.alert(<?php echo "\"".$language["setting-password-new-not-same"]."\""?>);
			else if (new_email!=""&&challenge2=="") layer.alert(<?php echo "\"".$language["setting-password-not-verify-new-email"]."\""?>);
			else 
			{
				$.ajax({
					type:"GET",
					url:<?php echo "\"".$config["api-server-address"]."/login/public.php"."\"";?>,
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						public_key=obj["data"]["public"];
						// console.log(public_key);
						var encrypt=new JSEncrypt();
						encrypt.setPublicKey(public_key);
						old_password=encrypt.encrypt(old_password);
						new_password=encrypt.encrypt(new_password);
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/account/password.php"."\"";?>,
							data:
							{
								old:old_password,
								oldemail:old_email,
								oldchallenge:challenge1,
								oldecode:old_email_code,
								new:new_password,
								newemail:new_email,
								newchallenge:challenge2,
								newecode:new_email_code
							},
							success:function(message)
							{
								var obj=JSON.parse(strip_tags(message));
								code=obj["code"];
								if (code==0) 
								{
									alert(<?php echo "\"".$language["setting-password-change-success"]."\""?>);
									window.location.href=<?php echo "\"".$config["server"]."/".$config["index-path"]."\""?>;
								}
								else layer.msg(obj["message"]);
							},
							error:function(jqXHR,textStatus,errorThrown)
							{
								layer.alert(<?php echo "\"".$language["setting-password-change-error"]."\""?>)
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
								console.log(jqXHR.readyState);
							    console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					}
				});
			}
		}
		function submit_base()
		{
			var sign=document.getElementById("setting-sign").value;
			var realname=document.getElementById("setting-realname").value;
			var school=document.getElementById("setting-school").value;
			var grade=document.getElementById("setting-grade").value;
			var aclass=document.getElementById("setting-class").value;
			var birth=document.getElementById("setting-birth").value;
			var bilibili=document.getElementById("setting-bilibili").value;
			var qq=document.getElementById("setting-qq").value;
			$.ajax({
				type:"POST",
				url:<?php echo "\"".$config["api-server-address"]."/account/infomation.php"."\"";?>,
				data:{sign:sign,realname:realname,school:school,grade:grade,"class":aclass,birth:birth,bilibili:bilibili,qq:qq},
				success:function(message)
				{
					var obj=JSON.parse(strip_tags(message));
					var code=obj["code"];
					if (code==0) alert(<?php echo "\"".$language["setting-base-change-success"]."\""?>);
					else
					{
						alert(<?php echo "\"".$language["setting-base-change-failed"]."\""?>);
						console.log(obj["message"])
					}
				},
				error:function(jqXHR,textStatus,errorThrown) 
				{
					layer.alert(<?php echo "\"".$language["setting-base-change-error"]."\""?>)
				    console.log(jqXHR.responseText);
				    console.log(jqXHR.status);
				    console.log(jqXHR.readyState);
				    console.log(jqXHR.statusText);
				    console.log(textStatus);
				    console.log(errorThrown);
				}
			});
		}
	</script>
</div>