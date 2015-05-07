<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
define('COM_MEDIA_BASE',    JPATH_ROOT);
if(!class_exists('VmView'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmview.php');
require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'fwimage.php');


class VirtuemartViewuploadimg extends JView{
	public function display($tpl = null){		
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$productid = JRequest::getInt('productid',0);
		$model = VmModel::getModel();
		$colors = $model->getColors();
		
		if($_POST['Submit'] == "Save"){
			
			
			
			$isImageUpload = $_FILES['img']['name']!='';
			if($isImageUpload){
				$file 		= JRequest::getVar( 'img', '', 'files', 'array' );
				$typeimg = strtolower($file['type']);
				$ext= '.'.substr($file['name'], strlen($file['name']) - 3);
				$file['name'] = time()."_img".$ext;
				$file['name']	= JFile::makeSafe($file['name']);
				$filepath = JPath::clean(COM_MEDIA_BASE.DS."images".DS."img-color".DS.strtolower($file['name']));

				JFile::upload($file['tmp_name'], $filepath);
				$img	=	strtolower($file['name']);
			}
			$fileThumbnail 		= JPATH_ROOT.DS.'images'.DS.'img-color'.DS.'thumb'.DS.strtolower($file['name']);
			
			$width=200;
			$height=0;
			$crop=0;
			$watermarkParams['create']	= 0;
			$watermarkParams['x'] 		= 'left';
			$watermarkParams['y']		= 'top';
			$watermarkParams['file']	= JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'images'.DS.'icon-48-cate.png';
			$errorMsg='';
			fwimageHelper::imageMagic($filepath, $fileThumbnail, $width , $height, $crop, null, $watermarkParams, 0, $errorMsg);
			
			$query="insert into #__virtuemart_image (`productid`,`colorid`,`name`,`img`) values ('".$productid."','".$_POST['color']."','".$_POST['name']."','".$img."') ";
			//echo $query;die;
			$db->setQuery($query);
			$db->query();
		}
		$taskraw=JRequest::getVar('taskraw','');
		$taskid=JRequest::getVar('taskid',0);
		//delete
		if($taskraw=='delete'){
			$sql="SELECT * from #__virtuemart_image WHERE `id`='".$taskid."' ";
			$db->setQuery($sql);
			$_row=$db->loadObject();
			if($_row->img!=''){
				JFile::delete(JPATH_SITE.DS.'images'.DS.'img-color'.DS.$_row->img);
				JFile::delete(JPATH_SITE.DS.'images'.DS.'img-color'.DS.'thumb'.DS.$_row->img);
			}
				
			$sql="DELETE from #__virtuemart_image WHERE `id`='".$taskid."' ";
			$db->setQuery($sql);
			$db->query();
				
			$mainframe->redirect('index.php?option=com_virtuemart&view=uploadimg&productid='.$productid.'&format=raw&tmpl=component');
		}
		$query = "SELECT * FROM #__virtuemart_image WHERE productid = '{$productid}' ";
		//echo $query;
		$db->setQuery($query);
		$rows=$db->loadObjectList();
?>
<script src="<?php echo JURI::root(); ?>media/system/js/mootools-core.js" type="text/javascript"></script>
<script src="<?php echo JURI::root(); ?>media/system/js/core.js" type="text/javascript"></script>
<script src="<?php echo JURI::root(); ?>media/system/js/mootools-more.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo JURI::root(); ?>administrator/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo JURI::root(); ?>administrator/templates/bluestork/css/template.css" type="text/css" />
<form class="form-validate" name="adminForm" id="adminForm" method="post" action="index.php?option=com_virtuemart&view=uploadimg&productid=<?php echo $productid; ?>&format=raw&tmpl=component" enctype="multipart/form-data">
	<fieldset class="adminform">
		<legend>Upload Color Image</legend>
		<ul class="adminformlist" style="float:left;">
			<li>
				<label><?php echo JText::_('Name'); ?></label>
				<input type="text" class="inputbox" value="" id="name" name="name">
			</li>
			<!-- li>
				<label><?php echo JText::_('Image'); ?></label>
				<input type="file" class="inputbox" value="" id="img" name="img">
			</li -->
		</ul>
		<ul class="adminformlist" style="float:left;padding-left:30px;">
			<li>
				<label><?php echo JText::_('Color'); ?></label>
				<?php echo $colors; ?>
			</li>
			<li>
				<label></label>
				<input type="submit" name="Submit" class="button" value="Save" />
			</li>
		</ul>
	</fieldset>
	
	<fieldset class="adminform">
		<legend>List of color</legend>
		<table class="adminlist" style="font-size:8pt;">
			<thead>
				<tr>
					<th><?php echo JText::_('Name'); ?></th>
					<th><?php echo JText::_('Color'); ?></th>
					<!-- th><?php echo JText::_('Image'); ?></th -->
					<th><?php echo JText::_('Delete'); ?></th>
				</tr>
			</thead>

			<tbody>
			
<?php 
	$i = 0;
	if(count($rows)>0){
		foreach($rows as $n => $row){
			$published 	= JHTML::_('grid.published', $row, $i );
			$query="select name from #__virtuemart_colors where id=".$row->colorid."";
			$db->setQuery($query);
			$row_type=$db->loadResult();
			
			$image = JURI::root()."images/img-color/thumb/".$row->img;
?>
            <tr class="row<?php echo $n % 2; ?>">
				<td>
					<?php echo $row->name; ?>
				</td>
				<td>
					<?php echo $row_type; ?>
				</td>
				<!-- td>
					<?php if(!empty($row->img)):?>
					<img src="<?php echo $image; ?>" style="width: 80px !important;" />
					<?php endif;?>
				</td -->
				<td>
					<a href="<?php echo JURI::root().'/administrator/index.php?option=com_virtuemart&view=uploadimg&id='.$productid.'&format=raw&tmpl=component&taskraw=delete&taskid='.$row->id;?>"><?php echo JText::_('Delete'); ?></a>
				</td>
            </tr>
<?php 
			$i++;
		}
	}
?>
			</tbody>
		</table>
	</fieldset>
	
</form>
<?php
	}
}
?>
