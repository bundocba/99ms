<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
class ComLogmanActivityMessageTranslatorParameterDefault extends KObject implements ComLogmanActivityMessageTranslatorParameterInterface
{
    protected $_name;

    protected $_translator;

    protected $_text;

    protected $_link;

    protected $_attribs;

    protected $_translate;

    protected $_delimiter;

    protected $_max_length;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_translator = $this->getService($config->translator);

        if (!$config->name) {
            throw new KException('A translator parameter must have a name');
        }

        $this->_name      = $config->name;
        $this->_delimiter = $config->delimiter;

        $this->setLink($config->link);
        $this->setAttribs($config->attribs);
        $this->setTranslate($config->translate);
        $this->setText($config->text);
        $this->setMaxLength($config->max_length);
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'link'       => array(),
            'delimiter'  => '%',
            'translate'  => false,
            'max_length' => 0,
            'translator' => 'com://admin/logman.activity.message.translator.default',
            'attribs'    => array()));
        parent::_initialize($config);
    }

    public function setText($text)
    {
        $this->_text = (string) $text;
        return $this;
    }

    public function setMaxLength($length)
    {
        $this->_max_length = (int) $length;
        return $this;
    }


    public function setTranslate($state)
    {
        $this->_translate = (bool) $state;
        return $this;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function setLink($config = array())
    {
        $config = new KConfig($config);

        $config->append(array('attribs' => array(), 'route' => true, 'absolute' => true));

        $this->_link = $config;

        return $this;
    }

    public function setAttribs($attribs = array())
    {
        $this->_attribs = (array) $attribs;
        return $this;
    }

    /**
     * Tells if the parameter text should be translated.
     *
     * @return bool True if it is, false otherwise.
     */
    public function isTranslatable()
    {
        return (bool) $this->_translate;
    }

    public function getName($delimiter = false)
    {
        $name = $this->_name;
        if ($delimiter) {
            $name = $this->_delimiter . $name . $this->_delimiter;
        }
        return $name;
    }

    public function __toString()
    {
        if ($result = $this->getText()) {
            if ($this->isTranslatable()) {
                $result = $this->_translator->translate($result);
            }

            if (($max_length = $this->_max_length) && strlen($result) > $max_length) {
                $result = htmlspecialchars(substr($result, 0, $max_length), ENT_QUOTES) . '&hellip;';
            }

            if (($link = $this->_link) && ($url = $link->url)) {

                $attribs = $link->attribs ? KHelperArray::toString(KConfig::unbox($link->attribs)) : '';

                if (strpos($url, 'mailto:') !== 0) {
                    if ($link->route) {
                        $url = JRoute::_($url);
                        if (version_compare(JVERSION, '1.6', '<') && JFactory::getApplication()->isAdmin()) {
                            // TODO Remove when J!1.5 support is dropped. J!1.5 backend router does not append the
                            // path relative to document root.
                            $url = KRequest::base() . '/' . $url;
                        }
                    } else {
                        // If routing is disabled, URLs are assumed as relative to site root.
                        $url = KRequest::root() . '/' . $url;
                    }

                    if ($link->absolute) {
                        $url = JURI::getInstance()->toString(array('scheme', 'host', 'port')) . $url;
                    }
                }

                if (!$link->route) {
                    $url = htmlspecialchars($url, ENT_QUOTES); // Routed links are already escaped.
                }

                $result = '<a ' . $attribs . ' href="' . $url . '">' . $result . '</a>';
            }

            if ($attribs = $this->_attribs) {
                $result = '<span ' . KHelperArray::toString($attribs) . '>' . $result . '</span>';
            }
        } else {
            $result = $this->getName(true);
        }

        return $result;
    }
}