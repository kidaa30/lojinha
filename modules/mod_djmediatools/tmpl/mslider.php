<?php
/**
 * @version $Id: mslider.php 62 2015-06-22 18:22:28Z szymon $
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
defined('_JEXEC') or die ('Restricted access'); ?>

<div style="border: 0px !important;">
<div id="dj-mslider<?php echo $mid; ?>" class="dj-mslider">
	<div class="dj-mslider-in">
		<div class="dj-slides">
        	
          	<?php foreach ($slides as $slide) { ?>
			
				<div class="dj-slide">
					<div class="dj-slide-in">
						<?php $image = 	'<img src="'.$params->get('blank').'" data-src="'.$slide->resized_image.'" '
 									.	(!empty($slide->srcset) ? ' data-srcset="'.$slide->srcset.'" data-sizes="'.$slide->sizes.'" ':'')
 									.	'alt="'.$slide->alt.'" class="dj-image" width="'.$slide->size->w.'" height="'.$slide->size->h.'" />'; ?>
            			<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1) > 1) { ?>
							<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_imagelink'); ?>
						<?php } else { ?>
							<?php echo $image; ?>
						<?php } ?>
						
						<?php if(!$params->get('desc_effect')) { ?>
							<div class="dj-slide-desc">
								<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_description'); ?>
							</div>
						<?php } ?>
					</div>
				</div>
				
				<?php if($params->get('desc_effect')) { ?>
				<div class="dj-slide-desc">
					<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_description'); ?>
				</div>
				<?php } ?>
				
            <?php } ?>
        	
        </div>
        <div class="dj-navigation">
        	<div class="dj-navigation-in">
        		<?php if($params->get('show_arrows')) { ?>
	        		<img class="dj-prev <?php echo ($params->get('show_arrows')==2 ? 'showOnMouseOver' : ''); ?>" src="<?php echo $navigation->prev; ?>" alt="<?php echo JText::_('Previous'); ?>" />
					<img class="dj-next <?php echo ($params->get('show_arrows')==2 ? 'showOnMouseOver' : ''); ?>" src="<?php echo $navigation->next; ?>" alt="<?php echo JText::_('Next'); ?>" />
				<?php } ?>
				<?php if($params->get('show_buttons')) { ?>
					<img class="dj-play <?php echo ($params->get('show_buttons')==2 ? 'showOnMouseOver' : ''); ?>" src="<?php echo $navigation->play; ?>" alt="<?php echo JText::_('Play'); ?>" />
					<img class="dj-pause <?php echo ($params->get('show_buttons')==2 ? 'showOnMouseOver' : ''); ?>" src="<?php echo $navigation->pause; ?>" alt="<?php echo JText::_('Pause'); ?>" />
        		<?php } ?>
			</div>
		</div>
		<?php if($params->get('show_custom_nav')) { 
		$count = ceil(count($slides) / $params->get('visible_images'));	?>
		<div class="dj-indicators <?php echo ($params->get('show_custom_nav')==2 ? 'showOnMouseOver' : ''); ?>">
			<div class="dj-indicators-in">
				<?php for($i = 1; $i <= $count; $i++) { ?>
					<span class="dj-load-button<?php if ($i == 1) echo ' dj-load-button-active'; ?>"><span class="dj-key"><?php //echo $i; ?></span></span>
				<?php } ?>
			</div>
        </div>
		<?php } ?>
		
		<div class="dj-loader"></div>
	</div>
</div>
</div>
<div style="clear: both"></div>