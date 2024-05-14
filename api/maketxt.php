<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>TGE TOOL</title>
</head>
<style>
body { 
	color: #999999;
	background-color: #000000;
	padding: 0;
}
table{
	width:100%;
	background-color: #ccc;
	border-spacing: 1px;
}
td {
	padding: 2px 10px;
	color: #bbb;
	background-color: #222;
}
a {
	color:#FF0000;
}
.ignore{
	color:#99FFFF;
}
</style>
<body>
<?php
	echo "<p>";
	$target = "duo";
	if($_GET['target']){
		$target = $_GET['target'];
	}

	echo "<h1>TARGET TEXT : ".$target."</h1>";
	echo "<p>";
	echo "OUTPUT DIR: ".$target."_ex ";
	echo "<a class='ignore' href='?mode=publish'>PUBLISH</a>";
	echo " | <a class='ignore' href='?mode=shuffle'>SHUFFLE-PUB</a>";
	echo " | <a href='?mode=reset'>RESET</a>";
	echo " | <a href='?mode=save'>save</a>";
	echo " | <a href='?mode=load'>load</a>";
	echo "</p>";

	$d_en = explode("\n",file_get_contents("../data/".$target."/en.dat"));
	$d_jp = explode("\n",file_get_contents("../data/".$target."/jp.dat"));
	$d_idn = explode("\n",file_get_contents("../data/".$target."/idn.dat"));

	foreach($d_en as $key => $val){
		if(trim($val) != ''){
			$dict[$key][] = trim($val);
			$dict[$key][] = trim($d_jp[$key]);
			$dict[$key][] = trim($d_idn[$key]);
		}
	}


	if($_GET['mode'] == 'reset'){
			$_SESSION['ignore'] = [];
	}

	if($_GET['mode'] == 'save'){
		$savedata = serialize($_SESSION['ignore']);
		file_put_contents('./output/'.$target."_ex/selialize.dat",$savedata);
	}

	if($_GET['mode'] == 'load'){
		$loaddata = file_get_contents('./output/'.$target."_ex/selialize.dat");
		$_SESSION['ignore'] = unserialize($loaddata);
	}

	if($_GET['mode'] == 'publish' || $_GET['mode'] == 'shuffle'){
		foreach($dict as $key => $val){
			if(!in_array($key,$_SESSION['ignore'])){
				$selection[] = $val;
			}
		}


		if($_GET['mode'] == 'shuffle'){
			shuffle($selection);
		}

		foreach($selection as $key => $val){
			if(!in_array($key,$_SESSION['ignore'])){
				$output['en.dat'][] = $val[0];
				$output['jp.dat'][] = $val[1];
				$output['idn.dat'][] = $val[2];
			}
		}

		foreach($output as $key => $val){
			echo "LANGFILE: ".$key." amount: ".count($val)."<br />";
			mkdir("./output/".$target."_ex", 0700);
			$txt = implode("\n",$val);
			file_put_contents('./output/'.$target."_ex/".$key,$txt);
		}
	}


	if($_GET['ignore'] != ''){
		$ignoreid = $_GET['ignore'];
		if($dict[$ignoreid][0] != ''){
			echo 'no.'.$ignoreid.' : <b>'.$dict[$ignoreid][0]."</b>を除外";	
			$_SESSION['ignore'][] = $ignoreid;
			sort($_SESSION['ignore']);
			$_SESSION['ignore'] = array_unique($_SESSION['ignore']);
		}
	}

	if($_GET['select'] != ''){
		$selectid = $_GET['select'];
		if($dict[$selectId][0] != ''){
			echo 'no.'.$selectid.' : <b>'.$dict[$selectid][0]."</b>を追加";	
			$_SESSION['ignore'] = array_diff($_SESSION['ignore'], array($selectid));
			sort($_SESSION['ignore']);
			$_SESSION['ignore'] = array_unique($_SESSION['ignore']);
		}
	}
	
	echo "<h2>Selection</h2>";
	echo "<table style='width:100%;'>";
	foreach($dict as $key => $val){
		if(!in_array($key,$_SESSION['ignore'])){
			echo "<tr>";
				echo "<td><a href='?ignore=$key'>".$key."</td>";
				echo "<td>".$val[0]."</td>";
				echo "<td>".$val[1]."</td>";
				//echo "<td>".$val[2]."</td>";
			echo "</tr>";
		}
	}
	echo "</table>";

	echo "<h2>Ignore</h2>";
	echo "<table style='width:100%;'>";
	foreach($dict as $key => $val){
		if(in_array($key,$_SESSION['ignore'])){
			echo "<tr>";
				echo "<td><a class='ignore' href='?select=$key'>".$key."</td>";
				echo "<td>".$val[0]."</td>";
				echo "<td>".$val[1]."</td>";
				//echo "<td>".$val[2]."</td>";
			echo "</tr>";
		}
	}
	echo "</table>";

	


?>
</body>
</html>


