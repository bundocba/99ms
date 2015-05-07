<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComLogmanViewActivitiesHtml extends ComActivitiesViewActivitiesHtml
{
    public function display()
    {
        $this->user = JFactory::getUser();

        if ($this->getLayout() == 'default')
        {
            $model = $this->getService($this->getModel()->getIdentifier());
    
            $this->assign('packages', $model
                ->distinct(true)
                ->column('package')
                ->getList()
            );

            // Determine if own activities should be greyed out or not.
            if ($this->getModel()->getState()->user == $this->user->id) {
                // Filtering by current logged user => we do not grey out.
                $this->grey_self = false;
            } else {
                // We do grey out.
                $this->grey_self = true;
            }

            /*
             * You would think that Joomla menu already loads the necessary language files.
             * Well it does but after the component has been rendered so we need to do this ourselves
             */
            foreach ($this->packages as $package) {
                $lang = JFactory::getLanguage();
                $component = 'com_'.$package->package;
                $lang->load($component.'.sys', JPATH_BASE, null, false, false)
                ||	$lang->load($component.'.sys', JPATH_ADMINISTRATOR.'/components/'.$component, null, false, false)
                ||	$lang->load($component.'.sys', JPATH_BASE, $lang->getDefault(), false, false)
                ||	$lang->load($component.'.sys', JPATH_ADMINISTRATOR.'/components/'.$component, $lang->getDefault(), false, false);
            }
        }
    
        return parent::display();
    }
}