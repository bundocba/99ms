<?php
defined('_JEXEC') or die;

class fwjQueryHelper
{
	public static function getjQuery($core_source, $core_version, $core_custom_source) {
		$src = "";

		switch ($core_source) {
			case 'custom':
				$src = $core_custom_source;
				break;
			case 'cloud':
				$src = "http://ajax.googleapis.com/ajax/libs/jquery/".$core_version."/jquery.min.js";
				break;
			default:
				
				if(version_compare($core_version, "1.9.0") >=0) {
					$migrate_src = JURI::root()."plugins/system/fwjquery/lib/core/jquery-migrate-1.1.0.min.js";
				}
				$src = JURI::root()."plugins/system/fwjquery/lib/core/jquery-".$core_version.".min.js";
				break;
		}

		return fwjQueryHelper::getScriptTag($src)."<script>jQuery.noConflict();</script>";
	}

	public static function getjQueryUI($is_ui, $ui_version, $ui_theme) {
		$src = $srcTouch = "";

		if($is_ui) {
			$src = JURI::root()."plugins/system/fwjquery/lib/ui/ui-".$ui_version."/jquery-ui.min.js";
			$srcTouch = JURI::root()."plugins/system/fwjquery/lib/core/jquery.touch.js";
			$link = JURI::root()."plugins/system/fwjquery/lib/ui/ui-".$ui_version."/themes/".$ui_theme."/jquery-ui.custom.min.css";
		}

		return fwjQueryHelper::getScriptTag($src).fwjQueryHelper::getScriptTag($srcTouch).fwjQueryHelper::getStyleSheetTag($link);
	}

	public static function getScrollPane($is_scrollpane) {
		$src = $link = $mousewheelsrc = "";
		if($is_scrollpane) {
			$mousewheelsrc = JURI::root()."plugins/system/fwjquery/lib/plugin/jascrollpane/jquery.mousewheel.min.js";
			$src = JURI::root()."plugins/system/fwjquery/lib/plugin/jascrollpane/jascrollpane.js";
			$link = JURI::root()."plugins/system/fwjquery/lib/plugin/jascrollpane/jascrollpane.css";
		}

		return fwjQueryHelper::getScriptTag($mousewheelsrc)
				.fwjQueryHelper::getScriptTag($src)
				.fwjQueryHelper::getStyleSheetTag($link);
	}

	public static function getSelectBox($is_selectbox, $selectbox_fn_name, 
		$selectbox_selector, $selectbox_class, $selectbox_bg_color, $selectbox_text_color,
		$selectbox_item_style, $selectbox_search_box, $selectbox_search_box_class,
		$selectbox_scrollbar_color, $selectbox_max_height = 200) {
		$src = $link = $script = "";
		
		if($is_selectbox) {
		
			/*Load Selectbox Core*/
			$src = JURI::root()."plugins/system/fwjquery/lib/plugin/selectbox/js/fwselect.js";
			$link = JURI::root()."plugins/system/fwjquery/lib/plugin/selectbox/css/style.css";
		
			/*Generate function defination*/
			$script = "
				function $selectbox_fn_name(selector) {
					jQuery(selector).fwselect({
						'class'				:	'$selectbox_class',
						'backgroundColor'	:	'$selectbox_bg_color',
						'textColor'			:	'$selectbox_text_color',
						'itemStyle'			: 	'$selectbox_item_style', 
						'showSearchBox'		:	$selectbox_search_box,
						'searchBoxClass'	: 	'$selectbox_search_box_class', 
						'scrollBarColor'	: 	'$selectbox_scrollbar_color', 
						'maxHeight'			: 	$selectbox_max_height
					});
				}
				jQuery(document).ready(function($) {
					$selectbox_fn_name('$selectbox_selector');
				})
			";
		}

		return fwjQueryHelper::getScriptTag($src)
				.fwjQueryHelper::getStyleSheetTag($link)
				.fwjQueryHelper::getScriptTag("",$script);
	}

