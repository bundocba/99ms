<?php
/**
 *
 * View class for the product
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 5843 2012-04-09 17:29:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author RolandD,Max Milbers
 */
if(!class_exists('VmView'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmview.php');

class VirtuemartViewColors extends VmView {

	function display($tpl = null) {
		
		// Get the task
		$task = JRequest::getWord('task');

		// Load helpers
	//	$this->loadHelper('currencydisplay');


	//	$this->loadHelper('html');
	//	$this->loadHelper('image');


		//$category_model = VmModel::getModel('category');
		$model = VmModel::getModel();

		// Handle any publish/unpublish
		switch ($task) {
			case 'add':
			case 'edit':

				//this was in the controller for the edit tasks, I dont know if it is still needed,
				$this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'colors'.DS.'tmpl');
				$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		    	if($cid[0])
					$id = $cid[0];
				else
					$id = JRequest::getInt('id');
				
				$row = $model->getColorSingle($id);
				$config = JFactory::getConfig();
				$tzoffset = $config->getValue('config.offset');
				
				$text ='';
				$this->SetViewTitle('COLORS',$text);
				$this->assignRef('row', $row);
				$this->addStandardEditViewCommands ($row->id);
				break;

			default:
				// Toolbar
				$colorlist = $model->getColorListing(false,false,false,false,true);
				$this->assignRef('colors', $colorlist);
				
				$this->addStandardDefaultViewCommands();
		
			break;
		}

		parent::display($tpl);
	}

	
}

//pure php no closing tag
