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
jimport( 'joomla.application.component.controller' );


class DJClassifiedsTheme {
	
	function __construct(){
	}

	public static function priceFormat($price,$unit='',$price_decimals = ''){
		$app = JFactory::getApplication();
        $par = JComponentHelper::getParams( 'com_djclassifieds' );				
		$price_decimal_separator = null;
		$price_thousands_separator = null;
		if(!$price_decimals){
			$price_decimals = $par->get('price_decimals',2);
		}		
		
		if($unit){
			$unit = '<span class=\'price_unit\'>'.$unit.'</span>';
		}else{	
			$unit = '<span class=\'price_unit\'>'.$par->get('unit_price','EUR').'</span>';
		}
		
		switch($par->get('price_thousand_separator',0)) {
			case 0: $price_thousands_separator=''; break;
			case 1: $price_thousands_separator=' '; break;
			case 2: $price_thousands_separator='\''; break;
			case 3: $price_thousands_separator=','; break;
			case 4: $price_thousands_separator='.'; break;
			default: $price_thousands_separator=''; break;
		}
		
		switch($par->get('price_decimal_separator',0)) {
			case 0: $price_decimal_separator=','; break;
			case 1: $price_decimal_separator='.'; break;
			default: $price_decimal_separator=','; break;
		}
		
		$price_to_format = $price;
		if ($par->get('price_format','0')== 1) {
			$price = str_ireplace(',', '.', $price);
			if(is_numeric($price)){
				$price_to_format = number_format($price, $price_decimals, $price_decimal_separator, $price_thousands_separator);	
			}
			
		}
		
		if ($par->get('unit_price_position','0')== 1) {			
			$formated_price = $unit;
			if ($par->get('unit_price_space','1')== 1) {$formated_price .= ' ';}
			$formated_price .= $price_to_format;
		}else {
			$formated_price = $price_to_format;
			if ($par->get('unit_price_space','1')== 1) {$formated_price .= ' ';}
			$formated_price .= $unit;
		}
		return $formated_price;
		
	}
	public static function formatDate($from, $to = null, $date_format=0){
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		if($from=='2145913200'){
			return JText::_('COM_DJCLASSIFIEDS_NEVER_EXPIRE');
		}else if($date_format){
			return DJClassifiedsTheme::dateFormatFromTo($from, $to);
		}else{
			if($par->get('date_persian',0)){
				return mds_date($par->get('date_format','Y-m-d H:i:s'),$from,1);
			}else{
				return date($par->get('date_format','Y-m-d H:i:s'),$from);
			}							
		}
	}
	public static function dateFormatFromTo($from, $to = null)
	 {
	 	$par = JComponentHelper::getParams( 'com_djclassifieds' );		
	  	$to = (($to === null || $to=='') ? (time()) : ($to));
	  	$to = ((is_int($to)) ? ($to) : (strtotime($to)));
	  	$from = ((is_int($from)) ? ($from) : (strtotime($from)));
	  	$output = '';	  
	  	$limit = $par->get('date_format_ago_limit','2');
	  	$units = array
	  	(
		   "COM_DJCLASSIFIEDS_DATE_YEAR"   => 31536000, 
		   "COM_DJCLASSIFIEDS_DATE_MONTH"  => 2628000,  
		   "COM_DJCLASSIFIEDS_DATE_WEEK"   => 604800,   
		   "COM_DJCLASSIFIEDS_DATE_DAY"    => 86400,    
		   "COM_DJCLASSIFIEDS_DATE_HOUR"   => 3600,     
		   "COM_DJCLASSIFIEDS_DATE_MINUTE" => 60,       
		   "COM_DJCLASSIFIEDS_DATE_SECOND" => 1         
	  	);
	
	  	$diff = abs($from - $to);
	  	$suffix = (($from > $to) ? (JTEXT::_('COM_DJCLASSIFIEDS_DATE_FROM_NOW')) : (JTEXT::_('COM_DJCLASSIFIEDS_DATE_AGO')));
		
		$i=0;
		  	foreach($units as $unit => $mult){
		   		if($diff >= $mult){
		    		if($i==$limit-1 && $i>0){
		    		 	$output .= " ".JTEXT::_('COM_DJCLASSIFIEDS_DATE_AND').' '.intval($diff / $mult)." ";
					}else{
						$output .= ", ".intval($diff / $mult)." ";
					}	
		    		//$and = (($mult != 1) ? ("") : (JTEXT::_('COM_DJCLASSIFIEDS_DATE_AND')));
		    		//$output .= ", ".$and.intval($diff / $mult)." ";
					if(intval($diff / $mult) == 1){
						$output .= JTEXT::_($unit);	
					}else{
						$output .= JTEXT::_($unit."S");
					}
		    		
		    		$diff -= intval($diff / $mult) * $mult;
					$i++;
					if($i==$limit){ break; }			
		   		}
			}
			$output .= " ".$suffix;
	  		$output = substr($output, strlen(", "));
	  return $output;
	 }
	 
