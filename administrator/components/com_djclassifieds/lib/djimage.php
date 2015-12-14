<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
*
*
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
*
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');

class DJClassifiedsImage {
	
	//function makeThumb_old($adres, $nw, $nh, $ext)

	public static function makeThumb($path, $newpath, $nw = 0, $nh = 0, $keep_ratio = false, $enlarge = true, $add_watermark=-1) {
			
		//$newpath = $path.'.'.$ext.'.jpg';	
		
		$params = JComponentHelper::getParams( 'com_djclassifieds' );
		if($add_watermark<0){
			$add_watermark = $params->get('watermark', '0');
		}
		
		if($params->get('image_resize', '0')){
			$keep_ratio = true;
		}
		if (!$path || !$newpath)
		return false;
		if (! list ($w, $h, $type, $attr) = getimagesize($path)) {
			return false;
		}

		$OldImage = null;

		switch($type)
		{
			case 1:
				$OldImage = imagecreatefromgif($path);
				break;
			case 2:
				$OldImage = imagecreatefromjpeg($path);
				break;
			case 3:
				$OldImage = imagecreatefrompng($path);
				break;
			default:
				return  false;
				break;
		}
		
		if ($nw == 0 && $nh == 0) {
			$nw = 75;
			$nh = (int)(floor(($nw * $h) / $w));
		}
		elseif ($nw == 0) {
			$nw = (int)(floor(($nh * $w) / $h));
		}
		elseif ($nh == 0) {
			$nh = (int)(floor(($nw * $h) / $w));
		}
		if ($keep_ratio) {
			$x_ratio = $nw / $w;
			$y_ratio = $nh / $h;

			if (($x_ratio * $h) < $nh){
				$nh = ceil($x_ratio * $h);
			}else{
				$nw = ceil($y_ratio * $w);
			}
		}
		
		if ( ($nw > $w || $nh > $h) && !$enlarge) {
			$nw = $w;
			$nh = $h;
		}

		// check if ratios match
		$_ratio=array($w/$h, $nw/$nh);
		if ($_ratio[0] != $_ratio[1]) { // crop image

			// find the right scale to use
			$_scale=min((float)($w/$nw),(float)($h/$nh));

			// coords to crop
			$cropX=(float)($w-($_scale*$nw));
			$cropY=(float)($h-($_scale*$nh));

			// cropped image size
			$cropW=(float)($w-$cropX);
			$cropH=(float)($h-$cropY);

			$crop=ImageCreateTrueColor($cropW,$cropH);
			if ($type == 3) {
				imagecolortransparent($crop, imagecolorallocate($crop, 0, 0, 0));
				imagealphablending($crop, false);
				imagesavealpha($crop, true);
			}
			
			$cropCoeffsX = array('l' => 0, 'm' => 0.5, 'r' => 1);
			$cropCoeffsY = array('t' => 0, 'm' => 0.5, 'b' => 1);
			
			$cropAlignmentX = $params->get('crop_alignment_h', 'm');
			$cropAlignmentY = $params->get('crop_alignment_v', 'm');
			
			if (!array_key_exists($cropAlignmentX, $cropCoeffsX)) {
				$cropAlignmentX = 'm';
			}
			
			if (!array_key_exists($cropAlignmentY, $cropCoeffsY)) {
				$cropAlignmentY = 'm';
			}
			
			ImageCopy(
			$crop,
			$OldImage,
			0,
			0,
			(int)($cropX * $cropCoeffsX[$cropAlignmentX]),
			(int)($cropY * $cropCoeffsY[$cropAlignmentY]),
			$cropW,
			$cropH
			);
		}

		// do the thumbnail
		$NewThumb=ImageCreateTrueColor($nw,$nh);
		if ($type == 3) {
			imagecolortransparent($NewThumb, imagecolorallocate($NewThumb, 0, 0, 0));
			imagealphablending($NewThumb, false);
			imagesavealpha($NewThumb, true);
		}
		if (isset($crop)) { // been cropped
			ImageCopyResampled(
			$NewThumb,
			$crop,
			0,
			0,
			0,
			0,
			$nw,
			$nh,
			$cropW,
			$cropH
			);
			ImageDestroy($crop);
		} else { // ratio match, regular resize
			ImageCopyResampled(
			$NewThumb,
			$OldImage,
			0,
			0,
			0,
			0,
			$nw,
			$nh,
			$w,
			$h
			);
		}
		
		
		$watermark_path = JPATH_SITE.'/images/djcf_watermark.png';					
		
		if($add_watermark>0 && JFile::exists($watermark_path)){		
			if (list ($w_w, $w_h, $w_type, $w_attr) = getimagesize($watermark_path)) {	
				$w_size = $params->get('watermark_size', '20');
			
				$nw_w = round($nw*$w_size/100);
				$nw_ratio = $nw_w/$w_w;
				$nw_h= round($w_h*$nw_ratio);
		
				if($nw_w>$w_w || $nw_h>$w_h ){
					$nw_w=$w_w;		
					$nw_h=$w_h;		
				}				
		
				$OldWatermark = imagecreatefrompng($watermark_path);		
				$NewWatermark=ImageCreateTrueColor($nw_w,$nw_h);		
				imagecolortransparent($NewWatermark, imagecolorallocate($NewWatermark, 0, 0, 0));		
				imagealphablending($NewWatermark, false);		
				imagesavealpha($NewWatermark, true);						
		
				ImageCopyResampled($NewWatermark,$OldWatermark,0,0,0,0,$nw_w,$nw_h,$w_w,$w_h);
										
				$NewWatermark = $NewWatermark;		
				$im = $NewThumb;				
		
				// Set the margins for the stamp and get the height/width of the stamp image		
				$margin_v = 10;		
				$margin_h = 10;		
				$sx = imagesx($NewWatermark);		
				$sy = imagesy($NewWatermark);		
				
				if($params->get('watermark_alignment_h', 'l')=='r'){		
					$pos_l = $nw - $nw_w - $margin_v;		
				}else if($params->get('watermark_alignment_h', 'l')=='m'){		
					$pos_l = round($nw/2) - round($nw_w/2);		
				}else{//left		
					$pos_l = $margin_v;		
				}		
				
				if($params->get('watermark_alignment_v', 'b')=='t'){		
					$pos_t = $margin_h;		
				}else if($params->get('watermark_alignment_v', 'b')=='m'){		
					$pos_t = round($nh/2) - round($nw_h/2);		
				}else{//bottom		
					$pos_t = $nh - $nw_h - $margin_h;		
				}				
		
				//$pos_l = $nw - $nw_w - $marge_right;		
				//$pos_t = $nh - $nw_h - $marge_bottom;		
				
				imagecopy($NewThumb, $NewWatermark, $pos_l , $pos_t , 0, 0, $nw_w, $nw_h);		
				
				//header('Content-Type: image/png');imagepng($im);die();
						
				ImageDestroy($OldWatermark);		
				ImageDestroy($NewWatermark);		
			}		
		}				

		$thumb_path = $newpath;
		if (is_file($thumb_path))
		unlink($thumb_path);
		switch($type)
		{
			case 1:
				imagegif($NewThumb, $thumb_path);
				break;
			case 2:
				imagejpeg($NewThumb, $thumb_path, $params->get('image_quality', '100'));
				break;
			case 3:
				imagepng($NewThumb, $thumb_path);
				break;
		}
		//imagejpeg($NewThumb, $thumb_path, 85);

		ImageDestroy($NewThumb);
		ImageDestroy($OldImage);

		return true;
	}	
	
