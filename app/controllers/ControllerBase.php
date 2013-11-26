<?php

use Phalcon\Mvc\Controller,
	Phalcon\Mvc\View;

class ControllerBase extends Controller
{
	protected function initialize()
    {
        Phalcon\Tag::prependTitle('WAYWE | ');
    }
 	protected function forward($uri){
    	$uriParts = explode('/', $uri);
    	return $this->dispatcher->forward(
    		array(
    			'controller' => $uriParts[0], 
    			'action' => $uriParts[1]
    		)
    	);
    }
	
	public function widget($controllerName,$actionName)
    {  
		
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

		$this->view->start();
		
		$this->view->render($controllerName, $actionName);
		
		$this->view->finish();
		
		$this->view->setRenderLevel(View::LEVEL_MAIN_LAYOUT);

        return $this->view->getContent();
    }
	
}