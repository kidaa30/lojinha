<?php
/**
 * @version $Id: nivoSlider.php 13 2013-06-26 11:51:08Z szymon $
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
<script type="text/javascript">
	jQuery(window).load(function() { jQuery('#nivoSlider<?php echo $mid; ?>').nivoSlider({effect: 'random'}); });
</script>

	<div id="nivoSlider-wrapper<?php echo $mid; ?>" class="slider-wrapper">
    	<div id="nivoSlider<?php echo $mid; ?>" class="nivoSlider">
    		<?php foreach ($slides as $key => $slide) { ?>
    					<?php $image = '<img src="'.$slide->resized_image.'" alt="'.$slide->alt.'" class="dj-image" title="#nivo'.$mid.'Desc'.$key.'" />'; ?>
            			<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1) > 1) { ?>
							<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_imagelink'); ?>
						<?php } else { ?>
							<?php echo $image; ?>
						<?php } ?>
            <?php } ?>
    	</div>
	</div>
	<?php foreach ($slides as $key => $slide) { ?>
	<div id="nivo<?php echo $mid; ?>Desc<?php echo $key ?>" class="nivo-html-caption">
    	<?php require JModuleHelper::getLayoutPath('mod_djmediatools', 'slideshow_description'); ?>
	</div>
	<?php } ?>