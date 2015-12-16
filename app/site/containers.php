<?php
namespace app\site;
use air;

class containers extends air\controller
{
	public function action_index()
	{
        $a = microtime(1);
        $docker = new air\docker();
        $containers = $docker->containers()->json( [ 'all' => 1 ] )->request();
        header("time: ".(microtime(1)-$a));
        $this->assign('containers', $containers);
        $this->render_view();
	}
}