<?php
namespace air\docker;
/**
 * 
 * @author wukezhan<wukezhan@gmail.com>
 * 2014-10-12 14:32
 * 
 */

class api
{
    const GET = 'GET';
    const POST = 'POST';
    const DELETE = 'DELETE';
    protected $_id;
    protected $_method;
    protected $_params;
    protected $_api_url;

    protected $_result;

    protected static $_cli = null;
    protected static $_config = array(
        'host' => 'localhost',
        'port' => 4243
    );

    public function __construct()
    {
    }
    public static function init_config($config)
    {
        self::$_config = $config;
    }

    public function id($id=null)
    {
        $this->_id = $id;
        return $this;
    }

    public function params($params)
    {
        $this->_params = $params;
        return $this;
    }

    public function request()
    {
        $prefix = str_replace(array('air\\docker\\', '\\'), array('', '/'), strtolower(get_class($this)));
        if('misc' == $prefix){
            $prefix = '';
        }else{
            $prefix = '/'.$prefix;
        }
        if($this->_id){
            $this->_api_url = $this->_id.'/'.$this->_api_url;
        }
        $this->_api_url = rtrim("{$prefix}/{$this->_api_url}", '/');
        if(!self::$_cli){
            self::$_cli = new \air\sock('unix:///var/run/docker.sock');
        }
        if(self::POST == $this->_method){
            $this->_result = self::$_cli->{$this->_method}($this->_api_url, $this->_params, \air\sock::TYPE_JSON);
        }else{
            $this->_result = self::$_cli->{$this->_method}($this->_api_url, $this->_params);
        }
        $json = json_decode($this->_result, 1);
        if($json){
            $this->_result = $json;
        }
        $this->_reset();
        return $this->_result;
    }
    public function get_header($key=null)
    {
        return self::$_cli->get_response_header($key);
    }
    protected function _reset()
    {
        $this->_id = null;
        $this->_params = null;
        $this->_api_url = null;
        return $this;
    }
}