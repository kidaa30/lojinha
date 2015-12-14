<?php
/**
 * @version $Id: panels.php 40 2014-09-08 14:28:34Z szymon $
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

	<ul id="djkwicks<?php echo $mid; ?>" class="kwicks kwicks-horizontal">
		<?php $key = 0; foreach ($slides as $slide) { ?>
		<li class="djpanel-<?php echo (++$key) . ($params->get('autoplay') && $key==1 ? ' kwicks-selected':'') ?>">
			
			<?php 
				$image = '<span class="dj-image" style="background-image: url('.$slide->grayscale_image.')">'
 						.'<span class="dj-image-color" style="background-image: url('.$slide->resized_image.')"></span>'
 						.'</span>';
			?>
			
			<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1) > 1) { ?>
				<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_imagelink'); ?>
			<?php } else { ?>
				<?php echo $image; ?>
			<?php } ?>
						
			<div class="dj-slide-desc">
				<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_description'); ?>
			</div>
					
		</li>
		<?php } ?>
	</ul>
	
</div>
<div style="clear: both"></div>