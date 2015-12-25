<?php
namespace app\site;

use air\controller;
use air\sock;

class docker extends controller
{
    public function action_api()
    {
        $sock = new sock('unix:///var/run/docker.sock');
        $sock->proxy('unix:///var/run/docker.sock', -1, '/docker/api');
    }
}