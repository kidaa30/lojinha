<?php
/**
 * @version $Id: slideshow_imagelink.php 21 2013-11-06 08:14:17Z szymon $
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

<?php switch($params->get('link_image',1)) {
	case 2:
		echo '<a rel="lightbox-grid'.$mid.'" title="'.htmlspecialchars($slide->title).( !empty($slide->description) ? ' - ':'').htmlspecialchars(strip_tags($slide->description,"<a><b><strong><em><i><u>")).'" ' 
			.'href="'.$slide->image.'" target="'.$slide->target.'">'.$image.'</a>';
		break;
	case 3:
		echo '<a class="djmodal" href="'.$slide->item_link.'" target="'.$slide->target.'">'.$image.(isset($slide->video) && !empty($slide->video) ? '<span class="video-icon showOnMouseOver"></span>':'').'</a>';
		break;
	default:
		//if(isset($slide->video) && !empty($slide->video)) echo $image.'<iframe width="100%" height="100%" src="'.$slide->video.'" frameborder="0" allowfullscreen></iframe>'; else
		echo '<a href="'.$slide->link.'" target="'.$slide->target.'">'.$image.(isset($slide->video) && !empty($slide->video) ? '<span class="video-icon showOnMouseOver"></span>':'').'</a>';		
} ?>
	