	static function includeCSSfiles($theme=''){				
	 	$par = JComponentHelper::getParams( 'com_djclassifieds' );
	 	$document= JFactory::getDocument();
	 	if(!$theme){ $theme = $par->get('theme','default');}
	 	$theme_path = JPATH_BASE.DS.'components'.DS.'com_djclassifieds'.DS.'themes'.DS.$theme.DS.'css'.DS;
	 	
		if (JFile::exists($theme_path.'style.css')){
	 		$cs = JURI::base().'components/com_djclassifieds/themes/'.$theme.'/css/style.css';
	 		$document->addStyleSheet($cs);
	 	}else if($theme!='default'){
	 		$cs = JURI::base().'components/com_djclassifieds/themes/default/css/style.css'; 
	 		$document->addStyleSheet($cs);
	 	}
	 	
	 	if($par->get('include_css','1')){
	 		if (JFile::exists($theme_path.'style_default.css')){
	 			$cs = JURI::base().'components/com_djclassifieds/themes/'.$theme.'/css/style_default.css';
	 			$document->addStyleSheet($cs);
	 		}else if($theme!='default'){
	 			$cs = JURI::base().'components/com_djclassifieds/themes/default/css/style_default.css'; 
	 			$document->addStyleSheet($cs);
	 		}  
	 	}
	 	
	 	$add_rtl=0;
	 	if($document->direction=='rtl'){
	 		$add_rtl=1;
		}else if (isset($_COOKIE["jmfdirection"])){
			if($_COOKIE["jmfdirection"]=='rtl'){
				$add_rtl=1;	
			}
		}else if (isset($_COOKIE["djdirection"])){
			if($_COOKIE["djdirection"]=='rtl'){
				$add_rtl=1;	
			}
		}
		if($add_rtl){
	 		if (JFile::exists($theme_path.'style_rtl.css')){
	 			$cs = JURI::base().'components/com_djclassifieds/themes/'.$theme.'/css/style_rtl.css';
	 			$document->addStyleSheet($cs);
	 		}
	 	}
	 	if (JFile::exists($theme_path.'responsive.css')){
	 		$cs = JURI::base().'components/com_djclassifieds/themes/'.$theme.'/css/responsive.css';
	 		$document->addStyleSheet($cs);
	 	}else if($theme!='default'){
	 		$cs = JURI::base().'components/com_djclassifieds/themes/default/css/responsive.css'; 
	 		$document->addStyleSheet($cs);
	 	}  	 	
	 	
	 	/*if($par->get('include_awesome_font','1')){
	 		$cs = JURI::base().'components/com_djclassifieds/assets/fontawesome/css/font-awesome.min.css';
	 		$document->addStyleSheet($cs);
	 	}*/
	 	
	 	
	 }	 
	 
