<?include("./config.php");?>
<!DOCTYPE html>
<html lang="en-US">
<head>
 <title></title>
 <meta name="description" content="">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
 <link rel="stylesheet" href="./css/main.css">
 <style>body{background:<?=$bg;?> !important;}</style>
</head>

<body onload="<?if(!isset($_POST['msg'])&&!isset($_GET['c'])){?>$m('console').focus();<?}elseif(isset($_GET['c'])&&$expires_in!=0){?>setTimeout(function(){FX.fadeOut($m('console'),{duration:2000,complete:function(){$m('console').innerHTML='This message has expired!';$m('console').style.opacity=100;}})},<?=$expires_in;?>);<?}elseif(isset($_POST['msg'])){?>$m('msglink').focus();$m('msglink').setSelectionRange(0,$m('msglink').value.length);<?}?>"<?if(isset($_POST['msg'])){?> onclick="$m('msglink').focus();$m('msglink').setSelectionRange(0,$m('msglink').value.length);"<?}elseif(!isset($_POST['msg'])&&!isset($_GET['c'])){?> onclick="$m('console').focus();"<?}?>>

<div id="console" class="console"<?if(!isset($_POST['msg'])&&!isset($_GET['c'])){?> placeholder="<?=$console_placeholder;?>" contenteditable="true" onKeyUp="checkField(this);"<?}?>><?=$msg;?></div>
<?if(!isset($_POST['msg'])&&!isset($_GET['c'])){?>
<div id="submit_btn" class="submit_btn" onclick="$m('msg_form').submit();">Save message</div>
<form id="msg_form" action="" method="post"><input type="hidden" id="msg" name="msg" value="" /><input type="hidden" id="rawmsg" name="rawmsg" value="" /></form>
<?}elseif(isset($_POST['msg'])&&$_POST['msg']!=""){?>
<span class="msglinkmsg">Copy The Link:</span><input type="text" id="msglink" class="msglink" value="http://<? echo $_SERVER[HTTP_HOST].dirname($_SERVER['PHP_SELF']);?><?=$code;?>" onmouseover="this.focus();this.setSelectionRange(0,this.value.length);" onclick="this.focus();this.setSelectionRange(0,this.value.length);" readonly="readonly" onkeydown="return false;" />
<?}elseif(isset($_GET['c'])&&$_GET['c']!=""){?>
<div id="newmsg_btn" class="newmsg_btn" onclick="document.location.href='http://messages.danielagodard.com';">New message</div>
<?}?>

<script src="./js/main.js"></script>

</body>
</html>
<?exit;?>