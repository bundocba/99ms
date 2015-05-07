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
* @version $Id: product.php 3304 2011-05-20 06:57:27Z alatak $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
AdminUIHelper::startAdminArea();

$i = 0;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="adminlist" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll('<?php echo count($this->productlist); ?>')" /></th>
				<th>Name</th>
				<th>Code</th>
				<th width="40px" ><?php echo $this->sort('published') ; ?></th>
	            <th width="5"><?php echo $this->sort('id', 'COM_VIRTUEMART_ID')  ?></th>
	        </tr>
		</thead>
	<tbody>
	<?php foreach($this->colors as $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
			<td> <?php echo JHtml::_('grid.id', $i, $item->id); ?>	</td>
			<td>
				<a href="index.php?option=com_virtuemart&view=colors&task=edit&id=<?php echo $item->id;?>"><?php echo $item->name; ?></a>
			</td>
			<td>
				<div style="background: #<?php echo $item->code; ?>; width: 45px; height: 15px;"></div>
			</td>
			<td align="center">
	       	 	<?php echo JHtml::_('jgrid.published', $item->published, $i); ?>
	       	</td>
	       	<td>
				<?php echo $item->id; ?>
			</td>
        </tr>
		<?php $i++; endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->addStandardHiddenToForm(); ?>
</form>
<?php AdminUIHelper::endAdminArea(); ?>
