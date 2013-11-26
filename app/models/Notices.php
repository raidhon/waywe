<?php


class Notices extends WayweModel
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
    public $event_date;
     
    /**
     *
     * @var string
     */
    public $accept_date;
     
    /**
     *
     * @var integer
     */
    public $event_id;
     
    /**
     *
     * @var string
     */
    public $text;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("creator_id", "Users", "id", NULL);
		$this->belongsTo("user_id", "Users", "id", NULL);

    }

}
