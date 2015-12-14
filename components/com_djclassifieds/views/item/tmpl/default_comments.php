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
$app = JFactory::getApplication();
$item = $this->item;

if((int)$par->get('comments','0') == 1 || (int)$par->get('comments','0') == 3 || (int)$par->get('comments','0') == 4 || ($par->get('comments','0') == 2 && $par->get('disqus_shortname',''))){
	$uri = JFactory::getURI();
	$lang = JFactory::getLanguage();
	$languge_tag = str_replace('-', '_', $lang->getTag());
		if($par->get('comments','0') == 1){?>
			<div class="djcf_comments fb_comments_box">
				<h2><?php echo JText::_('COM_DJCLASSIFIEDS_COMMENTS'); ?></h2>				
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/<?php echo $languge_tag; ?>/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>				
				<div class="fb-comments" data-href="<?php echo $uri->toString(); ?>" data-num-posts="<?php echo $par->get('fb_comments_posts','10');?>" data-width="<?php echo $par->get('fb_comments_width','550px');?>"></div> 
			</div>
		<?php }else if($par->get('comments','0') == 2){ 
			$devlist = array('localhost', '127.0.0.1');
	    	$disqus_shortname = $par->get('disqus_shortname','');
	    	$disqus_url = $uri->toString();
	    	$disqus_identifier = $disqus_shortname.'-djcf-'.$this->item->id;
	    	$disqus_developer = (in_array($_SERVER['HTTP_HOST'], $devlist)) ? 1 : 0;
	    	?>
	    	<div class="djcf_comments disqus_comments_box">
				<h2><?php echo JText::_('COM_DJCLASSIFIEDS_COMMENTS'); ?></h2>	
		    	<div id="disqus_thread"></div> 
			    <script type="text/javascript">
			        var disqus_shortname = '<?php echo $disqus_shortname; ?>';
			        var disqus_url = '<?php echo $disqus_url; ?>';
			        var disqus_identifier = '<?php echo $disqus_identifier; ?>';
					var disqus_developer = <?php echo $disqus_developer; ?>;
					
			        /* * * DON'T EDIT BELOW THIS LINE * * */
			        (function() {
			            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
			            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			        })();
			    </script>
			    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
			    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
			</div>						
		<?php }else if ($par->get('comments','0') == 3) {  				 
				$comments = JPATH_SITE.'/components/com_jcomments/jcomments.php';
				if (file_exists($comments)) {
					require_once($comments);?>
					<div class="djcf_comments jcomments_comments_box">
						<h2><?php echo JText::_('COM_DJCLASSIFIEDS_COMMENTS'); ?></h2>
						<?php echo JComments::show($this->item->id,'com_djclassifieds', $this->item->name); ?>
					</div>
					<?php 
				}	
		}else if ($par->get('comments','0') == 4) {   	
	   		$komento =  JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_komento' . DIRECTORY_SEPARATOR . 'bootstrap.php';
			if (file_exists($komento)) {

				require_once($komento);
				
				$options			= array();
				$options['trigger']	= 'onDJClassifiedsItem';
				$options['context']	= 'com_djclassifieds.item';
				$options['params']	= $par;
				
				$comments = Komento::commentify( 'com_djclassifieds', $this->item, $options );
				if ($comments) {
					echo $comments;
				}
			}	
		}  	 
}	