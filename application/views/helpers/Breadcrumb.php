<?php
/**
 * Zend Framework
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 */

class Zend_View_Helper_Breadcrumb extends Zend_View_Helper_Abstract
{
    /**
     * Limit of chars
     *
     * @var int
     */
    private $_charsLimit = 60;

    /**
     * Breadcrumbs separator string
     *
     * @var string
     */
    private $_separator = ' &gt; ';

    /**
     * Title separator string
     *
     * @var string
     */
    private $_titleSeparator = ' &raquo; ';

    /**
     * Breadcrumbs container
     *
     * @var array
     */
    private $_container = array();

    /**
     * Breadcrumbs
     *
     * @var string
     */
    private $_breadcrumbs;

    /**
     * Title
     *
     * @var string
     */
    private $_title;

    
    /**
     * Sets breadcrumb separator
     *
     * @param  string $separator
     */
    public function setSeparator($separator)
    {
        if (is_string($separator))
            $this->_separator = $separator;
    }

    /**
     * Sets title separator
     *
     * @param  string $separator
     */
    public function setTitleSeparator($separator)
    {
        if (is_string($separator))
            $this->_titleSeparator = $separator;
    }

    /**
     * Adds breadcrumb
     *
     * @param  string $title
     * @param  string $url
     * @return Zend_View_Helper_Breadcrumb
     */
    public function breadcrumb($title = null, $url = null)
    {
        if (!empty($title))
            $this->_container[] = array('title' => $title, 'url' => $url);
        else
            return $this;
    }

    /**
     * Renders breadcrumbs
     *
     * @return string
     */
    public function render($main = null)
    {
        $counter = 0;
        $count = count($this->_container);
        if ($count > 0)
        {
            if (!empty($main))
            {
                $this->_breadcrumbs .= '<a href="" title="' . $main . '">' . $main . '</a>';
                $this->_breadcrumbs .= $this->_separator;
            }
            $i = 0;
            foreach ($this->_container as $key => $breadcrumb)
            {
                $i++;
                if (strlen($breadcrumb['title']) + 1 > $this->_charsLimit)
                    $title = substr($breadcrumb['title'], 0, $this->_charsLimit - 1) . '...';
                else
                    $title = $breadcrumb['title'];
                
                if ($i == $count)
                {
                    $this->_breadcrumbs .= '<strong>' . $title . '</strong>';
                } 
                else
                {
                    $this->_breadcrumbs .= '<a href="' . $breadcrumb['url'] . '" title="' . htmlspecialchars($title) . '">' . $title . '</a>';
                    $this->_breadcrumbs .= $this->_separator;
                }
            }
        }

        return $this->_breadcrumbs;
    }

    /**
     * Renders title
     *
     * @return string
     */
    public function getTitle($main = null)
    {
        $counter = 0;
        $count = count($this->_container);
        if ($count == 0)
        {
            if (!empty($main))
                $this->_title .= $main;
        }
        else
        {
            $i = 0;
            foreach ($this->_container as $key => $breadcrumb)
            {
                $i++;
                if (strlen($breadcrumb['title']) + 1 > $this->_charsLimit)
                    $title = substr($breadcrumb['title'], 0, $this->_charsLimit - 1) . '...';
                else
                    $title = $breadcrumb['title'];

                if ($i == $count)
                {
                    $this->_title .= htmlspecialchars($title);
                }
                else
                {
                    $this->_title .= htmlspecialchars($title);
                    $this->_title .= $this->_titleSeparator;
                }
            }
        }
        return $this->_title;
    }

}