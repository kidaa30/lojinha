<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}

//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

/////////////////////////////////////////////////////
// ARTICLE ID
$errorpage = 104;
/////////////////////////////////////////////////////

//get error code
$errorcode = $this->error->getCode();


if($errorcode=='404') {
  //get a db connection.
  $db = JFactory::getDbo();
   
  //create a new query object.
  $query = $db->getQuery(true);
   
  //select all records from the user profile table where key begins with "custom.".
  //order it by the ordering field.
  $query->select($db->quoteName(array('id')));
  $query->from($db->quoteName('#__content'));
  $query->where($db->quoteName('id') . ' = '. $db->quote($errorpage));
   
  //reset the query using our newly populated query object.
  $db->setQuery($query);
   
  //load the results
  $results = $db->loadResult();
  
  require_once(JPATH_BASE.'/components/com_content/helpers/route.php');
  
  if($results) {
    if ($errorcode == '404') {
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: '.JRoute::_(ContentHelperRoute::getArticleRoute($errorpage), false));
      header('Connection: close');
      exit;
    }
  }
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/error.css" type="text/css" />
</head> 
<body>
	<div class="jm-error">
		<div class="jm-error-title">
			<div class="jm-error-code">
				<h1><?php echo $this->error->getCode(); ?></h1>
			</div>
			<div class="jm-error-message">
				<h2><?php echo $this->error->getMessage(); ?></h2>
			</div>
		</div>
		<div class="jm-error-desc">
			<?php echo JText::_('PLG_SYSTEM_JMFRAMEWORK_JERROR_PAGE_DOESNT_EXIST'); ?><br/>
			<?php echo JText::_('PLG_SYSTEM_JMFRAMEWORK_JERROR_GO_BACK_OR_HEAD_OVER'); ?><br />
			<div class="jm-error-buttons">
				<a class="jm-error-left" href="javascript:history.go(-1)"><?php echo JText::_('PLG_SYSTEM_JMFRAMEWORK_JERROR_BACK'); ?></a> <a class="jm-error-right" href="<?php echo JURI::base(); ?>" title="<?php echo JText::_('PLG_SYSTEM_JMFRAMEWORK_JERROR_HOME_PAGE'); ?>"><?php echo JText::_('PLG_SYSTEM_JMFRAMEWORK_JERROR_HOME_PAGE'); ?></a>
			</div>
		</div>
	</div>	
</body>
</html>