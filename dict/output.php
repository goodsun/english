<?php
	$csv = explode("\n",file_get_contents("./dict.csv"));
	$lang = [];
	foreach($csv as $row){
		$lang[] = explode(",",trim($row));
	}

	$output = [];
	foreach($lang as $key => $row){
		if($key == 0){
			$lang_code = $row;
		}else{
			foreach($row as $lcn => $word){
				if(empty($output[$lcn])){
					$output[$lcn] = ""; 
				}
				$output[$lcn] .= $word."\n"; 
			}
		}
	}
	foreach($lang_code as $key => $LangCode){
		file_put_contents($LangCode.".dat", trim($output[$key]));
	}



