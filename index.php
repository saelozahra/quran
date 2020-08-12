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
	if(empty($theme)){
		$themeUrl = "template/theme1.png";
	}else{
		$themeUrl = "template/$theme.jpg";
	}
	
	if(isset($_GET['type'])){$type 	= $_GET['type'];}else{$type = "img";}
	include('FarsiGD.php');
	include('db.php');
	define("includesfile","true");
	
	if(!empty($font)){
        $font_name = $font;
        $font = "fonts/$font.ttf";
    }else{
        $font_name = 'nahid';
        $font = 'fonts/nahid.ttf';
    }
	
	$gd = new FarsiGD();
	$arr_test_strings=array("بسم الله الرحمن الرحیم");
	$result = db_query("SELECT * FROM `Quran` WHERE `rowid` = $id ","select");
	if(mysqli_num_rows($result)){
		while($row = mysqli_fetch_assoc($result)){
			//var_dump($row);
			$arabic = str_replace("ْ","",$row['Arabic']);
			$arabic = str_replace("ٰ","",$arabic);
			$arabic = str_replace("ّ","",$arabic);
			$arr_test_strings = explode("|",chunk_split($arabic,88,"|"));
			

			$html = "<!DOCTYPE html><html lang='fa' dir='rtl' >";
			$html = $html ."<head><meta charset='utf-8'>
    <title>قرآن مجید</title> <meta property='og:image' content='https://w35.ir/quran/?id=$id' /> <meta itemprop='image' content='https://w35.ir/quran/?id=$id' /></head><body>";
			$html = $html ."<div class='outerdiv' style='background-image:url($themeUrl); position:relative; width: 350px; background-size: cover; font-family:MyFont; background-repeat: no-repeat; overflow: hidden; height: 538px; '>";
			$html = $html ."<div class='row header' style='position:absolute; top:21px; left:0; width:100%;' >";
			$html = $html .'	<div class="Juz" style="float: right;margin-right: 52px;" >جزء '.$row["Juz"].'</div>';
			$html = $html .'	<div class="Hizb" style="margin-left: 72px;float: left;" >حزب '.$row["Hizb"].'</div>';
			$html = $html ."</div>";
			$html = $html ."<div class='row main' style='top: 57px;position: absolute;left: 55px;right: 41px;'>";
			$html = $html .'	<div class="Arabic">'.$row["Arabic"].'</div><br>';
			$html = $html .'	<div class="tr">'.$row["tr"].'</div>';
			$html = $html ."</div>";
			$html = $html ."<div class='row footer' style='position:absolute; bottom:21px; left:5px; width:100%;text-align: center;' >";
			$html = $html .'	<div class="Page">'.$row["Page"].'</div>';
			$html = $html ."</div>";
			$html = $html ."</div></body></html>";
			//array_push( $arr_test_strings , $arabic );
		}//ENdWhile
	}else{
		die("آیه پیدا نشد");
	}
	
	
	$im = imagecreate(1000, (count($arr_test_strings)+1)*40 );
	$im = imagecreatefrompng($themeUrl);
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	if($type=="img"){
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
		header('Content-Type:text/html');
		echo $html;
		?>
<style>
@font-face {
  font-family: MyFont;
  src: url('<?php echo $font; ?>') format('truetype');
  font-weight: normal;
}

</style>
		<?php
	}
	


}else{
	echo "دنبال چی هستی ؟";
}
?>