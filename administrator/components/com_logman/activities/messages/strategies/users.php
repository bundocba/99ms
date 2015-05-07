<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageStrategyUsers extends ComLogmanActivityMessageStrategyDefault
{
    public function getIcon(KConfig $config)
    {
        $config->append(array('class' => 'icon-' . $config->activity->action));
        return parent::getIcon($config);
    }

    public function getText(KConfig $config)
    {
        $activity = $config->activity;

        switch ($activity->name) {
            case 'user':
                if ($this->_isActivation($activity) || $this->_isEditOwn($activity)) {
                    // Frontend activation.
                    $subject = 'own %type% %resource%';
                } elseif ($this->_isRegistration($activity) || $this->_isSelfLog($activity)) {
                    // No subject.
                    $subject = '%application%';
                } else {
                    $subject = '%resource% %name%';
                }
                break;
            default:
            case 'note':
            case 'group':
                $subject = '%type% %resource% %title%';
                break;
            case 'level':
                $subject = '%type% %resource% %name%';
                break;
        }

        $config->append(array('subject' => $subject));

        return parent::getText($config);
    }

    protected function _isSelfLog(ComActivitiesDatabaseRowActivity $activity)
    {
        return (bool) ($activity->name == 'user' && $activity->action == 'login' || ($activity->action == 'logout' && ($activity->created_by == $activity->row)));
    }

    protected function _isActivation(ComActivitiesDatabaseRowActivity $activity)
    {
        return (bool) ($activity->name == 'user' && $activity->action == 'edit' && $activity->application == 'site' && $activity->created_by == 0);
    }

    protected function _isRegistration(ComActivitiesDatabaseRowActivity $activity)
    {
        return (bool) ($activity->name == 'user' && $activity->action == 'add' && $activity->application == 'site');
    }

    protected function _isEditOwn(ComActivitiesDatabaseRowActivity $activity)
    {
        return (bool) ($activity->name == 'user' && $activity->action == 'edit' && $activity->row == $activity->created_by);
    }

    protected function _setApplication(KConfig $config)
    {
        $config->append(array('text' => $config->activity->application, 'translate' => true));
        $this->_setParameter($config);
    }

    protected function _setAction(KConfig $config)
    {
        $activity = $config->activity;

        if ($this->_isRegistration($activity)) {
            $config->append(array('text' => 'registered'));
        } elseif ($this->_isActivation($activity)) {
            $config->append(array('text' => 'activated'));
        }

        parent::_setAction($config);
    }

    protected function _setResource(KConfig $config)
    {
        $activity = $config->activity;

        if ($this->_isActivation($activity)) {
            $config->append(array('text' => 'account'));
        } elseif ($this->_isEditOwn($activity)) {
            $config->append(array('text' => 'profile'));
        }

        parent::_setResource($config);
    }

    protected function _setUser(KConfig $config)
    {
        $activity = $config->activity;

        if ($this->_isRegistration($activity) || $this->_isActivation($activity)) {
            // The actor (user) becomes the resource itself.
            $this->_setName($config);
        } else {
            parent::_setUser($config);
        }
    }

    protected function _setName(KConfig $config)
    {
        parent::_setTitle($config);
    }

    protected function _setType(KConfig $config)
    {
        $config->append(array('text' => 'user', 'translate' => true));
        $this->_setParameter($config);
    }

    protected function _resourceExists($config = array())
    {
        $config = new KConfig($config);

        switch ($config->activity->name) {
            case 'group':
                $table = 'usergroups';
                break;
            case 'note':
                $table = 'user_notes';
                break;
            case 'level':
                $table = 'viewlevels';
                break;
            default:
            case 'user':
                $table = 'users';
                break;

        }

        $config->append(array('table' => $table, 'identity_column' => 'id'));

        return parent::_resourceExists($config);
    }
}