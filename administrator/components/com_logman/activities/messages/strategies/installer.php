<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageStrategyInstaller extends ComLogmanActivityMessageStrategyDefault
{
    public function getIcon(KConfig $config)
    {
        $config->append(array('class' => 'icon-' . $config->activity->action));
        return parent::getIcon($config);
    }

    protected function _resourceExists($config = array())
    {
        $config = new KConfig($config);

        $config->append(array('table' => 'extensions', 'identity_column' => 'extension_id'));

        $result = parent::_resourceExists($config);

        $activity = $config->activity;

        if ($result && ($activity->name == 'component')) {
            // Check if an entry point file exists.
            $element = $activity->metadata->element;
            $result  = (bool) file_exists(JPATH_ADMINISTRATOR . '/components/' . $element . '/' . str_replace('com_',
                '',
                $element) . '.php');
        }

        return $result;
    }

    public function getText(KConfig $config)
    {
        $activity = $config->activity;

        $subject = array('%name%', '%resource%');

        if ($activity->metadata->version) {
            if ($activity->action == 'edit') {
                // Version should be part of the target.
                $config->append(array('target' => '%version%'));
            } else {
                // Version should be part of the subject.
                $subject[] = '%version%';
            }
        }

        $config->append(array('subject' => implode(' ', $subject)));

        return parent::getText($config);
    }

    protected function _setResource(KConfig $config)
    {

        $activity = $config->activity;

        if ($activity->name == 'language' && ($metadata = $activity->metadata)) {
            $config->append(array('text' => $metadata->client . ' ' . $activity->name));
        }

        parent::_setResource($config);
    }

    protected function _setVersion(KConfig $config)
    {
        $config->append(array('text' => $config->activity->metadata->version));

        return parent::_setParameter($config);
    }

    protected function _setName(KConfig $config)
    {
        $config->append(array('max_length' => 0));

        if (in_array($config->activity->name, array('module', 'package', 'language', 'file', 'library'))) {
            $config->append(array('link' => array('autogen' => false)));
        }

        return parent::_setTitle($config);
    }

    protected function _getResourceUrl(ComActivitiesDatabaseRowActivity $activity)
    {
        switch ($activity->name) {
            case 'plugin':
                $url = 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $activity->row;
                break;
            case 'component':
                $url = 'index.php?option=' . $activity->metadata->element;
                break;
            case 'template':
                $url = 'index.php?option=com_templates&view=template&id=' . $activity->row;
                break;
        }

        return $url;
    }
}