<?php


class UserProf extends WayweModel
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
    public $prof_id;
     
    /**
     *
     * @var integer
     */
    public $lvl;
     
    /**
     *
     * @var integer
     */
    public $cols;
     
    /**
     *
     * @var string
     */
    public $active;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("prof_id", "Professions", "id", NULL);
		$this->belongsTo("user_id", "Users", "id", NULL);

    }

}
