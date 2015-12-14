<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.joomla-monster.com/license.html Joomla-Monster Proprietary Use License
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

//advanced selectors font parameters
$advancedfontsize = $this->params->get('advancedFontSize', '18');
$advancedfonttype = $this->params->get('advancedFontType', '0');
$advancedfontfamily = $this->params->get('advancedFontFamily', 'Arial, Helvetica, sans-serif'); 
$advancedgooglewebfonturl = htmlspecialchars($this->params->get('advancedGoogleWebFontUrl'));
$advancedgooglewebfontfamily = $this->params->get('advancedGoogleWebFontFamily');
$advancedselectors = $this->params->get('advancedSelectors');
$advancedgeneratedwebfont = $this->params->get('advancedGeneratedWebFont');

if($advancedselectors != '') {
    echo $advancedselectors; ?> {
    <?php 
    switch($advancedfonttype) {
        case "0":
            echo "font-family: ".$advancedfontfamily.";";
        break;
        case "1":       
            echo "font-family: ".$advancedgooglewebfontfamily.";";
        break;
		case "2":       
            echo "font-family: ".$advancedgeneratedwebfont.";";
        break;
        default: 
            echo "font-family: Arial, Helvetica, sans-serif;";
    }
    ?>
    font-size: <?php echo $advancedfontsize; ?>;
	}
<?php } ?>

<?php 
// header image background
$headerimg = $this->params->get('headerBg');

if($headerimg!='') { ?>
#jm-header {
    background-image: url('<?php echo JURI::base().$headerimg; ?>');
}
<?php } ?>