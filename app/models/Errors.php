<?php


class Errors extends WayweModel
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
    public $action;
     
    /**
     *
     * @var integer
     */
    public $user_id;
     
    /**
     *
     * @var string
     */
    public $source_line;
     
    /**
     *
     * @var string
     */
    public $error_text;
     
    /**
     *
     * @var string
     */
    public $error_type;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("action", "Actions", "id", NULL);
		$this->belongsTo("user_id", "Users", "id", NULL);
		$this->skipAttributesOnCreate(array('time'));
		
		$this->user_id = $this->getDi()->getShared("session")->get("id");
		$action = $this->getDi()->getShared("dispatcher")->getActionName();
		$controller = $this->getDi()->getShared("dispatcher")->getControllerName();
		
		$controller_model = Controllers::findFirst(array("name = '$controller'","cache" => array("lifetime" => 3600, "key" => "mvc_" . $controller)));
		
		if ($controller_model)
			foreach ($controller_model->getActions(array("cache" => array("lifetime" => 3600, "key" => "mvc_action_" . $controller)))->toArray() as $m) 
				if ($m["name"] == $action) $this->action = $m["id"];

		parent::initialize();
    }

}
