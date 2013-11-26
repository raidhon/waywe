<?php


class Roles extends WayweModel
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
     * @var string
     */
    public $creation_date;
     
    /**
     *
     * @var integer
     */
    public $creator_id;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->hasMany("id", "RolesActions", "role_id", NULL);
		$this->hasMany("id", "UserRole", "role_id", NULL);
		$this->hasManyToMany(
                    "id",
                    "RolesActions",
                    "role_id", "action",
                    "Actions",
                    "id"
                );
    }

}