	public static function getRadio($is_radio, $radio_fn_name, 
		$radio_selector, $radio_class, $radio_mark_class, $radio_label_class) {
		$src = $link = $script = "";
		
		if($is_radio) {
		
			/*Load Radio Core*/
			$src = JURI::root()."plugins/system/fwjquery/lib/plugin/radiobutton/js/fwradio.js";
			$link = JURI::root()."plugins/system/fwjquery/lib/plugin/radiobutton/css/style.css";
		
			/*Generate function defination*/
			$script = "
				function $radio_fn_name(selector) {
					jQuery(selector).fwradio({
						radioClass		:	'$radio_class',
						radioMarkClass	:	'$radio_mark_class',
						labelClass		:	'$radio_label_class'
					});
				}
				jQuery(document).ready(function($) {
					$radio_fn_name('$radio_selector');
				})
			";
		}

		return fwjQueryHelper::getScriptTag($src)
				.fwjQueryHelper::getStyleSheetTag($link)
				.fwjQueryHelper::getScriptTag("",$script);
	}

	public static function getCheckbox($is_checkbox, $checkbox_fn_name, 
		$checkbox_selector, $checkbox_class, $checkbox_mark_class, $checkbox_label_class) {
		$src = $link = $script = "";
		
		if($is_checkbox) {
		
			/*Load Radio Core*/
			$src = JURI::root()."plugins/system/fwjquery/lib/plugin/checkbox/js/fwcheckbox.js";
			$link = JURI::root()."plugins/system/fwjquery/lib/plugin/checkbox/css/style.css";
		
			/*Generate function defination*/
			$script = "
				function $checkbox_fn_name(selector) {
					jQuery(selector).fwcheckbox({
						radioClass		:	'$checkbox_class',
						radioMarkClass	:	'$checkbox_mark_class',
						labelClass		:	'$checkbox_label_class'
					});
				}
				jQuery(document).ready(function($) {
					$checkbox_fn_name('$checkbox_selector');
				})
			";
		}

		return fwjQueryHelper::getScriptTag($src)
				.fwjQueryHelper::getStyleSheetTag($link)
				.fwjQueryHelper::getScriptTag("",$script);
	}

	public static function getIFUpload($is_ifupload, $ifupload_fn_name, $ifupload_selector) {
		$src = $link = $script = "";
		
		if($is_ifupload) {
		
			/*Load Radio Core*/
			$src = JURI::root()."plugins/system/fwjquery/lib/plugin/ifupload/js/ifupload.js";
			$link = JURI::root()."plugins/system/fwjquery/lib/plugin/ifupload/css/style.css";
		
			/*Generate function defination*/
			$script = "
				function $ifupload_fn_name(selector) {
					jQuery(selector).ifUpload({});
				}

				jQuery(document).ready(function($) {
					$ifupload_fn_name('$ifupload_selector');
				})
			";
		}

		return fwjQueryHelper::getScriptTag($src)
				.fwjQueryHelper::getStyleSheetTag($link)
				.fwjQueryHelper::getScriptTag("",$script);
	}

	public static function getPopup($is_popup) {
		$src = $link = "";
		if($is_popup) {
			/*Load Popup Core*/
			$src = JURI::root()."plugins/system/fwjquery/lib/plugin/popup/js/fwpopup.js";
			$link = JURI::root()."plugins/system/fwjquery/lib/plugin/popup/css/style.css";
		}

		return fwjQueryHelper::getScriptTag($src)
				.fwjQueryHelper::getStyleSheetTag($link);
	}

