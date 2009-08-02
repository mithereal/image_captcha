<?
$captcha_type= NULL; //images,words or null

//error_reporting(0);

if ( session_id() == '' ) 
{ 
session_start();
}
?>
<html>
<title>This is an example of using image_captcha</title>

<?
echo '<img id="captcha" src="image_show.php" alt="CAPTCHA Image" />'
?>

<?PHP
require_once 'image_captcha.php';
$captcha=new captcha;

if (isset($_POST['code']))
{
$code_correct=$captcha->check_code($_POST['code']);

}else
{
$code_correct=0;
}
if (isset($_GET['type']))
{
if ($_GET['type'])
{
$_SESSION['type']=$_GET['type'];
}
}
?>
<br>
<br>
<form name="captcha" method="POST" action="<? 'PHP_SELF' ?>">

<?PHP
if(isset($captcha_type))
{
switch ($captcha_type)
{
default :
echo 'Select the image from the list below';
break;
case words :
echo 'what word is this ?';
break;
case images :
echo 'What is this a picture of ?';
break;
}
}
?>

<br>

<?
if (isset($_SESSION['type']))
{
switch ($_SESSION['type'])
{
default :
$select=$captcha->select_box();
echo $select;
break;
case 'radio_list' :
$radio_list=$captcha->ratio_field('horizontal');
echo $radio_list;
break;
case 'radio_field' :
$radio_field=$captcha->ratio_field();
echo $radio_field;
break;
}
}
?>

<?
if ($_SESSION['audio_location'] !=null)
{
echo '<br>';
echo ' <a href="image_play.php"><img src="icons/audio_icon.gif" alt="Listen to audio"></a> Click to listen to audio';

}
?>
<br><br>
<input type="submit" value="Submit">
</form>



<? 
if (isset($_POST['code']))
{
if ($code_correct==1)
{
switch ($captcha_type)
{
default :
echo 'That is the Correct Image';
break;
case 'words' :
echo 'That is the Correct Word';
break;
case 'images' :
echo 'That is the Correct Picture';
break;
}
}
else
{
echo 'Sorry, Please Try Again';
}
}
?>
<br><br><br>
<form name="captcha" method="GET" action="<? 'PHP_SELF' ?>">
Select the type of selection field to use
<br>
<input name="type" type="radio" value="default">Use a dropdown box
<br>
<input name="type" type="radio" value="radio_field">Use a radio field (horizontal)
<br>
<input name="type" type="radio" value="radio_list">Use a radio list (vertical)
<br>
<input type="submit" value="Set Type">
</form>

<br><br>
</html>

<?PHP //var_dump($code_correct); ?>
<?PHP //var_dump($_SESSION['audio_location']); ?>
<?PHP //var_dump($captcha->image_array); ?>