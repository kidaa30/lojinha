<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
defined ('_JEXEC') or die('Restricted access');
JHTML::_('behavior.framework' );
JHTML::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHTML::_('behavior.calendar');
$toolTipArray = array('className'=>'djcf_label');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
$par = $this->par;
$imglimit = $par->get('img_limit','3');
$imgfreelimit = $par->get('img_free_limit','-1');
$img_maxsize = $par->get('img_maxsize',0);
$up_img_maxsize = 10240;
if($img_maxsize){
	$up_img_maxsize = 1024*$img_maxsize;	
}
$points_a = $par->get('points',0);
$id = JRequest::getVar('id', 0, '', 'int' );

$document= JFactory::getDocument();
$document->addScript(JURI::root().'/components/com_djclassifieds/assets/djuploader.js');
$settings = array();
$settings['max_file_size'] = $up_img_maxsize.'kb';
$settings['chunk_size'] = '1024kb';
$settings['resize'] = true;
$settings['width'] = $par->get('upload_width','1600');
$settings['height'] = $par->get('upload_height','1200');
$settings['quality'] = $par->get('upload_quality','90');
$settings['filter'] = 'jpg,png,gif';
$settings['onUploadedEvent'] = 'injectFrontUploaded';
$settings['onAddedEvent'] = 'startUploadLimit';
$settings['label_generate'] = $par->get('image_label_from_name','1');
//$settings['debug'] = true;
$this->uploader = DJUploadHelper::getUploader('uploader', $settings);
?>

	<div class="images_box additem_djform">
		<div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES');	?></div>
		<div class="additem_djform_in">
			<div class="img_info">
			<?php 	
				if($img_maxsize>0){
					echo '<div class="img_info_row">'.JText::_('COM_DJCLASSIFIEDS_MAX_IMAGE_SIZE').': <span>'.$img_maxsize.' MB </span></div>';
				}
				echo '<div class="img_info_row">'.JText::_('COM_DJCLASSIFIEDS_IMAGES_LIMIT').': <span>'.$imglimit.'</span></div>';
				if($imgfreelimit>-1){
					echo '<div class="img_info_row">'.JText::_('COM_DJCLASSIFIEDS_FREE_IMAGES_LIMIT').': <span id="free_img_limit">'.$imgfreelimit.'</span></div>';
										
					$img_price	= $par->get('img_price','0');
					$img_price_points	= $par->get('img_price_points','0');
					
					if($id==0 && $par->get('durations_list','')){
						$exp_days_def = $par->get('exp_days','7');
						if(isset($this->days[$exp_days_def])){
							if($this->days[$exp_days_def]->img_price_default==0){
								$img_price = $this->days[$exp_days_def]->img_price;
								$img_price_points = $this->days[$exp_days_def]->img_points;								
							}
						}						
					}else if(isset($this->days[$this->item->exp_days])){
						if($this->days[$this->item->exp_days]->img_price_default==0){
							$img_price = $this->days[$this->item->exp_days]->img_price;
							$img_price_points = $this->days[$this->item->exp_days]->img_points;
						}
					}
					
					echo '<div class="img_info_row">'.JText::_('COM_DJCLASSIFIEDS_PRICE_FOR_ADDITIONAL_IMAGE').': <span  id="extra_img_price" >';
							echo DJClassifiedsTheme::priceFormat($img_price,$par->get('unit_price'));
							if($points_a && $img_price_points>0){
								echo '&nbsp-&nbsp'.$img_price_points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
							}										
					echo '</span></div>';										
				}
				
				?>
			</div>
			<div id="itemImagesWrap">
				<div id="itemImages">
					<?php  if($this->images) foreach($this->images as $img) { ?>
						<div class="itemImage">
							<img src="<?php echo JURI::root().$img->path.$img->name.'_thb.'.$img->ext; ?>" alt="<?php echo $this->escape($img->caption); ?>" />
							<div class="imgMask">
								<input type="hidden" name="img_id[]" value="<?php echo $this->escape($img->id); ?>">
								<input type="hidden" name="img_image[]" value="">
								<input type="text" class="itemInput editTitle" name="img_caption[]" value="<?php echo $this->escape($img->caption); ?>">
								
								<span class="delBtn"></span>
							</div>
						</div>
					<?php }  ?>
				</div>
				<div class="clear_both"></div>
			</div>
			<div id="imageslimitalert"><?php echo JText::_('COM_DJCLASSIFIEDS_IMG_LIMIT_REACHED_PLEASE_DELETE_OLD_IMAGES');?></div>
			<div>
				<?php echo $this->uploader;?>
			</div>
	</div>
	
	<script type="text/javascript">
	
		var djcf_img_limit = <?php echo $imglimit; ?>;
		function startUploadLimit(up,files) {
			var djcf_img_total = document.id('itemImages').getElements('.itemImage').length;			
			if (djcf_img_total + files.length >= djcf_img_limit && djcf_img_limit >= 0) {
				var remaining = djcf_img_limit - djcf_img_total;
				var toRemove = files.length - remaining;
				
				if (toRemove > 0 && files.length > 0){
					for (var i = files.length-1; i >= 0; i--) {
						if (toRemove <= 0) {
							break;
						}
						up.removeFile(up.files[i]);					
						toRemove--;
					}		
					document.id('imageslimitalert').setStyle('display','block');
			      	(function() {
					    document.id('imageslimitalert').setStyle('display','none');
					 }).delay(3000);	
				}					   				
			}
			djcf_img_total += files.length;		
			up.start();					
		}
		<?php if($imgfreelimit>-1 && $par->get('durations_list','') && $id==0 && count($this->days)){ ?>
		var img_prices = [];	
		document.id('exp_days').addEvent('change', function(){
			document.id('extra_img_price').innerHTML = img_prices[document.id('exp_days').value];
			});
		<?php foreach($this->days as $day){
				if($day->img_price_default==0){
					$img_price = DJClassifiedsTheme::priceFormat($day->img_price,$par->get('unit_price'));
					if($points_a && $day->img_points>0){
						$img_price .= ' - '.$day->img_points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
					}
					echo 'img_prices['.$day->days.']="'.addslashes($img_price).'"; ';
				}else{
					$img_price = DJClassifiedsTheme::priceFormat($par->get('img_price','0'),$par->get('unit_price'));
					if($points_a && $par->get('img_price_points','0')>0){
						$img_price .= ' - '.$par->get('img_price_points','0').JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
					}
					echo 'img_prices['.$day->days.']="'.addslashes($img_price).'"; ';
				}
			}
			?>
		<?php } ?> 
	</script>
</div>