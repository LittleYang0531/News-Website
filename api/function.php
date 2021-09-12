<?php
	function ReturnError($error,$description)
	{
		$information=array(
			"code"=>$error,
			"message"=>$description,
			"ttl"=>1
		);
		return "<pre style='word-wrap: break-word;white-space: pre-wrap;'>".str_replace("\\/","/",json_encode($information, JSON_UNESCAPED_UNICODE))."</pre>";
	}
	function ReturnJSON($array)
	{
		return "<pre style='word-wrap: break-word;white-space: pre-wrap;'>".str_replace("\\/","/",json_encode($array, JSON_UNESCAPED_UNICODE))."</pre>";
	}
	function CheckLogin()
	{
		global $config;
		require_once "config.php";
		$right=0;
		if (!empty($_COOKIE['SESSDATA'])&&!empty($_COOKIE['DedeUserId'])&&!empty($_COOKIE['DedeUserId__ckMd5'])&&md5($_COOKIE['DedeUserId'])==$_COOKIE['DedeUserId__ckMd5'])
		{
			$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
			if (!$conn)
			{
				return false;
			}
			$sql = "SELECT * FROM LoginData where SESSDATA='".$_COOKIE['SESSDATA']."' and CSRF='".$_COOKIE['CSRF']."' and uid=".$_COOKIE['DedeUserId'];
			$result = mysqli_query($conn, $sql);
			if (!$result||mysqli_num_rows($result)==0)
			{
				$right=1;
				return false;
			}
			else if (mysqli_num_rows($result) > 0)
			{ 
			    while($row=mysqli_fetch_assoc($result))
			    {
			        if (time()-$row['time']<=30*3600*24)
			        {
						$sql1=" SELECT * FROM Users WHERE UserId=".$row['uid'];
						$result1=mysqli_query($conn, $sql1);
						$row1=mysqli_fetch_assoc($result1);
						if ($row1['banned']==0)
						{
							$right=1;
							return true;
						}
			        }
					else $conn->query("DELETE FROM LoginData where SESSDATA='".$_COOKIE['SESSDATA']."' and CSRF='".$_COOKIE['yc_jct']."' and uid=".$_COOKIE['DedeUserId']);
			    }
			}
			if (!$right) return false;
		}
		return false;
	}
	function CheckDevice($ignore_banned,$ua,$ip)
	{
		require "config.php";
		$right=0;
		if (!empty($_COOKIE['DeviceId']))
		{
			$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
			if (!$conn) return false;
			$sql = "SELECT * FROM device_id where id='".$_COOKIE['DeviceId']."' and ip='$ip' and ua='$ua'";
			$result = mysqli_query($conn, $sql);
			if (!$result||mysqli_num_rows($result)==0)
			{
				$right=1;
				return false;
			}
			else if (mysqli_num_rows($result) > 0)
			{ 
			    while($row=mysqli_fetch_assoc($result))
			    {
					if ($ignore_banned||$row['banned']==0)
					{
						$right=1;
						return true;
					}
				}
			}
		}
		return false;
	}
	function GetAuthority()
	{
		if (!CheckLogin()) return -1;
		require "config.php";
		$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
		if (!$conn) return -2;
		$sql="SELECT * FROM Users WHERE UserId=".$_COOKIE["DedeUserId"];
		$result=mysqli_query($conn,$sql);
		if (!$result) return -3;
		$row=mysqli_fetch_assoc($result);
		return $row["Authority"];
	}
	function CheckNameExist($name)
	{
		require "config.php";
		$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
		if (!$conn) return -1;
		$sql="SELECT * FROM Users WHERE UserName='$name'";
		$result=mysqli_query($conn,$sql);
		if (!$result) return -2;
		return (mysqli_num_rows($result))?1:0;
	}
	function CheckEmailExist($email)
	{
		require "config.php";
		$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
		if (!$conn) return -1;
		$sql="SELECT * FROM Users WHERE Mail='$email'";
		$result=mysqli_query($conn,$sql);
		if (!$result) return -2;
		return (mysqli_num_rows($result))?1:0;
	}
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
	function GetFromHTML($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
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
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$output=curl_exec($ch);
		curl_close($ch);
		return $output;
	}
?>