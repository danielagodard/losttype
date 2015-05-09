<?
/* Number of days to keep unviewed messages stored [minimum value: 1] */
$daystokeepmsgs				= 5;
/* Number of minutes per letter to keep a viewed message alive [minimum value: 1] */
$minutesPerLetter			= 60*27;


/* WARNING AND ERROR MESSAGES INSIDE THE PHP CODE */

$console_placeholder		= "";
$default_error_msg			= "Something went wrong!<br />Please try again.";
$default_warning_msg		= "You entered a wrong code<br />or<br />this message has expired!";
$msg_expired_msg			= "This message has expired!";



/**************************************
 * DO NOT CHANGE ANYTHING BELOW HERE
 **************************************/
$now=time();
$bg="";

/* check for old messages and delete them */
$files=scandir('./msg/');
foreach($files as $file){
	if($file!="."&&$file!=".."&&$file!="index.php"){
		$ret=file_get_contents('./msg/'.$file);
		$array=explode("\n",$ret);
		$msgTimeLimit=60*60*24*$daystokeepmsgs;
		if(isset($array[3])&&$array[4]<$now||!isset($array[3])&&($array[1]+$msgTimeLimit)<$now){
			unlink('./msg/'.$file);continue;
		}
	}
}

function genRandomString($len){$chars="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWZYZ";$str="";for($p=0;$p<$len;$p++){$str.=$chars[mt_rand(0,strlen($chars))];}unset($len,$chars);return $str;}
function securedata($d){$d=htmlentities(stripslashes(trim($d)));$d=str_replace("'"," ",$d);$d=str_replace(";"," ",$d);$d=str_replace("$"," ",$d);return $d;unset($d);}
function BB($text){
	$find=array(
					'~\[b\](.*?)\[/b\]~s',
					'~\[i\](.*?)\[/i\]~s',
					'~\[u\](.*?)\[/u\]~s',
					'~\<br\>~s',
					'~\<div\>(.*?)\</div\>~s',
					'~\<p\>(.*?)\</p\>~s',
					'~\&nbsp;~s'
	);
	$replace=array(
					'<0>$1</0>',
					'<1>$1</1>',
					'<2>$1</2>',
					'<3>',
					'<4>$1</4>',
					'<5>$1</5>'.
					'<6>'
	);
	return stripslashes(htmlspecialchars_decode(preg_replace($find,$replace,$text),ENT_QUOTES));
}
function saveBB($text){
	$find=array('~\<br\>~s','~\<div\>(.*?)\</div\>~s','~\<p\>(.*?)\</p\>~s');
	$replace=array('[br]','[div]$1[/div]','[p]$1[/p]');
	return htmlspecialchars(preg_replace($find,$replace,$text),ENT_QUOTES);
}
function showMessage($text){
	$find=array(
					'~\<0\>(.*?)\</0\>~s',
					'~\<1\>(.*?)\</1\>~s',
					'~\<2\>(.*?)\</2\>~s',
					'~\<3\>~s',
					'~\<4\>(.*?)\</4\>~s',
					'~\<5\>(.*?)\</5\>~s',
					'~\<6\>~s'
	);
	$replace=array(
					'<b>$1</b>',
					'<i>$1</i>',
					'<span style="text-decoration:underline;">$1</span>',
					'<br>',
					'<div>$1</div>',
					'<p>$1</p>',
					'&nbsp;'
	);
	return stripslashes(htmlspecialchars_decode(preg_replace($find,$replace,$text),ENT_QUOTES));
}
function destroyLetter($letter,$text){
	$find=array('/['.$letter.']/i');
	$replace=array('<6>');
	return htmlspecialchars(preg_replace($find,$replace,$text),ENT_QUOTES);
}

