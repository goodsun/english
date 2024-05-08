<?php
	// デフォルト設定
	$short_main_lang= "JP";
	$main_lang_cord = "ja-JP";
	$main_lang_file = "jp.dat";
	$short_target_lang = "US";
	$target_lang_cord = "en-US";
	$target_lang_file = "en.dat";
	$textname = "3pigs";
	include_once("../config.php");

	$dir = "../data/";
	$class = [];
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if($file != '.' && $file != '..') {
				$class[]= $file;
				}
			}
			closedir($dh);
		}
	}

	if(isset($_GET['text'])){
		$textname = $_GET['text'];
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

				//$output .= $beforePref."[".$val.':'.$dict[strtolower($val)].']';
        $output .= $beforePref."<span class=\"wordlink\" onclick=\"dispWord('".$val."','".$dict[strtolower($val)]."')\">".$val."</span>";
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

	include_once("../data/".$textname."/section.php");
	$target_lang = explode("\n",file_get_contents("../data/".$textname."/".$target_lang_file));
	$main_lang = explode("\n",file_get_contents("../data/".$textname."/".$main_lang_file));

	$data = [];
	foreach($target_lang as $key => $val){
		$data[$key + 1] = [
			'target_lang' =>$target_lang[$key],
			'main_lang' =>$main_lang[$key],
		];
	}

	$d_en = explode("\n",file_get_contents("../dict/".$target_lang_file));
	$dict_main_lang = explode("\n",file_get_contents("../dict/".$main_lang_file));
	$dict = [];

	foreach($d_en as $key => $val){
		$dict[$val] = $dict_main_lang[$key];
	}

	$text = [];
	foreach($data as $key => $page){
		$answer = $page['main_lang'];
		$question = $page['target_lang'];
		$words = dictparse($question,$dict);

		$text[$key]['question'] = $question;
		$text[$key]['answer'] = $answer;
		$text[$key]['words'] = $words;
	}

	$result = [];
	$result['textname'] = $textname;
	$result['qlang'] = "en-US";
	$result['alang'] = "ja-JP";
	$result['text'] = $text;
	$result['dictionaly'] = $dict;
	$result['section'] = $section;
	$result['class'] = $class;
	header("Content-Type: application/json; charset=utf-8");
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

