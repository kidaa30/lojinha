<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

//get logo and site description
$logo = htmlspecialchars($this->params->get('logo'));
$logotext = htmlspecialchars($this->params->get('logoText'));
$sitedescription = htmlspecialchars($this->params->get('siteDescription'));
$app = JFactory::getApplication();
$sitename = $app->getCfg('sitename');

?>

<?php if ($this->checkModules('top-bar') or ($logo != '') or ($logotext != '') or ($sitedescription != '') or $this->checkModules('top-menu-button')) : ?>
<section id="jm-top-bar" class="<?php echo $this->getClass('block#topbar') ?>">  
    <div class="container-fluid">
        <div id="jm-top-bar-in" class="clearfix">      
            <?php if (($logo != '') or ($logotext != '') or ($sitedescription != '')) : ?>
            <div id="jm-logo-sitedesc" class="pull-left">
                <?php if (($logo != '') or ($logotext != '')) : ?>
                <div id="jm-logo" class="pull-left">
                    <a href="<?php echo JURI::base(); ?>">
                        <?php if ($logo != '') : ?>
                        <img src="<?php echo JURI::base(), $logo; ?>" alt="<?php if(!$logotext) { echo $sitename; } else { echo $logotext; }; ?>" />
                        <?php else : ?>
                        <?php echo '<span>'.$logotext.'</span>';?>
                        <?php endif; ?>
                    </a>
                </div>
                <?php endif; ?>
                <?php if ($sitedescription != '') : ?>
                <div id="jm-sitedesc" class="pull-left">
                    <?php echo $sitedescription; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
			<?php if ($this->checkModules('top-menu-button')) : ?>
			<div id="jm-top-menu-button" class="pull-right <?php echo $this->getClass('top-menu-button') ?>">
				<jdoc:include type="modules" name="<?php echo $this -> getPosition('top-menu-button'); ?>" style="jmmoduleraw"/>
			</div>
			<?php endif; ?>
            <?php if($this->checkModules('top-bar')) : ?>
            <div id="jm-top-bar-mod" class="pull-right jm-light <?php echo $this->getClass('top-bar') ?>">
                <jdoc:include type="modules" name="<?php echo $this->getPosition('top-bar'); ?>" style="jmmoduleraw"/>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
