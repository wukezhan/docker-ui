<?php
namespace app\site;
use air;

class images extends air\controller
{
	public function action_index()
	{
        $docker = new air\docker();
        $images = $docker->images()->json()->request();

        $this->assign('images', $images);
        $this->render_view();
	}
}