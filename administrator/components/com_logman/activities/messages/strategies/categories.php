<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageStrategyCategories extends ComLogmanActivityMessageStrategyDefault
{
    protected function _resourceExists($config = array())
    {
        $config = new KConfig($config);
        $config->append(array('table' => 'categories', 'identity_column' => 'id'));
        return parent::_resourceExists($config);
    }

    public function getText(KConfig $config)
    {
        $subject = array();

        if ($config->activity->metadata) {
            $subject[] = '%type%';
        }

        $subject[] = '%resource% %title%';

        $config->append(array('subject' => implode(' ', $subject)));

        return parent::getText($config);
    }

    protected function _setType(KConfig $config)
    {
        switch ($config->activity->metadata->extension) {
            case 'com_users':
                $text = 'user notes';
                break;
            case 'com_content':
                $text = 'articles';
                break;
            case 'com_banners':
                $text = 'banners';
                break;
            case 'com_contact':
                $text = 'contacts';
                break;
            case 'com_newsfeeds':
                $text = 'newsfeeds';
                break;
            case 'com_weblinks':
                $text = 'weblinks';
                break;
            default:
                $text = '';
                break;
        }

        $config->append(array('text' => $text, 'translate' => true));

        parent::_setParameter($config);
    }

    protected function _getResourceUrl(ComActivitiesDatabaseRowActivity $activity)
    {
        $url = parent::_getResourceUrl($activity);

        if ($metadata = $activity->metadata) {
            // Append extension info.
            $url .= '&extension=' . $metadata->extension;
        }

        return $url;
    }
}