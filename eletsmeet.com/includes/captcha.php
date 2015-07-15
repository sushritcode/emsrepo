<?php
SESSION_START();
class CaptchaSecurityImages {
var $font = 'Woven Brickwork.ttf';
//var $font = 'monofont.ttf';
	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) {
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function CaptchaSecurityImages($width='100', $height='29',$characters='5') {
		$code = $this->generateCode($characters);
		$_SESSION['security_code'] = $code;

		/* font size will be 53% of the image height */
		$font_size = $height * 0.53;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

		/* set the colours */
		//$text_shadow_color = imagecolorallocate($image, 128, 128, 128);
		$background_color = imagecolorallocate($image, 255, 255, 255);

		//$text_color = imagecolorallocate($image, 20, 40, 100);
		$text_color = imagecolorallocate($image, 0, 0, 0);
		$noise_color = imagecolorallocate($image, 115, 145, 225);

		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}

		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i=($i+3)) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}

		/* create textbox and add text */

		$textbox = imagettfbbox($font_size, 0, getcwd()."/".$this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		//imagettftext($image, $font_size, 4, $x+1, $y+1, $text_color, $this->font , $code) or die('Error in imagettftext function');
		imagettftext($image, $font_size, 5, $x, $y, $text_color, getcwd()."/".$this->font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		//$_SESSION['security_code'] = $code;//$_SESSION['key'] is used for validation
	}
}

//$width = isset($_GET['width']) ? $_GET['width'] : '120';
//$height = isset($_GET['height']) ? $_GET['height'] : '40';
$width = '100';
$height = '29';
$characters = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '5';

$captcha = new CaptchaSecurityImages($width,$height,$characters);

?>
