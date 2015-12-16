<?php
namespace air;
/**
 * 
 * @author wukezhan<wukezhan@gmail.com>
 * 2014-10-13 14:17
 * 
 */

class docker
{
    protected $_containers;
    protected $_images;
    protected $_misc;
    public function __construct()
    {
        $this->_containers = new docker\containers();
        $this->_images = new docker\images();
        $this->_misc = new docker\misc();
    }
    public function containers()
    {
        return $this->_containers;
    }
    public function images()
    {
        return $this->_images;
    }
    public function misc()
    {
        return $this->_misc;
    }
}