	public static function makeThumb_old($path, $nw = 0, $nh = 0,$ext, $keep_ratio = false, $enlarge = true) {
		
		$params = JComponentHelper::getParams( 'com_djclassifieds' );
		$newpath = $path.'.'.$ext.'.jpg';
		
		if (!$path || !$newpath)
		return false;
		if (! list ($w, $h, $type, $attr) = getimagesize($path)) {
			return false;
		}

		$OldImage = null;

		switch($type)
		{
			case 1:
				$OldImage = imagecreatefromgif($path);
				break;
			case 2:
				$OldImage = imagecreatefromjpeg($path);
				break;
			case 3:
				$OldImage = imagecreatefrompng($path);
				break;
			default:
				return  false;
				break;
		}
		
		if ($nw == 0 && $nh == 0) {
			$nw = 75;
			$nh = (int)(floor(($nw * $h) / $w));
		}
		elseif ($nw == 0) {
			$nw = (int)(floor(($nh * $w) / $h));
		}
		elseif ($nh == 0) {
			$nh = (int)(floor(($nw * $h) / $w));
		}
		if ($keep_ratio) {
			$x_ratio = $nw / $w;
			$y_ratio = $nh / $h;

			if (($x_ratio * $h) < $nh){
				$nh = ceil($x_ratio * $h);
			}else{
				$nw = ceil($y_ratio * $w);
			}
		}
		
		if ( ($nw > $w || $nh > $h) && !$enlarge) {
			$nw = $w;
			$nh = $h;
		}

		// check if ratios match
		$_ratio=array($w/$h, $nw/$nh);
		if ($_ratio[0] != $_ratio[1]) { // crop image

			// find the right scale to use
			$_scale=min((float)($w/$nw),(float)($h/$nh));

			// coords to crop
			$cropX=(float)($w-($_scale*$nw));
			$cropY=(float)($h-($_scale*$nh));

			// cropped image size
			$cropW=(float)($w-$cropX);
			$cropH=(float)($h-$cropY);

			$crop=ImageCreateTrueColor($cropW,$cropH);
			if ($type == 3) {
				imagecolortransparent($crop, imagecolorallocate($crop, 0, 0, 0));
				imagealphablending($crop, false);
				imagesavealpha($crop, true);
			}
			ImageCopy(
			$crop,
			$OldImage,
			0,
			0,
			(int)($cropX/2),
			(int)($cropY/2),
			$cropW,
			$cropH
			);
		}

		// do the thumbnail
		$NewThumb=ImageCreateTrueColor($nw,$nh);
		if ($type == 3) {
			imagecolortransparent($NewThumb, imagecolorallocate($NewThumb, 0, 0, 0));
			imagealphablending($NewThumb, false);
			imagesavealpha($NewThumb, true);
		}
		if (isset($crop)) { // been cropped
			ImageCopyResampled(
			$NewThumb,
			$crop,
			0,
			0,
			0,
			0,
			$nw,
			$nh,
			$cropW,
			$cropH
			);
			ImageDestroy($crop);
		} else { // ratio match, regular resize
			ImageCopyResampled(
			$NewThumb,
			$OldImage,
			0,
			0,
			0,
			0,
			$nw,
			$nh,
			$w,
			$h
			);
		}

		$thumb_path = $newpath;
		if (is_file($thumb_path))
		unlink($thumb_path);
		
		/*switch($type)
		{
			case 1:
				imagegif($NewThumb, $thumb_path);
				break;
			case 2:
				imagejpeg($NewThumb, $thumb_path, 85);
				break;
			case 3:
				imagepng($NewThumb, $thumb_path);
				break;
		}*/
		
		imagejpeg($NewThumb, $thumb_path, $params->get('image_quality', '100'));
		
		ImageDestroy($NewThumb);
		ImageDestroy($OldImage);

		return true;
	}
	
