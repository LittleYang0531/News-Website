<div class="register-main" id="register-main">
	<script>
		function ReturnPage()
		{
			window.location.href=<?php 
				if ($_GET["return"]=="") echo "\"".$config["server"]."/".$config["index-path"]."\"";
				else echo "\"".$_GET["return"]."\"";
			?>;
			// window.history.back(-1);
		}
		function strip_tags(html) 
		{
			var div=document.createElement("div");
			div.innerHTML=html;
			return (div.textContent||div.innerText||"");
		}
		$.ajax({
			type:"POST",
			url:<?php echo "\"".$config["api-server-address"]."/check/login.php"."\"";?>,
			success:function(message)
			{
				var obj=JSON.parse(strip_tags(message));
				var code=obj["data"]["isLogin"];
				if (code==1)
				{
					alert(<?php echo "\"".$language["register-account-logined"]."\""?>);
					// setTimeout("ReturnPage()",3000);
					ReturnPage();
				}
			},
			error:function(jqXHR,textStatus,errorThrown) 
			{
				layer.alert(<?php echo "\"".$language["register-check-login-error"]."\""?>)
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
	<h2 style="width:100%;"><?php echo $language["register-intitle"];?></h2>
	<div id="infomation">
		<p class="input-p" id="username-p"><?php echo $language["register-username"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["register-username-description"];?>" class="input" id="username-input"></p>
		<p class="input-p" id="password-p"><?php echo $language["register-password"];?>:&nbsp;<input type="password" placeholder="<?php echo $language["register-password-description"];?>" class="input" id="password-input"></p>
		<p class="input-p" id="password2-p"><?php echo $language["register-check-password"];?>:&nbsp;<input type="password" placeholder="<?php echo $language["register-check-password-description"];?>" class="input" id="check-password-input"></p>
		<p class="input-p" id="email-p"><?php echo $language["register-email"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["register-email-description"];?>" class="input" id="email-input">&nbsp;
			<select id="email-suffix" class="select"><?php for ($i=0;$i<count($config["email-suffix"]);$i++) echo "<option value='@".$config["email-suffix"][$i]."'>@".$config["email-suffix"][$i]."</option>"?></select>
		</p>
		<div class="superlink"><a href="<?php echo $config["server"]."/".$config["login-path"]."&return=".$_GET["return"]?>"><?php echo $language["register-login"]?></a></div>
	</div>
	<br>
	<button id="submit" onclick=submit()><strong><?php echo $language["login-submit"];?></strong></button>
	<script>
		function submit()
		{
			var name=document.getElementById("username-input").value;
			var password=document.getElementById("password-input").value;
			var check_password=document.getElementById("check-password-input").value;
			var email=document.getElementById("email-input").value+document.getElementById("email-suffix").value;
			if (password!=check_password)
			{
				layer.msg(<?php echo "\"".$language["register-password-not-same"]."\""?>);
				return false;
			}
			$.ajax({
				type:"POST",
				url:<?php echo "\"".$config["api-server-address"]."/login/register.php"."\"";?>,
				data:{username:name,password:password,email:email,verify:<?php echo "\"".$config["api-server-address"]."/login/verify.php"."\""?>},
				success:function(message)
				{
					var obj=JSON.parse(strip_tags(message));
					var code=obj["code"];
					if (code==0)
					{
						alert(<?php echo "\"".$language["register-regist-succeed"]."\""?>);
						// setTimeout("ReturnPage()",3000);
						ReturnPage();
					}
					else 
					{
						alert(<?php echo "\"".$language["register-regist-failed"]."\"";?>+":"+obj["message"])
						console.log(obj["message"]);
					}
				},
				error:function(jqXHR,textStatus,errorThrown) 
				{
					layer.alert(<?php echo "\"".$language["register-regist-error"]."\""?>)
				    console.log(jqXHR.responseText);
				    console.log(jqXHR.status);
				    console.log(jqXHR.readyState);
				    console.log(jqXHR.statusText);
				    console.log(textStatus);
				    console.log(errorThrown);
				}
			});
		}
		$(document).keypress(function(event){  
			var keynum=(event.keyCode?event.keyCode:event.which);  
			if(keynum=='13'){  
				 document.getElementById("submit").click();
			}  
		});
	</script>
</div>