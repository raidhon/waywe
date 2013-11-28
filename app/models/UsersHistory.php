<?php


class UsersHistory extends WayweModel
{

    /**
     *
     * @var integer
     */
    public $user_id;
     
    /**
     *
     * @var string
     */
    public $time;
     
    /**
     *
     * @var string
     */
    public $old_value;
     
    /**
     *
     * @var string
     */
    public $field_name;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("user_id", "Users", "id", NULL);
		parent::initialize();
    }

}
