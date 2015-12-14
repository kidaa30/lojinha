<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
defined ('_JEXEC') or die('Restricted access');
JHTML::_('behavior.framework' );
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
?>
<div id="dj-classifieds" class="djcftheme-<?php echo $par->get('theme','default');?>">
<div class="pointspackages djcf_outer">
	<div class="title_top"><?php echo JText::_("COM_DJCLASSIFIEDS_POINTS_PACKAGES"); ?></div>
	<div class="djcf_outer_in paymentdetails">
		<?php	
			$i = 0;					
			foreach($this->points AS $point)
			{							
				?>
					<div class="djcf_prow"><div class="djcf_prow_in">								
						<div class="djcf_prow_col_desc">
							<h3><?php echo $point->name; ?></h3>
							<?php echo JText::_("COM_DJCLASSIFIEDS_POINTS").': '.$point->points; ?><br />													
							<?php echo JText::_("COM_DJCLASSIFIEDS_COST_PER_POINT").': '.DJClassifiedsTheme::priceFormat(round($point->price/$point->points,4),$par->get('unit_price',''),2); ?>													
						</div>
						<div class="djcf_prow_col_buynow">
							<div class="pp_price"><?php echo DJClassifiedsTheme::priceFormat($point->price,$par->get('unit_price','')); ?></div>
							<a class="button" href="index.php?option=com_djclassifieds&view=payment&type=points&id=<?php echo $point->id?>">
								<?php echo JText::_('COM_DJCLASSIFIEDS_BUY_NOW'); ?>
							</a>
						</div>
					</div></div>
				<?php
				$i++;
			}
		?>
	</div>
</div>
</div>