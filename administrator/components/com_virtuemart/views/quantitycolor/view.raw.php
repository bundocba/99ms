<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
define('COM_MEDIA_BASE',    JPATH_ROOT);
if(!class_exists('VmView'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmview.php');


class VirtuemartViewquantitycolor extends JView{
	
	function getColors($productid){
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$query = "SELECT c.* FROM #__virtuemart_image as i LEFT JOIN  #__virtuemart_colors as c ON c.id = i.colorid WHERE i.productid ='{$productid}' group by colorid";
		$db->setQuery($query);
		$rows_type=$db->loadObjectList();
	//	echo $query;
		$options = array();
		for($i=0;$i<count($rows_type);$i++){
			$options[] = JHTML::_('select.option', $rows_type[$i]->id, $rows_type[$i]->name);
		}

		$dropdown_color = JHTML::_('select.genericlist', $options, 'color', 'class="inputbox" ', 'value', 'text', 0);
		
		return $dropdown_color;
	}
	
	function getSizes($productid) {
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$query = "SELECT vpc.* FROM #__virtuemart_customs AS vc LEFT JOIN #__virtuemart_product_customfields AS vpc ON vc.virtuemart_custom_id = vpc.virtuemart_custom_id WHERE vpc.virtuemart_product_id = '{$productid}'";
		$db->setQuery($query);
		$rows_type = $db->loadObjectList();
		//echo $query;
		$options = array();
		for($i = 0; $i < count($rows_type); $i++) {
			$options[] = JHTML::_('select.option', $rows_type[$i]->custom_value, $rows_type[$i]->custom_value);
		}

		$dropdown_size = JHTML::_('select.genericlist', $options, 'size', 'class="inputbox" ', 'value', 'text', 0);
		return $dropdown_size;
	}
	public function updateproductquantity($id){
		
		$db = JFactory::getDBO();
		$query = "SELECT sum(`quantity`) as quan, sum(`color_ordered`) as ord FROM #__virtuemart_quantity as q WHERE q.productid = '{$id}' ";	
		$db->setQuery($query);

		$rows=$db->loadObjectList();
		

		if(count($rows)){
			if(empty($rows[0]->ord)&&empty($rows[0]->quan)){
				$rows[0]->quan=0;
				$rows[0]->ord=0;
			}
			$query="UPDATE `#__virtuemart_products` SET `product_ordered`=".$rows[0]->ord.",`product_in_stock`=".$rows[0]->quan." WHERE `virtuemart_product_id`='{$id}' ";
					$db->setQuery($query);
					$db->query();

		}
	}
	public function display($tpl = null){	
			
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$productid = JRequest::getInt('productid',0);
		
		$colors = $this->getColors($productid);
		$sizes = $this->getSizes($productid);
		if($_POST['Submit'] == "Save"){
			
			$query = "SELECT id FROM #__virtuemart_quantity WHERE colorid = '{$_POST['color']}' and productid='{$productid}' and sizename = '{$_POST['size']}'";
			$db->setQuery($query);
		//	echo $query;
			$id= $db->loadResult();
			
			if($id){
				echo '<p style="color:#F00;">This color has existed</p>';
			}else{
				$query="insert into #__virtuemart_quantity (`quantity`,`productid`,`colorid`, `sizename`, `color_ordered`) values ('".$_POST['quantity']."','".$productid."','".$_POST['color']."', '".$_POST['size']."', '".$_POST['color_ordered']."') ";
				
				$db->setQuery($query);
				$db->query();

				$this->updateproductquantity($productid);
				echo '<p style="color:#F00;">Add Successful</p>';
				echo "<script>window.open('index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id=".$productid."&product_parent_id=0','_parent');</script>";
			}
		}
		if($_POST['Submit'] == "Update"){
			
			
				$query="UPDATE `#__virtuemart_quantity` SET `color_ordered`=".$_POST['color_ordered'].",`quantity`=".$_POST['quantity']." WHERE `id`='".$_POST['id_quan']."' ";
					$db->setQuery($query);
					$db->query();
				$this->updateproductquantity($productid);
				echo '<p style="color:#F00;">Update Successful</p>';
				echo "<script>window.open('index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id=".$productid."&product_parent_id=0','_parent');</script>";
			
		}
		$taskraw=JRequest::getVar('taskraw','');
		$taskid=JRequest::getVar('taskid',0);
		//delete
		if($taskraw=='delete'){
			
				
			$sql="DELETE from #__virtuemart_quantity WHERE `id`='".$taskid."' ";
			$db->setQuery($sql);
			$db->query();
			$productid = JRequest::getInt('id',0);
			$this->updateproductquantity($productid);
			echo "<script>window.open('index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id=".$productid."&product_parent_id=0','_parent');</script>";
		}
		
		$query = "SELECT q.*, c.name as colorname FROM #__virtuemart_quantity as q LEFT JOIN #__virtuemart_colors as c ON c.id = q.colorid WHERE q.productid = '{$productid}' ";
		//echo $query;die;
		$db->setQuery($query);

		$rows=$db->loadObjectList();
?>
<script src="<?php echo JURI::root(); ?>media/system/js/mootools-core.js" type="text/javascript"></script>
<script src="<?php echo JURI::root(); ?>media/system/js/core.js" type="text/javascript"></script>
<script src="<?php echo JURI::root(); ?>media/system/js/mootools-more.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo JURI::root(); ?>administrator/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo JURI::root(); ?>administrator/templates/bluestork/css/template.css" type="text/css" />
<form class="form-validate" name="adminForm" id="adminForm" method="post" action="index.php?option=com_virtuemart&view=quantitycolor&productid=<?php echo $productid; ?>&format=raw&tmpl=component" enctype="multipart/form-data">
	<fieldset class="adminform">
		<legend>Quantity Color</legend>
		<ul class="adminformlist" >
			<li>
				<label><?php echo JText::_('Quantity'); ?></label>
				<input type="text" class="inputbox" value="0" id="quantity" name="quantity">
			</li>
			<li>
				<label><?php echo JText::_('Booked'); ?></label>
				<input type="text" class="inputbox" value="0" id="color_ordered" name="color_ordered">
			</li>
	
			<li>
				<label><?php echo JText::_('Color'); ?></label>
				<?php echo $colors; ?>
			</li>
			
			<li>
				<label><?php echo JText::_('Size'); ?></label>
				<?php echo $sizes; ?>
			</li>
			
			<li>
				<label></label>
				<input type="submit" name="Submit" class="button" value="Save" />
			</li>
		</ul>
	</fieldset>
	</form>
	<fieldset class="adminform">
		<legend>List of Quantity</legend>
		<table class="adminlist" style="font-size:8pt;">
			<thead>
				<tr>
					<th><?php echo JText::_('Quantity in Stock'); ?></th>
					<th><?php echo JText::_('Booked, ordered products');?></th>
					<th><?php echo JText::_('Color'); ?></th>
					<th><?php echo JText::_('Size'); ?></th>
					<th><?php echo JText::_('Update'); ?></th>
					<th><?php echo JText::_('Delete'); ?></th>
				</tr>
			</thead>

			<tbody>
<?php 
	$i = 0;
	if(count($rows)>0){
		foreach($rows as $n => $row){
			$published 	= JHTML::_('grid.published', $row, $i );
			
?>
	<form class="form-validate" name="adminForm1" id="adminForm1" method="post" action="index.php?option=com_virtuemart&view=quantitycolor&productid=<?php echo $productid; ?>&format=raw&tmpl=component" enctype="multipart/form-data">
            <tr class="row<?php echo $n % 2; ?>">
				<td>
					
					<input type="text" class="inputbox" value="<?php echo $row->quantity; ?>" id="quantity" name="quantity">
				</td>
				<td>
					
					<input type="text" class="inputbox" value="<?php echo $row->color_ordered; ?>" id="color_ordered" name="color_ordered">
				
				</td>
				<td>
					<?php echo $row->colorname; ?>
				</td>
				<td>
					<?php echo $row->sizename; ?>
				</td>
				<td>
						<input type="submit" name="Submit" class="button" value="Update" />
					<input type="hidden" class="inputbox" value="<?php echo $row->id; ?>" id="id_quan" name="id_quan">
				</td>
				<td>
					<a href="<?php echo JURI::root().'administrator/index.php?option=com_virtuemart&view=quantitycolor&id='.$productid.'&format=raw&tmpl=component&taskraw=delete&taskid='.$row->id;?>"><?php echo JText::_('Delete'); ?></a>
					<br>
					
				</td>
            </tr>
			</form>
<?php 
			$i++;
		}
	}
?>
			</tbody>
		</table>
	</fieldset>
	

<?php
	}
	
}
?>
