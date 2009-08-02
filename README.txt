/*
 *  Project: image_captcha A php class for creating captcha images using pictures instead of hard to read text
 *  File: image_captcha.php
 *  Copyright 2009 Jason Clark <mithereal@yahoo.com>
 *  version 1.00 - 08/02/09
 *  
 */
 
This is image_captcha
Why this was created: I was unsatisfied with the garbled and hard to read texts of common captchas available, most of the time I either fat fingered the code, missed a space or the galaxy would spew chunks of err. "incorect code".
so my answer image_captcha

This will pull the images out of a user defined dir and obfuscate them before rendering them to the screen
then it will allow the user to select the correct image via a select box or a radio button.
This also allows the admin to choose the number of random words or filenames that will appear in the select box or radio field.
Thumbnail support is available, in which missing thumbs are automatically generated from larger images and then displayed.
Audio support is also provided for the visually impared.
typical file structure is as follows
[images] -> images to display
[images/thumbnails] -> thumbnails of images
[words/wordlist.txt] -> the wordlist 
[audio] -> the names of the audio files, they must match imagenames (without file extension) and have the file extension '.wav'.

Donations are much appreciated
and go to the unemployment/food to mouth fund!! 
paypal: mithereal@yahoo.com

Usage:
Place your images in the images dir.
Underscores will be changed to spaces in the selection field.
thumbnails support provided/depend via phpThumb, you can download it freely from http://phpthumb.sourceforge.net, place in phpThumb dir.
to configure edit image_captcha.php.
thumbnails are turned off by default.
If you have thumbnails turned on they will be created.
Thumbs are created only once. 
If you change the thumbnail size later, you will want to delete everything in the thumbnails directory for the size change to take effect.

This code was inspired by Drew Phillips
<drew@drew-phillips.com> 
creator of securimage


