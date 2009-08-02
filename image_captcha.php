<?
/*
 *  Project: image_captcha A php class for creating captcha images using pictures instead of hard to read text
 *  File:        image_captcha.php
 *  Copyright 2009 Jason Clark
 *  Version 1.00 - 09/06/09
 *  
 */
 //phpThumb is a little heavy but does the job just fine
 //
 //First selection option when using select method
 define('SELECT_TEXT' , 'Select Me');
 define('IMAGE_DIR' , 'images');
 define('NUM_OF_SELECTIONS' , 5 );  
 define('USE_WORDLIST' , 1 ); 
 define('WORDLIST_FILE' , 'words/words.txt' );
 define('THUMBNAILS' , 0 );
 // dir name inside image_dir
 define('THUMBNAIL_DIR' , 'thumbnails' ); 
 define('THUMBNAIL_WIDTH' , 200 );
 define('THUMBNAIL_HEIGHT' , 200 );
 define('PATH_TO_PHPTHUMB' , 'phpThumb' );
 define('IMAGEMAGICK_PATH' , '/usr/local/bin/convert' );
 define('AUDIO' , 0 );
 define('AUDIO_DIR' , 'audio' );
 
 
class captcha
{
var $code;
var $image_array;
var $image_w_path;
var $selection_array;
var $thumbs_array;
var $thumbs_2_add;
var $audio_w_path;

function captcha()
{
    
if ( session_id() == '' ) 
{ 
session_start();
}
$i=1;

foreach(glob(IMAGE_DIR . "/*.*") as $file)
{
$this->image_array[$i] = $file;
$i++;
}


if (THUMBNAILS == 1 && $this->image_array != null)
{
$j=0;

foreach(glob(IMAGE_DIR . "/" .THUMBNAIL_DIR . "/*.*")  as $thumb)
{
$this->thumbs_array[$j]=$thumb;
$j++;
}
$this->thumbs_2_add=$this->pic_array_diff($this->image_array,$this->thumbs_array);
if (is_array($this->thumbs_2_add) )
{
$this->thumbnail($this->thumbs_2_add);
}
}
}



function select_box() 
{
$this->selection_array=$this->make_selection_array(NUM_OF_SELECTIONS);
$array=$this->selection_array;
$output='<select name="code" size="1">';
$output .='<option value=" ">'.SELECT_TEXT.'</option>' ."\n";
foreach ($array as $value)
{
$output .='<option value="' . $value . '">' . $value . '</option>' ."\n";
}
$output .='</select>';
return $output;
}

function ratio_field($list='')
{
$this->selection_array=$this->make_selection_array(NUM_OF_SELECTIONS);
$array=$this->selection_array;
$output='';
foreach ($array as $value)
{ 
$output .='<input name="' . 'code' . '" type="radio" value="'. $value . '">' . $value ."\n";
if ($list)
{
$output .='<br>';
}
}
return $output;
}

function audio_location($filename)
{
//strip out everything after dot in $array and replace with '.wav'
$filename=preg_replace("/\.([^\.]+)$/",'.wav',$filename);
if (THUMBNAILS == 1)
{
$filename=str_ireplace( IMAGE_DIR .'/'. THUMBNAIL_DIR . '/','',$filename);
}
else
{
$filename=str_ireplace( IMAGE_DIR . '/' ,'',$filename);
}
$filename=AUDIO_DIR .'/'. $filename;
if (is_file($filename))
{
$this->audio_w_path = $filename;
}
else
{
$this->audio_w_path = NULL;
}
}

function audio()
{
if ($_SESSION['audio_location'] !=null)
{
readfile($_SESSION['audio_location']);
}
}


function show()
{
$filextension=$this->get_filetype_from_image($_SESSION['image_location']);

switch ($filextension) {
case '.jpg' :
header('Content-Type: Image/jpg');
break;
case '.jpeg' :
header('Content-Type: Image/jpg');
break;
case '.gif' :
header('Content-Type: Image/gif');
break;
case '.png' :
header('Content-Type: Image/png');
break;
}
readfile($_SESSION['image_location']); 
exit;
}

function check_code($usercode)
{
if ($_SESSION['code_value'] == $usercode)
{
$success = 1;
}else{
$success = 0;
}
return $success;
}

private function thumbnail($array)
{
// phpthumb functions (if installed)
if (is_file(PATH_TO_PHPTHUMB.'/phpthumb.class.php'))
{
include_once PATH_TO_PHPTHUMB.'/phpthumb.class.php';
$phpThumb = new phpThumb();


foreach ($array as $value)
{
$phpThumb->resetObject();
$phpThumb->setSourceFilename(IMAGE_DIR . "/$value");
$output_filename = IMAGE_DIR .'/'. THUMBNAIL_DIR.'/'.str_ireplace(IMAGE_DIR . "/",'',$value);
$phpThumb->setParameter('w', THUMBNAIL_WIDTH);
$phpThumb->setParameter('h', THUMBNAIL_HEIGHT);
$phpThumb->setParameter('f', $filename=str_ireplace('.','',$this->get_filetype_from_image($value)));
$phpThumb->setParameter('config_imagemagick_path', IMAGEMAGICK_PATH);

if ($phpThumb->GenerateThumbnail()) 
{ 
if ($phpThumb->RenderToFile($output_filename)) 
{
//echo 'Successfully rendered to "'.$output_filename.'"';
} else 
{
//echo 'Failed:<pre>'.implode("\n\n", $phpThumb->debugmessages).'</pre>';
} 
} else 
{
// do something with debug/error messages
//echo 'Failed:<pre>'.$phpThumb->fatalerror."\n\n".implode("\n\n", $phpThumb->debugmessages).'</pre>';
}
}
}
//default thumbnailer

}

private function pic_array_diff($image,$thumb)
{
$fullsize_array=str_ireplace(IMAGE_DIR . "/",'',$image);
if ($thumb == null)
{
$diff=$fullsize_array;
}else{
$thumbs_array=str_ireplace(IMAGE_DIR ."/" .THUMBNAIL_DIR ."/",'',$thumb);
$diff=array_diff($fullsize_array,$thumbs_array);
}
sort($diff);
return $diff;
}

private function get_filetype_from_image($image)
{
preg_match("/\.([^\.]+)$/",$image,$filextension);
if (is_array($filextension))
{
$filextension=$filextension[0];
}
return $filextension;
}

private function filename_2_code($filename) 
{
$replacearray[0]=$this->get_filetype_from_image($filename);
$replacearray[1]=IMAGE_DIR ."/";
$replacearray[2]=THUMBNAIL_DIR . "/";
$code=str_ireplace($replacearray,'',$filename);
$code=str_ireplace('_',' ',$code);
$this->code=$code;
return $code;
}

private function make_selection_array($num) 
{
if (THUMBNAILS == 1)
{
if (is_array($this->thumbs_array))
{
shuffle($this->thumbs_array);
}
$this->code=$this->filename_2_code($this->thumbs_array[1]);
$this->image_w_path=$this->thumbs_array[1];
$this->audio_location($this->thumbs_array[1]);
$this->saveData();
}else{
if (is_array($this->image_array))
{
shuffle($this->image_array);
}
$this->code=$this->filename_2_code($this->image_array[1]);
$this->image_w_path=$this->image_array[1];
$this->audio_location($this->image_array[1]);
$this->saveData();
}
if(USE_WORDLIST == 1)
{
$select_array[0]=$_SESSION['code_value'];
for ($i=1;$i<$num;$i++)
{
$select_array[$i]=$this->readTextFromFile();
if ($select_array[$i] == NULL)
{
$select_array[$i]=$this->readTextFromFile();
}
}
}else
{
$select_array[0]=$_SESSION['code_value'];
$j=1;
if (THUMBNAILS==0)
{
$k=count($this->image_array);
}else
{
$k=count($this->image_array)+1;
}

for ($i=0;$i<$num;$i++)
{
if ($j<$k)
{
$select_array[$i]=$this->filename_2_code($this->image_array[$j]);
$j++;
}
else
{
$select_array[$i]=$this->readTextFromFile();

}
}


}
shuffle($select_array);
return $select_array;
}

private function readTextFromFile()
  {
    $fp = @fopen(WORDLIST_FILE, 'rb');
    if (!$fp) return false;

    $fsize = filesize(WORDLIST_FILE);
    if ($fsize < 32) return false; // too small of a list to be effective

    if ($fsize < 128) {
      $max = $fsize; // still pretty small but changes the range of seeking
    } else {
      $max = 128;
    }

    fseek($fp, rand(0, $fsize - $max), SEEK_SET);
    $data = fread($fp, 128); // read a random 128 bytes from file
    fclose($fp);
    $data = preg_replace("/\r?\n/", "\n", $data);

    $start = strpos($data, "\n", rand(0, 100)) + 1; // random start position
    $end   = strpos($data, "\n", $start);           // find end of word

    return strtolower(substr($data, $start, $end - $start)); // return substring in 128 bytes
  }
  
private function saveData()
  {
    $_SESSION['code_value'] = strtolower($this->code);
	$_SESSION['image_location'] = strtolower($this->image_w_path);
	$_SESSION['audio_location'] = $this->audio_w_path;
	
  }
  
}
