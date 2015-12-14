<?php
/**
 * @version $Id: video.php 58 2015-06-10 12:15:24Z szymon $
 * @package DJ-MediaTools
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-MediaTools is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-MediaTools is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-MediaTools. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

abstract class DJVideoHelper {
	
	private static $video = array();
	
	public static function getVideo($link) {
		
		$key = md5($link);
		
		if(!isset(self::$video[$key])) {
				
			self::$video[$key] = self::parseVideoLink($link);
				
		}
		
		return self::$video[$key];
		
	}
	
	/* Parsing the passed video url and create object with require information */
	private static function parseVideoLink($link) {
		
		$info = self::getInfoFromUrl($link);
		$video = new stdClass();
		//return $info;
		switch($info->provider) {
			
			case 'dailymotion':
				
				$api_url = 'https://api.dailymotion.com/video/'.$info->id.'?fields=title,embed_url,thumbnail_url,access_error';
				$data = self::getOembedObject($api_url);
				
				//$video->api_url = $api_url;
				$video->title = $data->title;
				$video->embed = $data->embed_url;
				$video->thumbnail = $data->thumbnail_url;
				$video->error = isset($data->error) ? $data->error : $data->access_error;
				break;
				
			case 'metacafe':
				
				$api_url = 'http://www.metacafe.com/api/item/'.$info->id;
				$data = self::getOembedObject($api_url, 'xml');
				
				$content = $data->media_content->attributes();
				$thumb = $data->media_thumbnail->attributes();
				
				$video->title = (string) $data->title;
				$video->embed = (string) $content['url'];
				$video->thumbnail = (string) $thumb['url'];
				//$video->error = isset($data->error) ? $data->error : $data->access_error;
				break;
				
			default:
				
				$api_url = 'http://noembed.com/embed?url='.urlencode($link);
				$data = self::getOembedObject($api_url);
				
				if(!in_array($data->type, array('video', 'rich'))) {
					$video->error = JText::_('COM_DJMEDIATOOLS_NOT_SUPPORTED_VIDEO_LINK');
				}
				
				preg_match('/<iframe [^>]*src="([^"]+)"/', $data->html, $match);
				
				if($match) {
					$video->embed = str_replace('http:', '', urldecode($match[1]));
				} else if(!$data->embed) {
					$video->error = JText::_('COM_DJMEDIATOOLS_NOT_SUPPORTED_VIDEO_LINK');
				} else {
					$video->embed = $data->embed;
				}
				
				$video->title = $data->title;
				$video->thumbnail = $data->thumbnail_url;
				break;
		}
		
		return $video;
	}
	
	private static function getInfoFromUrl($url) {
		
		$info = new stdClass();
		$info->provider = null;
		
		if(strstr($url, 'dailymotion.com/video') !== false) {
			//http://www.dailymotion.com/video/x34ftdo_need-for-speed-2015-gameplay-innovations-five-ways-to-play-official-street-racing-game-2015_videogames
			
			if(preg_match('/dailymotion\.com\/video\/([^_]+)\_/', $url, $match)) {
				$info->provider = 'dailymotion';
				$info->id = $match[1];
			}
		} else if(strstr($url, 'metacafe.com/watch') !== false) {
			//http://www.metacafe.com/watch/11006706/call_of_duty_ghosts_astronauts_child_of_light_announced_ps_vita_tv_more_destructoid/
			
			if(preg_match('/metacafe\.com\/watch\/(\d+)\/(\w+)\//', $url, $match)) {
				$info->provider = 'metacafe';
				$info->id = $match[1];
			}
		}

		return $info;
	}
	
	private static function getOembedObject($url, $format = 'json') {
		
		// use curl to get video oembed information
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
		$res = curl_exec($ch);
		
		// curl faild, we try to get video oembed info with file_get_contents
		if(!$res) {
			$res = file_get_contents($url);
		}
		
		if($format == 'xml') {
			
			$xml = simplexml_load_string(str_replace(array("<media:", "</media:"), array("<media_", "</media_"), $res));
			
			if (!$xml)
			{
				$res = '{"error": "Wrong XML format"}';
			} else {
				$res = $xml->channel->item;
			}
		}
		
		if(is_object($res)) {
			$json = $res;
		} else {
			$json = json_decode($res);
		}
		
		if(!$res && curl_errno($ch))
		{
			$json->error = 'API CALL ERROR ['.$url.']: '.curl_error($ch);
		}
		
		curl_close($ch);
		
		return $json;
	}
}