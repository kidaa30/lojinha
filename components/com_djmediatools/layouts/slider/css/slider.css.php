<?php 
/**
 * @version $Id: slider.css.php 58 2015-06-10 12:15:24Z szymon $
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

// Get slider parameters
$mid = isset($options['mid']) ? $options['mid'] : $_GET['mid'];
$slider_type = isset($options['st']) ? $options['st'] : $_GET['st'];
$slide_width = isset($options['w']) ? $options['w'] : $_GET['w'];
$slide_height = isset($options['h']) ? $options['h'] : $_GET['h'];
$slider_width = isset($options['sw']) ? $options['sw'] : $_GET['sw'];
$slider_height = isset($options['sh']) ? $options['sh'] : $_GET['sh'];
$spacing = isset($options['s']) ? $options['s'] : $_GET['s'];
$desc_width = isset($options['dw']) ? $options['dw'] : $_GET['dw'];
$desc_bottom = isset($options['db']) ? $options['db'] : $_GET['db'];
$desc_left = isset($options['dl']) ? $options['dl'] : $_GET['dl'];
$arrows_top = isset($options['at']) ? $options['at'] : $_GET['at'];
$arrows_horizontal = isset($options['ah']) ? $options['ah'] : $_GET['ah'];
$show_buttons = isset($options['sb']) ? $options['sb'] : $_GET['sb'];
$show_arrows = isset($options['sa']) ? $options['sa'] : $_GET['sa'];
$show_custom_nav = isset($options['sc']) ? $options['sc'] : $_GET['sc'];
$custom_nav_pos = isset($options['cnp']) ? $options['cnp'] : $_GET['cnp'];
$custom_nav_align = isset($options['cna']) ? $options['cna'] : $_GET['cna'];
$resizing = isset($options['r']) ? $options['r'] : $_GET['r'];

$image_width = 'max-width: 100%';
$image_height = 'max-height: 100%';

switch($resizing){
	case 'crop':
	case 'toWidth':
		$image_width = 'width: 100%';
		$image_height = 'height: auto';
		break;
	case 'toHeight':
		$image_width = 'width: auto';
		$image_height = 'height: 100%';
		break;
}

/* DON'T CHANGE ANYTHING UNLESS YOU ARE SURE YOU KNOW WHAT YOU ARE DOING */

/* General slider settings */ ?>
#djslider-loader<?php echo $mid; ?> {
	margin: 10px auto;
	position: relative;
	background: url(<?php echo $ipath ?>/images/loader.gif) center center no-repeat;
}
#djslider<?php echo $mid; ?> {
	opacity: 0;
	margin: 0 auto;
	position: relative;
	height: <?php echo $slider_height; ?>px; 
	width: <?php echo $slider_width; ?>px;
	max-width: <?php echo $slider_width; ?>px;
}
#slider-container<?php echo $mid; ?> {
	position: absolute;
	overflow: hidden;
	left: 0; 
	top: 0;
	height: 100%; 
	width: 100%;
}
#djslider<?php echo $mid; ?> ul#slider<?php echo $mid; ?> {
	margin: 0 !important;
	padding: 0 !important;
	border: 0 !important;
}
#djslider<?php echo $mid; ?> ul#slider<?php echo $mid; ?> > li:before {
	content: none;
}
#djslider<?php echo $mid; ?> ul#slider<?php echo $mid; ?> > li {
	list-style: none outside !important;
	<?php if($slider_type == 'left') { ?>
		float: left;
		padding: 0 <?php echo $spacing; ?>px 0 0 !important;
	<?php } else if($slider_type == 'right') { ?>
		float: right;
		padding: 0 0 0 <?php echo $spacing; ?>px !important;
	<?php } else if($slider_type == 'up') { ?>
		padding: 0 0 <?php echo $spacing; ?>px 0 !important;
	<?php } else if($slider_type == 'down') { ?>
		padding: <?php echo $spacing; ?>px 0 0 0 !important;
	<?php } else { ?>
		padding: 0 !important;
	<?php } ?>
	margin: 0 !important;
	border: 0 !important;
	position: relative;
	height: <?php echo $slide_height; ?>px;
	width: <?php echo $slide_width; ?>px;
	background: none;
	overflow: hidden;
	text-align: center;
}
#slider<?php echo $mid; ?> > li img {
	<?php echo $image_width; ?>;
	<?php echo $image_height; ?>;
	border: 0 !important;
	margin: 0 !important;
}
#slider<?php echo $mid; ?> > li a img, #slider<?php echo $mid; ?> > li a:hover img {
	border: 0 !important;
}
#slider<?php echo $mid; ?> .video-icon {
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
#slider<?php echo $mid; ?> .dj-slide-desc {
	position: absolute;
	bottom: <?php echo $desc_bottom; ?>%;
	left: <?php echo $desc_left; ?>%;
	width: <?php echo $desc_width; ?>%;
}
#slider<?php echo $mid; ?> .dj-slide-desc-in {
	position: relative;
	<?php if($slider_type == 'left') { ?>
		margin: 0 <?php echo $spacing; ?>px 0 0 !important;
	<?php } else if($slider_type == 'right') { ?>
		margin: 0 0 0 <?php echo $spacing; ?>px !important;
	<?php } else if($slider_type == 'up') { ?>
		margin: 0 0 <?php echo $spacing; ?>px 0 !important;
	<?php } else if($slider_type == 'down') { ?>
		margin: <?php echo $spacing; ?>px 0 0 0 !important;
	<?php } else { ?>
		margin: 0 !important;
	<?php } ?>
}
#slider<?php echo $mid; ?> .dj-slide-desc-bg {
	position:absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: #000;
	opacity: 0.5;
	filter: alpha(opacity = 50);
}
#slider<?php echo $mid; ?> .dj-slide-desc-text {
	position: relative;
	color: #ccc;
	padding: 10px;
	text-align: left;
}
#slider<?php echo $mid; ?> .dj-slide-desc-text p {
	display: block;
	padding: 0;
}
#slider<?php echo $mid; ?> .dj-slide-desc-text a {
	color: #f5f5f5;
}
#slider<?php echo $mid; ?> .dj-slide-title {
	font-size: 1.3em;
	font-weight: bold;
	line-height: 1.1;
	color: #f5f5f5;
	margin-bottom: 5px;
}
#slider<?php echo $mid; ?> .dj-slide-title a {
	background: none;
}
#slider<?php echo $mid; ?> .dj-readmore-wrapper {
	padding: 5px 0 0;
	text-align: right;
}
#slider<?php echo $mid; ?> a.dj-readmore {
	font-size: 1.1em;
}
#slider<?php echo $mid; ?> .dj-extra {
	float: right;
	margin: 0 0 5px 20px;
}

