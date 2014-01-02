<?php

class WIPController extends BaseController {

	public function getIndex()
	{
		return $this->theme->scope('wip')->render();
	}

}