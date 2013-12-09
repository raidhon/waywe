<?php

require_once('ic_waywe.php');

use Phalcon\Tag as Tag,
  Phalcon\Acl,
  Phalcon\Mvc\Model\Query;

class UserController extends ControllerBase
{
	public function initialize() {
		Tag::setTitle('Пользователь');
		parent::initialize();
	}
	
	public function indexAction()
	{

	}
	
  public function authAction()
	{
	$this->assets->addCss($this->url->getBaseUri() . 'css/fw/signin.css',true,false,array("media" => "screen"));
	$ip = ip2long($this->request->getClientAddress());
	$attempts = IntrusionLog::count("type = 2 AND ip = $ip AND time > '" . date("Y-m-d H:i:s",time() - 60) . "'");	  // Сколько попыток входа было с этого ip за последнюю минуту?
	$auth_success = false;						// Результат авторизации

	$tContr = $this->dispatcher->getParam('controller');
	$tAct = $this->dispatcher->getParam('action');
	$tParams = $this->dispatcher->getParam('params');
	if(is_array($tParams))
		$tParams = implode('/', $tParams);
	$tRedir = $tContr . '/' . $tAct . '/' . $tParams;
	$this->view->setVar('redirVal', $tRedir);

	if ($this->request->isPost() && !$auth_success) {
		$email = $this->request->getPost('email', 'email');
		$password = $this->request->getPost('password');
		$password_hash = base64_encode(keccak_hash($password));

		if ($attempts > 2) {
			$user = Users::findFirst("email='$email' AND password='$password_hash' AND active='A'");
			if ($user) {
				require_once('/home/www/waywe/app/lib/recaptchalib.php');
				$privatekey = "6LcvXukSAAAAAKPAf1mhq0tmHlr_FqN6IJ3Z_iP-";
				$resp = recaptcha_check_answer ($privatekey,
					$this->request->getClientAddress(),
					$this->request->getPost("recaptcha_challenge_field"),
					$this->request->getPost("recaptcha_response_field"));
				if (!$resp->is_valid) {
					$user = false;
					$this->flash->error("Проверочный код не верен");
					}
				}
		} else 
			$user = Users::findFirst("email='$email' AND password='$password_hash' AND active='A'");

		if (false == $user) {
			if ($attempts < 10) {
				$int = new IntrusionLog;
				$int->ip = $ip;
				$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
				$int->text = $email . "|" . $password;
				$int->type = 2;
				$int->headers = implode("\n",$this->request->getHeaders());
				$int->save();
				$this->flash->error("Email или пароль не верен");
			}
		} else {
			if ($user->getAllowedIps()->count()) $allow_by_ip = -1; else $allow_by_ip = 0;
			$subnets = array();
			foreach ($user->getAllowedIps()->toArray() as $subnet) {
				if ($subnet["ip_mask"] == ($ip & $subnet["ip_mask"])) $allow_by_ip++;
				$subnets[] = $subnet["ip_mask"];
			}

		if ($allow_by_ip < 0) {
			$this->flash->error("Попытка входа с запрещенного ip");
			$int = new IntrusionLog;
			$int->ip = $ip;
			$int->user_id = $user->id;
			$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			$int->text = "Попытка входа с запрещенного ip";
			$int->type = 3;
			$int->headers = implode("\n",$this->request->getHeaders());
			$int->save();
		} else {
			$successAuth = SysEvents::count("source_id = {$user->id} and action = 1 and time > '" . date("Y-m-d H:i:s",time() - 60) . "'");
			if ($successAuth > 10) {
				$this->flash->error("С вашего аккаунта превышено колличество авторизаций");
				$int = new IntrusionLog;
				$int->ip = $ip;
				$int->user_id = $user->id;
				$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
				$int->text = "Превышено колличество авторизаций";
				$int->type = 4;
				$int->headers = implode("\n",$this->request->getHeaders());
				$int->save();
				$auth_success = false;
			} else {
				$event = new SysEvents;
				$event->source_id = $user->id;
				$event->text = $this->request->getClientAddress();
				$event->action = 1;
				$event->save();

				if (!$user->session_id)
					$user->session_id = sha1(uniqid());

				session_id($user->session_id);

				$this->session->start();
				if (!$this->session->has("id") || $this->session->get("expires") < time()) {
					$expires = 2147483647;
					$this->session->set("id",$user->id);
					$this->session->set("hits",1);
					$this->session->set("time",time());
					if (count($subnets)) 
						$this->session->set("subnets",$subnets);
		
					$acl = new Phalcon\Acl\Adapter\Memory();
					$acl->setDefaultAction(Phalcon\Acl::DENY);
					$acl->addRole(new Phalcon\Acl\Role('User'));
					$roles = $user->getRoles();
					$actions_array = array();
					while ($roles->valid()) {
						$actions = $roles->current()->getActions();
					while ($actions->valid()) {
						$bd_expires = strtotime(RolesActions::findFirst
							("action = " . $actions->current()->id . " AND role_id = " . $roles->current()->id)->expires);
						if ($bd_expires && $expires > $bd_expires && $bd_expires > time()) 
							$expires = $bd_expires;
		
						if (!$bd_expires || $bd_expires > time()) {
							$controller_model = Controllers::findFirst($actions->current()->controller);
							$actions_array[$controller_model->name][] = $actions->current()->name;
						}
						$actions->next();
					}
					$roles->next();
				}

			foreach ($actions_array as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
				foreach ($actions as $action) $acl->allow('User', $resource, $action);
			}

			$this->session->set("expires",$expires);					  // Это ближайший минимальный срок окончания прав пользователя на определенные экшены

			$this->session->set("acl",$acl);

			$this->session->set("actions",$actions_array);

			$this->session->set("class",1);

			if ($user->partner_id > 0) $this->session->set("class",2);

			if ($roles->count()) $this->session->set("class",3);

			$this->session->set("get_loc_by_ip",$user->get_loc_by_ip);
			}
			if ('Y' == $user->get_loc_by_ip)
				if ($zone = Ip2locationDb11::findFirst(array("$ip >= ip_from AND $ip <= ip_to","cache" => array("lifetime" => 3600, "key" => "tz_" . $ip))))
					$user->time_zone = $zone->time_zone;

			if (!$ips = $this->session->get('ip')) 
				$ips = array();

			if (!isset($ips[$ip])) {
				if ('Y' == $user->get_loc_by_ip) {
					if ($zone->time_zone) {
						$ips[$ip] = $zone->time_zone;
					} else {
						if ($user->time_zone) 
							$ips[$ip] = $user->time_zone;
						else 
							$ips[$ip] = "+00:00";
					}
				} else if ($user->time_zone) 
					$ips[$ip] = $user->time_zone; else $ips[$ip] = "+00:00";
			}
			
			if (count($ips) > 10) unset($ips[key($ips)]);

			$this->session->set('ip',$ips);

			$user->last_login = date("Y-m-d H:i:s");

			if (!$user->save()) throw new Exception('Ошибка авторизации. Запись в бд не удалась.');

			$this->flash->success('Добро пожаловать ' . $user->first_name);

			$auth_success = true;
		  }
		}
	  }
	}

	if ($auth_success || $this->session->has("id")) {
		if ($this->request->getPost('redirTo') != "//") {
			$this->response->redirect($this->request->getPost('redirTo'));
		} else switch ($this->session->get('class')) {
			case 1: $this->response->redirect("test/profile"); break;
			case 2: $this->response->redirect("user/partner"); break;
			case 3: $this->response->redirect("user/admin"); break;
			default: case 1: $this->response->redirect("user/office"); break;
		}
	}
	
	if ($attempts > 2) $this->view->pick("user/capcha");
	}
	
	
	public function registerAction()
	{   
       $ip = ip2long($this->request->getClientAddress());
		$ourLoc = Ip2locationDb11::findFirst(array("$ip >= ip_from AND $ip <= ip_to","cache" => array("lifetime" => 3600, "key" => "tz_" . $ip)));
		$timeZone = $ourLoc->time_zone;
		
		if ($this->request->isPost())
		{
			$user = new Users();
					
			$pswAsIs = $_POST['password'];
			
			$_POST['password'] = base64_encode(keccak_hash($_POST['password']));
			$_POST['birthdate'] = date("Y-m-d", strtotime($_POST['birthdate']));
			
			$aToSend = $this->request->getPost();

			
			
			
			$aToSend['time_zone'] = $timeZone;
			$aToSend['reg_ip'] = $ip;
			
			$aToSend['forget_hash'] = base64_encode (keccak_hash(rand(0 ,10000000)));
			
			
			$res = $user->save($aToSend, array('last_name', 'first_name' , 'patronym' ,'sex' , 'birthdate' ,'country' , 'location' , 'email' , 'password',  'time_zone' , 'reg_ip' ,'forget_hash'));
			
			if(false == $res) {
				$_POST['password'] = $pswAsIs;
				$_POST['repeatPassword'] = $pswAsIs;
				foreach ($user->getMessages() as $message)
					$this->flash->error($message);
					}
				else 
					{
					$this->flash->success('Успешная регистрация: ' . $user->id);
					$userDir = sprintf("/tmp/users/%d/", $user->id);
					$user->sc_dir = $userDir;
					mkdir($userDir, 0777, true);
					$user->save();
					//Отправка письма активации
					$mail_header = "MIME-Version: 1.0\r\n";
                    $mail_header.= "Content-type: text/html; charset=UTF-8\r\n";
					$mail_header.= "Content-Transfer-Encoding: quoted-printable\r\n";
                    $mail_header.= "From: Waywe <admin@waywe.com>\r\n";
                    $mail_header.= "Reply-to: Waywe <admin@waywe.com>\r\n";
					mail ($user->email , $user->first_name . $user->last_name ,'
					<html>
					<body>
					<div>
					Для активации профиля пройдите по ссылке <a href="http://'.$_SERVER['SERVER_NAME'].'/waywe/user/checkuser?hash='.$user->forget_hash.' " target="_blank">'.$user->email.'</a>
					</div>
					</body></html>' , $mail_header);
					
					
					}
				
			
		}
	
		$this->assets
					->addCss('css/fw/uniform.default.css')
			  ->addCss('css/fw/select2.css')
			  ->addCss('css/fw/signup.css')
			  ->addCss('css/fw/bootstrap.datepicker.css')
			  ->addCss('css/fw/typeahead.css')
			  ->addCss('css/fw/switchy.css');
		   $this->assets
					->addJs('js/fw/jquery.uniform.min.js')
			  ->addJs('js/fw/typeahead.min.js')
			  ->addJs('js/fw/sprintf.min.js')
					->addJs('js/fw/theme.js')
			  ->addJs('js/fw/bootstrap.datepicker.js')
			  ->addJs('js/fw/select2.min.js')
			  ->addJs('js/fw/wysihtml5-0.3.0.js')
			  ->addJs('js/fw/jquery.animate-color.js')
			  ->addJs('js/fw/jquery.event.drag.js')
			  ->addJs('js/fw/switchy.js');

			  // TODO: Сохранять ip, с которого произошла регистрация
			  // TODO: Что делать, если зашёл уже авторизованный человек? Предложение: редирект на его профиль
			  // Предварительный набор стран
			  $ourCountries = array('РОССИЙСКАЯ ФЕДЕРАЦИЯ', 'УКРАИНА');
			
			  $countryName = $ourLoc->country_name;
			  //$regionName = $ourLoc->region_name;
			  $cityName = $ourLoc->city_name;
			  $detectedLoc = array('country' => $countryName, 'city' => $cityName, 'timezone' => $timeZone);

			  $res = array_search($countryName, $ourCountries);

			  if($res === FALSE)
				$ourCountries[] = $countryName;
			  $ourCountries = aValToFront($ourCountries, $countryName);

			  $ourReq = sprintf("SELECT DISTINCT country_name as country_name, city_name FROM [Ip2locationDb11] WHERE country_name in ('%s')", implode("', '", $ourCountries));

			  $query = $this->modelsManager->createQuery($ourReq)
				->cache(array('key'=>'countries', 'lifetime' => 86400))
				->execute()
				->toArray();

			  $db_loc = array();

			  foreach($query as $val)
				$db_loc[$val["country_name"]][] = $val["city_name"];

			  /*
			  //==============================================================================
			  
			  // Вариант implode -- Vasya-Implode

			  $jsLocBase = '';
			  foreach ($ourCountries as $cn)
				$jsLocBase .= "\"$cn\":[\"" . implode("\",\"",$db_loc[$cn]) . "\"],";
			  $jsLocBase = sprintf("/ * Vasya implode * / var jsLocBase = {%s};", $jsLocBase);

			  $jsDetectedLoc = sprintf('var jsDetectedLoc = ["%s"];',
				implode('","' , $detectedLoc));
			  
			  
			  //==============================================================================

			  require_once('ic_waywe.php');

			  // Вариант нативного кодирования
			  
			  $jsLocBase = 'var jsLocBase = ' . json_encode($db_loc) . ';';
			  $jsDetectedLoc = 'var jsDetectedLoc = ' . json_encode($detectedLoc) . ';';
			  
			  // Вариант библиотеки ic_*  ic_jsonEncode

			  $jsLocBase = sprintf('/ * ic_jsonEncode * / var jsLocBase = %s;', ic_jsonEncode($db_loc, ''));
			  $jsDetectedLoc = sprintf('var jsDetectedLoc = %s;', ic_jsonEncode($detectedLoc, ''));


			  */
			  $jsLocBase = '';
			  foreach ($ourCountries as $cn)
				$jsLocBase .= "\"$cn\":[\"" . implode("\",\"",$db_loc[$cn]) . "\"],";
			  $jsLocBase = sprintf("var jsLocBase = {%s};", $jsLocBase);

			  $jsDetectedLoc = sprintf('var jsDetectedLoc = ["%s"];',
				implode('","' , $detectedLoc));

			  $this->view->setVars(array
				(
				'ourCountries' =>  $ourCountries,
				'jsDetectedLoc' => $jsDetectedLoc,
				'jsLocBase' => $jsLocBase
				));
	
	}

		public function validemailAction()
		{
		$this->view->disable();
		if (isset($_POST['email'])) 
		{
			$em = $_POST['email'];
			$user =	 Users::findFirst(array("email = '$em' ","columns" => "email"));
		if($user->email == $_POST['email'])
		{
			$response = new \Phalcon\Http\Response();
			$response->setStatusCode(201, "Created");
			$response->send();
		}
		}
		}

		public function checkuserAction () 
		{
		// Action проверки хеша активации

		$h = $_GET['hash'];
		$h = str_replace(" ", "+", $h);
		$userHash = Users::findFirst(array("forget_hash = '$h' and active <> 'A' "));
		//print_r($userHash);
		
		if (is_a( $userHash, 'Users')&& $userHash->forget_hash == $h) 
		{
		
		$userHash->active = "A";
		$userHash->save();

		}
		else 
		{
            $this->flash->error('Ссылка активации не найдена');
		$this->dispatcher->forward(array('controller' => 'user',
            'action' => 'auth' ));
		}
		
		
		}
		
		public function recpwdAction()
		{

            $ip = ip2long($this->request->getClientAddress());
            $count_recpwd = SysEvents::count("source_id = {$user->id} and action = 18 and time > '" . date("Y-m-d H:i:s",time() - 60) . "'");
            if ($count_recpwd > 3) {
                $this->flash->error("С вашего аккаунта превышено колличество запросов на восстановление пароля");
                $int = new IntrusionLog;
                $int->ip = $ip;
                $int->user_id = $user->id;
                $int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                $int->text = "Превышено колличество авторизаций";
                $int->type = 4;
                $int->headers = implode("\n",$this->request->getHeaders());
                $int->save();
                $auth_success = false;
            } else {
                $event = new SysEvents;
                $event->source_id = $user->id;
                $event->text = $this->request->getClientAddress();
                $event->save();


        $formBase;
        $this->view->setVar('formbase' , $formBase);
		$this->assets
			->addCss('css/fw/signin.css')
			->addJs('js/fw/theme.js');

        if (isset($_POST['email'])) {
		$em = $_POST['email'];
		//pre_pr ($_POST);
		$email = User::findFirst ( array ("emai =  '$em'"));
		
		if ($em == $email->email) 
		{
		

		
		}
        }
		}
		
        }
		
		
	public function profileAction()
	{
        $this->view->setTemplateAfter('main');
        $this->assets->addCss($this->url->getBaseUri() . 'css/fw/personal-info.css',true,false,array("media" => "screen"))->addCss($this->url->getBaseUri() . 'css/fw/bootstrap.datepicker.css',true,false,array("media" => "screen"));
        $this->assets
                ->addJs('js/fw/jquery.uniform.min.js')
          ->addJs('js/fw/typeahead.min.js')
                ->addJs('js/fw/theme.js')
          ->addJs('js/fw/bootstrap.datepicker.js')
          ->addJs('js/fw/select2.min.js')
          ->addJs('js/fw/wysihtml5-0.3.0.js');
	}

  public function listAction()
	{
	echo $this->session->get("hits") . "\n";
	echo $this->session->get("time");
	}
	public function officeAction()
	{

	//print_r($this->request->get("skfhsdjf"));
	print_r($this->request->getPost());

	$this->view->setVar("date",$this->session->get("id"));
	}

    public function exitAction()
	{
		if ($this->cookies->has(session_name())){				// Есть ли id сессии в куках?
			if (false == $this->session->isStarted()) {
				$this->session->start();
				$this->session->destroy();				  // Чтобы не поимели подделав session_name()
			}
			$this->cookies->set(session_name(),"");
		}
		$this->response->redirect("user/auth");
	}
}
