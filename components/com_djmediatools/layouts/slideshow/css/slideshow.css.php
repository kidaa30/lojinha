<?php 
/**
 * @version $Id: slideshow.css.php 58 2015-06-10 12:15:24Z szymon $
 * @package DJ-MediaTools
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-MediaTools is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-MediaTools is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-MediaTools. If not, see <http://www.gnu.org/licenses/>.
 *
 */
defined('_JEXEC') or die;
//Header ("Content-type: text/css");

// Get slideshow parameters
$mid = isset($options['mid']) ? $options['mid'] : $_GET['mid'];
$slide_width = isset($options['w']) ? $options['w'] : $_GET['w'];
$slide_height = isset($options['h']) ? $options['h'] : $_GET['h'];
$desc_position = isset($options['dp']) ? $options['dp'] : $_GET['dp'];
$desc_width = isset($options['dw']) ? $options['dw'] : $_GET['dw'];
$arrows_top = isset($options['at']) ? $options['at'] : $_GET['at'];
$arrows_horizontal = isset($options['ah']) ? $options['ah'] : $_GET['ah'];
$custom_nav_pos = isset($options['cnp']) ? $options['cnp'] : $_GET['cnp'];
$custom_nav_align = isset($options['cna']) ? $options['cna'] : $_GET['cna'];
$loader_position = isset($options['lip']) ? $options['lip'] : $_GET['lip'];
if($desc_position == 'over') {
	$desc_bottom = isset($options['db']) ? $options['db'] : $_GET['db'];
	$desc_left = isset($options['dl']) ? $options['dl'] : $_GET['dl'];
}
$resizing = isset($options['r']) ? $options['r'] : $_GET['r'];

$img_w = 100;
if($desc_position == 'left' || $desc_position == 'right') $img_w -= $desc_width;

$image_width = 'max-width: '.$img_w.'%';
$image_height = 'max-height: 100%';

switch($resizing){
	case 'crop':
	case 'toWidth':
		$image_width = 'width: '.$img_w.'%';
		$image_height = 'height: auto';
		break;
	case 'toHeight':
		$image_width = 'width: auto';
		$image_height = 'height: 100%';
		break;
}

/* DON'T CHANGE ANYTHING UNLESS YOU ARE SURE YOU KNOW WHAT YOU ARE DOING */

/* General slideshow settings */ ?>
#dj-slideshow<?php echo $mid; ?> {
	margin: 10px auto;
	border: 0px; <?php /* must be declared in pixels */ ?>
}
#dj-slideshow<?php echo $mid; ?> .dj-slideshow-in {
	margin: 0 auto;
	width: <?php echo $slide_width; ?>px;
	max-width: <?php echo $slide_width; ?>px;
	height: <?php echo $slide_height; ?>px;
	position: relative;
	box-shadow: 0 0 5px #666;
}
#dj-slideshow<?php echo $mid; ?> .dj-slides {
	opacity: 0;
	width: 100%;
	height: 100%;
	overflow: hidden;
	position: relative;
	z-index: 5;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide {
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	overflow: hidden;
	text-align: center;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-in {
	height: 100%;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-in > a:hover {
	background: none;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide img.dj-image, 
#dj-slideshow<?php echo $mid; ?> .dj-slide a:hover img.dj-image {
	border: 0 !important;
	<?php echo $image_width; ?>;
	<?php echo $image_height; ?>;
	<?php if($desc_position=='left') { ?>
		margin: 0 0 0 <?php echo $desc_width; ?>%;
	<?php } else if($desc_position=='right') { ?>
		margin: 0 <?php echo $desc_width; ?>% 0 0;
	<?php } ?>
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-in .video-icon {
	display: block;
	position: absolute;
	left: 50%;
	top: 50%;
	width: 100px;
	height: 100px;
	margin: -50px 0 0 -50px;
	background: url(<?php echo $ipath ?>/images/video.png) center center no-repeat;
}

<?php /* Slide description area settings */ ?>
#dj-slideshow<?php echo $mid; ?> .dj-slide-desc {
	position: absolute;
	width: <?php echo $desc_width; ?>%;
	<?php if($desc_position=='over') { ?>
		bottom: <?php echo $desc_bottom; ?>%;
		left: <?php echo $desc_left; ?>%;
	<?php } else if($desc_position=='left') { ?>
		left: 0;
		bottom: 0;
	<?php } else if($desc_position=='right') { ?>
		right: 0;
		bottom: 0;
	<?php } ?>
	<?php if($desc_position!='over') { ?>
		height: 100%;
	<?php } ?>
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-desc-in {
	position: relative;
	height: 100%;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-desc-bg {
	position:absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: #000;
	<?php //if($desc_position=='over') { ?>
		opacity: 0.5;
		filter: alpha(opacity = 50);
	<?php //} ?>
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-desc-text {
	position: relative;
	color: #ccc;
	padding: 10px;
	text-align: left;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-desc-text p {
	display: block;
	padding: 0;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-desc-text a {
	color: #f5f5f5;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-title {
	font-size: 1.5em;
	font-weight: bold;
	line-height: 1.1;
	color: #f5f5f5;
	margin-bottom: 5px;
}
#dj-slideshow<?php echo $mid; ?> .dj-slide-title a {
	background: none;
}
#dj-slideshow<?php echo $mid; ?> .dj-readmore-wrapper {
	padding: 5px 0;
	text-align: right;
}
#dj-slideshow<?php echo $mid; ?> a.dj-readmore {
	font-size: 1.1em;
}
#dj-slideshow<?php echo $mid; ?> .dj-extra {
	float: right;
	margin: 0 0 5px 20px;
}

<?php /* Navigation buttons settings */ ?>
#dj-slideshow<?php echo $mid; ?> .dj-navigation {
	position: absolute;
	top: <?php echo $arrows_top; ?>%;
	width: 100%;
	z-index: 10;
}
#dj-slideshow<?php echo $mid; ?> .dj-navigation-in {
	position: relative;
	margin: 0 <?php echo $arrows_horizontal; ?>px;
}
#dj-slideshow<?php echo $mid; ?> .dj-navigation .dj-prev {
	cursor: pointer;
	display: block;
	position: absolute;
	left: 0;
}
#dj-slideshow<?php echo $mid; ?> .dj-navigation .dj-next {
	cursor: pointer;
	display: block;
	position: absolute;
	right: 0;
}
#dj-slideshow<?php echo $mid; ?> .dj-navigation .dj-play, 
#dj-slideshow<?php echo $mid; ?> .dj-navigation .dj-pause {
	cursor: pointer;
	display: block;
	position: absolute;
	left: 50%;
	margin-left: -18px;
}

