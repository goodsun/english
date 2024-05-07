<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>TOKYO GOODSUN ENGLISH | PROGRESS</title>
</head>
<style>
body { background-color: #000000; }
h1 {
	font-size:50px;
	color: #666666;
	margin:20px 10px;
	span {
		color: #990000;
	}
}
h2 {
	font-size:80px;
	color: #ffffff;
}
p {
	margin:5px 15px;
	font-size:45px;
	color: #999999;
	text-decoration: none;
}
span {
	color: #cc9999;
	text-decoration: none;
}
a {
	color: #9999bb;
	text-decoration: none;
}
.lang {
	margin: 0 15px
}
.list {
	font-size:50px;
	color: #FFFFFF;
	text-decoration: none;
}
.button {
	margin:20px 2px;
	padding:20px ;
	width:48%;
	font-size:50px;
	color: #666666;
	background-color: #BBBBBB;
	border-radius: 20px;
}
</style>
<body>
<h1><a id="home" href="/"><span>E</span>GT</a> | PROGRESS</h1>
<?php
echo"<p>";
if($_GET['mode'] == 'clear'){
	$_SESSION['study_data'] = [];
	echo "Cleard Session Data!<br />";
}
echo "MODE: <a href='/?mode=basic'>BASIC</a>";
if ($_SESSION['study_mode'] != 'progress' && $_SESSION['study_data']['success']){
echo " | <a href='/?mode=progress'>PROGRESS</a>";
}
if ($_SESSION['study_mode'] != 'retry' && $_SESSION['study_data']['fail']){
	echo " | <a href='/?mode=retry'>RETRY</a>";
}
echo"<p>";

$arr = $_SESSION['study_data']['fail'];
if(!empty($arr)){
	echo "<h1>Review Sentence</h1>";
	echo "<p>";
	foreach($arr as $val){
		echo 'Q.<a href="/?page='.$val.'">'.$val.'</a>　';
	}
	echo "</p>";
}

$arr = $_SESSION['study_data']['success'];
if(!empty($arr)){
	echo "<h1>Master Sentence</h1>";
	echo "<p>";
	foreach($arr as $val){
		echo 'Q.<a href="/?page='.$val.'">'.$val.'</a>　';
	}
	echo "</p>";
}



?>
<p>
<!--
この単語には
<span onclick='explain("音声に応じた解説をします","explain this words")'/>音声解説</span>
がついています<br />
-->
<br />
<br />
<a href='?mode=clear'>SESSION CLEAR</a><br />
<br />
</p>

</body>
<script type="text/javascript">

	document.addEventListener('keydown', function(event) {
		if (event.key === 'ArrowUp') {
			document.getElementById('home').click();
		}
		if (event.key === '_') {
			window.speechSynthesis.cancel()
		}
	});

	function speech(word) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = word;
		u.lang = "en-US";
		u.rate = 0.8;
		window.speechSynthesis.speak(u);
	}
	function explain(info,word) {
		speech(word);
		alert(info);
	}
</script>
</html>
