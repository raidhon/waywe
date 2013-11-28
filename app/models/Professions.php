<?php


class Professions extends WayweModel
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
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->hasMany("id", "UserProf", "prof_id", NULL);
		$this->hasMany("id", "Users", "prof_active", NULL);
		parent::initialize();
    }

}
