<?php
/**
*
* Description
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
* @version $Id: product_edit.php 5225 2012-01-06 01:50:19Z electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
AdminUIHelper::startAdminArea();


$document = JFactory::getDocument();
$path = JURI::root().'images/colors/';
$row = $this->row ;

?>
<script type="text/javascript" src="<?php echo JURI::root();?>jscolor/jscolor.js"></script>

<form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=colors'); ?>" method="post" name="adminForm" id="products-form" enctype="multipart/form-data">
<input type="hidden" name="img1" id="img1" value="" />
	<fieldset class="adminform">
		<legend><?php echo JText::_('Details'); ?></legend>
		 <div class="col100">
     <table class="admintable" >
		 <tr>
       <td class="key" style="width:180px;">
         <?php echo JText::_('Published');?>
       </td>
       <td>
         <input type = "checkbox" name = "published" id = "published" value = "1" <?php if ($row->published) echo 'checked = "checked"'?> />
       </td>
     </tr>
       <tr>
         <td class="key" style="width:180px;">
           <?php echo JText::_('Name');?>
         </td>
         <td>
           <input type = "text" class = "inputbox" size = "50" name="name" value = "<?php echo $row->name?>" />
         </td>
       </tr>
	  
	  <tr>
         <td class="key" style="width:180px;">
           <?php echo JText::_('Code');?>
         </td>
         <td>
           <input type = "text" class = "inputbox color" size = "50" name="code" value = "<?php echo $row->code?>" />
         </td>
       </tr>
	  	
	   
	   
		
      
     </table>
     </div>
     <div class="clr"></div>
	</fieldset>
	<div>
		 <input type = "hidden" name = "task" value = "" />
		 <input type = "hidden" name = "option" value = "com_virtuemart" />
		 <input type = "hidden" name = "controller" value = "colors" />
		 <input type = "hidden" name = "view" value = "colors" />
 		  <input type = "hidden" name = "id" value = "<?php echo $row->id?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>sf
<?php AdminUIHelper::endAdminArea(); ?>