<?php
	function GetRandStr($length,$str){
		$len = strlen($str)-1;
		$randstr = '';
		for ($i=0;$i<$length;$i++) {
			$num=mt_rand(0,$len);
			$randstr .= $str[$num];
		}
		return $randstr;
	}
	function DecodeBrowser($ua) {
	    $browser=null;
	    if (preg_match('#(Camino|Chimera)[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Camino Version ' . $matches[2];
	    } elseif (preg_match('#SE 2([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Sogou Browser 2 Version ' . $matches[1];
	    } elseif (preg_match('#360([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = '360 Browser Version ' . $matches[1];
	    } elseif (preg_match('#Maxthon( |\/)([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Maxthon Version ' . $matches[2];
	    } elseif (preg_match('#Edge ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Edge on Windows Version ' . $matches[1];
	    } elseif (preg_match('#Edg/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Edge on Windows Version ' . $matches[1];
	    } elseif (preg_match('#EdgA/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Edge on Android Version ' . $matches[1];
	    } elseif (preg_match('#XiaoMi/MiuiBrowser/([0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Xiaomi Browser Version ' . $matches[1];
	    } elseif (preg_match('#opera mini#i', $ua)) {
	        $browser = 'Opera Mini Version ' . $matches[1];
	    } elseif (preg_match('#Opera.([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Opera Version ' . $matches[1];
	    } elseif (preg_match('#TencentTraveler ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Tencent Traveler Version ' . $matches[1];
	    } elseif (preg_match('#(UCWEB|UBrowser|UCBrowser)/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'UC Browser Version ' . $matches[1];
	    } elseif (preg_match('#Vivaldi/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Vivaldi Version ' . $matches[1];
	    } elseif (preg_match('#wp-(iphone|android)/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'WordPress Client ' . $matches[1];
	    } elseif (preg_match('#Chrome/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Chrome ' . $matches[1];
	    } elseif (preg_match('#Safari/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Safari ' . $matches[1];
	    } elseif (preg_match('#MSIE ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Internet Explorer ' . $matches[1];
	    } elseif (preg_match('#(Firefox|Phoenix|SeaMonkey|Firebird|BonEcho|GranParadiso|Minefield|Iceweasel)/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
	        $browser = 'Firefox ' . $matches[2];
	    } 
	    return ($browser==null)?"Unknown Browser":$browser;
	}
	function DecodeOS($ua)
	{
		$os=null;
		if (preg_match('/Windows NT 6.0/i', $ua)) {
		    $os = "Windows Vista";
		} if (preg_match('/Windows NT 6.1/i', $ua)) {
		    $os = "Windows 7";
		} if (preg_match('/Windows NT 6.2/i', $ua)) {
		    $os = "Windows 8";
		} if (preg_match('/Windows NT 6.3/i', $ua)) {
		    $os = "Windows 8.1";
		} if (preg_match('/Windows NT 10.0/i', $ua)) {
		    $os = "Windows 10";
		} if (preg_match('/Windows NT 5.1/i', $ua)) {
		    $os = "Windows XP";
		} if (preg_match('/Mac OS X/i', $ua)) {
		    $os = "Mac OS X";
		} if (preg_match('#Linux#i', $ua)) {
		    $os = "Linux ";
		} if (preg_match('#Windows Phone#i', $ua)) {
		    $os = "Windows Phone ";
		} if (preg_match('/Windows NT 5.2/i', $ua) && preg_match('/Win64/i', $ua)) {
		    $os = "Windows XP 64 bit";
		} if (preg_match('/Android ([0-9.]+)/i', $ua, $matches)) {
		    $os = "Android " . $matches[1];
		} if (preg_match('/iPhone OS ([_0-9]+)/i', $ua, $matches)) {
		    $os = 'iPhone ' . $matches[1];
		}
		return ($os==null)?"Unknown Operating System":$os;
	}
	function ReturnCookieString()
	{
		$str="";
		foreach ($_COOKIE as $key=>$value) $str.=$key."=".$value."; ";
		return $str;
	}
	function GetFromHTML($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_COOKIE, ReturnCookieString());
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$output=curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	function PostFromHTML($url,$param)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_COOKIE, ReturnCookieString());
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$output=curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	function output($method,$text,$strong,$locate) 
	{
		global $config;
		if ($locate!=0) echo "<div class=\"block$strong\" onclick=locate_nogui(\"".$config["server"]."/".$method."&page=$locate\")>$text</div>";
		else echo "<div class=\"block$strong\">$text</div>";
	}
	function CreatePageList($num,$ps,$method)
	{
		global $config;
		echo "<div class=\"ps-list\" id=\"ps-list\">";
		$ps_max=max(1,ceil($num/$ps));
		if ($_GET['page']!=1) output($method,"<img style='line-height:33px;height:10px;width:7px;' src='".$config["photo-left-arrow-path"]."'/>",0,$_GET['page']-1);
		if ($_GET['page']>5)
		{
			output($method,1,0,1);
			output($method,"...",0,0);
			for ($i=$_GET['page']-3;$i<=$_GET['page'];$i++)
			{
				if ($_GET['page']==$i) output($method,$i,1,$i);
				else output($method,$i,0,$i);
			}
		}
		else for ($i=1;$i<=$_GET['page'];$i++)
		{
			if ($_GET['page']==$i) output($method,$i,1,$i);
			else output($method,$i,0,$i);
		}
		if ($_GET['page']<=$ps_max-5) 
		{
			for ($i=$_GET['page']+1;$i<=$_GET['page']+3;$i++)
			{
				if ($_GET['page']==$i) output($method,$i,1,$i);
				else output($method,$i,0,$i);
			}
			output($method,"...",0,0);
			output($method,$ps_max,0,$ps_max);
		}
		else for ($i=$_GET['page']+1;$i<=$ps_max;$i++)
		{
			if ($_GET['page']==$i) output($method,$i,1,$i);
			else output($method,$i,0,$i);
		}
		if ($_GET['page']!=$ps_max) output($method,"<img style='line-height:33px;height:10px;width:7px;' src='".$config["photo-right-arrow-path"]."'/>",0,$_GET['page']+1);
		echo "</div>";
	}
?>