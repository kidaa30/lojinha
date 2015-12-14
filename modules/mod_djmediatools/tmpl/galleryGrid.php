<?php
/**
 * @version $Id: galleryGrid.php 62 2015-06-22 18:22:28Z szymon $
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

// no direct access
defined('_JEXEC') or die ('Restricted access'); 

$tip = ($params->get('desc_position')=='tip' ? true : false);
if($tip){
	$toolTipArray = array(
		'showDelay'=>'0',
		'hideDelay'=>'200', 'fixed'=>false,
		'onShow'=>"function(tip) {tip.fade('in');}", 
		'onHide'=>"function(tip) {tip.fade('out');}",
		'offsets'=>array('x'=>20, 'y'=>20));
	JHTML::_('behavior.tooltip', '.descTip', $toolTipArray); 
}
?>

<div id="dj-galleryGrid<?php echo $mid; ?>" class="dj-galleryGrid">
	<div class="dj-galleryGrid-in">
		<div class="dj-slides">
        	
          	<?php foreach ($slides as $slide) { ?>
			
				<div class="dj-slide <?php echo $tip ? 'descTip':'' ?>" <?php echo $tip ? 'title="'.htmlspecialchars($slide->title).'::'.htmlspecialchars($slide->description).'"':''?>>
					<div class="dj-slide-in">
						<?php $image = 	'<img src="'.$params->get('blank').'" data-src="'.$slide->resized_image.'" '
 									.	(!empty($slide->srcset) ? ' data-srcset="'.$slide->srcset.'" data-sizes="'.$slide->sizes.'" ':'')
 									.	'alt="'.$slide->alt.'" class="dj-image" width="'.$slide->size->w.'" height="'.$slide->size->h.'" />'; ?>
            			<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1) > 1) { ?>
							<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_imagelink'); ?>
						<?php } else { ?>
							<?php echo $image; ?>
						<?php } ?>		
						
						<?php if(!$tip && ($params->get('show_title') || ($params->get('show_desc') && !empty($slide->description)) || ($params->get('show_readmore') && $slide->link))) { ?>
						<div class="dj-slide-desc">
							<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_description'); ?>
						</div>
						<?php } ?>
					</div>
				</div>
				
            <?php } ?>
        	
        </div>
		
		<div style="clear: both"></div>
	</div>
</div>

