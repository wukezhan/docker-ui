<?php
namespace air\docker;
/**
 *
 * @author wukezhan<wukezhan@gmail.com>
 * 2014-10-12 14:28
 *
 */

class images extends api
{
    public function json($params = array())
    {
        $this->_api_url = 'json';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function create($params = array())
    {
        $this->_api_url = 'create';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function history($params = array())
    {
        $this->_api_url = 'history';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function push($params = array())
    {
        $this->_api_url = 'push';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function tag($params = array())
    {
        $this->_api_url = 'tag';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function test($params = array())
    {
        $this->_api_url = 'test';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function get($params = array())
    {
        $this->_api_url = 'get';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function load($params = array())
    {
        $this->_api_url = 'load';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
}