<?php /* Slide indicators settings */ ?>
#dj-slideshow<?php echo $mid; ?> .dj-indicators {
	<?php if($custom_nav_pos=='above') { ?>
		margin: 0 auto 15px auto;
		max-width: <?php echo $slide_width; ?>px;
	<?php } else if($custom_nav_pos=='topin') { ?>
		position: absolute;
		width: 100%;
		z-index: 15;
		top: 10px;
	<?php } else if($custom_nav_pos=='bottomin') { ?>
		position: absolute;
		width: 100%;
		z-index: 15;
		bottom: 10px;
	<?php } else if($custom_nav_pos=='below') { ?>
		margin: 15px auto 0 auto;
		max-width: <?php echo $slide_width; ?>px;
	<?php } ?>
}
#dj-slideshow<?php echo $mid; ?> .dj-indicators-in {
	text-align: <?php echo $custom_nav_align; ?>;
	padding: 0 10px;
}
#dj-slideshow<?php echo $mid; ?> .dj-load-button {
	width: 16px;
	height: 16px;
	display: inline-block;
	background: #222;
	border: 2px solid #ccc;
	text-align: center;
	margin: 2px;
	cursor: pointer;
	border-radius: 4px;
	opacity: 0.4;
	filter: alpha(opacity = 40);
}
#dj-slideshow<?php echo $mid; ?> span.dj-load-button-active {
	opacity: 1;
	filter: alpha(opacity = 100);
}
#dj-slideshow<?php echo $mid; ?> .dj-load-button .dj-key {
	display: block;
	font-size: 10px;
	line-height: 16px;
	font-weight: bold;
	text-align: center;
	color: #fff;
}

<?php /* Loader icon styling */ ?>
#dj-slideshow<?php echo $mid; ?> .dj-loader {
	position: absolute;
	<?php if($loader_position=='tl') { ?>
		top: 10px;
		left: 10px;
	<?php } else if($loader_position=='tr') { ?>
		top: 10px;
		right: 10px;
	<?php } else if($loader_position=='bl') { ?>
		bottom: 10px;
		left: 10px;
	<?php } else if($loader_position=='br') { ?>
		bottom: 10px;
		right: 10px;
	<?php } ?>
	z-index: 20;
	width: 24px;
	height: 24px;
	display: block;
	background: url(<?php echo $ipath ?>/images/ajax-loader.gif) left top no-repeat;
	opacity: 0.8;
	filter: alpha(opacity = 80);
}

#dj-slideshow<?php echo $mid; ?> .showOnMouseOver {
	opacity: 0;
}