<?php /* Navigation buttons settings */ ?>
#navigation<?php echo $mid; ?> {
	position: relative;
	top: <?php echo $arrows_top; ?>%; 
	margin: 0 <?php echo $arrows_horizontal; ?>px;
	text-align: center !important;
}
#prev<?php echo $mid; ?> {
	cursor: pointer;
	display: block;
	position: absolute;
	left: 0;
	<?php if(!$show_arrows) { ?>
		top: -9999px;
	<?php } ?>
}
#next<?php echo $mid; ?> {
	cursor: pointer;
	display: block;
	position: absolute;
	right: 0;
	<?php if(!$show_arrows) { ?>
		top: -9999px;
	<?php } ?>
}
#play<?php echo $mid; ?>, 
#pause<?php echo $mid; ?> {
	cursor: pointer;
	display: block;
	position: absolute;
	left: 50%;
	<?php if(!$show_buttons) { ?>
		top: -9999px;
	<?php } ?>
}

<?php /* Slide indicators settings */ ?>
<?php if($show_custom_nav) { ?>
#cust-navigation<?php echo $mid; ?> {
	<?php if($custom_nav_pos=='above') { ?>
		margin: 0 auto 10px auto;
		max-width: <?php echo $slider_width; ?>px;
	<?php } else if($custom_nav_pos=='topin') { ?>
		position: absolute;
		z-index: 15;
		width: 100%;
		top: 10px;
	<?php } else if($custom_nav_pos=='bottomin') { ?>
		position: absolute;
		z-index: 15;
		width: 100%;
		bottom: 10px;
	<?php } else if($custom_nav_pos=='below') { ?>
		margin: 10px auto 0 auto;
		max-width: <?php echo $slider_width; ?>px;
	<?php } ?>
	
}
#cust-navigation<?php echo $mid; ?> .cust-navigation-in {
	text-align: <?php echo $custom_nav_align ?>;
	padding: 0 10px;
}
#cust-navigation<?php echo $mid; ?> span.load-button {
	width: 10px;
	height: 10px;
	display: inline-block;
	background: #000;
	border: 2px solid #fff;
	box-shadow: 0 0 2px #999;
	margin: 2px;
	cursor: pointer;
	border-radius: 7px;
	-moz-border-radius: 7px;
	opacity: 0.2;
	filter: alpha(opacity = 20);
}
#cust-navigation<?php echo $mid; ?> span.load-button-active {
	opacity: 0.8;
	filter: alpha(opacity = 80);
}
<?php } ?>