if(isset($_POST['msg'])&&$_POST['msg']!=""){
	$code="c_".genRandomString(6);
	$created=time();
	/* calculate $visibleUntil from message length */
	$visibleUntil=strlen($_POST['rawmsg']);
	$savemsg=saveBB($_POST['msg']);
	$msg=BB($_POST['msg']);
	$data=$msg."\n".$created."\n".$visibleUntil;
    $ret=file_put_contents('./msg/'.$code,$data,FILE_APPEND|LOCK_EX);
    if($ret===false){$msg=$default_error_msg;}
	header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."".$code,true,303);
	die();
}elseif(isset($_GET['c'])&&$_GET['c']!="") {
	$code=securedata($_GET['c']);
	$visited=time();
	if(is_file('./msg/'.$code)){
		$ret=file_get_contents('./msg/'.$code);
		$array=explode("\n",$ret);
		$msg=$array[0];
		if(!isset($array[3])){
			$msg_expires=$visited+$array[2]+(60*$minutesPerLetter);	/* Multiply by x minutes(per letter) */
			$expires_in=$array[2]+(1000*60*$minutesPerLetter);		/* For JavaScript */
			$data="\n".$visited."\n".$msg_expires;
			$pu=file_put_contents('./msg/'.$code,$data,FILE_APPEND|LOCK_EX);
			if($pu===false){$msg=$default_error_msg;}
		}elseif($array[4]<$visited){/* delete message */
			unlink('./msg/'.$code);$expires_in=0;$msg=$msg_expired_msg;
		}else{
			$first=$array[3];
			$diff=floor(($visited-$first)/3600);
			if($diff!=0){
			if($diff==1){$msg=destroyLetter("a",$msg);}
			elseif($diff<=2){$msg=destroyLetter("ab",$msg);}
			elseif($diff<=3){$msg=destroyLetter("a-c",$msg);}
			elseif($diff<=4){$msg=destroyLetter("a-d",$msg);}
			elseif($diff<=5){$msg=destroyLetter("a-e",$msg);}
			elseif($diff<=6){$msg=destroyLetter("a-f",$msg);}
			elseif($diff<=7){$msg=destroyLetter("a-g",$msg);}
			elseif($diff<=8){$msg=destroyLetter("a-h",$msg);}
			elseif($diff<=9){$msg=destroyLetter("a-i",$msg);}
			elseif($diff<=10){$msg=destroyLetter("a-j",$msg);}
			elseif($diff<=11){$msg=destroyLetter("a-k",$msg);}
			elseif($diff<=12){$msg=destroyLetter("a-l",$msg);}
			elseif($diff<=13){$msg=destroyLetter("a-m",$msg);}
			elseif($diff<=14){$msg=destroyLetter("a-n",$msg);}
			elseif($diff<=15){$msg=destroyLetter("a-o",$msg);}
			elseif($diff<=16){$msg=destroyLetter("a-p",$msg);}
			elseif($diff<=17){$msg=destroyLetter("a-q",$msg);}
			elseif($diff<=18){$msg=destroyLetter("a-r",$msg);}
			elseif($diff<=19){$msg=destroyLetter("a-s",$msg);}
			elseif($diff<=20){$msg=destroyLetter("a-t",$msg);}
			elseif($diff<=21){$msg=destroyLetter("a-u",$msg);}
			elseif($diff<=22){$msg=destroyLetter("a-v",$msg);}
			elseif($diff<=23){$msg=destroyLetter("a-w",$msg);}
			elseif($diff<=24){$msg=destroyLetter("a-x",$msg);}
			elseif($diff<=25){$msg=destroyLetter("a-y",$msg);}
			elseif($diff>=26){$msg=destroyLetter("a-z",$msg);}
			}
			$expires_in=($array[4]-$visited)*1000;
		}/* message not yet expired - calculate remaining time for Javascript */
		//$msg=BB($msg);
		
		$mt=date('G',$array[1]);
		if($mt>0&&$mt<=4)$bg="#D8DDCE";
		if($mt>4&&$mt<=8)$bg="#C1D1BF";
		if($mt>8&&$mt<=12)$bg="#A5BFAA";
		if($mt>12&&$mt<=16)$bg="#7F9F8C";
		if($mt>16&&$mt<=20)$bg="#5B8672";
		if($mt>20&&$mt<=24)$bg="#20533F";
		
		$msg=showMessage(showMessage($msg));
		if($ret===false){$msg=$default_error_msg;}
	}else{$msg=$default_warning_msg;}
}else{
	$mt=date('G',$now);
	if($mt>0&&$mt<=4)$bg="#D8DDCE";
	if($mt>4&&$mt<=8)$bg="#C1D1BF";
	if($mt>8&&$mt<=12)$bg="#A5BFAA";
	if($mt>12&&$mt<=16)$bg="#7F9F8C";
	if($mt>16&&$mt<=20)$bg="#5B8672";
	if($mt>20&&$mt<=24)$bg="#20533F";
}
?>