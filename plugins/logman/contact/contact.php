<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * LOGman contact plugin.
 *
 * Provides handlers for dealing with contact events.
 */
class PlgLogmanContact extends ComLogmanPluginAbstract
{
    public function onSubmitContact($contact, $data)
    {
        $activity = $this->getActivity(array(
            'subject' => array(
                'component' => 'contact',
                'resource'  => 'contact',
                'title'     => $contact->name,
                'id'        => $contact->id,
                'metadata'  => array(
                    'sender' => array(
                        'name'  => @$data['contact_name'],
                        'email' => @$data['contact_email']))),
            'result'  => 'contacted',
            'action'  => 'contact'));

        $this->save($activity);
    }
}