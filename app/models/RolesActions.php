<?php


class RolesActions extends WayweModel
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
    public $action;
     
    /**
     *
     * @var integer
     */
    public $role_id;
     
    /**
     *
     * @var integer
     */
    public $creator_id;
     
    /**
     *
     * @var string
     */
    public $creation_date;
     
    /**
     *
     * @var string
     */
    public $expires;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("action", "Actions", "id", NULL);
		$this->belongsTo("creator_id", "Users", "id", NULL);
		$this->belongsTo("role_id", "Roles", "id", NULL);

    }

}
