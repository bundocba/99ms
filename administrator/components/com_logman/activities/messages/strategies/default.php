<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageStrategyDefault extends ComLogmanActivityMessageStrategyAbstract
{
    /**
     * @see ComLogmanActivityMessageStrategyInterface::getText
     */
    public function getText(KConfig $config)
    {
        $config->append(array('parts' => array('actor', 'action', 'subject', 'target'), 'action' => '%action%'));

        if (is_null($config->actor)) {
            $config->actor = '%user%';
        }

        if (is_null($config->subject)) {
            $config->subject = '%resource% %title%';
        }

        $message = array();

        foreach ($config->parts as $part) {
            if ($text = $config->$part) {
                $message[] = $text;
            }
        }

        return implode(' ', $message);
    }

    /**
     * @see ComLogmanActivityMessageStrategyInterface::getIcon
     */
    public function getIcon(KConfig $config)
    {
        $activity = $config->activity;
        $config->append(array(
            'class' => 'icon-' . (in_array($activity->action,
                array('add', 'edit', 'delete')) ? $activity->action : 'default')));

        return $config->class;
    }

    /**
     * @see ComLogmanActivityMessageStrategyInterface::getContent
     */
    public function getContent(KConfig $config)
    {
        return '';
    }

    protected function _setUser(KConfig $config)
    {
        $activity = $config->activity;

        if ($actor_name = $activity->created_by_name) {
            $config->link = array('url' => $this->_getActorUrl($activity->created_by));
        } else {
            // User wasn't found.
            $actor_name        = $activity->created_by ? 'Deleted user' : 'Guest user';
            $config->translate = true;
        }

        $config->text = $actor_name;

        $this->_setParameter($config);
    }


    protected function _setAction(KConfig $config)
    {
        $activity = $config->activity;

        $config->append(array(
            'text'      => $activity->status,
            'translate' => true));

        $this->_setParameter($config);
    }


    protected function _setResource(KConfig $config)
    {
        $config->append(array(
            'translate' => true,
            'text'      => $config->activity->name,
            'attribs'   => array('class' => 'subject'),
        ));

        $this->_setParameter($config);
    }

    protected function _setTitle(KConfig $config)
    {
        $activity = $config->activity;

        $config->append(array(
            'link'       => array(
                'attribs' => array(),
                'route'   => true,
                'autogen' => (bool) $this->_resourceExists(array('activity' => $activity))),
            'translate'  => false,
            'text'       => $activity->title,
            'max_length' => 50
        ));

        $link = $config->link;

        if (!$link->url && $link->autogen && ($url = $this->_getResourceUrl($activity))) {
            $link->url = $url;
        }

        if ($activity->status == 'deleted') {
            $config->attribs = array('class' => 'deleted');
        }

        parent::_setParameter($config);
    }

    protected function _resourceExists($config = array())
    {
        $config = new KConfig($config);

        $activity = $config->activity;

        // Set Nooku conventions as default.
        $config->append(array(
            'table'           => $activity->package . '_' . KInflector::pluralize($activity->name),
            'identity_column' => $activity->package . '_' . $activity->name . '_' . 'id'));

        return parent::_resourceExists($config);
    }
}