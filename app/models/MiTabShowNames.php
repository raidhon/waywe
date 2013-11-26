<?php

class MiTabShowNames extends WayweModel
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
    public $tab_name;
     
    /**
     *
     * @var string
     */
    public $show_name;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->hasMany("id", "MiColShowNames", "tab_id", NULL);

    }

}
