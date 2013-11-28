<?php


class AllowedIps extends WayweModel
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
    public $ip_mask;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("user_id", "Users", "id", NULL);
		parent::initialize();
    }

}
