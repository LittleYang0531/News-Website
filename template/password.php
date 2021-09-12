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
				alert(<?php echo "\"".$language["change-password-login-account-logined"]."\""?>);
				// setTimeout("ReturnPage()",3000);
				ReturnPage();
			}
		},
		error:function(jqXHR,textStatus,errorThrown) 
		{
			layer.alert(<?php echo "\"".$language["change-password-check-login-error"]."\""?>)
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
<div>
	<div style="display:inline-block" id="website-logo">
		<div class="login-icon" id="login-icon" style="display:inline-block"></div>
		<p id="website-name" style="display:inline-block"><?php echo $config["website-name"];?></p>
	</div>
	<h2><?php echo $language["change-password-intitle"];?></h2>
	<p class="input-p"><?php echo $language["change-password-email"]?>:&nbsp;<input id="change-password-email" type="text" class="input" placeholder="<?php echo $language["change-password-email-placeholder"]?>"/>
		&nbsp;<select id="change-password-email-suffix" class="select"><?php for ($i=0;$i<count($config["email-suffix"]);$i++) echo "<option value='@".$config["email-suffix"][$i]."'>@".$config["email-suffix"][$i]."</option>"?></select>
	</p>
	<p class="input-p"><?php echo $language["change-password-email-check"]?>:&nbsp;<input id="change-password-email-check" type="text" class="input" placeholder="<?php echo $language["change-password-email-check-placeholder"]?>"/>&nbsp;<a onclick=send_captcha()><?php echo $language["change-password-send-captcha"];?></a></p>
	<p class="input-p"><?php echo $language["change-password-new"]?>:&nbsp;<input id="change-password-new" type="password" class="input" placeholder="<?php echo $language["change-password-new-placeholder"]?>"/></p>
	<p class="input-p"><?php echo $language["change-password-repeat"]?>:&nbsp;<input id="change-password-repeat" type="password" class="input" placeholder="<?php echo $language["change-password-repeat-placeholder"]?>"/></p>
	<center><button class="submit" onclick=submit_password()><?php echo $language["change-password-submit"]?></button></center>
</div>
<script>
	var challenge="";
	function send_captcha() 
	{
		var email=document.getElementById("change-password-email").value;
		var suffix=document.getElementById("change-password-email-suffix").value;
		if (email=="") {
			layer.msg(<?php echo "\"".$language["change-password-email-empty"]."\""?>);
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
					alert(<?php echo "\"".$language["change-password-send-captcha-success"]."\""?>);
					challenge=obj["data"]["challenge"];
				}
				else
				{
					// alert(<?php echo "\"".$language["change-password-send-captcha-failed"]."\""?>);
					layer.msg(obj["message"])
				}
			},
			error:function(jqXHR,textStatus,errorThrown) 
			{
				layer.alert(<?php echo "\"".$language["change-password-send-captcha-error"]."\""?>)
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
		var email=document.getElementById("change-password-email").value;
		if (email=="")
		{
			layer.alert(<?php echo "\"".$language["change-password-email-empty"]."\""?>);
			return;
		}
		email+=document.getElementById("change-password-email-suffix").value;
		var password=document.getElementById("change-password-new").value;
		var password_repeat=document.getElementById("change-password-repeat").value;
		var email_code=document.getElementById("change-password-email-check").value;
		var public_key="";
		if (email_code=="") layer.alert(<?php echo "\"".$language["change-password-email-check-empty"]."\""?>);
		else if (challenge=="") layer.alert(<?php echo "\"".$language["change-password-not-verify-email"]."\""?>);
		else if (password!=password_repeat) layer.alert(<?php echo "\"".$language["change-password-new-not-same"]."\""?>);
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
						url:<?php echo "\"".$config["api-server-address"]."/account/password2.php"."\"";?>,
						data:
						{
							email:email,
							challenge:challenge,
							ecode:email_code,
							password:password,
						},
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							code=obj["code"];
							if (code==0) 
							{
								alert(<?php echo "\"".$language["change-password-change-success"]."\""?>);
								window.location.href=<?php echo "\"".$config["server"]."/".$config["index-path"]."\""?>;
							}
							else layer.msg(obj["message"]);
						},
						error:function(jqXHR,textStatus,errorThrown)
						{
							layer.alert(<?php echo "\"".$language["change-password-change-error"]."\""?>)
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
</script>