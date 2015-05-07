<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Product
* @author RolandD, mwattier, pablo
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: waitinglist.php 5758 2012-03-31 10:15:11Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the model framework
jimport( 'joomla.application.component.model');

/**
* Model for VirtueMart Product Files
*
* @package	VirtueMart
* @author RolandD
*/
class VirtueMartModelWaitingList extends JModel {

	/**
	* Load the customers on the waitinglist
	*/
	public function getWaitingusers($virtuemart_product_id) {
		
		if (!$virtuemart_product_id) { return false; }
		
		//Sanitize param
		$virtuemart_product_id  = (int) $virtuemart_product_id;
		
		$db = JFactory::getDBO();
		$q = 'SELECT name, username, virtuemart_user_id, notify_email, notified, notify_date FROM `#__virtuemart_waitingusers`
				LEFT JOIN `#__users` ON `virtuemart_user_id` = `id`
				WHERE `virtuemart_product_id`=' .$virtuemart_product_id ;
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Notify customers product is back in stock
	* @author RolandD
	* @author Christopher Rouseel
	* @todo Add Itemid &Itemid='.$sess->getShopItemid()
	* @todo Do something if the mail cannot be send
	* @todo Update mail from
	* @todo Get the from name/email from the vendor
	*/
	public function notifyList ($virtuemart_product_id,$subject = '', $mailbody = '', $max_number = 0) {
		if (!$virtuemart_product_id) { return false; }


		//sanitize id
		$virtuemart_product_id = (int)$virtuemart_product_id;
		$max_number = '';

		if (!class_exists ('shopFunctionsF')) {
			require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
		}
		$vars = array();
		$waitinglistModel = VmModel::getModel ('waitinglist');
		$waiting_users = $waitinglistModel->getWaitingusers ($virtuemart_product_id);

		/* Load the product details */
		$db = JFactory::getDbo ();
		$q = "SELECT l.product_name,product_in_stock FROM `#__virtuemart_products_" . VMLANG . "` l
				JOIN `#__virtuemart_products` p ON p.virtuemart_product_id=l.virtuemart_product_id
			   WHERE p.virtuemart_product_id = " . $virtuemart_product_id;
		$db->setQuery ($q);
		$item = $db->loadObject ();
		$vars['productName'] = $item->product_name;
		
		if ($item->product_in_stock <= 0) {
			return FALSE;
		}
		
		$productModel = VmModel::getModel('Product');
		$product = $productModel->getProductSingle($virtuemart_product_id, true, false, true, false);
		$productModel->addImages($product);
		
		
	
		
		$url = JURI::root () . 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $virtuemart_product_id;
		$vars['link'] = '<a href="'. $url.'">'. $item->product_name.'</a>';


		if (empty($subject)) {
			$subject = JText::sprintf('COM_VIRTUEMART_PRODUCT_WAITING_LIST_EMAIL_SUBJECT', $item->product_name);
		}
		
		
		$vars['subject'] = $subject;

		$virtuemart_vendor_id = 1;
		$vendorModel = VmModel::getModel ('vendor');
		$vendor = $vendorModel->getVendor ($virtuemart_vendor_id);
		$vendorModel->addImages ($vendor);
		$vars['vendor'] = $vendor;


		$vendorEmail = $vendorModel->getVendorEmail ($virtuemart_vendor_id);
		$vars['vendorEmail'] = $vendorEmail;
		
		$vars['task'] = 'waitinglist';
		
		$mailbody = "<img src='".JURI::root ().$vendor->images[0]->file_url."' />";
		$mailbody.="<br /><br />Hello,<br /><br />Our $item->product_name is now in stock and can be purchased by following this link:
{$vars['link']}.</br />
This is a one time notice, you will not receive this e-mail again.</br /></br />
Thank you for purchasing at Mummy's Secrect.
<br />
Mummy's Secrect";

		$vars['mailbody'] = $mailbody;

		$i = 0;
		foreach ($waiting_users as $waiting_user) {
			if($waiting_user->notified == 1)
				continue;

			$vars['user'] =  $waiting_user->name ;
			if (shopFunctionsF::renderMail ('productdetails', $waiting_user->notify_email, $vars, 'productdetails')) {

				$db->setQuery ('UPDATE #__virtuemart_waitingusers SET notified=1 WHERE virtuemart_user_id =' . $waiting_user->virtuemart_user_id);
				$db->query ();
				$i++;
			}
			if (!empty($max_number) && $i >= $max_number) {
				break;
			}
		}
		return TRUE;
	}
	
	/**
	 * Add customer to the waiting list for specific product
	 *
	 * @author Seyi Awofadeju
	 * @return insert_id if the save was successful, false otherwise.
	 */
	public function adduser($data) {
		JRequest::checkToken() or jexit( 'Invalid Token, in notify customer');

		
		$field = $this->getTable('waitingusers');

		if (!$field->bind($data)) { // Bind data
			vmError($field->getError());
			return false;
		}

		if (!$field->check()) { // Perform data checks
			vmError($field->getError());
			return false;
		}

		$_id = $field->store();
		if ($_id === false) { // Write data to the DB
			vmError($field->getError());
			return false;
		}


		//jexit();
		return $_id ;
	}


}
// pure php no closing tag
