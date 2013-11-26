<?php

use Phalcon\Mvc\View;
use Phalcon\Tag as Tag;
use Phalcon\Flash as Flash;
use Phalcon\Validation\Validator\Regex as RegexValidator;

use Phalcon\DI\FactoryDefault;


class TestController extends ControllerBase
{
	public $user, $user_images_dir;
	
	public function initialize()
    {
        //$this->view->setTemplateAfter('main');  123
        Tag::setTitle('Тест контроллер');
        parent::initialize();
        $this->user_images_dir = "/home/www/waywe/public/img/user/" . $this->session->get("id") . "/";
        $this->user = Users::findFirst($this->session->get("id"));
    }


	public function getcodeAction()
	{
		if ($this->request->isPost())
    	{
    		
    	}
	}

	public function checkcodeAction()
	{
	
	}

    
	
	public function indexAction()
    {
        /*
        foreach ($this->getDI()->getServices() as $serv)
            echo "<br>" . $serv->getName();
		pre_pr($this->config);
		*/

        //pre_dump($this->config->regexps);
        //['fio']
		//require_once('wconf.php');
		//print ('Inside ' . __FILE__ . ':<br />');
		//pre_dump($config);

		//echo "[{$wConf->}]";
		//pre_pr($users->getTest01());
		//pre_dump($wConf);
		/*
		$di = new FactoryDefault();
		$myConf = $di->get('wConf');
		pre_dump(myConf);
    */

		
		
		// Разрешено ли получение SMS
		
		//$users->setValidators();
		// Get Phalcon\Mvc\Model\Metadata instance
		//$metaData = $user->getModelsMetaData();
		
		// Get robots fields names
		//$attributes = $this->modelsManager->getBelongsTo($user);
		//pre_dump($attributes);
		
		// Get robots fields data types
		//$dataTypes = $metaData->getDataTypes($user);
		//pre_dump($dataTypes);
    	
		/*


		$reAll = '/.* /i'; // лишний пробел, чтобы не было закр. комментария
		$reValidFio = '/^[- 0-9a-zA-Z\'\.АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯІЇЄЃабвгдеёжзийклмнопрстуфхцчшщьыъэюяабвгдеёжзийклмнопрстуфхцчшщьыъэюяіїєѓ]+$/i';
		//$reValidFio = '';
		$reBase64 = '/^[0-9a-zA-Z\/=+]+$/i';
		$reDate = '/^\d{4}\-\d{2}\-\d{2}$/';

		*/
		

		$user = Users::findFirst(2);
		$user->save();
		foreach ($user->getMessages() as $message)
			$this->flash->error($message);
		
		//$user->setTest(false);
		/*
		$user->save();
		foreach ($user->getMessages() as $message)
			$this->flash->error($message);
		
		pre_dump($user->getLocalFieldNames());
		*/
		
		$this->view->setVar("test","1234");
    }
    
    public function deletephotoAction()
    {
    	if ($this->request->isPost())
    	{
    		if (file_exists($this->user_images_dir . $this->user->photo_large)) unlink($this->user_images_dir . $this->user->photo_large);
    		if (file_exists($this->user_images_dir . $this->user->photo_medium)) unlink($this->user_images_dir . $this->user->photo_medium);
    		if (file_exists($this->user_images_dir . $this->user->photo_avatar)) unlink($this->user_images_dir . $this->user->photo_avatar);
    		$this->user->photo_large = "";
			$this->user->photo_medium = "";
			$this->user->photo_avatar = "";
			$this->user->photo_upload_time = date("Y-m-d H:i:s");
			$this->user->photo_checked = "";
			$this->user->save();
			$event = new SysEvents;
			$event->source_id = $this->user->id;
			$event->text = "Удаление фото " . $this->request->getClientAddress();
			$event->save();
    	}
    }
    
