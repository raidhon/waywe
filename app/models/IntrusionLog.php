<?php


class IntrusionLog extends WayweModel
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
    public $ip;
     
    /**
     *
     * @var string
     */
    public $time;
     
    /**
     *
     * @var integer
     */
    public $user_id;
     
    /**
     *
     * @var string
     */
    public $url;
     
    /**
     *
     * @var integer
     */
    public $type;
     
    /**
     *
     * @var string
     */
    public $text;
     
    /**
     *
     * @var string
     */
    public $headers;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("user_id", "Users", "id", NULL);
		$this->skipAttributesOnCreate(array('time'));
		parent::initialize();
    }

}
