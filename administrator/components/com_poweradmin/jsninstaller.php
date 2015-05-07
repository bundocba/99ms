<?php
/**
 * @version    $Id$
 * @package    JSN_Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Disable notice and warning by default for our products.
// The reason for doing this is if any notice or warning appeared then handling JSON string will fail in our code.
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
/**
 * Class for finalizing JSN extension installation.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
abstract class JSNInstallerScript
{
	/**
	 * XML manifest.
	 *
	 * @var  SimpleXMLElement
	 */
	protected $manifest;
	
	/**
	 * Implement preflight hook.
	 *
	 * This step will be verify permission for install/update process.
	 *
	 * @param   string  $mode    Install or update?
	 * @param   object  $parent  JInstaller object.
	 *
	 * @return  boolean
	 */
	public function preflight($mode, $parent)
	{
		// Initialize variables
		$installer = $parent->getParent();

		$this->app		= JFactory::getApplication();
		$this->manifest	= $installer->getManifest();

		// Check if installed Joomla version is compatible
		$joomlaVersion	= new JVersion;
		$canInstall		= $joomlaVersion->RELEASE == (string) $this->manifest['version'] ? true : false;

		if ( ! $canInstall)
		{
			$this->app->enqueueMessage(sprintf('Cannot install "%s" because the installation package is not compatible with your installed Joomla version', (string) $this->manifest->name), 'error');
		}

		// Parse dependency
		$this->parseDependency($installer);

		// Check environment
		$canInstallExtension		= true;
		$canInstallSiteLanguage		= is_writable(JPATH_SITE . '/language');
		$canInstallAdminLanguage	= is_writable(JPATH_ADMINISTRATOR . '/language');

		if ( ! $canInstallSiteLanguage)
		{
			$this->app->enqueueMessage(sprintf('Cannot install language file at "%s"', JPATH_SITE . '/language'), 'error');
		}
		else
		{
			foreach (glob(JPATH_SITE . '/language/*', GLOB_ONLYDIR) AS $dir)
			{
				if ( ! is_writable($dir))
				{
					$canInstallSiteLanguage = false;
					$this->app->enqueueMessage(sprintf('Cannot install language file at "%s"', $dir), 'error');
				}
			}
		}

		if ( ! $canInstallAdminLanguage)
		{
			$this->app->enqueueMessage(sprintf('Cannot install language file at "%s"', JPATH_ADMINISTRATOR . '/language'), 'error');
		}
		else
		{
			foreach (glob(JPATH_ADMINISTRATOR . '/language/*', GLOB_ONLYDIR) AS $dir)
			{
				if ( ! is_writable($dir))
				{
					$canInstallAdminLanguage = false;
					$this->app->enqueueMessage(sprintf('Cannot install language file at "%s"', $dir), 'error');
				}
			}
		}

		// Checking directory permissions for dependency installation
		foreach ($this->dependencies AS $extension)
		{
			// Simply continue if extension is set to be removed
			if (isset($extension->remove) AND (int) $extension->remove > 0)
			{
				continue;
			}

			// Check if dependency can be installed
			switch ($extension->type = strtolower($extension->type))
			{
				case 'component':
					$sitePath	= JPATH_SITE . '/components';
					$adminPath	= JPATH_ADMINISTRATOR . '/components';

					if ( ! is_dir($sitePath) OR ! is_writable($sitePath))
					{
						$canInstallExtension = false;
						$this->app->enqueueMessage(sprintf('Cannot install "%s" %s because "%s" is not writable', $extension->name, $extension->type, $sitePath), 'error');
					}

					if ( ! is_dir($adminPath) OR ! is_writable($adminPath))
					{
						$canInstallExtension = false;
						$this->app->enqueueMessage(sprintf('Cannot install "%s" %s because "%s" is not writable', $extension->name, $extension->type, $adminPath), 'error');
					}
				break;

				case 'plugin':
					$path = JPATH_ROOT . '/plugins/' . $extension->folder;

					if ((is_dir($path) AND ! is_writable($path)) OR ( ! is_dir($path) AND ! is_writable(dirname($path))))
					{
						$canInstallExtension = false;
						$this->app->enqueueMessage(sprintf('Cannot install "%s" %s because "%s" is not writable', $extension->name, $extension->type, $path), 'error');
					}
				break;

				case 'module':
				case 'template':
					$path = ($extension->client == 'site' ? JPATH_SITE : JPATH_ADMINISTRATOR) . "/{$extension->type}s";

					if ( ! is_dir($path) OR ! is_writable($path))
					{
						$canInstallExtension = false;
						$this->app->enqueueMessage(sprintf('Cannot install "%s" %s because "%s" is not writable', $extension->name, $extension->type, $path), 'error');
					}
				break;
			}
		}

		return $canInstall AND $canInstallExtension AND $canInstallSiteLanguage AND $canInstallAdminLanguage;
	}

	/**
	 * Implement postflight hook.
	 *
	 * @param   string  $type    Extension type.
	 * @param   object  $parent  JInstaller object.
	 *
	 * @return  void
	 */
	public function postflight($type, $parent)
	{
		// Get original installer
		$installer = $parent->getParent();

		// Process dependency installation
		foreach ($this->dependencies AS $extension)
		{
			// Remove installed extension if requested
			if (isset($extension->remove) AND (int) $extension->remove > 0)
			{
				$this->removeExtension($extension);
				continue;
			}

			// Install only dependency that has local installation package
			if (isset($extension->source))
			{
				// Install and update dependency status
				$this->installExtension($extension);
			}
			elseif ( ! isset($this->missingDependency))
			{
				$this->missingDependency = true;
			}

			// Build query to get dependency installation status
			$db	= JFactory::getDbo();
			$q	= $db->getQuery(true);

			$q->select('custom_data, manifest_cache, params');
			$q->from('#__extensions');
			$q->where("element = '{$extension->name}'");
			$q->where("type = '{$extension->type}'", 'AND');
			$extension->type != 'plugin' OR $q->where("folder = '{$extension->folder}'", 'AND');

			$db->setQuery($q);

			if ($status = $db->loadObject())
			{
				$ext = strtolower($this->manifest->name);
				$dep = array($ext);

				// Backward compatible: move all dependency data from params to custom_data column
				if (is_array($params = (isset($status->params) AND $status->params != '{}') ? (array) json_decode($status->params) : null))
				{
					foreach (array('imageshow', 'poweradmin', 'sample') AS $com)
					{
						if ($com != $ext AND isset($params[$com]))
						{
							$dep[] = $com;
						}
					}
				}

				// Preset dependency object
				$status->custom_data = ! empty($status->custom_data) ? (array) json_decode($status->custom_data) : array();
				$status->custom_data = array_unique(array_merge($status->custom_data, $dep));

				// Build query to update dependency data
				$q = $db->getQuery(true);

				$q->update('#__extensions');
				$q->set("custom_data = '" . json_encode($status->custom_data) . "'");

				// Backward compatible: keep data in this column for older product to recognize
				$manifestCache = json_decode($status->manifest_cache);
				$manifestCache->dependency = $status->custom_data;

				$q->set("manifest_cache = '" . json_encode($manifestCache) . "'");

				// Backward compatible: keep data in this column also for another old product to recognize
				$q->set("params = '" . json_encode((object) array_combine($status->custom_data, $status->custom_data)) . "'");

				$q->where("element = '{$extension->name}'");
				$q->where("type = '{$extension->type}'", 'AND');
				$extension->type != 'plugin' OR $q->where("folder = '{$extension->folder}'", 'AND');

				$db->setQuery($q);
				$db->query();
			}
		}

		// Get installed extension directory and name
		$path = $installer->getPath('extension_administrator');
		$name = substr(basename($path), 4);

		if ( ! defined('JSN_' . strtoupper($name) . '_DEPENDENCY'))
		{
			// Unset some unnecessary properties
			foreach ($this->dependencies AS & $dependency)
			{
				unset($dependency->source);
				unset($dependency->upToDate);
			}
			$this->dependencies = json_encode($this->dependencies);

			// Store dependency declaration
			file_exists($defines = $path . '/defines.php') OR file_exists($defines = $path . "/{$name}.defines.php") OR $defines = $path . "/{$name}.php";

			if (is_writable($defines))
			{
				$buffer = preg_replace(
					'/(defined\s*\(\s*._JEXEC.\s*\)[^\n]+\n)/',
					'\1' . "\ndefine('JSN_" . strtoupper($name) . "_DEPENDENCY', '" . $this->dependencies . "');\n",
					file_get_contents($defines)
				);

				JFile::write($defines, $buffer);
			}
		}
		
		// Check if redirect should be disabled
		if ($this->app->input->getBool('tool_redirect', true))
		{
			// Do we have any missing dependency
			if ($this->missingDependency)
			{
				if (strpos($_SERVER['HTTP_REFERER'], '/administrator/index.php?option=com_installer') !== false)
				{
					// Set redirect to finalize dependency installation
					$installer->setRedirectURL('index.php?option=com_' . $name . '&view=installer');
				}
				else
				{
					// Let Ajax client redirect
					echo '
<script type="text/javascript">
	if (window.parent)
		window.parent.location.href ="' . JUri::root() . 'administrator/index.php?option=com_' . $name . '&view=installer";
	else
		location.href ="' . JUri::root() . 'administrator/index.php?option=com_' . $name . '&view=installer";
</script>';
					exit;
				}
			}
		}
		
		//load Advancemodules plugin after
		$isAdvmInstalled = count($this->_getExtension('advancedmodules', 'plugin'));
		if($isAdvmInstalled){
			$dbo = JFactory::getDBO();
			$dbo->setQuery("UPDATE #__extensions SET ordering=1 WHERE element='advancedmodules' AND type='plugin'");
			@$dbo->query();
		}
		
		// Rearrange menu ordering if poweradmin was used before
		
		$dbo = JFactory::getDBO();
		$dbo->setQuery("UPDATE #__menu SET ordering=0 WHERE lft > 0");
		$dbo->query();
			
		$menuTable = JTable::getInstance('menu');
		$menuTable->rebuild();
	
	}

	/**
	 * Implement uninstall hook.
	 *
	 * @param   object  $parent  JInstaller object.
	 *
	 * @return  void
	 */
	public function uninstall($parent)
	{
		// Initialize variables
		$installer = $parent->getParent();

		$this->app			= JFactory::getApplication();
		$this->manifest		= $installer->getManifest();

		// Parse dependency
		$this->parseDependency($installer);

		// Remove all dependency
		foreach ($this->dependencies AS $extension)
		{
			$this->removeExtension($extension);
		}
	}

	/**
	 * Retrieve dependency from manifest file.
	 *
	 * @param   object  $installer  JInstaller object.
	 *
	 * @return  object  Return itself for method chaining.
	 */
	protected function parseDependency($installer)
	{
		// Continue only if dependency not checked before
		if ( ! isset($this->dependencies) OR ! is_array($this->dependencies))
		{
			// Preset dependency list
			$this->dependencies = array();

			if (isset($this->manifest->subinstall) AND $this->manifest->subinstall instanceOf SimpleXMLElement)
			{
				// Loop on each node to retrieve dependency information
				foreach ($this->manifest->subinstall->children() AS $node)
				{
					// Verify tag name
					if (strcasecmp($node->getName(), 'extension') != 0)
					{
						continue;
					}

					// Re-create serializable dependency object
					$extension = (array) $node;
					$extension = (object) $extension['@attributes'];

					$extension->title = trim((string) $node != '' ? (string) $node : ($node['title'] ? (string) $node['title'] : (string) $node['name']));

					// Validate dependency
					if ( ! isset($extension->name) OR ! isset($extension->type) OR ! in_array($extension->type, array('template', 'plugin', 'module', 'component')) OR ($extension->type == 'plugin' AND ! isset($extension->folder)))
					{
						continue;
					}

					// Check if dependency has local installation package
					if (isset($extension->dir) AND is_dir($source = $installer->getPath('source') . '/' . $extension->dir))
					{
						$extension->source	= $source;
					}

					$this->dependencies[] = $extension;
				}
			}
		}

		return $this;
	}

	/**
	 * Install a dependency.
	 *
	 * @param   object  $extension  Object containing extension details.
	 *
	 * @return  void
	 */
	public function installExtension($extension)
	{
		// Get application object
		isset($this->app) OR $this->app = JFactory::getApplication();

		// Get database object
		$db	= JFactory::getDbo();
		$q	= $db->getQuery(true);

			// Build query to get dependency installation status
		$q->select('manifest_cache, custom_data');
		$q->from('#__extensions');
		$q->where("element = '{$extension->name}'");
		$q->where("type = '{$extension->type}'", 'AND');
		$extension->type != 'plugin' OR $q->where("folder = '{$extension->folder}'", 'AND');

		// Execute query
		$db->setQuery($q);

		if ($status = $db->loadObject())
		{
			// Initialize variables
			$jVersion = new JVersion;
			$manifest = json_decode($status->manifest_cache);

			// Get information about the dependency to be installed
			$xml = JPATH::clean($extension->source . '/' . $extension->name . '.xml');

			if (is_file($xml) AND ($xml = simplexml_load_file($xml)))
			{
				if ($jVersion->RELEASE == (string) $xml['version'] AND version_compare($manifest->version, (string) $xml->version, '<'))
				{
					// The dependency to be installed is newer than the existing one, mark for update
					$doInstall = true;
				}

				if ($jVersion->RELEASE != (string) $xml['version'] AND version_compare($manifest->version, (string) $xml->version, '<='))
				{
					// The dependency to be installed might not newer than the existing one but Joomla version is difference, mark for update
					$doInstall = true;
				}
			}
		}
		elseif (isset($extension->source))
		{
			// The dependency to be installed not exist, mark for install
			$doInstall = true;
		}

		if (isset($doInstall) AND $doInstall)
		{
			// Install dependency
			$installer = new JInstaller;

			if ( ! $installer->install($extension->source))
			{
				$this->app->enqueueMessage(sprintf('Error installing "%s" %s', $extension->name, $extension->type), 'error');
			}
			else
			{
				$this->app->enqueueMessage(sprintf('Install "%s" %s was successfull', $extension->name, $extension->type));

				// Update dependency status
				$this->updateExtension($extension);

				// Build query to get dependency installation status
				$q	= $db->getQuery(true);

				$q->select('manifest_cache, custom_data');
				$q->from('#__extensions');
				$q->where("element = '{$extension->name}'");
				$q->where("type = '{$extension->type}'", 'AND');
				$extension->type != 'plugin' OR $q->where("folder = '{$extension->folder}'", 'AND');

				$db->setQuery($q);

				// Load dependency installation status
				$status = $db->loadObject();
			}
		}

		// Update dependency tracking
		if (isset($status))
		{
			$ext = strtolower(isset($this->manifest) ? $this->manifest->name : substr($this->app->input->getCmd('option'), 4));
			$dep = ! empty($status->custom_data) ? (array) json_decode($status->custom_data) : array();

			// Update dependency list
			in_array($ext, $dep) OR $dep[] = $ext;
			$status->custom_data = array_unique($dep);

			// Build query to update dependency data
			$q = $db->getQuery(true);

			$q->update('#__extensions');
			$q->set("custom_data = '" . json_encode($status->custom_data) . "'");

			// Backward compatible: keep data in this column for older product to recognize
			$manifestCache = json_decode($status->manifest_cache);
			$manifestCache->dependency = $status->custom_data;

			$q->set("manifest_cache = '" . json_encode($manifestCache) . "'");

			// Backward compatible: keep data in this column also for another old product to recognize
			$q->set("params = '" . json_encode((object) array_combine($status->custom_data, $status->custom_data)) . "'");

			$q->where("element = '{$extension->name}'");
			$q->where("type = '{$extension->type}'", 'AND');
			$extension->type != 'plugin' OR $q->where("folder = '{$extension->folder}'", 'AND');

			$db->setQuery($q);
			$db->query();
		}
	}

	/**
	 * Update dependency status.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  object  Return itself for method chaining.
	 */
	protected function updateExtension($extension)
	{
		// Get object to working with extensions table
		$table = JTable::getInstance('Extension');

		// Load extension record
		$table->load(array('element' => $extension->name));

		// Update extension record
		$table->enabled		= (isset($extension->publish)	AND (int) $extension->publish > 0)	? 1 : 0;
		$table->protected	= (isset($extension->lock)		AND (int) $extension->lock > 0)		? 1 : 0;
		$table->client_id	= (isset($extension->client)	AND $extension->client == 'site')	? 0 : 1;

		// Store updated extension record
		$table->store();

		// Update module instance
		if ($extension->type == 'module')
		{
			// Get object to working with modules table
			$module = JTable::getInstance('module');

			// Load module instance
			$module->load(array('module' => $extension->name));

			// Update module instance
			$module->title		= $extension->title;
			$module->ordering	= isset($extension->ordering) ? $extension->ordering : 0;
			$module->published	= (isset($extension->publish) AND (int) $extension->publish > 0) ? 1 : 0;

			if ($hasPosition = (isset($extension->position) AND (string) $extension->position != ''))
			{
				$module->position = (string) $extension->position;
			}

			// Store updated module instance
			$module->store();

			// Set module instance to show in all page
			if ($hasPosition AND (int) $module->id > 0)
			{
				// Get database object
				$db	= JFactory::getDbo();
				$q	= $db->getQuery(true);

				try
				{
					// Remove all menu assignment records associated with this module instance
					$q->delete('#__modules_menu');
					$q->where("moduleid = {$module->id}");

					// Execute query
					$db->setQuery($q);
					$db->query();

					// Build query to show this module instance in all page
					$q->insert('#__modules_menu');
					$q->columns('moduleid, menuid');
					$q->values("{$module->id}, 0");

					// Execute query
					$db->setQuery($q);
					$db->query();
				}
				catch (Exception $e)
				{
					throw $e;
				}
			}
		}

		return $this;
	}

	/**
	 * Disable a dependency.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  object  Return itself for method chaining.
	 */
	protected function disableExtension($extension)
	{
		// Get database object
		$db	= JFactory::getDbo();
		$q	= $db->getQuery(true);

		// Build query
		$q->update('#__extensions');
		$q->set('enabled = 0');
		$q->where("element = '{$extension->name}'");

		// Execute query
		$db->setQuery($q);
		$db->query();

		return $this;
	}

	/**
	 * Unlock a dependency.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  object  Return itself for method chaining.
	 */
	protected function unlockExtension($extension)
	{
		// Get database object
		$db	= JFactory::getDbo();
		$q	= $db->getQuery(true);

		// Build query
		$q->update('#__extensions');
		$q->set('protected = 0');
		$q->where("element = '{$extension->name}'");

		// Execute query
		$db->setQuery($q);
		$db->query();

		return $this;
	}

	/**
	 * Remove a dependency.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  object  Return itself for method chaining.
	 */
	protected function removeExtension($extension)
	{
		// Get application object
		isset($this->app) OR $this->app = JFactory::getApplication();

		// Preset dependency status
		$extension->removable = true;

		// Get database object
		$db	= JFactory::getDbo();
		$q	= $db->getQuery(true);

		// Build query to get dependency installation status
		$q->select('extension_id, manifest_cache, custom_data');
		$q->from('#__extensions');
		$q->where("element = '{$extension->name}'");
		$q->where("type = '{$extension->type}'", 'AND');
		$extension->type != 'plugin' OR $q->where("folder = '{$extension->folder}'", 'AND');

		// Execute query
		$db->setQuery($q);

		if ($status = $db->loadObject())
		{
			// Initialize variables
			$id		= $status->extension_id;
			$ext	= strtolower(isset($this->manifest) ? $this->manifest->name : substr($this->app->input->getCmd('option'), 4));
			$deps	= ! empty($status->custom_data) ? (array) json_decode($status->custom_data) : array();

			// Update dependency tracking
			$status->custom_data = array();

			foreach ($deps AS $dep)
			{
				// Backward compatible: ensure that product is not removed
				// if ($dep != $ext)
				if ($dep != $ext AND is_dir(JPATH_BASE . '/components/com_' . $dep))
				{
					$status->custom_data[] = $dep;
				}
			}

			if (count($status->custom_data))
			{
				// Build query to update dependency data
				$q = $db->getQuery(true);

				$q->update('#__extensions');
				$q->set("custom_data = '" . json_encode($status->custom_data) . "'");

				// Backward compatible: keep data in this column for older product to recognize
				$manifestCache = json_decode($status->manifest_cache);
				$manifestCache->dependency = $status->custom_data;

				$q->set("manifest_cache = '" . json_encode($manifestCache) . "'");

				// Backward compatible: keep data in this column also for another old product to recognize
				$q->set("params = '" . json_encode((object) array_combine($status->custom_data, $status->custom_data)) . "'");

				$q->where("element = '{$extension->name}'");
				$q->where("type = '{$extension->type}'", 'AND');
				$extension->type != 'plugin' OR $q->where("folder = '{$extension->folder}'", 'AND');

				$db->setQuery($q);
				$db->query();

				// Indicate that extension is not removable
				$extension->removable = false;
			}
		}
		else
		{
			// Extension was already removed
			$extension->removable = false;
		}

		if ($extension->removable)
		{
			// Disable and unlock dependency
			$this->disableExtension($extension);
			$this->unlockExtension($extension);

			// Remove dependency
			$installer = new JInstaller;

			if ($installer->uninstall($extension->type, $id))
			{
				$this->app->enqueueMessage(sprintf('"%s" %s has been uninstalled', $extension->name, $extension->type));
			}
			else
			{
				$this->app->enqueueMessage(sprintf('Cannot uninstall "%s" %s', $extension->name, $extension->type));
			}
		}

		return $this;
	}
	
	/**
	 * Get a dependency.
	 *
	 * @param   string  $name  Name of extension.
	 *
	 * @return  object
	 */
	private function _getExtension($name, $type = null)
	{
		$dbo 	= JFactory::getDBO();
		$query 	= "SELECT * FROM #__extensions WHERE element='{$name}'" ;
		if($type) {
			$query .= " AND type='{$type}'";
		}
		$dbo->setQuery($query);
		return $dbo->loadObjectList();
	}
}