	public static function getAdsImages($item_ids){
		$db			= JFactory::getDBO();
		$query = "SELECT img.* FROM #__djcf_images img "
				."WHERE img.item_id IN (".$item_ids.") AND img.type='item' "
						."ORDER BY img.item_id, img.ordering";
		$db->setQuery($query);
		$items_img=$db->loadObjectList();
			foreach($items_img as $img){
				$img->thumb_s = $img->path.$img->name.'_ths.'.$img->ext;
				$img->thumb_m = $img->path.$img->name.'_thm.'.$img->ext;
				$img->thumb_b = $img->path.$img->name.'_thb.'.$img->ext;
			}
		
		return $items_img; 
	} 
	
	
	public static function resmushitThumbnails($file){
		
	// Losslessly compressing with resmush.it
		$url = 'http://www.resmush.it/ws.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('files' => '@' . $file));
		$data = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($data);
		//echo '<pre>';print_r($json);die();
		
		// download and write file only if image size is smaller
		if($json->src_size > $json->dest_size) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $json->dest);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
			$image = curl_exec($ch);
			curl_close($ch);
			
			JFile::write($file, $image);
			
			//$json->percent = 100 * ($json->src_size - $json->dest_size) / $json->src_size;
		}
		//die('done');
		return true;		
	}
   
}