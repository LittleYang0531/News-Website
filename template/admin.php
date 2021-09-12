<h2 style="width:100%;"><center><?php echo $language["admin-intitle"];?></center></h2>
<div class="admin">
	<div id="left">
		<button class="left-elem" onclick=change("main")><?php echo $language["admin-toolbar-main"]?></button><br>
<!-- 		<button class="left-elem" onclick=change("user")><?php echo $language["admin-toolbar-user"]?></button><br>
		<button class="left-elem" onclick=change("column")><?php echo $language["admin-toolbar-column"]?></button><br>
		<button class="left-elem" onclick=change("wiki")><?php echo $language["admin-toolbar-wiki"]?></button><br>
		<button class="left-elem" onclick=change("notice")><?php echo $language["admin-toolbar-notice"]?></button><br> -->
		<button class="left-elem" onclick=change("data")><?php echo $language["admin-toolbar-data"]?></button><br>
		<button class="left-elem" onclick=change("mysql")><?php echo $language["admin-toolbar-mysql"]?></button><br>
		<button class="left-elem" onclick=change("bash")><?php echo $language["admin-toolbar-bash"]?></button><br>
	</div>
	<div class="right">
		<div id="admin-main">
			<?php 
				$json=PostFromHTML($config["api-server-address"]."/admin/system.php",array());
				$array=json_decode(strip_tags($json),true);	
			?>
			<h3><?php echo $language["admin-main-system-info"]?></h3>
			<p class="input-p">
				<?php echo $language["admin-main-cpu-info"]?>:&nbsp;
				<input id="admin-main-cpu-info" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["CPUInfo"]["name"]?>"/>
			</p>
			<p class="input-p">
				<?php echo $language["admin-main-cpu-cores"]?>:&nbsp;
				<input id="admin-main-cpu-cores" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["CPUInfo"]["Cores"]?> Cores"/>
			</p>
			<h3><?php echo $language["admin-main-memory-info"]?></h3>
			<div class="input-p">
				<?php echo $language["admin-main-memory-used"]?>:&nbsp;
				<div id="admin-main-memory-used-process" class="process">
					<div id="admin-main-memory-used" class="used" style="width:<?php echo $array["data"]["MemInfo"]["memUsedPercent"]?>%">
						<?php echo $array["data"]["MemInfo"]["memUsedPercent"]?>%
						&nbsp;&nbsp;&nbsp;<?php echo $array["data"]["MemInfo"]["memUsed"]."/".$array["data"]["MemInfo"]["memTotal"]?>
					</div>
				</div>
			</div>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-swap-used"]?>:&nbsp;
				<div id="admin-main-swap-used-process" class="process">
					<div id="admin-main-swap-used" class="used" style="width:<?php echo $array["data"]["MemInfo"]["swapPercent"]?>%">
						<?php echo $array["data"]["MemInfo"]["swapPercent"]?>%
						&nbsp;&nbsp;&nbsp;<?php echo $array["data"]["MemInfo"]["swapUsed"]."/".$array["data"]["MemInfo"]["swapTotal"]?>
					</div>
				</div>
			</div>
			<h3 style="margin-bottom:0px;"><?php echo $language["admin-main-disk-info"]?></h3>
			<?php
				for ($i=0;$i<count($array["data"]["DiskInfo"]);$i++) 
				{
					echo "
						<div class='input-p' id='admin-main-disk-into#$i' style='margin-top:12px;'>
							".$array["data"]["DiskInfo"][$i]["diskCapt"]." ".$array["data"]["DiskInfo"][$i]["diskName"]."<br>
						</div>
						<div class='input-p'>
							<div id='admin-main-disk-process#$i' class='process'>
								<div id='admin-main-disk-used#$i' class='used' style='width:".$array["data"]["DiskInfo"][$i]["diskPercent"]."%'>
									".$array["data"]["DiskInfo"][$i]["diskPercent"]."%
									&nbsp;&nbsp;&nbsp;".$array["data"]["DiskInfo"][$i]["diskUsed"]."/".$array["data"]["DiskInfo"][$i]["diskTotal"]."
								</div>
							</div>
						</div>
					";
				}
			?>
			<h3><?php echo $language["admin-main-time-info"]?></h3>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-time-utc"]?>:&nbsp;
				<input id="admin-main-time-utc" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["TimeInfo"]["timeGlobal"]?>"/>
			</div>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-time-system"]?>:&nbsp;
				<input id="admin-main-time-system" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["TimeInfo"]["timeServer"]?>"/>
			</div>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-time-zone"]?>:&nbsp;
				<input id="admin-main-time-zone" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["TimeInfo"]["timeZone"]?>"/>
			</div>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-time-stamp"]?>:&nbsp;
				<input id="admin-main-time-stamp" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["TimeInfo"]["timeStamp"]?>"/>
			</div>
			<h3><?php echo $language["admin-main-system-info"]?></h3>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-system-php"]?>:&nbsp;
				<input id="admin-main-system-php" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["SysInfo"]["sysPHPVers"]?>"/>
			</div>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-system-os"]?>:&nbsp;
				<input id="admin-main-system-os" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["SysInfo"]["sysOperSys"]?>"/>
			</div>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-system-arch"]?>:&nbsp;
				<input id="admin-main-system-arch" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["SysInfo"]["sysProcArch"]?>"/>
			</div>
			<div class="input-p" style="margin-top:12px;">
				<?php echo $language["admin-main-system-domain"]?>:&nbsp;
				<input id="admin-main-system-domain" class="input" type="text" disabled=disabled value="<?php echo $array["data"]["SysInfo"]["sysDomain"]?>"/>
			</div>
			<br><br><br>
			<script>
				function strip_tags(html) 
				{
					var div=document.createElement("div");
					div.innerHTML=html;
					return (div.textContent||div.innerText||"");
				}
				function UpdateSystemInfo() 
				{
					$.ajax({
						type:"POST",
						url:<?php echo "\"".$config["api-server-address"]."/admin/system.php"."\"";?>,
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								document.getElementById("admin-main-cpu-info").value=obj["data"]["CPUInfo"]["name"];
								document.getElementById("admin-main-cpu-cores").value=obj["data"]["CPUInfo"]["Cores"]+" Cores";
								document.getElementById("admin-main-memory-used").style.width=obj["data"]["MemInfo"]["memUsedPercent"]+"%";
								document.getElementById("admin-main-memory-used").innerHTML=
									obj["data"]["MemInfo"]["memUsedPercent"]+"%&nbsp;&nbsp;&nbsp;"+obj["data"]["MemInfo"]["memUsed"]+"/"+obj["data"]["MemInfo"]["memTotal"];
								document.getElementById("admin-main-swap-used").style.width=obj["data"]["MemInfo"]["swapPercent"]+"%";
								document.getElementById("admin-main-swap-used").innerHTML=
									obj["data"]["MemInfo"]["swapPercent"]+"%&nbsp;&nbsp;&nbsp;"+obj["data"]["MemInfo"]["swapUsed"]+"/"+obj["data"]["MemInfo"]["swapTotal"];
								for (i=0;i<obj["data"]["DiskInfo"].length;i++) 
								{
									document.getElementById("admin-main-disk-used#"+i).style.width=obj["data"]["DiskInfo"][i]["diskPercent"]+"%";
									document.getElementById("admin-main-disk-used#"+i).innerHTML=
										obj["data"]["DiskInfo"][i]["diskPercent"]+"%&nbsp;&nbsp;&nbsp;"+obj["data"]["DiskInfo"][i]["diskUsed"]+"/"+obj["data"]["DiskInfo"][i]["diskTotal"];
								}
								document.getElementById("admin-main-time-utc").value=obj["data"]["TimeInfo"]["timeGlobal"];
								document.getElementById("admin-main-time-system").value=obj["data"]["TimeInfo"]["timeServer"];
								document.getElementById("admin-main-time-zone").value=obj["data"]["TimeInfo"]["timeZone"];
								document.getElementById("admin-main-time-stamp").value=obj["data"]["TimeInfo"]["timeStamp"];
								document.getElementById("admin-main-system-php").value=obj["data"]["SysInfo"]["sysPHPVers"];
								document.getElementById("admin-main-system-os").value=obj["data"]["SysInfo"]["sysOperSys"];
								document.getElementById("admin-main-system-arch").value=obj["data"]["SysInfo"]["sysProcArch"];
								document.getElementById("admin-main-system-domain").value=obj["data"]["SysInfo"]["sysDomain"];
							}
							else 
							{
								console.log(<?php echo "\"".$language["admin-main-get-failed"]."\""?>);
								console.log(obj["message"])
							}
						},
						error:function(jqXHR,textStatus,errorThrown) 
						{
							layer.alert(<?php echo "\"".$language["admin-main-get-error"]."\""?>)
						    console.log(jqXHR.responseText);
						    console.log(jqXHR.status);
						    console.log(jqXHR.readyState);
						    console.log(jqXHR.statusText);
						    console.log(textStatus);
						    console.log(errorThrown);
						}
					});
				}
				setInterval(UpdateSystemInfo,1000);
			</script>
		</div>
		<div id="admin-data" style="display:none">
			<h3><?php echo $language["admin-data-title"]?></h3>
			<table style="width:100%;text-align:center" border=1 id="admin-data-main" >
				<tr>
				    <th><?php echo $language["admin-data-time"]?></th>
				    <th><?php echo $language["admin-data-ip"]?></th>
				    <th><?php echo $language["admin-data-explorer"]?></th>
				    <th><?php echo $language["admin-data-os"]?></th>
				    <th><?php echo $language["admin-data-page"]?></th>
				</tr>
				<?php
					$json=PostFromHTML($config["api-server-address"]."/admin/getdata.php",array());
					$array=json_decode(strip_tags($json),true);
					for ($i=0;$i<count($array["data"]);$i++) 
					{
						echo "
							<tr>
								<td>".date("Y-m-d H:i:s",$array["data"][$i]["time"])."</td>
								<td>".$array["data"][$i]["ip"]."</td>
								<td>".DecodeBrowser($array["data"][$i]["ua"])."</td>
								<td>".DecodeOS($array["data"][$i]["ua"])."</td>
								<td>".$array["data"][$i]["page"]."</td>
							</tr>
						";
					}
				?>
			</table>
			<script>
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
				function UpdateData() 
				{
					$.ajax({
						type:"POST",
						url:<?php echo "\"".$config["api-server-address"]."/admin/getdata.php"."\"";?>,
						data:{mode:"js"},
						success:function(message)
						{
							var obj=JSON.parse(strip_tags(message));
							var code=obj["code"];
							if (code==0)
							{
								document.getElementById("admin-data-main").innerHTML=
								"<tr>"+
								"    <th>"+<?php echo "\"".$language["admin-data-time"]."\""?>+"</th>"+
								"    <th>"+<?php echo "\"".$language["admin-data-ip"]."\""?>+"</th>"+
								"    <th>"+<?php echo "\"".$language["admin-data-explorer"]."\""?>+"</th>"+
								"    <th>"+<?php echo "\"".$language["admin-data-os"]."\""?>+"</th>"+
								"    <th>"+<?php echo "\"".$language["admin-data-page"]."\""?>+"</th>"+
								"</tr>";
								for (i=0;i<obj["data"].length;i++) 
								{
									document.getElementById("admin-data-main").innerHTML+=
									"<tr>"+
									"	<td>"+TimeToString(obj["data"][i]["time"])+"</td>"+
									"	<td>"+obj["data"][i]["ip"]+"</td>"+
									"	<td>"+obj["data"][i]["browser"]+"</td>"+
									"	<td>"+obj["data"][i]["os"]+"</td>"+
									"	<td>"+obj["data"][i]["page"]+"</td>"+
									"</tr>";
								}
							}
							else 
							{
								console.log(<?php echo "\"".$language["admin-data-get-failed"]."\""?>);
								console.log(obj["message"])
							}
						},
						error:function(jqXHR,textStatus,errorThrown) 
						{
							layer.alert(<?php echo "\"".$language["admin-data-get-error"]."\""?>)
						    console.log(jqXHR.responseText);
						    console.log(jqXHR.status);
						    console.log(jqXHR.readyState);
						    console.log(jqXHR.statusText);
						    console.log(textStatus);
						    console.log(errorThrown);
						}
					});
				}
				setInterval(UpdateData,1000);
			</script>
		</div>
		<div id="admin-mysql" style="display:none;">
			<h3><?php echo $language["admin-mysql"]?></h3>
			<p class="input-p" style="margin:0px">  
				<?php echo $language["admin-mysql-database"]?>
				<select class="input" id="admin-mysql-database">
					<?php
						$json=PostFromHTML($config["api-server-address"]."/admin/getdatabase.php",array());
						$array=json_decode(strip_tags($json),true);	
						for ($i=0;$i<count($array["data"]);$i++) 
						{
							echo "<option value='".$array["data"][$i]."'>".$array["data"][$i]."</option>";
						}
					?>
				</select><br>	
			</p>
			<div class="admin-command" id="admin-command">
				<div id="admin-mysql-output">
					Welcome to the MySQL monitor.  Commands end with ; or \g.<br>
					Server version: MySQL/MariaDB Community Server (GPL)<br>
					<br>
					Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.<br>
					<br>
					Oracle is a registered trademark of Oracle Corporation and/or its affiliates. Other names may be trademarks of their respective owners.<br>
					<br>
					Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.<br>
					<br>
				</div>
				<div id="admin-mysql-main">
					mysql>&nbsp;<input id="admin-mysql-input" class="admin-input"/>
				</div>
				<script>
					$(document).keypress(function(event){
						if (document.getElementById("admin-mysql").style.display=="block")
						{
							var keynum=(event.keyCode?event.keyCode:event.which);  
							if(keynum=='13'){  
								document.getElementById("admin-mysql-output").innerHTML+="mysql> "+document.getElementById("admin-mysql-input").value+"<br>";
								com=document.getElementById("admin-mysql-input").value;
								document.getElementById("admin-mysql-input").value="";
								document.getElementById("admin-mysql-main").style.display="none";
								db=document.getElementById("admin-mysql-database").value;
								document.getElementById("admin-command").style.width=document.getElementById("admin-command").offsetWidth-20+"px";
								$.ajax({
									type:"POST",
									url:<?php echo "\"".$config["api-server-address"]."/admin/mysqlexec.php"."\"";?>,
									data:{database:db,command:com},
									success:function(message)
									{
										var obj=JSON.parse(message.substr(58,message.length-64));
										var code=obj["code"];
										if (code==0)
										{
											document.getElementById("admin-mysql-output").innerHTML+=obj["data"];
										}
										else 
										{
											document.getElementById("admin-mysql-output").innerHTML+="[Error "+Math.abs(obj["code"])+"]: "+obj["message"]+"<br><br>";
										}
										document.getElementById("admin-mysql-main").style.display="block";
									},
									error:function(jqXHR,textStatus,errorThrown) 
									{
										document.getElementById("admin-mysql-output").innerHTML+="Your server may have some problem,please wait for a minute and try again!<br><br>";
										document.getElementById("admin-mysql-main").style.display="block";
									}
								});		
							}  		
						}
					});
				</script>
			</div>
		</div>
		<div id="admin-bash" style="display:none">
			<h3><?php echo $language["admin-bash"]?></h3>
			<div class="admin-command" id="admin-bash-command" style="height:640px;">
				<div id="admin-bash-output">
					RemoteShell 1.0.0<br>
					Copyright (c) LittleYang0531.<br>
					<br>
					Type 'help' to get help.<br>
					<br>
				</div>
				<div id="admin-bash-main">
					root@localhost>&nbsp;<input id="admin-bash-input" class="admin-input" style="width:calc(100% - 122px)"/>
				</div>
				<script>
					$(document).keypress(function(event){
						if (document.getElementById("admin-bash").style.display=="block")
						{
							var keynum=(event.keyCode?event.keyCode:event.which);  
							if(keynum=='13'){  
								document.getElementById("admin-bash-output").innerHTML+="root@localhost> "+document.getElementById("admin-bash-input").value+"<br>";
								com=document.getElementById("admin-bash-input").value;
								document.getElementById("admin-bash-input").value="";
								document.getElementById("admin-bash-main").style.display="none";
								document.getElementById("admin-bash-command").style.width=document.getElementById("admin-bash-command").offsetWidth-20+"px";
								$.ajax({
									type:"POST",
									url:<?php echo "\"".$config["api-server-address"]."/admin/bashexec.php"."\"";?>,
									data:{command:com},
									success:function(message)
									{
										var obj=JSON.parse(message.substr(58,message.length-64));
										var code=obj["code"];
										document.getElementById("admin-bash-output").innerHTML+=obj["data"];
										document.getElementById("admin-bash-main").style.display="block";
									},
									error:function(jqXHR,textStatus,errorThrown) 
									{
										document.getElementById("admin-bash-output").innerHTML+="Your server may have some problem,please wait for a minute and try again!<br><br>";
										document.getElementById("admin-bash-main").style.display="block";
									}
								});		
							}  	
						}
					});
				</script>
			</div>
			
		</div>
		<script>
			function change(id)
			{
				document.getElementById("admin-main").style.display="none";
				// document.getElementById("admin-user").style.display="none";
				// document.getElementById("admin-column").style.display="none";
				// document.getElementById("admin-wiki").style.display="none";
				// document.getElementById("admin-notice").style.display="none";
				document.getElementById("admin-data").style.display="none";
				document.getElementById("admin-mysql").style.display="none";
				document.getElementById("admin-bash").style.display="none";
				document.getElementById("admin-"+id).style.display="block";
			}
		</script>
	</div>
</div>