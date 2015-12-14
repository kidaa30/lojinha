<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;
?>

<?php if($this->countFlexiblock('bottom3')) : ?>
<section id="jm-bottom3" class="<?php echo $this->getClass('block#bottom3'); ?>">
	<div class="container-fluid jm-bottom">
		<?php echo $this->renderFlexiblock('bottom3','jmmodule'); ?>
	</div>
</section>
<?php endif; ?>