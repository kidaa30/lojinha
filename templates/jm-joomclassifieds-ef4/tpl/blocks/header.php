<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;
?>

<?php if ($this->checkModules('header')) : ?>
<section id="jm-header" class="<?php echo $this->getClass('block#header') ?>">
    <div id="jm-header-in" class="container-fluid">
  		<jdoc:include type="modules" name="<?php echo $this->getPosition('header'); ?>" style="jmmodule"/>
  	</div>
</section>
<?php endif; ?>
