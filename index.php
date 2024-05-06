<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>TOKYO GOODSUN ENGLISH</title>
</head>
<style>
body { background-color: #000000; }
.linknum {
	color:#666;
	font-size:15px;
}
.noview {
	color:#000;
	width:1px;
	height:1px;
	overflow: hidden;
}
h1 {
	font-size:40px;
	color: #cccccc;
	span {
		color: #990000;
	}
}
h2 {
	font-size:80px;
	color: #ffffff;
	margin: 10px;
}
h3 {
	font-size:40px;
	color: #ffffff;
	margin: 10px;
}
p {
	color: #9999bb;
	text-decoration: none;
}
span {
	color: #bb9999;
	text-decoration: none;
}
.fail {
	color: #8888FF;
}
.success {
	color: #FF8888;
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
	text-align: center;
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
.selbutton {
	margin:20px 2px;
	padding:20px ;
	width:32%;
	font-size:50px;
	color: #666666;
	background-color: #BBBBBB;
	border-radius: 20px;
}
</style>
<body>
<?php

	$short_main_lang= "JP";
	$main_lang_cord = "ja-JP";
	$main_lang_file = "jp.dat";
	$short_target_lang = "US";
	$target_lang_cord = "en-US";
	$target_lang_file = "en.dat";

	include_once("./config.php");

	function prevPage($arr,$page){
		arsort($arr);
		foreach($arr as $val){
			if($val < $page){
				break;
			}
		}
		return $val;
	}
	function nextPage($arr,$page){
		asort($arr);
		foreach($arr as $val){
			if($val > $page){
				break;
			}
		}
		return $val;
	}

	function delArrVal($arr,$val){
		foreach($arr as $key => $row){
			if($row == $val){
				unset($arr[$key]);
			}
		}
		return array_unique($arr);
	}

	function dictparse($word,$dict) {
		$piriods = [".",",","!","?","-"];
		$connector = ["-"];
		foreach($piriods as $piriod){
			$word  = str_replace($piriod,' '.$piriod.' ', $word);
		}
		/*
		$word  = str_replace('.',' . ', $word);
		$word  = str_replace(',',' , ', $word);
		$word  = str_replace('?',' ? ', $word);
		$word  = str_replace('!',' ! ', $word);
		*/
		$words  = explode(' ', $word);
		$wordlist = [];
		$beforePref = " ";
		foreach($words as $key => $val){
			if (in_array($val,$piriods)) {
				$output .= $val;
			} else if(empty($dict[strtolower($val)])){
				$output .= $beforePref.$val;
			} else {
				$wordlist[] = [$val,$dict[strtolower($val)]];
				$output .= $beforePref."<span onclick='wordAnswer(\"".$val."\",\"".$dict[strtolower($val)]."\")'/>".$val."</span>";
				if(count($wordlist) <= 10){
					$output .= "<span class='linknum'>".count($wordlist)."</span>";
				}
			}
			if (in_array($val,$connector)) {
				$beforePref="";
			}else{
				$beforePref=" ";
			}
		}
		$result['output'] = $output;
		$result['wordlist'] = $wordlist;
		return $result;
	}

	if($_GET['mode'] == 'basic'){
		unset($_SESSION['study_mode']);
	}
	if($_GET['mode'] == 'progress'){
		$_SESSION['study_mode'] = 'progress';
	}
	if($_GET['mode'] == 'retry'){
		$_SESSION['study_mode'] = 'retry';
	}

	if($_GET['settext'] != ""){
		$_SESSION["text"] = $_GET['settext'];
	}
	if($_SESSION["text"] == ""){
		$_SESSION["text"] = "alice";
	}
	$textname = $_SESSION['text'];

	$target_lang = explode("\n",file_get_contents("./data/".$textname."/".$target_lang_file));
	$main_lang = explode("\n",file_get_contents("./data/".$textname."/".$main_lang_file));
	$data = [];
	foreach($target_lang as $key => $val){
		$data[$key + 1] = [
			'target_lang' =>$target_lang[$key],
			'main_lang' =>$main_lang[$key],
		];
	}

	$d_en = explode("\n",file_get_contents("./dict/".$target_lang_file));
	$dict_main_lang = explode("\n",file_get_contents("./dict/".$main_lang_file));
	$dict = [];
	foreach($d_en as $key => $val){
		$dict[$val] = $dict_main_lang[$key];
	}

	include_once("./data/".$textname."/section.php");

	if($_GET['page'] == ""){
		if(isset($_SESSION['nowpage'])){
			$page = $_SESSION['nowpage'];
		}else{
			$page = 1;
		}
	} else if($_GET['page'] < count($data) AND $_GET['page'] > 0){
		$page = $_GET['page'];
	} else if ($_GET['page'] == 0){
		$page = count($data) - 1;
	}
	$_SESSION['nowpage'] = $page;



	echo "<h1>";
	echo '<a href="/"><span>E</span>GT</a> | '. $textname." | ";

	foreach($section as $start => $end){
		if($section[$start-1] < $page && $page <= $end){
			echo 'Section' . ($start)." : Q.".$page;
		}
	}

	echo "　　";
	echo "<a class='lang' href='?lang=target_lang&page=".$page."'>".$short_target_lang."</a>";
	echo " | <a class='lang' href='?lang=main_lang&page=".$page."'>".$short_main_lang."</a>";
	echo " | <a class='lang' href='?lang=no&page=".$page."'>NO</a>";

	echo ' <br /> ';
	echo "</h1>";

	echo "<h3><a href='/progress.php'>";
	if ($_SESSION['study_mode'] == 'retry'){
		$arr = $_SESSION['study_data']['fail'];
		echo "RETRY MODE";
	} else if ($_SESSION['study_mode'] == 'progress' && $_SESSION['study_data']['success']){
		echo "PROGRESS MODE";
		$arr = array_diff(array_keys($data), $_SESSION['study_data']['success']);
	} else {
		$arr = array_keys($data);
		echo "BASIC MODE";
	}
	echo "</a></h3>";

	echo "<h2>";
	if($_GET['lang']){
		$_SESSION['disp'] = $_GET['lang'];
	}

	if($_GET['set']){
		if($_GET['set'] == 'delete'){
				$_SESSION['study_data']['success'] = delArrVal($_SESSION['study_data']['success'],$page);
				$_SESSION['study_data']['fail'] = delArrVal($_SESSION['study_data']['fail'],$page);
		}
		if($_GET['set'] == 'success'){
			$_SESSION['study_data']['success'][] = $page;
			$_SESSION['study_data']['success'] = array_unique($_SESSION['study_data']['success']);
			asort($_SESSION['study_data']['success']);
			if(isset($_SESSION['study_data']['fail'])){
				$_SESSION['study_data']['fail'] = delArrVal($_SESSION['study_data']['fail'],$page);
			}
		}
		if($_GET['set'] == 'fail'){
			$_SESSION['study_data']['fail'][] = $page;
			$_SESSION['study_data']['fail'] = array_unique($_SESSION['study_data']['fail']);
			asort($_SESSION['study_data']['fail']);
			if(isset($_SESSION['study_data']['success'])){
				$_SESSION['study_data']['success'] = delArrVal($_SESSION['study_data']['success'],$page);
			}
		}
	}

	foreach($_SESSION['study_data']['success'] as $key => $val){
		if ($val == $page){
			echo '<span class="success" >Master</span> | ';
		}
	}
	foreach($_SESSION['study_data']['fail'] as $key => $val){
		if ($val == $page){
			echo '<span class="fail" >Rev</span> | ';
		}
	}

	$question = $data[$page]['target_lang'];
	$wordlist = [];
	if($_SESSION['disp'] == '' or $_SESSION['disp'] == 'target_lang'){
		$disp_answer = $data[$page]['main_lang'];
		$dictparse = dictparse($question,$dict);
		echo $dictparse['output'];
		$wordlist = $dictparse['wordlist'];
	}else if($_SESSION['disp'] == 'main_lang'){
		$disp_answer = $data[$page]['target_lang'];
		echo $data[$page]['main_lang']."<br />";
	}
	$speech_answer = $data[$page]['main_lang'];
	echo "<h2>";



	echo "<a id='revlink' href='?page=".($page)."&set=fail'><button class='selbutton'>❌</button></a>";
	echo "<a id='clearlink' href='?page=".($page)."&set=delete'><button class='selbutton'>-</button></a>";
	echo "<a id='masterlink' href='?page=".($page)."&set=success'><button class='selbutton'>⭕️</button></a>";

	echo '<input id="answerlink" class="button" type="button" onclick="answer(\''.$disp_answer.'\')" value="Anser" />';
	echo '<input id="speechlink" class="button" type="button" onclick="speech(\''.str_replace("'","\'",$question).'\')" value="speak" />';

	if(prevPage($arr,$page)){
		echo "<a id='prevlink' href='?page=".prevPage($arr,$page)."'><button class='button'>Prev Q.".prevPage($arr,$page)."</button></a>";
	}else{
		echo "<a id='prevlink' href='/progress.php'><button class='button'>PROGRESS</button></a>";
	}
	if(nextPage($arr,$page)){
		echo "<a id='nextlink' href='?page=".nextPage($arr,$page)."'><button class='button'>Next Q.".nextPage($arr,$page)."</button></a>";
	}else{
		echo "<a id='nextlink' href='/progress.php'><button class='button'>PROGRESS</button></a>";
	}
	echo '<p class="list">';
	foreach($section as $key => $val){
		$no = $key + 1;
		echo "<a href='?page=".($section[$key] + 1)."'>section".$no."</a> | ";
	}
	echo "<br />";
	echo " Text ";

	$dir = 'data';
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if($file != '.' && $file != '..') {
				echo " | <a href='?settext=".$file."'>". $file . "</a>";
				}
			}
			closedir($dh);
		}
	}
	echo " | <a id='proglink' href='/progress.php'>SESSION</a>";
	echo "<br />";
	echo " | <a id='basiclink' href='/?mode=basic'>BASIC</a>";
	echo " | <a id='practicelink' href='/?mode=progress'>PROGRESS</a>";
	echo " | <a id='reviewlink' href='/?mode=retry'>REVIEW</a>";
	echo '</p">';
	echo "<div id='answer' class='noview'>".$disp_answer."</div>";
	echo "<div id='quiz'class='noview'>".$question."</div>";
	echo "<div id='main_lang_cord' class='noview'>".$main_lang_cord."</div>";
	echo "<div id='target_lang_cord' class='noview'>".$target_lang_cord."</div>";

	foreach($wordlist as $key => $val){
		echo "<div id='wordq".($key + 1)."' class='noview'>".$val[0]."</div>";
		echo "<div id='worda".($key + 1)."' class='noview'>".$val[1]."</div>";
	}
