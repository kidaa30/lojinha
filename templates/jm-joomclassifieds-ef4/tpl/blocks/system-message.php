<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

// check messages
$message = JFactory::getApplication()->getMessageQueue();

?>
<?php if(!empty($message) || JFactory::getApplication()->isAdmin()) : ?>
<section id="jm-system-message">
	<div class="container-fluid">
		<jdoc:include type="message" />
	</div>
</section>
<?php endif; ?>