<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageStrategyNewsfeeds extends ComLogmanActivityMessageStrategyDefault
{
    protected function _resourceExists($config = array())
    {
        $config = new KConfig($config);
        $config->append(array('table' => 'newsfeeds', 'identity_column' => 'id'));
        return parent::_resourceExists($config);
    }
}