<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

$topmodspan1 = ($this->checkModules('top-mod1') AND $this->checkModules('top-mod2')) ? 'span7' : 'span12';
$topmodspan2 = ($this->checkModules('top-mod1') AND $this->checkModules('top-mod2')) ? 'span5' : 'span12';

?>

<?php if($this->checkModules('top-mod1') OR $this->checkModules('top-mod2') OR $this->checkModules('top-menu-nav')) : ?>
<section id="jm-top-mod" class="<?php echo $this->getClass('block#top-mod') ?>">
    <div class="container-fluid">    
    	<?php if($this->checkModules('top-menu-nav')) : ?>
        <nav id="jm-djmenu" class="clearfix <?php echo $this->getClass('top-menu-nav') ?>">
            <jdoc:include type="modules" name="<?php echo $this->getPosition('top-menu-nav') ?>" style="jmmoduleraw"/>
        </nav>
		<?php endif; ?> 
    	    
        <?php if($this->checkModules('top-mod1') OR $this->checkModules('top-mod2')) : ?>
        <div id="jm-top-mod-in" class="clearfix">
    		<div class="row-fluid">
                <?php if($this->checkModules('top-mod1')) : ?>
                <div id="jm-top-mod1" class="<?php echo $topmodspan1; ?> pull-left <?php echo $this->getClass('top-mod1') ?>">
                    <div id="jm-top-mod1-in">
                        <jdoc:include type="modules" name="<?php echo $this->getPosition('top-mod1') ?>" style="jmmodule"/>
                    </div>
                </div>
                <?php endif; ?> 
                <?php if($this->checkModules('top-mod2')) : ?>
                <div id="jm-top-mod2" class="<?php echo $topmodspan2; ?> pull-right <?php echo $this->getClass('top-mod2') ?>">
                    <div id="jm-top-mod2-in">
                        <jdoc:include type="modules" name="<?php echo $this->getPosition('top-mod2') ?>" style="jmmodule"/>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div> 
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>