<?php
	global $page;
	if ($_GET["page"]=="") $page=1;
	else $page=$_GET["page"];
	require_once "config/common.php";
	$language="";
	for ($i=0;$i<count($config["language-source"]);$i++) if ($config["language-source"][$i]["code"]==$config["language"]) $language=$config["language-source"][$i]["path"];
	$language_path="./".$config["language-data"]."/".$language;
	require_once $language_path;
	foreach($config as $key=>$value)
	{
		if (trim($_POST[$key])=="") continue;
		$temp=trim($_POST[$key]);
		if (is_array($config[$key])) $config[$key]=json_decode($temp,true);
		else $config[$key]=$temp;
	}
	function SaveConfig($config,$filePath)
	{
		if (!is_array($config)) return 0;
		$handle=fopen($filePath,"w");
		$output="<?php\n\tglobal \$config;\n\t\$config=array();\n";
		foreach($config as $key=>$value)
		{
			$output.="\t\$config[\"$key\"]=";
			if (is_array($value)) $output.=var_export($value,true).";\n";
			else if (is_string($value)) $output.="\"$value\";\n";
			else if (is_bool($value))
			{
				if (!$value) $output.="false;\n";
				else $output.="true;\n";
			}
			else if ($value==0) $output.="0;\n";
			else $output.="$value;\n";
		}
		$output.="?>";
		if(fwrite($handle,$output)==FALSE) return 0;
		else return 1;
		fclose($handle); 
	}
	SaveConfig($config,"config/common.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $language["setup-title"];?> - <?php echo $config["website-title"];?></title>
		<style>
			body
			{
				background-image:url("<?php echo $config["setup-background"]?>");
				background-repeat:no-repeat;
				background-size:cover;
				background-position:center;
				background-attachment:fixed;	
			}
			.main 
			{
				border-radius:50px;
				padding:50px;
				width:400px;
				min-height:700px;
				margin:auto;
				background-color:RGBA(255,255,255,0.75);
				margin-top:5%;
			}
			.setting 
			{
				height:auto;
				width:100%;
				text-align:center;
			}
			.last
			{
				position:relative;
			}
			.last button 
			{
				border-radius:10px;
				background-color:RGBA(0,60,200,0.75);
				transition:background-color 0.4s;
				width:100px;
				height:30px;
			}
			.last button:hover
			{
				background-color:RGBA(0,50,0,0.75);
			}
			input[type="text"]
			{
				text-indent:13px;
				outline:none;
				border:2px solid;
				border-radius:20px;
				border-color:#FFDBFF;
				font-size:12px;
				background-color:#FFDBFF;    
				height:30px;
				width:250px;
				transition:border-color 0.5s;
			}
			input[type="text"]:focus
			{
				outline:none;
				border-color:#B79ADD;
			}
			td 
			{
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}
			select
			{
				text-indent:13px;
				outline:none;
				border:2px solid;
				border-radius:20px;
				border-color:#FFDBFF;
				font-size:12px;
				background-color:#FFDBFF;    
				height:30px;
				width:250px;
				transition:border-color 0.5s;
			}
			select:focus
			{
				outline:none;
				border-color:#B79ADD;
			}
		</style>
		<script src="<?php echo $config["extension-data"]."/jQuery/jQuery.js"?>"></script>
		<script src="<?php echo $config["extension-data"]."/layer/layer.js"?>"></script>
		<link rel="shortcut icon" href="<?php echo $config["icon-addr"]?>" />
	</head>
	<body>
		<div class="main" id="main">
			<div class="setting" id="setting">
				<!-- 标题部分 -->
				<h2 style="margin-top: 0px;"><center><?php echo $setup_title[$page-1];?></center></h2>
				<!-- 描述部分 -->
				<center><?php echo $setup_desc[$page-1];?></center>
				<!-- 设置正文部分 -->
				<?php
					SaveConfig($config,"config.php");
					$table=0;
					for ($i=0;$i<count($setup_config[$page-1]);$i++)
					{
						if ($setup_config[$page-1][$i][2]=="input") echo "<p>".$setup_config[$page-1][$i][1]." :<input type=\"text\" id=\"".$setup_config[$page-1][$i][0]."\" value=\"".$config[$setup_config[$page-1][$i][0]]."\"></input></p>";
						else if ($setup_config[$page-1][$i][2]=="array")
						{
							$table++;
							$conf=$config[$setup_config[$page-1][$i][0]];
							echo "<p>".$setup_config[$page-1][$i][1]." <button onclick=\"copy_table_json($table)\">Copy JSON Code</button> <button onclick=\"import_table_json($table)\">Import JSON Code</button></p>
							<table width='100%' style='table-layout:fixed;word-break:break-all;word-wrap:break-word;' border=\"1\" id='".$setup_config[$page-1][$i][0]."'><thead><tr id='".$setup_config[$page-1][$i][0]."-header'>";
							for ($j=0;$j<count($setup_config[$page-1][$i]["var"]);$j++) echo "<th>".$setup_config[$page-1][$i]["var"][$j]."</th>";
							echo "<th width=\"70px\">operator</th></tr></thead><tbody id='".$setup_config[$page-1][$i][0]."-content'>";
							for ($k=0;$k<count($conf);$k++)
							{
								echo "<tr id=".$setup_config[$page-1][$i][0].($k+1).">";
								for ($j=0;$j<count($setup_config[$page-1][$i]["var"]);$j++) echo "<td id='".$setup_config[$page-1][$i][0].($k+1)."-".$setup_config[$page-1][$i]["var"][$j]."'>".$conf[$k][$setup_config[$page-1][$i]["var"][$j]]."</td>";
								echo "<td><button id='".$setup_config[$page-1][$i][0].($k+1)."-delete' onclick=delete_table($table,".($k+1).")>delete</button></td></tr>";
							}
							echo "</tbody><tbody><tr>";
							for ($j=0;$j<count($setup_config[$page-1][$i]["var"]);$j++) echo "<td id='".$setup_config[$page-1][$i][0]."-add-".$setup_config[$page-1][$i]["var"][$j]."'><input type='text' style='width:calc( 100% - 10px );height:20px;' id='".$setup_config[$page-1][$i][0]."-add-".$setup_config[$page-1][$i]["var"][$j]."-input'></input></td>";							
							echo "<td><button style=\"width:52px\" onclick=add_table($table)>add</button></td></tbody></table>";
						}
						else if ($setup_config[$page-1][$i][2]=="choice")
						{
							$source=$setup_config[$page-1][$i]["source"];
							echo "<p>".$setup_config[$page-1][$i][1]." :<select id='".$setup_config[$page-1][$i][0]."'>";
							if (is_array($config[$source])) for ($j=0;$j<count($config[$source]);$j++) 
							{
								echo "<option value='".$config[$source][$j][$setup_config[$page-1][$i]["value"]]."' ".(($config[$source][$j][$setup_config[$page-1][$i]["value"]]==$config[$setup_config[$page-1][$i][0]])?"selected=\"selected\"":"").">".$config[$source][$j][$setup_config[$page-1][$i]["name"]]."</option>";
							}
							echo "</select></p>";
						}
						else if ($setup_config[$page-1][$i][2]=="check") echo "<p> <label><input type=\"checkbox\" id=\"".$setup_config[$page-1][$i][0]."\" ".(($config[$setup_config[$page-1][$i][0]])?"checked":"").">".$setup_config[$page-1][$i][1]."</label></p>";
					}
				?>	
				<script>
					// 全局表格事件
					<?php
						$table=0;
						for ($i=0;$i<count($setup_config[$page-1]);$i++) if ($setup_config[$page-1][$i][2]=="array") $table++;
						echo "var table_rows=Array(0,";
						for ($i=0;$i<count($setup_config[$page-1]);$i++) if ($setup_config[$page-1][$i][2]=="array") echo count($config[$setup_config[$page-1][$i][0]]).",";
						echo "),table_name=Array('',";
						for ($i=0;$i<count($setup_config[$page-1]);$i++) if ($setup_config[$page-1][$i][2]=="array") echo "'".$setup_config[$page-1][$i][0]."',";
						echo "),table_lines=Array(0,";
						for ($i=0;$i<count($setup_config[$page-1]);$i++) if ($setup_config[$page-1][$i][2]=="array")
						{
							echo "Array(";
							for ($j=0;$j<count($setup_config[$page-1][$i]["var"]);$j++) echo "\"".$setup_config[$page-1][$i]["var"][$j]."\",";
							echo "),";
						}
						echo ");\n";
					?>
					function add_table(table)
					{
						var code="";
						code+="<tr id='"+table_name[table]+(table_rows[table]+1)+"'>";
						for (var i=0;i<table_lines[table].length;i++) code+="<td id=\""+table_name[table]+(table_rows[table]+1)+"-"+table_lines[table][i]+"\">"+document.getElementById(table_name[table]+"-add-"+table_lines[table][i]+"-input").value+"</td>";
						code+="<td><button id='"+table_name[table]+(table_rows[table]+1)+"-delete' onclick='delete_table("+table+","+(table_rows[table]+1)+")'>delete</button></td></tr>";
						document.getElementById(table_name[table]+"-content").innerHTML+=code;
						table_rows[table]++;
						adjust_main();adjust_main();adjust_main();
					}
					function delete_table(table,row)
					{
						for (var i=row+1;i<=table_rows[table];i++) 
						{
							for (var j=0;j<table_lines[table].length;j++) document.getElementById(table_name[table]+(i-1)+"-"+table_lines[table][j]).innerHTML=document.getElementById(table_name[table]+i+"-"+table_lines[table][j]).innerHTML;
							$(table_name[table]+(i-1)+"-delete").attr("onclick","delete_table("+table+","+(i-1)+")");
						}
						document.getElementById(table_name[table]+table_rows[table]).remove();
						table_rows[table]--;
						adjust_main();adjust_main();adjust_main();
					}
					function get_table_json(table)
					{
						var array=Array();
						for (var i=1;i<=table_rows[table];i++)
						{
							var temp=new Object();
							for (var j=0;j<table_lines[table].length;j++) temp[table_lines[table][j]]=document.getElementById(table_name[table]+i+"-"+table_lines[table][j]).innerHTML;
							array.push(temp);
						}
						return JSON.stringify(array);
					}
					function copy_table_json(table)
					{
						let transfer = document.createElement('input');
						document.body.appendChild(transfer);
						transfer.value = get_table_json(table);  // 这里表示想要复制的内容
						transfer.focus();
						transfer.select();
						if (document.execCommand('copy')) {
						    document.execCommand('copy');
						}
						transfer.blur();
						layer.msg("Copy JSON Code Successfully!");
						document.body.removeChild(transfer);
					}
					function import_table_json(table)
					{
						var name=table_name[table];
						layer.prompt({title: 'Input Your JSON Code here:', formType: 2}, function(text, index){
						    layer.close(index);
							var data=JSON.parse(text);
							var code="<thead>";
							for (var i=0;i<table_lines[table].length;i++) code+="<th>"+table_lines[table][i]+"</th>";
							code+="<th width=\"70px\">operator</th></tr></thead><tbody id='"+table_name[table]+"-content'>";
							for (var i=0;i<data.length;i++)
							{
								code+="<tr id="+table_name[table]+(i+1)+">";
								for (var j=0;j<table_lines[table].length;j++) code+="<td id='"+table_name[table]+(i+1)+"-"+table_lines[table][j]+"'>"+data[i][table_lines[table][j]]+"</td>";
								code+="<td><button id='"+table_name[table]+i+"-delete' onclick=delete_table("+table+","+(i+1)+")>delete</button></td></tr>";
							}
							code+="</tbody><tbody><tr>";
							for (var i=0;i<table_lines[table].length;i++) code+="<td id='"+table_name[table]+"-add-"+table_lines[table][i]+"'><input type=\"text\" style='width:calc( 100% - 10px );height:20px;' id='"+table_name[table]+"-add-"+table_lines[table][i]+"-input'></input></td>";							
							code+="<td><button style=\"width:52px\" onclick=add_table("+table+")>add</button></td></tbody></table>";
							document.getElementById(table_name[table]).innerHTML=code;
							table_rows[table]=data.length;
							adjust_main();adjust_main();adjust_main();
						    layer.msg("Import JSON Code Successfully!");
						});
					}
				</script>
			</div>
			<div class="last" id="footer">
				<hr/>
				<button id="last" style="
				float: left;
				margin-left: 10px;
				"><span title="Last Page">Last Page</span></button>
				<button id="next" style="
				float: right;
				margin-right: 10px;
				"><span title="Next Page"><?php
					$last=count($setup_title);
					echo ($last!=$page)?"Next Page":"Finish";
				?></button>
				<script>
					// 全局按钮事件
					document.getElementById("last").onclick=function()
					{
						$.ajax({
							url:<?php echo "\"".$config["setup-path"]."\"";?>,
							data:{<?php 
								$table=0;
								for ($i=0;$i<count($setup_config[$page-1]);$i++)
								{
									if ($setup_config[$page-1][$i][2]=="input") echo "\"".$setup_config[$page-1][$i][0]."\":document.getElementById(\"".$setup_config[$page-1][$i][0]."\").value,";
									else if ($setup_config[$page-1][$i][2]=="array")
									{
										++$table;
										echo "\"".$setup_config[$page-1][$i][0]."\":get_table_json($table),";
									}
									else if ($setup_config[$page-1][$i][2]=="choice") echo "\"".$setup_config[$page-1][$i][0]."\":document.getElementById(\"".$setup_config[$page-1][$i][0]."\").value,";
									else if ($setup_config[$page-1][$i][2]=="check") echo "\"".$setup_config[$page-1][$i][0]."\":(document.getElementById(\"".$setup_config[$page-1][$i][0]."\").checked)?1:0,";
								}
							?>},
							type:"POST",
							success: function(data) {
								<?php
									if ($page-1!=0) echo "window.location.href=\"setup.php?page=".($page-1)."\"";
									else echo "layer.alert(\"Already the first page!\")";
								?>
							}
						});
					}
					document.getElementById("next").onclick=function()
					{
						$.ajax({
							url:<?php echo "\"".$config["setup-path"]."\"";?>,
							data:{<?php 
								$table=0;
								for ($i=0;$i<count($setup_config[$page-1]);$i++)
								{
									if ($setup_config[$page-1][$i][2]=="input") echo "\"".$setup_config[$page-1][$i][0]."\":document.getElementById(\"".$setup_config[$page-1][$i][0]."\").value,";
									else if ($setup_config[$page-1][$i][2]=="array")
									{
										++$table;
										echo "\"".$setup_config[$page-1][$i][0]."\":get_table_json($table),";
									}
									else if ($setup_config[$page-1][$i][2]=="choice") echo "\"".$setup_config[$page-1][$i][0]."\":document.getElementById(\"".$setup_config[$page-1][$i][0]."\").value,";
									else if ($setup_config[$page-1][$i][2]=="check") echo "\"".$setup_config[$page-1][$i][0]."\":(document.getElementById(\"".$setup_config[$page-1][$i][0]."\").checked)?1:0,";
								}
							?>},
							type:"POST",
							success: function(data) {
								<?php
									if ($page+1!=count($setup_title)+1) echo "window.location.href=\"setup.php?page=".($page+1)."\"";
									else echo "layer.alert(\"Finish to config the website!\");window.location.href=\"".$config["index-path"]."\"";
								?>
							}
						});
					}
				</script>
			</div>
		</div>
		<script>
			function adjust_main()
			{
				if (window.innerWidth>=1020)
				{
					document.getElementById("main").style.width=0.5*window.innerWidth-100+"px";
					// document.getElementById("main").style.marginTop="10%";
				}
				else 
				{
					document.getElementById("main").style.width=0.9*window.innerWidth-100+"px";
					// document.getElementById("main").style.marginTop="10%";
				}
				document.getElementById("footer").style.top=document.getElementById("main").offsetHeight-document.getElementById("setting").offsetHeight-120+"px";
			}
			adjust_main();adjust_main();adjust_main();
			window.onresize=function()
			{
				adjust_main();adjust_main();adjust_main();
			}
		</script>
	</body>
</html>