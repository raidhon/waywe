<?php


class MiColShowNames extends WayweModel
{

    /**
     *
     * @var integer
     */
    public $id;
     
    /**
     *
     * @var integer
     */
    public $tab_id;
     
    /**
     *
     * @var string
     */
    public $col_name;
     
    /**
     *
     * @var string
     */
    public $show_name;

    /**
     *
     * @var string
     */
    public $validations;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("tab_id", "MiTabShowNames", "id", array("foreignKey"=>true));
    }

}
