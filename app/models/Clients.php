<?php


class Clients extends WayweModel
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
     * @var string
     */
    public $first_name;
     
    /**
     *
     * @var string
     */
    public $last_name;
     
    /**
     *
     * @var string
     */
    public $patronym;
     
    /**
     *
     * @var string
     */
    public $email;
     
    /**
     *
     * @var string
     */
    public $phone;
     
    /**
     *
     * @var integer
     */
    public $time_zone;
     
    /**
     *
     * @var string
     */
    public $location;
     
    /**
     *
     * @var string
     */
    public $active;
     
    /**
     *
     * @var string
     */
    public $sex;
     
    /**
     *
     * @var string
     */
    public $vkontakte;
     
    /**
     *
     * @var string
     */
    public $facebook;
     
    /**
     *
     * @var string
     */
    public $icq;
     
    /**
     *
     * @var string
     */
    public $skype;
     
    /**
     *
     * @var string
     */
    public $comment;
     
    /**
     *
     * @var string
     */
    public $birthdate;
     
    /**
     *
     * @var string
     */
    public $passport_no;
     
    /**
     *
     * @var string
     */
    public $issued;
     
    /**
     *
     * @var string
     */
    public $when_issued;
     
    /**
     *
     * @var string
     */
    public $subdiv_code;
     
    /**
     *
     * @var string
     */
    public $born_place;
     
    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    "field"    => "email",
                    "required" => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("user_id", "Users", "id", NULL);

    }

}
