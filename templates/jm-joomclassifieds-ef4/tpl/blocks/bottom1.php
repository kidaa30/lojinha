<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;
?>

<?php if($this->countFlexiblock('bottom1')) : ?>
<section id="jm-bottom1" class="<?php echo $this->getClass('block#bottom1'); ?>">
	<div class="container-fluid">
		<?php echo $this->renderFlexiblock('bottom1','jmmodule'); ?>
	</div>
</section>
<?php endif; ?>