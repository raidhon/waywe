<?php

class SysEvents extends WayweModel
{

    /**
     *
     * @var integer
     */
    public $id;
     
    /**
     *
     * @var string
     */
    public $time;
     
    /**
     *
     * @var integer
     */
    public $source_id;
     
    /**
     *
     * @var string
     */
    public $text;
     
    /**
     *
     * @var integer
     */
    public $action;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("action", "Actions", "id", NULL);
		$this->skipAttributesOnCreate(array('time'));
		
		$action = $this->getDi()->getShared("dispatcher")->getActionName();
		$controller = $this->getDi()->getShared("dispatcher")->getControllerName();
		
		$controller_model = Controllers::findFirst(array("name = '$controller'","cache" => array("lifetime" => 3600, "key" => "mvc_" . $controller)));
		
		if ($controller_model)
			foreach ($controller_model->getActions(array("cache" => array("lifetime" => 3600, "key" => "mvc_action_" . $controller)))->toArray() as $m) 
				if ($m["name"] == $action) $this->action = $m["id"];
		
    }

}
