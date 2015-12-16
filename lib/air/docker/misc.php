<?php
namespace air\docker;
/**
 *
 * @author wukezhan<wukezhan@gmail.com>
 * 2014-10-12 14:28
 *
 */

class misc extends api
{

    public function build($params = array())
    {
        $this->_api_url = 'build';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function auth($params = array())
    {
        $this->_api_url = 'auth';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function info($params = array())
    {
        $this->_api_url = 'info';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function version($params = array())
    {
        $this->_api_url = 'version';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function _ping($params = array())
    {
        $this->_api_url = '_ping';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function commit($params = array())
    {
        $this->_api_url = 'commit';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function events($params = array())
    {
        $this->_api_url = 'events';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
}