    public function uploadAction()
    {
    	$photo_large_width = 300;
    	$photo_medium_width = 120;
    	$photo_avatar_width = 50;
    	
    	if ($this->request->isPost())
    	{
    		
    		if (isset($_POST["photo_path"])) {
    			// обработка файла на ФРОНТЕНДЕ. пока фронтенд и бэкенд совмещен - задача простенькая. посчитать размер и тип. потом нужно будет переделать
    			try {
    				if (!is_dir($this->user_images_dir)) mkdir($this->user_images_dir);
    				
    				if (is_file($this->user_images_dir . $this->user->photo_large)) unlink($this->user_images_dir . $this->user->photo_large);
    				if (is_file($this->user_images_dir . $this->user->photo_medium)) unlink($this->user_images_dir . $this->user->photo_medium);
    				if (is_file($this->user_images_dir . $this->user->photo_avatar)) unlink($this->user_images_dir . $this->user->photo_avatar);
    				
    				$im = new Imagick($_POST["photo_path"]);
    				
    				$photo_large_path = md5(time()) . "large." . strtolower($im->getImageFormat());
    				$photo_medium_path = md5(time()) . "medium." . strtolower($im->getImageFormat());
    				$photo_avatar_path = md5(time()) . "avatar." . strtolower($im->getImageFormat());
    				
    				$im->resizeImage($photo_large_width,0,Imagick::FILTER_LANCZOS,1);
					$im->writeImage($this->user_images_dir . $photo_large_path);
					$im->clear();
					
					$im->readImage($_POST["photo_path"]);
					$im->resizeImage($photo_medium_width,0,Imagick::FILTER_LANCZOS,1);
					$im->writeImage($this->user_images_dir . $photo_medium_path);
					$im->clear();
    				
    				$im->readImage($_POST["photo_path"]);
					$im->resizeImage($photo_avatar_width,0,Imagick::FILTER_LANCZOS,1);
					$im->writeImage($this->user_images_dir . $photo_avatar_path);
					$im->destroy();
					
					$this->user->photo_large = $photo_large_path;
					$this->user->photo_medium = $photo_medium_path;
					$this->user->photo_avatar = $photo_avatar_path;
					$this->user->photo_upload_time = date("Y-m-d H:i:s");
					$this->user->photo_checked = "N";
					$this->user->save();
					
					unlink($_POST["photo_path"]);
					
					echo "/waywe/img/user/" . $this->session->get("id") . "/" . $photo_medium_path;
    				
    				$event = new SysEvents;
					$event->source_id = $this->user->id;
					$event->text = "Загрузка фото " . $this->request->getClientAddress();
					$event->save();
    			} catch (Exception $e) {
    				$error = New Errors;
					$error->user_id = $this->session->get("id");
					$error->source_line = $e->getLine();
					$error->error_text = $e->getFile() . ' ' . $e->getMessage();
					$error->error_type = "Ошибка обработки изображения " . $e->getCode();
					$error->save();
					$this->response->setStatusCode(500, "Error processing image");
    			}
    		}
    		$this->view->disable();
    	}
    }
    
    public function profileAction()
    {
    	$this->view->setTemplateAfter('main');
		$this->assets
			->addCss($this->url->getBaseUri() . 'css/fw/personal-info.css',true,false,array("media" => "screen"))
			->addCss($this->url->getBaseUri() . 'css/fw/bootstrap.datepicker.css',true,false,array("media" => "screen"));
		$this->assets
            ->addCss('css/fw/uniform.default.css')
			->addCss('css/fw/select2.css')
			->addCss('css/fw/typeahead.css');
		$this->assets
            ->addJs('js/fw/jquery.uniform.min.js')
			->addJs('js/fw/typeahead.min.js')
            ->addJs('js/fw/theme.js')
			->addJs('js/fw/bootstrap.datepicker.js')
			->addJs('js/fw/select2.min.js')
			->addJs('js/fw/wysihtml5-0.3.0.js');
		$this->view->pick("user/profile");
		$this->user = Users::findFirst($this->session->get("id"));
		
		$this->view->setVar("user",$this->user);
		
		$ourCountries = array('РОССИЙСКАЯ ФЕДЕРАЦИЯ', 'УКРАИНА');

		$ip = ip2long($this->request->getClientAddress());
		$ourLoc = Ip2locationDb11::findFirst(array("$ip >= ip_from AND $ip <= ip_to","cache" => array("lifetime" => 3600, "key" => "tz_" . $ip)));
		$key1 = array_search($ourLoc->country_name, $ourCountries);
		$key2 = array_search($this->user->country, $ourCountries);
		
		if (false === $key1)
			$ourCountries[] = $ourLoc->country_name;
		
		if (false != $key2)
			unset($ourCountries[$key2]);
		
		array_unshift($ourCountries,$this->user->country);

		$ourReq = sprintf("SELECT DISTINCT country_name as country_name, city_name FROM [Ip2locationDb11] WHERE country_name in ('%s')", implode("', '", $ourCountries));
		
		$db_loc = array();
		
		$query = $this->modelsManager->createQuery($ourReq)
			->cache(array('key'=>'countries' . $ip, 'lifetime' => 86400))
			->execute()
			->toArray();
		
		foreach($query as $val)
			$db_loc[$val["country_name"]][] = $val["city_name"];
		
		$this->view->setVar("ourCountries",$ourCountries);
		$loc = "var loc = {";
		foreach ($ourCountries as $cn)
			$loc .= "\"$cn\":[\"" . implode("\",\"",$db_loc[$cn]) . "\"],";
		$loc .= "};";
		$this->view->setVar("loc",$loc);
    }
}

