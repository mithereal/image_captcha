<?
/*
* Secimage in action
*/
include 'image_captcha.php';
$image=new captcha;
header("Expires: Sun, 1 Jan 2000 12:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$image->show(); 

?>
