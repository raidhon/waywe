<?php


class Actions extends WayweModel
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
    public $name;
     
    /**
     *
     * @var integer
     */
    public $controller;
     
    /**
     *
     * @var string
     */
    public $type;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->hasMany("id", "Errors", "action", NULL);
		$this->hasMany("id", "RolesActions", "action", NULL);
		$this->hasMany("id", "SysEvents", "action", NULL);
		$this->belongsTo("controller", "Controllers", "id", NULL);
		parent::initialize();
    }

}
