<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>TOKYO GOODSUN ENGLISH</title>
</head>
<link rel="stylesheet" href="/css/main.css" media="all">
<link rel="stylesheet" href="/css/modal.css" media="all">
<script type="text/javascript" async="" src="/js/entool.js"></script>
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
	include_once("./data/".$textname."/section.php");

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

	echo "<h3><a href='/menu.php'>";
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
	$speech_question = str_replace('"','', $question);

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


	echo '
	<div id="answermodal" class="modal-container" onclick="toggleAnswerModal()">
		<div class="modal-body">
			<div class="modal-content">
					<p class="txt">'.$disp_answer.'</p>
			</div>
		</div>
	</div>
	';

	echo '
	<div id="wordmodal" class="modal-container" onclick="toggleWordModal()">
		<div class="modal-body">
			<div class="modal-content">
					<p id="wordmodalcontent" class="txt"></p>
			</div>
		</div>
	</div>
	';

	echo "<h2>";



	echo "<a id='revlink' href='?page=".($page)."&set=fail'><button class='selbutton rev'>review</button></a>";
	echo "<a id='clearlink' href='?page=".($page)."&set=delete'><button class='selbutton clear'>clear</button></a>";
	echo "<a id='masterlink' href='?page=".($page)."&set=success'><button class='selbutton master'>Master</button></a>";

	echo '<input id="answerlink" class="button" type="button" onclick="toggleAnswerModal()" value="Anser" />';
	echo '<input id="speechlink" class="button" type="button" onclick="speech(\''.str_replace("'","\'",$speech_question).'\')" value="speak" />';

	if(prevPage($arr,$page)){
		echo "<a id='prevlink' href='?page=".prevPage($arr,$page)."'><button class='button'>Prev Q.".prevPage($arr,$page)."</button></a>";
	}else{
		echo "<a id='prevlink' href='/menu.php'><button class='button'>PROGRESS</button></a>";
	}
	if(nextPage($arr,$page)){
		echo "<a id='nextlink' href='?page=".nextPage($arr,$page)."'><button class='button'>Next Q.".nextPage($arr,$page)."</button></a>";
	}else{
		echo "<a id='nextlink' href='/menu.php'><button class='button'>PROGRESS</button></a>";
	}
	echo '<p class="list">';
	foreach($section as $key => $val){
		$no = $key + 1;
		echo "<a href='?page=".($section[$key] + 1)."'>section".$no."</a> | ";
	}
	echo "<br />";
	echo " Text ";

	$dir = 'data';
	$texts = [];
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if($file != '.' && $file != '..') {
				$texts[$file]= " | <a href='?settext=".$file."&page=1'>". $file . "</a>";
				}
			}
			closedir($dh);
		}
	}

	ksort($texts);
	foreach($texts as $link){
		echo $link;
	}

	echo " | <a id='proglink' href='/menu.php'>SESSION</a>";
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
<script type="text/javascript" async="" src="/js/footer.js"></script>
</html>
