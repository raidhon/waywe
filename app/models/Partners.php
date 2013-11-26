<?php


class Partners extends WayweModel
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
    public $name;
     
    /**
     *
     * @var string
     */
    public $legal_adress;
     
    /**
     *
     * @var string
     */
    public $ogrn;
     
    /**
     *
     * @var string
     */
    public $inn;
     
    /**
     *
     * @var string
     */
    public $kpp;
     
    /**
     *
     * @var string
     */
    public $cur_account;
     
    /**
     *
     * @var string
     */
    public $bank;
     
    /**
     *
     * @var string
     */
    public $corr_account;
     
    /**
     *
     * @var string
     */
    public $corr_bank;
     
    /**
     *
     * @var string
     */
    public $bik;
     
    /**
     *
     * @var string
     */
    public $email;
     
    /**
     *
     * @var string
     */
    public $partner_agent;
     
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
		$this->hasMany("id", "Users", "partner_id", NULL);
		$this->belongsTo("creator_id", "Users", "id", NULL);

    }

}
