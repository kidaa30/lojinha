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
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
$ord_dir = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}

?>
<div id="dj-classifieds" class="clearfix djcftheme-<?php echo $par->get('theme','default');?>">
	<div class="title_top"><h1>
		<?php	echo JText::_('COM_DJCLASSIFIEDS_YOUR_POINTS_HISTORY');?>
	</h1></div>
	<div class="userpoints">
	<div class="points_available">
		<?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_AVAILABLE').': <span>'.$this->user_points.'</span>';?>
	</div>
	
	
	
	
	<?php	
		$r=TRUE;
		?>
		
		
		<div class="dj-items-table2">
			<div class="item_row item_header main_title">
				<div class="item_col first">	
					<?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION'); ?>
				</div>
				<?php if($order=="points"){$class="active";}else{$class="normal";}?>
				<div class="item_col <?php echo $class; ?>">
					<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=userpoints&order=points&ord_t=<?php echo $ord_t;?>">
						<?php echo JText::_('COM_DJCLASSIFIEDS_POINTS');
						if($order=="points"){
							if($ord_t=='asc'){ echo '<img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
							}else{ echo '<img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
						}else{	echo '<img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
					</a> 
				</div>		
				<?php if($order=="date"){$class="active";}else{$class="normal";}?>
				<div class="item_col <?php echo $class; ?>">
					<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=userpoints&order=date&ord_t=<?php echo $ord_t;?>">
					<?php echo JText::_('COM_DJCLASSIFIEDS_DATE');
					if($order=="date"){
						if($ord_t=='asc'){ echo '<img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
						}else{ echo '<img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
					}else{	echo '<img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/sort.gif" />'; }?></a>			 
				</div>
			</div>		
			<?php	
			foreach($this->points as $point){
				$row = $r==TRUE ? '0' : '1';
				$r=!$r;
				echo '<div class="item_row row'.$row.'">';
					echo '<div class="item_col first">'.$point->description.'</div>';
					echo '<div class="item_col">'.$point->points.'</div>';						
					echo '<div class="item_col">'.DJClassifiedsTheme::formatDate(strtotime($point->date)).'</div>';								
				echo '</div>';
				
			}
			?>	
		</div>
		<?php if($this->pagination->getPagesLinks()){ ?>						
			<div class="pagination">
				<?php echo $this->pagination->getPagesLinks(); ?> 
			</div>
		<?php } ?>
	</div>	

</div>