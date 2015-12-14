<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

//get information about 'back to top' button
$backtotop = $this->params->get('backToTop', '1');

?>

<footer id="jm-footer" class="<?php echo $this->getClass('block#footer') ?>">
    <div class="container-fluid jm-footer">
        <div id="jm-footer-in" class="row-fluid">
            <?php if($this->checkModules('copyrights')) : ?>
            <div id="jm-footer-left" class="pull-left span6 <?php echo $this->getClass('copyrights') ?>">
                <div id="jm-copyrights">
                    <jdoc:include type="modules" name="<?php echo $this->getPosition('copyrights') ?>" style="raw"/>
                </div>
            </div>
            <?php endif; ?>

            <div id="jm-footer-right" class="pull-right span6">
                <div id="jm-poweredby">
                    <a href="http://www.joomla-monster.com" target="_blank" title="Joomla Templates">Joomla Templates</a> by Joomla-Monster.com
                </div>
            </div>
        </div>
    </div>
</footer>
<?php if($backtotop == '1') : ?>
    <p id="jm-back-top"><a id="backtotop" href="#top"><span>&nbsp;</span></a></p>
<?php endif; ?>