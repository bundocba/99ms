<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageStrategyRedirect extends ComLogmanActivityMessageStrategyDefault
{
    public function getText(KConfig $config)
    {
        $config->append(array('subject' => '%type% %resource%'));
        return parent::getText($config);
    }

    protected function _setType(KConfig $config)
    {
        $config->append(array('text' => 'redirect', 'translate' => true));
        parent::_setParameter($config);
    }

    protected function _setResource(KConfig $config)
    {
        $config->append(array('text' => $config->activity->name, 'translate' => true, 'max_length' => 0));
        parent::_setTitle($config);
    }

    protected function _resourceExists($config = array())
    {
        $config = new KConfig($config);
        $config->append(array('table' => 'redirect_links', 'identity_column' => 'id'));
        return parent::_resourceExists($config);
    }
}