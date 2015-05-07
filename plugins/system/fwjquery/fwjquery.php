<?php
defined('_JEXEC') or die;
require_once(dirname(__FILE__).DS."helpers".DS."fwjquery.php");

class plgSystemFwjQuery extends JPlugin
{
	public function onBeforeCompileHead()
	{
		$params = $this->params;
		$app = JFactory::getApplication();
		if(in_array($app->getName(), $params->get('load_in', array('site')))) {
			
			/*Load jQuery*/
			echo fwjQueryHelper::getjQuery(
				$params->get('core_source', 'local'), 
				$params->get('core_version', '1.9.0'), 
				$params->get('core_custom_source', ''));

			/*Load jQuery UI*/
			echo fwjQueryHelper::getjQueryUI(
				$params->get('is_ui', '0'),
				$params->get('ui_version', '1.10.0'),
				$params->get('ui_theme', 'ui-lightness')
				);

			/*Load Scroll Pane*/
			echo fwjQueryHelper::getScrollPane(
				$params->get('is_scrollpane', '0')
				);

			/*Load Select Box*/
			echo fwjQueryHelper::getSelectBox(
				$params->get('is_selectbox', '0'),
				$params->get('selectbox_fn_name', 'fwselectbox'),
				$params->get('selectbox_selector', '.select'),
				$params->get('selectbox_class', ''),
				$params->get('selectbox_bg_color', ''),
				$params->get('selectbox_text_color', ''),
				$params->get('selectbox_item_style', ''),
				$params->get('selectbox_search_box', ''),
				$params->get('selectbox_search_box_class', ''),
				$params->get('selectbox_scrollbar_color', '#fff'),
				$params->get('selectbox_max_height', '100')
				);

			/*Load Radio*/
			echo fwjQueryHelper::getRadio(
				$params->get('is_radio', '0'),
				$params->get('radio_fn_name', 'fwselectbox'),
				$params->get('radio_selector', '.select'),
				$params->get('radio_class', ''),
				$params->get('radio_mark_class', ''),
				$params->get('radio_label_class', '')
				);

			/*Load Checkbox*/
			echo fwjQueryHelper::getCheckbox(
				$params->get('is_checkbox', '0'),
				$params->get('checkbox_fn_name', 'fwselectbox'),
				$params->get('checkbox_selector', '.select'),
				$params->get('checkbox_class', ''),
				$params->get('checkbox_mark_class', ''),
				$params->get('checkbox_label_class', '')
				);

			/*Load Iframe Upload*/
			echo fwjQueryHelper::getIFUpload(
				$params->get('is_ifupload', '0'),
				$params->get('ifupload_fn_name', 'fwselectbox'),
				$params->get('ifupload_selector', '.select')
				);

			/*Load Popup*/
			echo fwjQueryHelper::getPopup(
				$params->get('is_popup', '0')
				);

			/*Load Custom Alert*/
			echo fwjQueryHelper::getAlert(
				$params->get('is_popup', '0'),
				$params->get('alert_fn_name', 'fwalert'),
				$params->get('alert_bg', ''),
				$params->get('alert_popup_class', ''),
				$params->get('alert_popup_bg', ''),
				$params->get('alert_popup_border', ''),
				$params->get('alert_popup_width', ''),
				$params->get('alert_popup_height', ''),
				$params->get('alert_popup_top', ''),
				$params->get('alert_animation_speed', ''),
				$params->get('alert_opacity', ''),
				$params->get('alert_button_class', ''),
				$params->get('alert_button_value', '')
				);

			/*Load Custom Iframe*/
			echo fwjQueryHelper::getIframe(
				$params->get('is_popup', '0'),
				$params->get('iframe_fn_name', 'fwiframe'),
				$params->get('iframe_bg', ''),
				$params->get('iframe_popup_class', ''),
				$params->get('iframe_popup_bg', ''),
				$params->get('iframe_popup_border', ''),
				$params->get('iframe_popup_width', ''),
				$params->get('iframe_popup_height', ''),
				$params->get('iframe_iframe_width', ''),
				$params->get('iframe_iframe_height', ''),
				$params->get('iframe_popup_top', ''),
				$params->get('iframe_animation_speed', ''),
				$params->get('iframe_opacity', ''),
				$params->get('iframe_button_class', ''),
				$params->get('iframe_button_value', '')
				);

			/*Load Custom Iframe*/
			echo fwjQueryHelper::getHtml(
				$params->get('is_popup', '0'),
				$params->get('html_fn_name', 'fwhtml'),
				$params->get('html_bg', ''),
				$params->get('html_popup_class', ''),
				$params->get('html_popup_bg', ''),
				$params->get('html_popup_border', ''),
				$params->get('html_popup_width', ''),
				$params->get('html_popup_height', ''),
				$params->get('html_popup_top', ''),
				$params->get('html_animation_speed', ''),
				$params->get('html_opacity', ''),
				$params->get('html_button_class', ''),
				$params->get('html_button_value', '')
				);

		}
	}
}
