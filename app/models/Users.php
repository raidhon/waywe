<?php

class Users extends WayweModel
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
    public $password;
     
    /**
     *
     * @var string
     */
    public $phone;
     
    /**
     *
     * @var string
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
    public $country;
     
    /**
     *
     * @var string
     */
    public $active;
     
    /**
     *
     * @var string
     */
    public $last_login;
     
    /**
     *
     * @var string
     */
    public $date_register;
     
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
    public $forget_hash;
     
    /**
     *
     * @var string
     */
    public $fh_expire_date;
     
    /**
     *
     * @var string
     */
    public $get_loc_by_ip;
     
    /**
     *
     * @var integer
     */
    public $partner_id;
     
    /**
     *
     * @var string
     */
    public $receive_sms;
     
    /**
     *
     * @var string
     */
    public $photo_large;
     
    /**
     *
     * @var string
     */
    public $photo_medium;
     
    /**
     *
     * @var string
     */
    public $photo_avatar;
     
    /**
     *
     * @var string
     */
    public $sc_dir;
     
    /**
     *
     * @var string
     */
    public $photo_upload_time;
     
    /**
     *
     * @var string
     */
    public $photo_checked;
     
    /**
     *
     * @var string
     */
    public $profile_change_time;
     
    /**
     *
     * @var string
     */
    public $profile_checked;
     
    /**
     *
     * @var string
     */
    public $date_block;
     
    /**
     *
     * @var string
     */
    public $block_reason;
     
    /**
     *
     * @var integer
     */
    public $prof_active;
     
    /**
     * Validations and business logic
     */
    public $session_id;

	public $reg_ip; 
	public $phone_checked;
	public $sms_allow;
	
	/**
	В дальнейшем мы будем получать этот массив программным путём -- возможно, унаследовав от модели. 
	Его структура в будущем может измениться.
	Следует рассматривать этот массив как временную заглушку, существующую до того, как будет реализована соответствуюая функциональность.
	
	Пока я намеренно не буду заполнять этот массив целиком, на все поля. 
	Если значение в нём не найдено, используется исходное имя поля.
	*/
	
    /**
     * Initialize method for model.
     */

    public function initialize()
    {
		$this->hasMany("id", "AllowedIps", "user_id", NULL);
		$this->hasMany("id", "Clients", "user_id", NULL);
		$this->hasMany("id", "Errors", "user_id", NULL);
		$this->hasMany("id", "IntrusionLog", "user_id", NULL);
		$this->hasMany("id", "Notices", "creator_id", NULL);
		$this->hasMany("id", "Notices", "user_id", NULL);
		$this->hasMany("id", "Partners", "creator_id", NULL);
		$this->hasMany("id", "RolesActions", "creator_id", NULL);
		$this->hasMany("id", "UserProf", "user_id", NULL);
		$this->hasMany("id", "UserRole", "user_id", NULL);
		$this->hasMany("id", "UsersHistory", "user_id", NULL);
		$this->belongsTo("partner_id", "Partners", "id", NULL);
		$this->belongsTo("prof_active", "Professions", "id", NULL);
		$this->hasManyToMany(
	            "id",
        	    "UserRole",
	            "user_id", "role_id",
        	    "Roles",
	            "id"
       		);
			
		$this->get_loc_by_ip = "Y";
		$this->active = "N";

		//print(__METHOD__ . '<br/>');
		parent::initialize();
	}

}