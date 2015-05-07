<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla! System Remember Me Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	System.remember
 */
class plgSystemJselectbox extends JPlugin
{
	function onAfterInitialise()
	{
		
		$app = JFactory::getApplication();

		// No remember me for admin
		if ($app->isAdmin()) {
			return;
		}
		jimport( 'joomla.html.parameter' );

		$plugin = JPluginHelper::getPlugin('system', 'jselectbox');

		$params = new JParameter($plugin->params);
		
		$document =& JFactory::getDocument();
		
		$document->addScript(JURI::Root().'plugins/system/jselectbox/asset/selectbox.js');
		$document->addStyleSheet(JURI::Root().'plugins/system/jselectbox/asset/selectbox.css');
		$document->addStyleSheet(JURI::Root().'plugins/system/jselectbox/asset/config.css');

		$content = '
			jQuery(function() {
				jQuery(".jselectbox").jselectbox({
					"effect":"slide",
					"optionMaxHeight":200,
					"search":false,
					"speed":"fast" // fast, slow, null
				});
			})
		';

		//$document->addScriptDeclaration( $content );
	}
}
