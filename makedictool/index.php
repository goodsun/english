<?php
	$replace = explode("\n",file_get_contents('text/replace.txt'));
	$ignore = explode("\n",file_get_contents('text/ignore.txt'));
	foreach($ignore as $ig_word){
				 $replace[] = ' '.$ig_word.' ';
	}

	$data = file_get_contents('text/en.txt');
	$data = strtolower($data);
	$data = str_replace("\n",' ',$data);
	$data = str_replace($replace,' ',$data);
	$data = explode(" ",$data);

	sort($data);
	$data = array_unique($data);
	$result = [];
	foreach($data as $key => $word){
		if(!in_array($word , $ignore) and strlen($word) > 2){
			$result[] = $word;
		}
	}

	echo 'total:'.count($result)." words<br />";

	$dict = [];
	foreach($result as $key => $word){
		$len = strlen($word);
		$dict[$len][] = $word;
	}
	ksort($dict);

	foreach($dict as $key => $list){
		foreach($list as $word){
			echo str_replace(["'s"],"",$word).'<br />';
		}
	}

