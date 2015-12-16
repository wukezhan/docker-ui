<?php

namespace air\view;

use air\config;
use air\exception;

define('SMARTY_PATH', VENDOR_PATH.'/smarty/');

class smarty extends \air\view
{
	/**
	 * @var string the file-extension for viewFiles this renderer should handle
	 * for smarty templates this usually is .tpl
	 */
	public $file_extension='.tpl';

	/**
	 * @var int dir permissions for smarty compiled templates directory
	 */
	public $directory_permission=0771;

	/**
	 * @var int file permissions for smarty compiled template files
	 * NOTE: BEHAVIOR CHANGED AFTER VERSION 0.9.8
	 */
	public $file_permission=0644;

	/**
	 * @var null|string alias of the directory where your smarty plugins are located
	 * application.extensions.Smarty.plugins is always added
	 */
	public $plugins_dir = null;

	/**
	 * @var null|string alias of the directory where your smarty template-configs are located
	 */
	public $config_dir = null;

	/**
	 * @var array smarty configuration values
	 * this array is used to configure smarty at initialization you can set all
	 * public properties of the Smarty class e.g. error_reporting
	 *
	 * please note:
	 * compile_dir will be created if it does not exist, default is <app-runtime-path>/smarty/compiled/
	 *
	 * @since 0.9.9
	 */
	public $_config = array();
	public $_data = array();

	/**
	 * @var Smarty smarty instance for rendering
	 */
	private $smarty = null;

	public function __construct($config=array())
	{
		// need this to avoid Smarty rely on spl autoload function,
		// this has to be done since we need the Yii autoload handler
		if (!defined('SMARTY_SPL_AUTOLOAD')) {
		    define('SMARTY_SPL_AUTOLOAD', 0);
		} elseif (SMARTY_SPL_AUTOLOAD !== 0) {
			throw new \Exception('\air\smarty cannot work with SMARTY_SPL_AUTOLOAD enabled. Set SMARTY_SPL_AUTOLOAD to 0.');
		}

		// including Smarty class and registering autoload handler

		require_once(SMARTY_PATH.'SmartyBC.class.php');

		spl_autoload_register(function($classname){
            if(preg_match('/^smarty/i', $classname)){
                $classname = strtolower($classname);
                require_once(SMARTY_PATH."sysplugins/{$classname}.php");
            }
        });
	}

	public function set_config($_config = [])
	{
		$this->smarty = new \SmartyBC();

		$this->_config = $_config;
		// configure smarty
		if (is_array($this->_config)) {
			foreach ($this->_config as $key => $value) {
				if ($key{0} != '_') { // not setting semi-private properties
					$this->smarty->$key = $value;
				}
			}
		}

		// need this since Yii autoload handler raises an error if class is not found
		// Yii autoloader needs to be the last in the autoload chain
		//spl_autoload_unregister('smartyAutoload');


		$this->smarty->_file_perms = $this->file_permission;
		$this->smarty->_dir_perms = $this->directory_permission;

		if (!$this->smarty->template_dir || !$this->smarty->compile_dir) {
			throw new \Exception('error template config!');
		}
		// create compiled directory if not exists
		if(!file_exists($this->smarty->compile_dir)){
			mkdir($this->smarty->compile_dir, $this->directory_permission, true);
		}
	}

	public function render($view_path = null, $return=false) {
		//$view_path = config::get_path('app.view.path').'/'.$view_path;//var_dump($view_path);
		$view_path = config::get_path('app.view.config.template_dir').'/'.$view_path;//var_dump($view_path);

		if (!file_exists($view_path)) {
		    throw new exception("#{$view_path} template file not exists!");
		}
		$template = $this->smarty->createTemplate($view_path, null, null, $this->_data, false);

		//render or return
		if($return){
			return $template->fetch($view_path);
		}else{
		    header("Content-Type:text/html; charset=UTF-8");
			$template->display($view_path);
		}
	}
}