	 public static function djAccessRestriction($type=''){
	 	$app = JFactory::getApplication();
	 	$par = JComponentHelper::getParams( 'com_djclassifieds' );
	 	
	 	if($type=='category'){
	 		$message = JText::_("COM_DJCLASSIFIEDS_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_CATEGORY");
	 	}else{
	 		$message = JText::_("COM_DJCLASSIFIEDS_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_ADVERT");
	 	}	 	
		 	
		 	if($par->get('acl_redirect','0')==1){
		 		JError::raiseWarning(403, $message);
		 		$redirect = JURI::base();
		 	}else if($par->get('acl_redirect','0')==2 && $par->get('acl_red_article_id','0')>0){		 				 		
		 		
		 		$db= JFactory::getDBO();
				$query = "SELECT a.id, a.alias, a.catid, c.alias as c_alias FROM #__content a "
						."LEFT JOIN #__categories c ON c.id=a.catid "
						."WHERE a.state=1 AND a.id=".$par->get('acl_red_article_id','0');
				
				$db->setQuery($query);
				$acl_article=$db->loadObject();		 				 				 				 		
		 		
		 		if($acl_article){
		 			require_once JPATH_ROOT.'/components/com_content/helpers/route.php';
		 			$slug = $acl_article->id.':'.$acl_article->alias;
		 			$cslug = $acl_article->catid.':'.$acl_article->c_alias;
		 			$article_link = ContentHelperRoute::getArticleRoute($slug,$cslug);		 			
		 			$redirect = JRoute::_($article_link);
		 		}else{
		 			$redirect = JURI::base();
		 		}
		 		
		 	}else{
		 		$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');		 		
		 	}
		 	$redirect = JRoute::_($redirect);
		 	$app->redirect($redirect,$message,'error');
	 	
	 	return null;
	 	
	 }
	 
	 
	 public static function getFontsAwesomeSelect(){
	 	$select ='<select style="font-family: \'FontAwesome\', Helvetica;" >
				 	<optgroup label="Web Application Icons">
				 	<option value="fa-adjust">&#xf083; aaaa</option>
				 	<option value="icon-asterisk">&#xf083; icon-asterisk</option>
				 	<option value="icon-ban-circle">&#xf083; icon-ban-circle</option>
				 	<option value="icon-bar-chart">icon-bar-chart</option>
				 	<option value="icon-barcode">icon-barcode</option>
				 	<option value="icon-beaker">icon-beaker</option>
				 	<option value="icon-beer">icon-beer</option>
				 	<option value="icon-bell">icon-bell</option>
				 	<option value="icon-bell-alt">icon-bell-alt</option>
				 	<option value="icon-bolt">icon-bolt</option>
				 	<option value="icon-book">icon-book</option>
				 	<option value="icon-bookmark">icon-bookmark</option>
				 	<option value="icon-bookmark-empty">icon-bookmark-empty</option>
				 	<option value="icon-briefcase">icon-briefcase</option>
				 	<option value="icon-bullhorn">icon-bullhorn</option>
				 	<option value="icon-calendar">icon-calendar</option>
				 	<option value="icon-camera">icon-camera</option>
				 	<option value="icon-camera-retro">icon-camera-retro</option>
				 	<option value="icon-certificate">icon-certificate</option>
				 	<option value="icon-check">icon-check</option>
				 	<option value="icon-check-empty">icon-check-empty</option>
				 	<option value="icon-circle">icon-circle</option>
				 	<option value="icon-circle-blank">icon-circle-blank</option>
				 	<option value="icon-cloud">icon-cloud</option>
				 	<option value="icon-cloud-download">icon-cloud-download</option>
				 	<option value="icon-cloud-upload">icon-cloud-upload</option>
				 	<option value="icon-coffee">icon-coffee</option>
				 	<option value="icon-cog">icon-cog</option>
				 	<option value="icon-cogs">icon-cogs</option>
				 	<option value="icon-comment">icon-comment</option>
				 	<option value="icon-comment-alt">icon-comment-alt</option>
				 	<option value="icon-comments">icon-comments</option>
				 	<option value="icon-comments-alt">icon-comments-alt</option>
				 	<option value="icon-credit-card">icon-credit-card</option>
				 	<option value="icon-dashboard">icon-dashboard</option>
				 	<option value="icon-desktop">icon-desktop</option>
				 	<option value="icon-download">icon-download</option>
				 	<option value="icon-download-alt">icon-download-alt</option>
				 	<option value="icon-edit">icon-edit</option>
				 	<option value="icon-envelope">icon-envelope</option>
				 	<option value="icon-envelope-alt">icon-envelope-alt</option>
				 	<option value="icon-exchange">icon-exchange</option>
				 	<option value="icon-exclamation-sign">icon-exclamation-sign</option>
				 	<option value="icon-external-link">icon-external-link</option>
				 	<option value="icon-eye-close">icon-eye-close</option>
				 	<option value="icon-eye-open">icon-eye-open</option>
				 	<option value="icon-facetime-video">icon-facetime-video</option>
				 	<option value="icon-fighter-jet">icon-fighter-jet</option>
				 	<option value="icon-film">icon-film</option>
				 	<option value="icon-filter">icon-filter</option>
				 	<option value="icon-fire">icon-fire</option>
				 	<option value="icon-flag">icon-flag</option>
				 	<option value="icon-folder-close">icon-folder-close</option>
				 	<option value="icon-folder-open">icon-folder-open</option>
				 	<option value="icon-folder-close-alt">icon-folder-close-alt</option>
				 	<option value="icon-folder-open-alt">icon-folder-open-alt</option>
				 	<option value="icon-food">icon-food</option>
				 	<option value="icon-gift">icon-gift</option>
				 	<option value="icon-glass">icon-glass</option>
				 	<option value="icon-globe">icon-globe</option>
				 	<option value="icon-group">icon-group</option>
				 	<option value="icon-hdd">icon-hdd</option>
				 	<option value="icon-headphones">icon-headphones</option>
				 	<option value="icon-heart">icon-heart</option>
				 	<option value="icon-heart-empty">icon-heart-empty</option>
				 	<option value="icon-home">icon-home</option>
				 	<option value="icon-inbox">icon-inbox</option>
				 	<option value="icon-info-sign">icon-info-sign</option>
				 	<option value="icon-key">icon-key</option>
				 	<option value="icon-leaf">icon-leaf</option>
				 	<option value="icon-laptop">icon-laptop</option>
				 	<option value="icon-legal">icon-legal</option>
				 	<option value="icon-lemon">icon-lemon</option>
				 	<option value="icon-lightbulb">icon-lightbulb</option>
				 	<option value="icon-lock">icon-lock</option>
				 	<option value="icon-unlock">icon-unlock</option>
				 	<option value="icon-magic">icon-magic</option>
				 	<option value="icon-magnet">icon-magnet</option>
				 	<option value="icon-map-marker">icon-map-marker</option>
				 	<option value="icon-minus">icon-minus</option>
				 	<option value="icon-minus-sign">icon-minus-sign</option>
				 	<option value="icon-mobile-phone">icon-mobile-phone</option>
				 	<option value="icon-money">icon-money</option>
				 	<option value="icon-move">icon-move</option>
				 	<option value="icon-music">icon-music</option>
				 	<option value="icon-off">icon-off</option>
				 	<option value="icon-ok">icon-ok</option>
				 	<option value="icon-ok-circle">icon-ok-circle</option>
				 	<option value="icon-ok-sign">icon-ok-sign</option>
				 	<option value="icon-pencil">icon-pencil</option>
				 	<option value="icon-picture">icon-picture</option>
				 	<option value="icon-plane">icon-plane</option>
				 	<option value="icon-plus">icon-plus</option>
				 	<option value="icon-plus-sign">icon-plus-sign</option>
				 	<option value="icon-print">icon-print</option>
				 	<option value="icon-pushpin">icon-pushpin</option>
				 	<option value="icon-qrcode">icon-qrcode</option>
				 	<option value="icon-question-sign">icon-question-sign</option>
				 	<option value="icon-quote-left">icon-quote-left</option>
				 	<option value="icon-quote-right">icon-quote-right</option>
				 	<option value="icon-random">icon-random</option>
				 	<option value="icon-refresh">icon-refresh</option>
				 	<option value="icon-remove">icon-remove</option>
				 	<option value="icon-remove-circle">icon-remove-circle</option>
				 	<option value="icon-remove-sign">icon-remove-sign</option>
				 	<option value="icon-reorder">icon-reorder</option>
				 	<option value="icon-reply">icon-reply</option>
				 	<option value="icon-resize-horizontal">icon-resize-horizontal</option>
				 	<option value="icon-resize-vertical">icon-resize-vertical</option>
				 	<option value="icon-retweet">icon-retweet</option>
				 	<option value="icon-road">icon-road</option>
				 	<option value="icon-rss">icon-rss</option>
				 	<option value="icon-screenshot">icon-screenshot</option>
				 	<option value="icon-search">icon-search</option>
				 	<option value="icon-share">icon-share</option>
				 	<option value="icon-share-alt">icon-share-alt</option>
				 	<option value="icon-shopping-cart">icon-shopping-cart</option>
				 	<option value="icon-signal">icon-signal</option>
				 	<option value="icon-signin">icon-signin</option>
				 	<option value="icon-signout">icon-signout</option>
				 	<option value="icon-sitemap">icon-sitemap</option>
				 	<option value="icon-sort">icon-sort</option>
				 	<option value="icon-sort-down">icon-sort-down</option>
				 	<option value="icon-sort-up">icon-sort-up</option>
				 	<option value="icon-spinner">icon-spinner</option>
				 	<option value="icon-star">icon-star</option>
				 	<option value="icon-star-empty">icon-star-empty</option>
				 	<option value="icon-star-half">icon-star-half</option>
				 	<option value="icon-tablet">icon-tablet</option>
				 	<option value="icon-tag">icon-tag</option>
				 	<option value="icon-tags">icon-tags</option>
				 	<option value="icon-tasks">icon-tasks</option>
				 	<option value="icon-thumbs-down">icon-thumbs-down</option>
				 	<option value="icon-thumbs-up">icon-thumbs-up</option>
				 	<option value="icon-time">icon-time</option>
				 	<option value="icon-tint">icon-tint</option>
				 	<option value="icon-trash">icon-trash</option>
				 	<option value="icon-trophy">icon-trophy</option>
				 	<option value="icon-truck">icon-truck</option>
				 	<option value="icon-umbrella">icon-umbrella</option>
				 	<option value="icon-upload">icon-upload</option>
				 	<option value="icon-upload-alt">icon-upload-alt</option>
				 	<option value="icon-user">icon-user</option>
				 	<option value="icon-user-md">icon-user-md</option>
				 	<option value="icon-volume-off">icon-volume-off</option>
				 	<option value="icon-volume-down">icon-volume-down</option>
				 	<option value="icon-volume-up">icon-volume-up</option>
				 	<option value="icon-warning-sign">icon-warning-sign</option>
				 	<option value="icon-wrench">icon-wrench</option>
				 	<option value="icon-zoom-in">icon-zoom-in</option>
				 	<option value="icon-zoom-out">icon-zoom-out</option>
				 	<optgroup label="Text Editor Icons">
				 	<option value="icon-file">icon-file</option>
				 	<option value="icon-file-alt">icon-file-alt</option>
				 	<option value="icon-cut">icon-cut</option>
				 	<option value="icon-copy">icon-copy</option>
				 	<option value="icon-paste">icon-paste</option>
				 	<option value="icon-save">icon-save</option>
				 	<option value="icon-undo">icon-undo</option>
				 	<option value="icon-repeat">icon-repeat</option>
				 	<option value="icon-text-height">icon-text-height</option>
				 	<option value="icon-text-width">icon-text-width</option>
				 	<option value="icon-align-left">icon-align-left</option>
				 	<option value="icon-align-center">icon-align-center</option>
				 	<option value="icon-align-right">icon-align-right</option>
				 	<option value="icon-align-justify">icon-align-justify</option>
				 	<option value="icon-indent-left">icon-indent-left</option>
				 	<option value="icon-indent-right">icon-indent-right</option>
				 	<option value="icon-font">icon-font</option>
				 	<option value="icon-bold">icon-bold</option>
				 	<option value="icon-italic">icon-italic</option>
				 	<option value="icon-strikethrough">icon-strikethrough</option>
				 	<option value="icon-underline">icon-underline</option>
				 	<option value="icon-link">icon-link</option>
				 	<option value="icon-paper-clip">icon-paper-clip</option>
				 	<option value="icon-columns">icon-columns</option>
				 	<option value="icon-table">icon-table</option>
				 	<option value="icon-th-large">icon-th-large</option>
				 	<option value="icon-th">icon-th</option>
				 	<option value="icon-th-list">icon-th-list</option>
				 	<option value="icon-list">icon-list</option>
				 	<option value="icon-list-ol">icon-list-ol</option>
				 	<option value="icon-list-ul">icon-list-ul</option>
				 	<option value="icon-list-alt">icon-list-alt</option>
				 	<optgroup label="Directional Icons">
				 	<option value="icon-angle-left">icon-angle-left</option>
				 	<option value="icon-angle-right">icon-angle-right</option>
				 	<option value="icon-angle-up">icon-angle-up</option>
				 	<option value="icon-angle-down">icon-angle-down</option>
				 	<option value="icon-arrow-down">icon-arrow-down</option>
				 	<option value="icon-arrow-left">icon-arrow-left</option>
				 	<option value="icon-arrow-right">icon-arrow-right</option>
				 	<option value="icon-arrow-up">icon-arrow-up</option>
				 	<option value="icon-caret-down">icon-caret-down</option>
				 	<option value="icon-caret-left">icon-caret-left</option>
				 	<option value="icon-caret-right">icon-caret-right</option>
				 	<option value="icon-caret-up">icon-caret-up</option>
				 	<option value="icon-chevron-down">icon-chevron-down</option>
				 	<option value="icon-chevron-left">icon-chevron-left</option>
				 	<option value="icon-chevron-right">icon-chevron-right</option>
				 	<option value="icon-chevron-up">icon-chevron-up</option>
				 	<option value="icon-circle-arrow-down">icon-circle-arrow-down</option>
				 	<option value="icon-circle-arrow-left">icon-circle-arrow-left</option>
				 	<option value="icon-circle-arrow-right">icon-circle-arrow-right</option>
				 	<option value="icon-circle-arrow-up">icon-circle-arrow-up</option>
				 	<option value="icon-double-angle-left">icon-double-angle-left</option>
				 	<option value="icon-double-angle-right">icon-double-angle-right</option>
				 	<option value="icon-double-angle-up">icon-double-angle-up</option>
				 	<option value="icon-double-angle-down">icon-double-angle-down</option>
				 	<option value="icon-hand-down">icon-hand-down</option>
				 	<option value="icon-hand-left">icon-hand-left</option>
				 	<option value="icon-hand-right">icon-hand-right</option>
				 	<option value="icon-hand-up">icon-hand-up</option>
				 	<option value="icon-circle">icon-circle</option>
				 	<option value="icon-circle-blank">icon-circle-blank</option>
				 	<optgroup label="Video Player Icons">
				 	<option value="icon-play-circle">icon-play-circle</option>
				 	<option value="icon-play">icon-play</option>
				 	<option value="icon-pause">icon-pause</option>
				 	<option value="icon-stop">icon-stop</option>
				 	<option value="icon-step-backward">icon-step-backward</option>
				 	<option value="icon-fast-backward">icon-fast-backward</option>
				 	<option value="icon-backward">icon-backward</option>
				 	<option value="icon-forward">icon-forward</option>
				 	<option value="icon-fast-forward">icon-fast-forward</option>
				 	<option value="icon-step-forward">icon-step-forward</option>
				 	<option value="icon-eject">icon-eject</option>
				 	<option value="icon-fullscreen">icon-fullscreen</option>
				 	<option value="icon-resize-full">icon-resize-full</option>
				 	<option value="icon-resize-small">icon-resize-small</option>
				 	<optgroup label="Social Icons">
				 	<option value="icon-phone">icon-phone</option>
				 	<option value="icon-phone-sign">icon-phone-sign</option>
				 	<option value="icon-facebook">icon-facebook</option>
				 	<option value="icon-facebook-sign">icon-facebook-sign</option>
				 	<option value="icon-twitter">icon-twitter</option>
				 	<option value="icon-twitter-sign">icon-twitter-sign</option>
				 	<option value="icon-github">icon-github</option>
				 	<option value="icon-github-alt">icon-github-alt</option>
				 	<option value="icon-github-sign">icon-github-sign</option>
				 	<option value="icon-linkedin">icon-linkedin</option>
				 	<option value="icon-linkedin-sign">icon-linkedin-sign</option>
				 	<option value="icon-pinterest">icon-pinterest</option>
				 	<option value="icon-pinterest-sign">icon-pinterest-sign</option>
				 	<option value="icon-google-plus">icon-google-plus</option>
				 	<option value="icon-google-plus-sign">icon-google-plus-sign</option>
				 	<option value="icon-sign-blank">icon-sign-blank</option>
				 	<optgroup label="Medical Icons">
				 	<option value="icon-ambulance">icon-ambulance</option>
				 	<option value="icon-beaker">icon-beaker</option>
				 	<option value="icon-h-sign">icon-h-sign</option>
				 	<option value="icon-hospital">icon-hospital</option>
				 	<option value="icon-medkit">icon-medkit</option>
				 	<option value="icon-plus-sign-alt">icon-plus-sign-alt</option>
				 	<option value="icon-stethoscope">icon-stethoscope</option>
				 	<option value="icon-user-md">icon-user-md</option>
				</select>';
	 	return $select;
	 }
	 
}