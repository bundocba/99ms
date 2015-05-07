<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageStrategyPlugins extends ComLogmanActivityMessageStrategyDefault
{
    protected function _getResourceUrl(ComActivitiesDatabaseRowActivity $activity)
    {
        return 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $activity->row;
    }

    public function getText(KConfig $config)
    {
        $config->append(array('subject' => '%name% %resource%'));
        return parent::getText($config);
    }

    protected function _setName(KConfig $config)
    {
        $config->append(array('max_length' => 0));
        return parent::_setTitle($config);
    }

    protected function _resourceExists($config = array())
    {
        $config = new KConfig($config);
        $config->append(array('table' => 'extensions', 'identity_column' => 'extension_id'));
        return parent::_resourceExists($config);
    }
}