	public static function getAlert($is_popup, $alert_fn_name, $alert_bg, $alert_popup_class,
		$alert_popup_bg, $alert_popup_border, $alert_popup_width, $alert_popup_height,
		$alert_popup_top, $alert_animation_speed, $alert_opacity, $alert_button_class,
		$alert_button_value) {
		$script = "";
		
		if($is_popup) {
			$script = "
				function $alert_fn_name(msg) {
					jQuery.fwpopup({
						message: msg,
						background: '$alert_bg',
						popupClass: '$alert_popup_class',
						popupBackground: '$alert_popup_bg',
						popupBorder: '$alert_popup_border',
						popupWidth: '$alert_popup_width',
						popupHeight: '$alert_popup_height',
						popupTop: '$alert_popup_top',
						speed: $alert_animation_speed,
						opacity: $alert_opacity,
						buttonClass: '$alert_button_class',
						button:{
							button_yes: {
								'onClick'	: 	function() {},
								'value'		:	'$alert_button_value'
							}
						},
						closeButton: false
					})
				}

			";
		}

		return fwjQueryHelper::getScriptTag("", $script);

	}


	public static function getIframe($is_popup, $iframe_fn_name, $iframe_bg, $iframe_popup_class,
		$iframe_popup_bg, $iframe_popup_border, $iframe_popup_width, $iframe_popup_height,
		$iframe_iframe_width, $iframe_iframe_height,$iframe_popup_top, $iframe_animation_speed, 
		$iframe_opacity, $iframe_button_class, $iframe_button_value) {
		$script = "";

		if($is_popup) {
			$script = "
				function $iframe_fn_name(link, width, height, iframeWidth, iframeHeight) {
					if (typeof width == 'undefined') {
						width = '$iframe_popup_width';
					}

					if (typeof height == 'undefined') {
						height = '$iframe_popup_height';
					}

					if (typeof iframeWidth == 'undefined') {
						iframeWidth = '$iframe_iframe_width';
					}

					if (typeof iframeHeight == 'undefined') {
						iframeHeight = '$iframe_iframe_height';
					}
					jQuery.fwpopup({
						background: '$iframe_bg',
						popupClass: '$iframe_popup_class',
						popupBackground: '$iframe_popup_bg',
						popupBorder: '$iframe_popup_border',
						popupWidth: width,
						popupHeight: height,
						backgroundClickClose: true,
						speed: $iframe_animation_speed,
						opacity: $iframe_opacity,
						popupTop: '$iframe_popup_top',
						buttonClass: '$iframe_button_class',
						closeButton: false,
						iframe: {
							link: link,
							width: iframeWidth,
							height: iframeHeight
						}
					})
				}
			";
		}

		return fwjQueryHelper::getScriptTag("", $script);
	}

	public static function getHTML($is_popup, $html_fn_name, $html_bg,
		$html_popup_class, $html_popup_bg, $html_popup_border, $html_popup_width, 
		$html_popup_height,$html_popup_top, $html_animation_speed, 
		$html_opacity, $html_button_class, $html_button_value) {
		$script = "";

		if($is_popup) {
			$script = "
				function $html_fn_name(html, width, height) {
					if (typeof width == 'undefined') {
						width = '$html_popup_width';
					}

					if (typeof height == 'undefined') {
						height = '$html_popup_height';
					}
					jQuery.fwpopup({
						message: html,
						background: '$html_bg',
						popupClass: '$html_popup_class',
						popupBackground: '$html_popup_bg',
						popupBorder: '$html_popup_border',
						popupWidth: width,
						popupHeight: height,
						speed: '$html_animation_speed',
						opacity: '$html_opacity',
						popupTop: '$html_popup_top',
						buttonClass: '$html_button_class',
						closeButton: false,
						closeButtonClass: 'closeButton',
						backgroundClickClose: true,
					})
				}
			";
		}

		return fwjQueryHelper::getScriptTag("", $script);
	}

	public static function getScriptTag($src, $data = "") {
		if($src)
			return "<script src='".$src."' type='text/javascript'></script>";
		if($data)
			return "<script type='text/javascript'>$data</script>";

		return "";
	}

	public static function getStyleSheetTag($link) {
		if($link)
			return '<link rel="stylesheet" href="'.$link.'" type="text/css">';
	}
}
