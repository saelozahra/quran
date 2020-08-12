<?php
header("Content-type: image/png");

if(isset($_GET['page'])){
	$page = intval($_GET['page']);
	
	$bg = imagecreatefrompng("template/theme1.png");
	$name = "template/pages/page$page.png";
	$textPng = imagecreatefrompng($name);

	//$fp = fopen($name, 'rb');
	//header("Content-Length: " . filesize($name));
	//fpassthru($fp);
	
	
	
	imagecopymerge($bg, $textPng, 0, 0, -122, -133, 1000, 1535, 100);

	imagepng($bg);
	imagedestroy($bg);
	imagedestroy($textPng);

	exit;
}elseif(isset($_GET['id'])){
	
	$id 	= intval($_GET['id']);
	$font 	= $_GET['font'];
	$theme 	= $_GET['theme'];
	include('FarsiGD.php');
	include('db.php');
	define("includesfile","true");
	
	if(!empty($font)){$font = "fonts/$font.ttf";}else{$font = 'fonts/aviny.ttf';}
	
	$gd = new FarsiGD();
	$arr_test_strings=array("بسم الله الرحمن الرحیم");
	$result = db_query("SELECT * FROM `Quran` WHERE `rowid` = $id ","select");
	if(mysqli_num_rows($result)){
		while($row = mysqli_fetch_assoc($result)){
			//var_dump($row);
			array_push($arr_test_strings,$row['Arabic']);
		}//ENdWhile
	}else{
		die("آیه پیدا نشد");
	}

	//$arr_test_strings = explode("|",chunk_split($arr_test_strings,10,"|"));
	
	$im = imagecreate(1000, (count($arr_test_strings)+1)*40 );
	if(empty($theme)){
		$themeUrl = "template/theme1.png";
	}else{
		$themeUrl = "template/$theme.jpg";
	}
	$im = imagecreatefrompng($themeUrl);
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);

	$i = 313;
	foreach($arr_test_strings as $str) {
		$text = $gd->persianText($str, 'fa', 'normal');
		imagettftext($im, 50, 0, 202, $i, $black, $font, $text);
		$i += 133;
	}

	// Using imagepng() results in clearer text compared with imagejpeg()
	imagepng($im);
	imagedestroy($im);

	


}else{
	echo "دنبال چی هستی ؟";
}
?>