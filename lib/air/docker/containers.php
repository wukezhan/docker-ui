<?php
namespace air\docker;
/**
 * 
 * @author wukezhan<wukezhan@gmail.com>
 * 2014-10-12 14:28
 * 
 */

class containers extends api
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
    public function export($params = array())
    {
        $this->_api_url = 'export';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function copy($params = array())
    {
        $this->_api_url = 'copy';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function top($params = array())
    {
        $this->_api_url = 'top';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function start($params = array())
    {
        $this->_api_url = 'start';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function stop($params = array())
    {
        $this->_api_url = 'stop';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function kill($params = array())
    {
        $this->_api_url = 'kill';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function restart($params = array())
    {
        $this->_api_url = 'restart';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function pause($params = array())
    {
        $this->_api_url = 'pause';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function unpause($params = array())
    {
        $this->_api_url = 'unpause';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function changes($params = array())
    {
        $this->_api_url = 'changes';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function logs($params = array())
    {
        $this->_api_url = 'logs';
        $this->_method = self::GET;
        $this->_params = $params;
        return $this;
    }
    public function attach($params = array())
    {
        $this->_api_url = 'attach';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function wait($params = array())
    {
        $this->_api_url = 'wait';
        $this->_method = self::POST;
        $this->_params = $params;
        return $this;
    }
    public function delete($params = array())
    {
        $this->_api_url = '';
        $this->_method = self::DELETE;
        $this->_params = $params;
        return $this;
    }
}
