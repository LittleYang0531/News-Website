<script>
	$.ajax({
		type:"POST",
		url:<?php echo "\"".$config["api-server-address"]."/check/login.php"."\"";?>,
		success:function(message)
		{
			var obj=JSON.parse(strip_tags(message));
			var code=obj["data"]["isLogin"];
			if (code==1)
			{
				alert(<?php echo "\"".$language["login-account-logined"]."\""?>);
				// setTimeout("ReturnPage()",3000);
				ReturnPage();
			}
		},
		error:function(jqXHR,textStatus,errorThrown) 
		{
			layer.alert(<?php echo "\"".$language["login-check-login-error"]."\""?>)
		    console.log(jqXHR.responseText);
		    console.log(jqXHR.status);
		    console.log(jqXHR.readyState);
		    console.log(jqXHR.statusText);
		    console.log(textStatus);
		    console.log(errorThrown);
		}
	});
</script>
<style>
	#login-icon{background-image:url("<?php echo $config["sign-addr"]?>");}
</style>
<div style="display:inline-block" id="website-logo">
	<div class="login-icon" id="login-icon" style="display:inline-block"></div>
	<p id="website-name" style="display:inline-block"><?php echo $config["website-name"];?></p>
</div>
<h2 style="width:100%;"><?php echo $language["login-intitle"];?></h2>
<button id="username-botton" onclick=change(0)><?php echo $language["login-username-login"];?></button>&nbsp;&nbsp;<button id="captcha-botton" onclick=change(1)><?php echo $language["login-captcha-login"];?></button>
<div id="username">
	<p class="input-p" id="username-p"><?php echo $language["login-username"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["login-username-description"];?>" class="input" id="username-input"></p>
	<p class="input-p" id="password-p"><?php echo $language["login-password"];?>:&nbsp;<input type="password" placeholder="<?php echo $language["login-password-description"];?>" class="input" id="password-input"></p>
	<div class="superlink"><a href="<?php echo $config["server"]."/".$config["change-password-path"]?>"><?php echo $language["login-forget-password"]?></a></div>
	<div class="superlink"><a href="<?php echo $config["server"]."/".$config["register-path"]."&return=".$_GET["return"]?>"><?php echo $language["login-register"];?></a></div>
</div>
<div id="captcha" style="display:none">
	<p class="input-p" id="email-p"><?php echo $language["login-email"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["login-email-description"];?>" class="input" id="email-input">&nbsp;
		<select id="email-suffix" class="select"><?php for ($i=0;$i<count($config["email-suffix"]);$i++) echo "<option value='@".$config["email-suffix"][$i]."'>@".$config["email-suffix"][$i]."</option>"?></select>
	</p>
	<p class="input-p" id="captcha-p"><?php echo $language["login-captcha"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["login-captcha-description"];?>" class="input" id="captcha-input">&nbsp;
		<a onclick=send_captcha()><?php echo $language["login-send-captcha"];?></a>
	</p>
	<div class="superlink"><a href="<?php echo $config["server"]."/".$config["register-path"]."&return=".$_GET["return"]?>"><?php echo $language["login-register"];?></a></div>
</div>
<br>
<button id="submit" onclick=submit()><strong><?php echo $language["login-submit"];?></strong></button>
<script>
	var page=0,challenge="";
	function ReturnPage()
	{
		window.location.href=<?php 
			if ($_GET["return"]=="") echo "\"".$config["server"]."/".$config["index-path"]."\"";
			else echo "\"".$_GET["return"]."\"";
		?>;
		// window.history.back(-1);
	}
	function change(x)
	{
		page=x;
		if (x==1) 
		{
			document.getElementById("captcha").style.display="block";
			document.getElementById("username").style.display="none";
		}
		else 
		{
			document.getElementById("captcha").style.display="none";
			document.getElementById("username").style.display="block";
		}
	}
	function strip_tags(html) 
	{
	    var div=document.createElement("div");
	    div.innerHTML=html;
	    return (div.textContent||div.innerText||"");
	}
	function send_captcha()
	{
		var email=document.getElementById('email-input').value;
		if (email!="")
		{
			var full=email+document.getElementById("email-suffix").value;
			$.ajax({
				type:"POST",
				url:<?php echo "\"".$config["api-server-address"]."/login/captcha.php"."\"";?>,
				data:{purpose:"GET",email:full},
				success:function(message)
				{
					var obj=JSON.parse(strip_tags(message));
					var code=obj["code"];
					if (code==0)
					{
						layer.alert(<?php echo "\"".$language["login-captcha-success"]."\"";?>);
						challenge=obj["data"]["challenge"];
					}
					else console.log(obj["message"]);
					if (code==-102) layer.alert(<?php echo "\"".$language["login-account-banned"]."\"";?>);
					if (code==-400) layer.alert(<?php echo "\"".$language["login-request-error"]."\"";?>);
					if (code==-404) layer.alert(<?php echo "\"".$language["login-nonexist-useragent"]."\"";?>);
					if (code==-405) layer.alert(<?php echo "\"".$language["login-method-error"]."\"";?>);
					if (code==-500) layer.alert(<?php echo "\"".$language["login-connect-database-error"]."\"";?>);
					if (code==-626) layer.alert(<?php echo "\"".$language["login-nonexist-account"]."\"";?>);
					if (code==-652) layer.alert(<?php echo "\"".$language["login-account-logined"]."\"";?>);
					if (code==-653) layer.alert(<?php echo "\"".$language["login-empty-email"]."\"";?>);
				},
				error:function(jqXHR,textStatus,errorThrown) 
				{
					layer.alert(<?php echo "\"".$language["login-captcha-get-error"]."\""?>);
				    console.log(jqXHR.responseText);
				    console.log(jqXHR.status);
				    console.log(jqXHR.readyState);
				    console.log(jqXHR.statusText);
				    console.log(textStatus);
				    console.log(errorThrown);
				}
			});
		}
		else layer.alert(<?php echo "\"".$language["login-email-error"]."\""?>);
	}
	function addCookie(cname,cvalue,exdays)
	{
		var d=new Date();
		d.setTime(d.getTime()+(exdays*24*60*60*1000));
		var expires="expires="+d.toGMTString();
		document.cookie=cname+"="+cvalue+";"+expires;
	}
	function submit()
	{
		if (page==0)
		{
			var username=document.getElementById("username-input").value;
			var password=document.getElementById("password-input").value;
			var public_key="";
			if (username=="") layer.alert(<?php echo "\"".$language["login-username-error"]."\""?>);
			else if (password=="") layer.alert(<?php echo "\"".$language["login-password-error"]."\""?>);
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
						password=encrypt.encrypt(password);
						$.ajax({
							type:"POST",
							url:<?php echo "\"".$config["api-server-address"]."/login/password.php"."\"";?>,
							data:{username:username,password:password},
							success:function(message)
							{
								// console.log(message);
								var obj=JSON.parse(strip_tags(message));
								var code=obj["code"];
								if (code==0)
								{
									alert(<?php echo "\"".$language["login-password-success"]."\"";?>);
									addCookie("CSRF",obj["data"]["CSRF"],30);
									addCookie("SESSDATA",obj["data"]["SESSDATA"],30);
									addCookie("DedeUserId",obj["data"]["uid"],30);
									addCookie("DedeUserId__ckMd5",obj["data"]["DedeUserId__ckMd5"],30);
									ReturnPage();
								}
								else console.log(obj["message"]);
								if (code==-102) layer.alert(<?php echo "\"".$language["login-account-banned"]."\"";?>);
								if (code==-400) layer.alert(<?php echo "\"".$language["login-request-error"]."\"";?>);
								if (code==-404) layer.alert(<?php echo "\"".$language["login-nonexist-useragent"]."\"";?>);
								if (code==-405) layer.alert(<?php echo "\"".$language["login-method-error"]."\"";?>);
								if (code==-500) layer.alert(<?php echo "\"".$language["login-connect-database-error"]."\"";?>);
								if (code==-629) layer.alert(<?php echo "\"".$language["login-error-username"]."\"";?>);
								if (code==-652) layer.alert(<?php echo "\"".$language["login-account-logined"]."\"";?>);
								if (code==-653) layer.alert(<?php echo "\"".$language["login-empty-username"]."\"";?>);
							},
							error:function(jqXHR,textStatus,errorThrown) 
							{
								layer.alert(<?php echo "\"".$language["login-password-post-error"]."\""?>);
							    console.log(jqXHR.responseText);
							    console.log(jqXHR.status);
							    console.log(jqXHR.readyState);
							    console.log(jqXHR.statusText);
							    console.log(textStatus);
							    console.log(errorThrown);
							}
						});
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["login-public-get-error"]."\""?>);
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
		else 
		{
			var email=document.getElementById("email-input").value;
			var captcha=document.getElementById("captcha-input").value;
			if (email=="") layer.alert(<?php echo "\"".$language["login-email-error"]."\""?>);
			else if (captcha=="") layer.alert(<?php echo "\"".$language["login-captcha-error"]."\""?>);
			else 
			{
				var full=email+document.getElementById("email-suffix").value;
				$.ajax({
					type:"POST",
					url:<?php echo "\"".$config["api-server-address"]."/login/captcha.php"."\"";?>,
					data:{purpose:"VERIFY",email:full,challenge:challenge,key:captcha},
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							alert(<?php echo "\"".$language["login-verify-success"]."\"";?>);
							addCookie("CSRF",obj["data"]["CSRF"],30);
							addCookie("SESSDATA",obj["data"]["SESSDATA"],30);
							addCookie("DedeUserId",obj["data"]["uid"],30);
							addCookie("DedeUserId__ckMd5",obj["data"]["DedeUserId__ckMd5"],30);
							ReturnPage();
						}
						else console.log(obj["message"]);
						if (code==-102) layer.alert(<?php echo "\"".$language["login-account-banned"]."\"";?>);
						if (code==-400) layer.alert(<?php echo "\"".$language["login-request-error"]."\"";?>);
						if (code==-404) layer.alert(<?php echo "\"".$language["login-nonexist-variable"]."\"";?>);
						if (code==-405) layer.alert(<?php echo "\"".$language["login-method-error"]."\"";?>);
						if (code==-500) layer.alert(<?php echo "\"".$language["login-connect-database-error"]."\"";?>);
						if (code==-626) layer.alert(<?php echo "\"".$language["login-nonexist-account"]."\"";?>);
						if (code==-629) layer.alert(<?php echo "\"".$language["login-nonexist-key"]."\"";?>);
						if (code==-652) layer.alert(<?php echo "\"".$language["login-account-logined"]."\"";?>);
						if (code==-653) layer.alert(<?php echo "\"".$language["login-empty-variable"]."\"";?>);
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["login-password-post-error"]."\""?>);
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
	}
	$(document).keypress(function(event){  
	    var keynum=(event.keyCode?event.keyCode:event.which);  
	    if(keynum=='13'){  
	         document.getElementById("submit").click();
	    }  
	});
</script>