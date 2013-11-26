<?php

use Phalcon\Tag as Tag;
use Phalcon\Flash as Flash;

class IndexController extends ControllerBase
{
	public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Бета');
        parent::initialize();
    }
    public function indexAction()
    {
		$test = New TestController;
		$test->indexAction();
		print_r($this->request);
		$this->view->setVar("date",date("Y-m-d H:i:s",strtotime("-1 minute")));		  // $test->widget("test","widget")
    }

}

