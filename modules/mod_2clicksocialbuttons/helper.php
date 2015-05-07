<?php
/**
 *  @Copyright
 *  @package    2CSB - 2 Click Social Buttons - Module
 *  @author     Viktor Vogel {@link http://www.kubik-rubik.de}
 *  @version    2.5-1
 *  @date       Created on 24-Jun-2012
 *  @link       Project Site {@link http://joomla-extensions.kubik-rubik.de/2csb-2-click-social-buttons}
 *
 *  @license GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

class mod_2clicksocialbuttonsHelper extends JObject
{
    function loadHeadData($params)
    {
        $document = JFactory::getDocument();

        if($params->get('twittername') != '')
        {
            $script = '/* <![CDATA[ */ var twittername = "'.$params->get('twittername').'"; /* ]]> */';
            $document->addScriptDeclaration($script);
        }

        $document->addStyleSheet('modules/mod_2clicksocialbuttons/2clicksocialbuttons.css', 'text/css');

        if($params->get('layout') == 1)
        {
            $css = '#social_bookmarks ul.social_share li {float: left;}';

            $document->addStyleDeclaration($css, 'text/css');
        }
    }

    function getButtons($params)
    {
        $html = '<!-- Click Social Buttons -->';
        $html .= '<div id="social_bookmarks"><ul class="social_share">';

        if($params->get('facebook_button') == 1)
        {
        	$send_url = $params->get('send_url');
        	$send_font = $params->get('send_font');
        	$send_colorscheme = $params->get('send_colorscheme');
			//'.JURI::current().'
           	$html .= '<li id="facebook_button_on"><iframe src="http://www.facebook.com/plugins/like.php?href='.JURI::current().'&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;locale=en_US" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></li>';
        	$html .= '<div id="fb-root"></div><div><fb:send href="'.$send_url.'" font="'.$send_font.'" colorscheme="'.$send_colorscheme.'"></fb:send></div>';
        }

        if($params->get('twitter_button') == 1)
        {
        	$doc = JFactory::getDocument();
        	$page_title = $doc->getTitle();       	

        	if($params->get('twittername')!=""){
        		$via = '&amp;via='.$params->get('twittername');
        	}else{
        		$via = "";
        	}
        	
            $html .= '<li><iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?text='.$page_title. $via .'" style="width:100px; height:21px;"></iframe></li>';
        }

        if($params->get('googleplus_button') == 1)
        {
            $html .= '<li><iframe src="https://plusone.google.com/u/0/_/+1/fastbutton?url='.JURI::current().'&amp;size=medium&amp;count=true&amp;lang=de" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" align="left"></iframe></li>';
        }


        $html .= '</ul></div>';

        return $html;
    }
}?>
