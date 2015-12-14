<?php
/**
 * @version $Id: default.php 35 2015-04-07 13:23:38Z michal $
 * @package DJ-Reviews
 * @copyright Copyright (C) 2014 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJ-Reviews is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Reviews is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Reviews. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
$params = DJReviewsHelper::getParams($this->review_object->rating_group_id);
?>
<div id="djrv-reviews-list-<?php echo $this->review_object->id; ?>" class="djrv_reviews_list djreviews clearfix">
<?php if ($params->get('reviews', '1') == '1') { ?>

<h3><?php echo JText::_('COM_DJREVIEWS_USERS_REVIEWS_HEADING'); ?></h3>

<?php if (count($this->items) > 0) {?>
<div class="djrv_listing row-striped">
<?php foreach ($this->items as $item) { ?>
	<?php if ($item->published == 0) continue;?>
	<div class="djrv_single_review row-fluid djrv_clearfix" itemprop="review" itemscope itemtype="http://schema.org/Review">
	<meta itemprop="itemReviewed" content="<?php echo $item->object_name; ?>" />
	<div class="span12">
		<?php 
		/*
		$item->user_login;
		$item->user_name;
		$item->email;
		$item->created;
		$item->created_by;
		$item->message;
		$item->title;
		$item->avg_rate;
		$item->published;
		*/
		?>
		
		<?php if ($item->avg_rate > 0.0 && $params->get('rating', '1') == '1') { ?>
		<div class="djrv_rating djrv_user_rating pull-right" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
			<span class="djrv_stars">
			<?php for ($i = 1; $i <= 5; $i++) {?>
			<span class="djrv_star <?php if ($i <= $item->avg_rate || ($item->avg_rate - $i) >= -0.5) echo 'active';?>"></span>
			<?php } ?>
			</span>
			<span class="djrv_avg">
				<?php echo $item->avg_rate; ?>
			</span>
			<meta itemprop="worstRating" content="1"/>
      		<meta itemprop="ratingValue" content="<?php echo $item->avg_rate; ?>" />
      		<meta itemprop="bestRating" content="5"/>
		</div>
		<?php } ?>
		
		<?php if ($item->title && (int)$params->get('title', 2) > 0) {?>
		<h4 itemprop="name"><?php echo $item->title; ?></h4>
		<?php } ?>
		
		<div class="djrv_post_info">
			<?php /*?>
			<div class="djrv_poster small">
				<?php echo JText::_('COM_DJREVIEWS_POSTED_BY')?><span><?php echo $item->user_login; ?></span>
			</div>
			<div class="djrv_review_date small">
				<?php echo JText::_('COM_DJREVIEWS_POSTED_ON');?><span><?php echo JHtml::_('date', $item->created, $params->get('date_format', 'd-m-Y H:i'));?></span>
			</div>
			<?php */ ?>
			<div class="djrv_poster small">
				<?php echo JText::sprintf('COM_DJREVIEWS_POSTED_BY_ON', '<span itemprop="author">'.$item->user_login.'</span>', JHtml::_('date', $item->created, $params->get('date_format', 'd-m-Y H:i'))); ?>
				<meta itemprop="datePublished" content="<?php echo JHtml::_('date', $item->created, 'Y-m-d'); ?>" />
			</div>
		</div>
		
		<?php if ($item->message && (int)$params->get('message', 2) > 0) {?>			
		<p class="djrv_message_quote" itemprop="reviewBody"><?php echo nl2br($item->message); ?></p>
		<?php } ?>
		
		<?php 
		$has_toolbar = false;
		if (($user->id > 0 && $item->created_by == $user->id && $user->authorise('review.edit.own', 'com_djreviews')) || $user->authorise('core.edit', 'com_djreviews')
			|| ($user->id > 0 && $item->created_by == $user->id && $user->authorise('review.delete.own', 'com_djreviews')) || $user->authorise('core.delete', 'com_djreviews')) {
			$has_toolbar = true;
		}
		?>
		
		<?php if ($has_toolbar) { ?>
		<div class="djrv_review_toolbar pull-right">
			<div class="btn-toolbar">
				<div class="btn-group">
				<?php 
				if (($user->id > 0 && $item->created_by == $user->id && $user->authorise('review.edit.own', 'com_djreviews')) || $user->authorise('core.edit', 'com_djreviews')) {
					JUri::reset();
					$uri = JUri::getInstance($this->review_object->link);
					$query = $uri->getQuery(true);
					$query['djreviews_action'] = 'edit';
					$query['djreviews_review'] = $item->id;
					$uri->setQuery($query);
					$uri->setFragment('your-review');
					$edit_link = $uri->toString();
					JUri::reset();
				?>
				<a class="djrv_edit_button btn button btn-mini" href="<?php echo $edit_link; ?>" data-action="edit" data-id="<?php echo $item->id; ?>"><?php echo JText::_('COM_DJREVIEWS_EDIT'); ?></a>
				<?php } ?>
				
				<?php 
				if (($user->id > 0 && $item->created_by == $user->id && $user->authorise('review.delete.own', 'com_djreviews')) || $user->authorise('core.delete', 'com_djreviews')) {
					$uri = JRoute::_($this->review_object->link);
					$remove_link = JRoute::_('index.php?option=com_djreviews&task=review.delete&id='.$item->id.'&return='.base64_encode($uri).'&'.JSession::getFormToken().'=1');
				?>
				<a class="djrv_delete_button btn button btn-mini btn-danger" href="<?php echo $remove_link; ?>" data-action="delete" data-id="<?php echo $item->id; ?>"><?php echo JText::_('COM_DJREVIEWS_DELETE'); ?></a>
				<?php } ?>
				
				<?php 
					/*TODO if ($user->id > 0 ) {
					$uri = JRoute::_($this->review_object->link);				
					$abuse_link = JRoute::_('index.php?option=com_djreviews&task=review.report&id='.$item->id.'&return='.base64_encode($uri).'&'.JSession::getFormToken().'=1');
					?>
					<a class="djrv_abuse_button btn button btn-mini btn-warning" href="<?php echo $abuse_link; ?>" data-action="report" data-id="<?php echo $item->id; ?>"><?php echo JText::_('COM_DJREVIEWS_REPORT_ABUSE'); ?></a>
				<?php } */?>
				</div>
			</div>
		</div>
		<?php } ?>
		</div>
	</div>