?>
</body>
<script type="text/javascript">

	var main_lang_cord = document.getElementById('main_lang_cord').textContent
	var target_lang_cord = document.getElementById('target_lang_cord').textContent
	var que = document.getElementById('quiz').textContent
	var ans = document.getElementById('answer').textContent

	function wordAnswerByNo(num) {
			var wq = document.getElementById('wordq'+num).textContent ;
			var wa = document.getElementById('worda'+num).textContent ;
			wordAnswer(wq,wa);
	}

	document.addEventListener('keydown', function(event) {

		console.log(event.key);
		if ( event.key == '1' || event.key == '2' || event.key == '3' || event.key == '4' || event.key == '5' || event.key == '6' || event.key == '7' || event.key == '8' || event.key == '9') {
			wordAnswerByNo(event.key);
		}
		if (event.key === '0') {
			wordAnswerByNo('10');
		}
		if (event.key === '-') {
			wordAnswerByNo('11');
		}
		if (event.key === '^') {
			wordAnswerByNo('12');
		}
		if (event.key === "¥") {
			wordAnswerByNo('13');
		}
		if (event.key === "Backspace") {
			wordAnswerByNo('14');
		}


		if (event.key === 'ArrowRight') {
			document.getElementById('nextlink').click();
		}
		if (event.key === 'ArrowLeft') {
			document.getElementById('prevlink').click();
		}
		if (event.key === 'ArrowUp') {
			ansspeech(ans);
		}
		if (event.key === '_') {
			window.speechSynthesis.cancel()
		}
		if (event.key === 'ArrowDown') {
			document.getElementById('proglink').click();
		}
		if (event.key === 'Shift') {
			speech(que);
		}
		if (event.key === 'Enter') {
			alert(ans);
		}
		if (event.key === 'y') {
			console.log('progress');
			document.getElementById('proglink').click();
		}
		if (event.key === 'n') {
			console.log('basic');
			document.getElementById('basiclink').click();
		}
		if (event.key === 'm') {
			console.log('practice');
			document.getElementById('practicelink').click();
		}
		if (event.key === ',') {
			console.log('review');
			document.getElementById('reviewlink').click();
		}
		if (event.key === 'h') {
			console.log('back');
			document.getElementById('prevlink').click();
		}
		if (event.key === 'j') {
			console.log(ans);
			alert(answer);
		}
		if (event.key === 'k') {
			console.log(que);
			speech(que);
		}
		if (event.key === 'l') {
			console.log('next');
			document.getElementById('nextlink').click();
		}
		if (event.key === 'u') {
			console.log('review');
			document.getElementById('revlink').click();
		}
		if (event.key === 'i') {
			console.log('clear');
			document.getElementById('clearlink').click();
		}
		if (event.key === 'o') {
			console.log('master');
			document.getElementById('masterlink').click();
		}
		if (event.key === '.') {
			ansspeech(ans);
		}
	});

	function speech(word) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = word;
		u.lang = target_lang_cord;
		u.rate = 0.8;
		window.speechSynthesis.speak(u);
	}
	function ansspeech(word) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = word;
		u.lang = main_lang_cord;
		u.rate = 1.4;
		window.speechSynthesis.speak(u);
	}
	function answer(answer) {
		alert(answer);
	}
	function wordAnswer(question,answer) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = question;
		u.lang = target_lang_cord;
		u.rate = 0.8;
		window.speechSynthesis.speak(u);
		alert(question +' : '+ answer);
		window.speechSynthesis.cancel()
	}
</script>
</html>
