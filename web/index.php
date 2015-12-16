<?php
/**
 *
 */
define('ROOT_PATH', dirname(__DIR__));
define('LIB_PATH', ROOT_PATH . '/lib');
define('VENDOR_PATH', LIB_PATH . '/vendor');

air\loader::autoload();

if (isset($_GET['debug'])) {
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

    define('START_TIME', microtime(true));
    define('START_MEMORY_USAGE', memory_get_usage());
}

air\config::set([
    'app' => [
        'view' => [
            'engine' => 'air\view\smarty',
            'type' => '.tpl',
            'config' => [
                'template_dir' => ROOT_PATH."/app/view/",
                'compile_dir' => ROOT_PATH."/tmp/view_c/"
            ],
        ],
    ]
]);

date_default_timezone_set('Asia/Shanghai');

if($argv){
    $url = $argv[1]?: '/';
}else{
    $url = $_SERVER['REQUEST_URI']?: '/';
}

$router = new air\router();
$router->set_rules([
    '^/$' => 'containers.index',
    '^/(<c:\w+>/?(<a:\w+>)?)?' => '$c.$a'
])->set_url($url);

$app = new air\app();
$app->set_router($router)->run();

$xhprof_data = xhprof_disable();
