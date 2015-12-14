<?php
/**
* @version 2.0
* @package DJ Classifieds
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
defined( '_JEXEC' ) or die( 'Restricted access' );




jimport('joomla.plugin.plugin');
jimport('joomla.utilities.utility');
class plgDJClassifiedsPagebreak extends JPlugin{
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	function onPrepareItemDescription( &$row, &$params, $page=0 )
	{
		$app = JFactory::getApplication();
		if(JRequest::getVar('view')!='item'){
			return true;
		}
		// expression to search for
		$regex = '#<hr([^>]*?)class=(\"|\')system-pagebreak(\"|\')([^>]*?)\/*>#iU';
	
		// Get Plugin info
		$pluginParams	= $this;
		if (!$pluginParams->get('enabled', 1)) {
			return true;
		}
		JPlugin::loadLanguage( 'plg_djclassifieds_pagebreak', JPATH_ADMINISTRATOR );
		// replacing readmore with <br /> - we don't need it
		$row->description = str_replace("<hr id=\"system-readmore\" />", "<br />", $row->description);
	
	    if ( strpos( $row->description, 'class="system-pagebreak' ) === false && strpos( $row->description, 'class=\'system-pagebreak' ) === false ) {
			return true;
		}
	
	    $view  = $app->input->get('view', null, 'string');
	
		if (!JPluginHelper::isEnabled('djclassifieds', 'pagebreak') || ($view != 'item' && $view != 'itemstable' && $view != 'items' && $view != 'producer')) {
			$row->description = preg_replace( $regex, '', $row->description );
			return;
		}
	
		// find all instances of plugin and put in $matches
		$matches = array();
		preg_match_all( $regex, $row->description, $matches, PREG_SET_ORDER );
		
		// split the text around the plugin
		$text = preg_split( $regex, $row->description );
		$title = array();
	
		// count the number of pages
		$n = count( $text );
	
		if ($n > 1)
		{
			$pluginParams = $this->params;
            $style = $pluginParams->get( 'accordion', 2 );
			
			$row->description = '';
			$row->description .= $text[0];
			
			$i = 1;
			
			foreach ( $matches as $match ) {
				if ( @$match[0] )
				{
					$attrs = JUtility::parseAttributes($match[0]);
		
					if ( @$attrs['alt'] )
					{
						$title[] = stripslashes( $attrs['alt'] );
					}
					elseif ( @$attrs['title'] )
					{
						$title[] = stripslashes( $attrs['title'] );
					}
					else
					{
						$title[] =  JText::sprintf( 'PLG_DJCLASSIFIEDS_PAGEBREAK_TOGGLE', $i );
					}
				}
				else
				{
					$title[] =  JText::sprintf( 'PLG_DJCLASSIFIEDS_PAGEBREAK_TOGGLE', $i );
				}
				$i++;
			}
			
			$group_id = 'tab-'.htmlspecialchars($row->alias).'-';
			
			$row->tabs = '';
			if ($style == '1') {
				$row->tabs .= '<div class="accordion">';
				
				for($i = 1; $i < $n; $i++) {
				    $class = ($i == 1) ? 'class="accordion-body collapse in"' : 'class="accordion-body collapse"';
				    $row->tabs .= '<div class="accordion-group">';
					$row->tabs .= '<div class="accordion-heading"><a class="accordion-toggle">'.$title[$i-1].'</a></div>';
					$row->tabs .= '<div '.$class.' id="'.$group_id.$i.'"><div class="accordion-inner">'.$text[$i].'</div></div>';
                    $row->tabs .= '</div>';
				}
				
				$row->tabs .= '</div>';
			}
			else if ($style == '2') {
				
				$row->tabs .='<ul class="nav nav-tabs">';
				for($i = 1; $i < $n; $i++) {
				    $class = ($i == 1) ? 'class="nav-toggler active"' : 'class="nav-toggler"';
					$row->tabs .= '<li '.$class.'><a>'.$title[$i-1].'</a></li>';
				}
				$row->tabs .='</ul>';
				$row->tabs .= '<div class="tab-content">';
				for($i = 1; $i < $n; $i++) {
				    $class = ($i == 1) ? 'class="tab-pane active"' : 'class="tab-pane"';
					$row->tabs .= '<div '.$class.' id="'.$group_id.$i.'">';
					$row->tabs .= '<div>'.$text[$i].'</div>';
					$row->tabs .= '</div>';
				}
				$row->tabs .= '</div>';
			}
			else {
				$row->tabs .= '<div class="djcf_pagebreak">';
				
				for($i = 1; $i < $n; $i++) {
					$row->tabs .= '<h3 class="djcf_pagebreak-title">'.$title[$i-1].'</h3>';
					$row->tabs .= '<div class="djcf_pagebreak-content">'.$text[$i].'</div>';
				}
				
				$row->tabs .= '</div>';
			}
		}
	
		return true;
	}
	
}


