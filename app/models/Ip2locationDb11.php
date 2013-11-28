<?php


class Ip2locationDb11 extends WayweModel
{

    /**
     *
     * @var integer
     */
    public $ip_from;
     
    /**
     *
     * @var integer
     */
    public $ip_to;
     
    /**
     *
     * @var string
     */
    public $country_code;
     
    /**
     *
     * @var string
     */
    public $country_name;
     
    /**
     *
     * @var string
     */
    public $region_name;
     
    /**
     *
     * @var string
     */
    public $city_name;
     
    /**
     *
     * @var string
     */
    public $latitude;
     
    /**
     *
     * @var string
     */
    public $longitude;
     
    /**
     *
     * @var string
     */
    public $zip_code;
     
    /**
     *
     * @var string
     */
    public $time_zone;

    public function initialize()
    {
		parent::initialize();
	}
	
}
