<?php
/**
 * @version $Id: djreviews.php 30 2015-02-25 16:01:31Z michal $
 * @package DJ-Reviews
 * @copyright Copyright (C) 2014 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJ-Reviews is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Reviews is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Reviews. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die('Restricted access');

jimport ( 'joomla.filesystem.file' );

class plgDJCatalog2DJReviews extends JPlugin {
	
    public function __construct(& $subject, $config) {
        JHTML::_ ( 'behavior.keepalive' ); // Keep session
        parent::__construct ( $subject, $config );
        $this->loadLanguage ();
    }
    
    /**
     * It returns only basic rating, without the form nor list of reviews
     * 
     * @param object $row DJ-Catalog2 Object
     * @param JRegistry $params
     * @param int $page 
     * @return boolean|Ambigous <boolean, string>
     */
    public function onAfterDJCatalog2DisplayTitle(&$row, &$params, $page = 0, $context = 'item') {
		$app = JFactory::getApplication();
		
		if (!$app->isSite()) {
			return false;
		}

		$view = $app->input->get('view');

    	$excluded_categories = $this->params->get('exclude_categories', array());
		if (in_array($row->cat_id, $excluded_categories)) {
			return false;
		}
		
		$excluded_products = explode(',', $this->params->get('exclude_items', ''));
		if (in_array($row->id, $excluded_products)) {
			return false;
		}
		
		if ($context == 'items.table' && $this->params->get('table_layout', 0) != '1') {
			return false;
		}
		
		if ($context == 'items.items' && $this->params->get('blog_layout', 1) != '1') {
			return false;
		}
		
		$group_id = $this->params->get('rating_group', false);
		if (!$group_id) {
			return false;
		}

		require_once JPath::clean(JPATH_ROOT.'/components/com_djreviews/lib/api.php');
		require_once JPath::clean(JPATH_ROOT.'/components/com_djcatalog2/helpers/route.php');
		
		$review = DJReviewsAPI::getInstance(array(
				'group' => $group_id,
				'type'  => 'com_djcatalog2.item',
				'name'	=> $row->name,
				'link'	=> DJCatalogHelperRoute::getItemRoute($row->slug, $row->catslug),
				'id'	=> $row->id
				)
		);
		
		return $review->getRatingAvg();
		
		/*if ($view == 'item') {
			return $review->getRatingFull();
		} else  {
			return $review->getRatingAvg();
		}*/
    }
    
    /**
     * Should return everything - Full rating, list of reviews and add review form
     *
     * @param object $row DJ-Catalog2 Object
     * @param JRegistry $params
     * @param int $page
     * @return boolean|Ambigous <boolean, string>
     */
    public function onAfterDJCatalog2DisplayContent(&$row, &$params, $page = 0, $context = 'item') {
        $app = JFactory::getApplication();
		
		$view = $app->input->get('view');

		if ($view != 'item') {
			return false;
		}

    	$excluded_categories = $this->params->get('exclude_categories', array());
		if (in_array($row->cat_id, $excluded_categories)) {
			return false;
		}
		
		$excluded_products = explode(',', $this->params->get('exclude_items', ''));
		if (in_array($row->id, $excluded_products)) {
			return false;
		}
		
   	 	$group_id = $this->params->get('rating_group', false);
		if (!$group_id) {
			return false;
		}
		
		require_once JPath::clean(JPATH_ROOT.'/components/com_djreviews/lib/api.php');
		require_once JPath::clean(JPATH_ROOT.'/components/com_djcatalog2/helpers/route.php');
		
		$review = DJReviewsAPI::getInstance(array(
				'group' => $group_id,
				'type'  => 'com_djcatalog2.item',
				'name'	=> $row->name,
				'link'	=> DJCatalogHelperRoute::getItemRoute($row->slug, $row->catslug),
				'id'	=> $row->id
		)
		);

		return $review->getFullReview();
    }
}