<?php } ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($this->pagination->total > 0 && $this->pagination->total > $this->pagination->limit) { ?>
<div class="djrv_pagination pagination djc_clearfix">
<?php
$pagination_data = $this->pagination->getData();
if (!empty($pagination_data->pages)) { ?>
<ul>
<?php
    $pages = array();

    //$pages[] = $pagination_data->start;
    
    $pagination_data->previous->text = '&laquo;';
    $pages[] = $pagination_data->previous;
    
    $pages = array_merge($pages, $pagination_data->pages);
    
    $pagination_data->next->text = '&raquo;';
    $pages[] = $pagination_data->next;
    
    //$pages[] = $pagination_data->end;
    
    foreach($pages as $pageno => $page) {
        ?>
            <?php if ($page->active || $page->base === null) { ?>
                <li class="hidden-phone active small">
                    <a><?php echo $page->text; ?></a>
                </li>
            <?php } else {?>
                <li class="hidden-phone  small">
                    <a class="pagenav djrv_pagination_link" data-page="<?php echo $page->base; ?>" href="<?php echo JRoute::_($this->review_object->link.'&djreviews_limitstart='.$page->base)  ?>"><?php echo $page->text; ?></a>
                </li>
            <?php } ?>
        <?php
    } ?>
</ul>
    
<?php } ?>
</div>
<?php } ?>

<?php
JUri::reset();
$uri = JUri::getInstance();
$query = $uri->getQuery(true);
$query['djreviews_action'] = 'add';
if (isset($query['djreviews_review'])) {
	unset($query['djreviews_review']);
}
$uri->setQuery($query);
$uri->setFragment('your-review');
$add_link = $uri->toString();
JUri::reset();

?>

<?php if ($user->authorise('core.create', 'com_djreviews') || ($user->authorise('review.create', 'com_djreviews'))) { ?>
<a data-action="add" class="btn button djrv_add_button" href="<?php echo JRoute::_($add_link); ?>"><?php echo JText::_('COM_DJREVIEWS_ADD_REVIEW_BUTTON'); ?></a>
<?php } else if ($user->guest) { ?>
<a class="btn button djrv_addreview_btn guest" href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($this->review_object->link)); ?>"><?php echo JText::_('COM_DJREVIEWS_LOGIN_TO_REVIEW_BUTTON'); ?></a>
<?php } ?>

</div>
