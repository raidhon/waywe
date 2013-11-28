<?php


class UserRole extends WayweModel
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
    public $user_id;
     
    /**
     *
     * @var integer
     */
    public $role_id;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("role_id", "Roles", "id", NULL);
		$this->belongsTo("user_id", "Users", "id", NULL);
		parent::initialize();
    }

}
