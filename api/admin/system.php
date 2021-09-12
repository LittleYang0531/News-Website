<?php
	require "../config.php";
	require "../".$config["function-path"];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	if (!CheckLogin())
	{
		echo ReturnError(-101,"账号未登录");
		exit;
	}
	$return=GetAuthority();
	if ($return==-2)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	if ($return==-3)
	{
		echo ReturnError(-500,"数据库查询错误");
		exit;
	}
	if ($return!=1)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$server=php_uname("s");
	if (strtoupper(substr($server,0,3))==='WIN') 
	{
		$out='';
		$info=exec('wmic os get TotalVisibleMemorySize,FreePhysicalMemory,TotalVirtualMemorySize,FreeVirtualMemory',$out,$status);
		$phymem=preg_replace("/\s(?=\s)/","\\1",$out[1]);
		$phymem_array=explode(' ',$phymem);
		$freephymem=$phymem_array[0]/1024;
		$freevitmem=$phymem_array[1]/1024;
		$totalvitmem=$phymem_array[2]/1024;
		$totalphymem=$phymem_array[3]/1024;
		$meminfo=[];
		$meminfo['memTotal'] = round($totalphymem, 2);
		$meminfo['memFree'] = round($freephymem, 2);
		$meminfo['memUsed'] = round($totalphymem-$freephymem, 2);
		$meminfo['memUsedPercent'] = (floatval($meminfo['memTotal'])!=0)?round($meminfo['memUsed']/$meminfo['memTotal']*100,2):0;
		$meminfo['swapTotal'] = round($totalvitmem, 2);
		$meminfo['swapFree'] = round($freevitmem, 2);
		$meminfo['swapUsed'] = round($meminfo['swapTotal']-$meminfo['swapFree'], 2);
		$meminfo['swapPercent'] = (floatval($meminfo['swapTotal'])!=0)?round($meminfo['swapUsed']/$meminfo['swapTotal']*100,2):0;
		foreach ($meminfo as $key => $value) {
		    if (strpos($key, 'Percent') > 0)
		        continue;
		    if ($value < 1024)
		        $meminfo[$key] .= ' M';
		    else
		        $meminfo[$key] = round($value / 1024, 3) . ' G';
		}
		
		
		
		$cpuinfo=[];
		$out="";
		$info=exec("wmic cpu get Name,NumberOfCores",$out,$status);
		$temp=preg_replace("/\s(?=\s)/","\\1",$out[1]);
		$temp_array=explode(' ',$temp);
		$cpuinfo["name"]=$temp_array[0];
		for ($i=1;$i<count($temp_array)-1;$i++) $cpuinfo["name"].=(" ".$temp_array[$i]);
		$cpuinfo["Cores"]=$temp_array[count($temp_array)-1];
		
		
		
		$diskinfo=[];
		$out="";
		$info=exec("wmic logicaldisk get Caption,FreeSpace,Size,VolumeName",$out,$status);
		for ($i=1;$i<count($out)-1;$i++)
		{
			$disk=preg_replace("/\s(?=\s)/","\\1",iconv("GBK","UTF-8",$out[$i]));			$disk_array=explode(' ',$disk);
			$tmp=[];
			$tmp["diskCapt"]=$disk_array[0];
			$tmp["diskName"]=$disk_array[3];
			for ($j=4;$j<count($disk_array);$j++) $tmp["diskName"].=" ".$disk_array[$j];
			$tmp['diskTotal']=round($disk_array[2]/(1024*1024),2);
			$tmp['diskFree']=round($disk_array[1]/(1024*1024),2);
			$tmp['diskUsed']=round($tmp['diskTotal']-$tmp['diskFree'],2);
			$tmp['diskPercent']=0;
			if (floatval($tmp['diskTotal'])!=0)
			    $tmp['diskPercent']=round($tmp['diskUsed']/$tmp['diskTotal']*100,2);
			foreach ($tmp as $key => $value) {
				if (strpos($key,'Percent')>0||strpos($key,'Capt')>0||strpos($key,'Name')>0)
				    continue;
				if ($value<1024)
				    $tmp[$key].=' M';
				else
				    $tmp[$key]=round($value/1024,3).' G';
			}
			$diskinfo[]=$tmp;
		}
		
		
		
		$timeinfo=[];
		$timeinfo["timeGlobal"]=gmdate("Y-m-d H:i:s");
		$timeinfo["timeServer"]=date("Y-m-d H:i:s");
		$timeinfo["timeStamp"]=time();
		$timeinfo["timeZone"]=date_default_timezone_get();
		
		
		
		$sysinfo=[];
		$sysinfo["sysPHPVers"]=phpversion();
		$out="";
		$info=exec("wmic os get Caption",$out,$status);
		$sysinfo["sysOperSys"]=iconv("GBK","UTF-8",$out[1])." ".php_uname("v");
		$sysinfo["sysProcArch"]=strtolower(php_uname("m"));
		$sysinfo["sysServer"]=$_SERVER["SERVER_SOFTWARE"];
		$sysinfo["sysDomain"]=$_SERVER["SERVER_NAME"];
		
		
		
		echo ReturnJSON(array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array
			(
				"CPUInfo"=>$cpuinfo,
				"MemInfo"=>$meminfo,
				"DiskInfo"=>$diskinfo,
				"TimeInfo"=>$timeinfo,
				"SysInfo"=>$sysinfo,
			),
		));
	} 
	else 
	{
		$meminfo = [];
		if (!($str = @file('/proc/meminfo')))
		{
			echo ReturnError(-500,"读入文件失败");
			exit;
		}
		$str = implode('', $str);
		preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
		preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);
		
		$meminfo['memTotal'] = round($buf[1][0] / 1024, 2);
		$meminfo['memFree'] = round(($buf[2][0]+$buffers[1][0]+$buf[3][0]) / 1024, 2);
		$meminfo['memUsed'] = round($meminfo['memTotal'] - $meminfo['memFree'], 2);
		$meminfo['memUsedPercent'] = (floatval($meminfo['memTotal']) != 0) ? round($meminfo['memUsed'] / $meminfo['memTotal'] * 100, 2) : 0;
		
		$meminfo['swapTotal'] = round($buf[4][0] / 1024, 2);
		$meminfo['swapFree'] = round($buf[5][0] / 1024, 2);
		$meminfo['swapUsed'] = round($meminfo['swapTotal'] - $meminfo['swapFree'], 2);
		$meminfo['swapPercent'] = (floatval($meminfo['swapTotal']) != 0) ? round($meminfo['swapUsed'] / $meminfo['swapTotal'] * 100, 2) : 0;
		
		foreach ($meminfo as $key => $value) {
		    if (strpos($key, 'Percent') > 0)
		        continue;
		    if ($value < 1024)
		        $meminfo[$key] .= ' M';
		    else
		        $meminfo[$key] = round($value / 1024, 3) . ' G';
		}
		
		
		
		$cpuinfo = [];
		if (!($str = @file("/proc/cpuinfo")))
		{
			echo ReturnError(-500,"读入文件失败");
			exit;
		}
		$str=implode("", $str);
		@preg_match_all("/processor\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $processor);
		$tmp2=explode("Hardware",$str);
		$tmp3=explode(":",trim($tmp2[1]));
		$cpuinfo['name']=trim($tmp3[1]);
		$cpuinfo['Cores']=sizeof($processor[1]);
		
		
		
		$diskinfo=[];
		$diskinfo["diskCapt"]="/:";
		$diskinfo["diskName"]="Local Disk";
		$diskinfo['diskTotal']=round(@disk_total_space('.')/(1024*1024),2);
		$diskinfo['diskFree']=round(@disk_free_space('.')/(1024*1024),2);
		$diskinfo['diskUsed']=round($diskinfo['diskTotal']-$diskinfo['diskFree'],2);
		$diskinfo['diskPercent']=0;
		if (floatval($diskinfo['diskTotal'])!=0)
		    $diskinfo['diskPercent'] = round($diskinfo['diskUsed']/$diskinfo['diskTotal']*100,2);
		foreach ($diskinfo as $key => $value) {
			if (strpos($key,'Percent')>0||strpos($key,'Capt')>0||strpos($key,'Name')>0)
			    continue;
			if ($value<1024)
			    $diskinfo[$key].=' M';
			else
			    $diskinfo[$key]=round($value/1024,3).' G';
		}
		
		
		
		$timeinfo=[];
		$timeinfo["timeGlobal"]=gmdate("Y-m-d H:i:s");
		$timeinfo["timeServer"]=date("Y-m-d H:i:s");
		$timeinfo["timeStamp"]=time();
		$timeinfo["timeZone"]=date_default_timezone_get();
		
		
		
		$sysinfo=[];
		$sysinfo["sysPHPVers"]=phpversion();
		if (!($str=@file("/etc/issue")))
		{
			echo ReturnError(-500,"读入文件失败");
			exit;
		}
		$str_array=explode(" ",$str[0]);
		$sysinfo["sysOperSys"]=$str_array[0]." ".$str_array[2]." ".$str_array[1];
		$sysinfo["sysProcArch"]=strtolower(php_uname("m"));
		$sysinfo["sysServer"]=$_SERVER["SERVER_SOFTWARE"];
		$sysinfo["sysDomain"]=$_SERVER["SERVER_NAME"];
		
		
		
		echo ReturnJSON(array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array
			(
				"CPUInfo"=>$cpuinfo,
				"MemInfo"=>$meminfo,
				"DiskInfo"=>
				array(
					$diskinfo,
				),
				"TimeInfo"=>$timeinfo,
				"SysInfo"=>$sysinfo,
			)
		));
	}
?>