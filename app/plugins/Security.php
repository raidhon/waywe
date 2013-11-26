<?php

use Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Acl;

/**
 * Security
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 
 Типы атак:
 1. DDOS
 2. Неверный ввод email/пароля
 3. Попытка входа с запрещенного ip
 4. Превышено колличество авторизаций
 5. Подбор id сессии
 6. Попытка захода на несуществующий контроллер/экшен
 7. Инъекция в get/post
 
 */


 class Security extends Plugin
{
	public $ip , $user_id;
	
	public function __construct($dependencyInjector)
	{
		$this->_dependencyInjector = $dependencyInjector;
	}

	/**
	Функция фильтрации вызывается последовательно для 2-х суперглобальных массивов $_GET и $_POST, и возвращает их же в изменённом виде.
	В случае критичных сбоев она возвращает false, что должно быть обработано в точке вызова.
	@param[in] $inArray Исходный массив для фильтрации
	@param[in] $type GET или POST, на алгоритм не влияет, используется в логировании
	*/
	public function filterGetPost($inArray,$type) {
		$tplFilerKeys = '/[^_0-9a-zA-Z\/]/'; // Допустимые символы для ключей (инвертируются крышкой ("^") в недопустимые)
		// Не удалять!
		// Внимание! классы PCRE на кириллице отрабатывают плохо, т.е. вообще не отрабатывают, аплоад картинок отвалился, потому делаем так.
		$tplFilerVals = '/[^- =_0-9a-zA-Z\'!#$%:&*\/?\^_`{|}~@.АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯІЇЄЃабвгдеёжзийклмнопрстуфхцчшщьыъэюяабвгдеёжзийклмнопрстуфхцчшщьыъэюяіїєѓ]/i'; // Недопустимые символы для значений
		
		// Вот это на кириллице не работает
		//$tplFilerVals = '/[^-[:alpha:][:digit:] !#$%&*\/?\^_`{|}~@.]/i';
		$maxBadReqs = (isset($this->user_id) && $this->user_id) ? 5 : 15;
		
		foreach ($inArray as $key => $value)  
			{
			$inArray[$key] = $value = trim($value);
			
			$newKey = preg_replace($tplFilerKeys, '', $key);
			if($newKey != $key) {
				$badReqs = IntrusionLog::count("type = 7 AND ip = {$this->ip} AND time > '" . date("Y-m-d H:i:s",time() - 60) . "'");
				
				if ($badReqs < $maxBadReqs)
				{
					$int = new IntrusionLog;
					$int->ip = $this->ip;
					$int->user_id = $this->user_id;
					$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
					$int->text = sprintf("Недопустимые символы в ключе %s: [%s]=>[%s]", $type, $key, $value);
					$int->type = 7;													
					$int->headers = implode("\n",$this->request->getHeaders());
					$int->save();
					return false;
					}
				}
				
			$newVal = preg_replace($tplFilerVals, '', $value);
			if($newVal != $value)  {
				$inArray[$key] = $newVal;
				$badReqs = IntrusionLog::count("type = 7 AND ip = {$this->ip} AND time > '" . date("Y-m-d H:i:s",time() - 60) . "'");
				if ($badReqs < $maxBadReqs)
				{
					$int = new IntrusionLog;
					$int->ip = $this->ip;
					$int->user_id = $this->user_id;
					$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
					$int->text = sprintf("Недопустимые символы в значении %s: [%s]=>[%s]", $type, $key, $value);
					$int->type = 7;													// DDOSят
					$int->headers = implode("\n",$this->request->getHeaders());
					$int->save();
					return false;
					}
				}
			}
		return $inArray;
	}

	/**
	 * This action is executed before execute any action in the application
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();
		$params = $dispatcher->getParams();
		$controller_model = $controller_id = $action_id = $type = false;
		$this->ip = ip2long($this->request->getClientAddress());
		
		if ($this->cookies->has(session_name())){										// Есть ли id сессии в куках?
			if (false == $this->session->isStarted()) {
				$this->session->start();
			}
			if ($this->session->has("id")) $this->user_id = $this->session->get("id");// Проверяю, наша ли эта сессия
			else {
				$this->session->destroy();										// Чтобы не поимели подделав xiixix
				$this->cookies->set(session_name(),"");							// В экшене на 1.2.4 работал $this->cookies->delete, тут delete не работал, поэтому написали так. В будущих версиях это надо проверить
				
				$attempts = IntrusionLog::count("type = 5 AND ip = {$this->ip} AND time > '" . date("Y-m-d H:i:s",time() - 60) . "'");
				
				if ($attempts < 5)
				{
					$int = new IntrusionLog;
					$int->ip = $this->ip;
					$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
					$int->text = "Подбор id сессии " . session_name();
					$int->type = 5;													// Подбор id сессии
					$int->headers = implode("\n",$this->request->getHeaders());
					$int->save();
				}
				if ($action != "auth") $this->response->redirect("user/auth");
			}
		}
		
		$controller_model = Controllers::findFirst(array("name = '$controller'","cache" => array("lifetime" => 3600, "key" => "mvc_" . $controller)));
		
		if ($controller_model)
			foreach ($controller_model->getActions(array("cache" => array("lifetime" => 3600, "key" => "mvc_action_" . $controller)))->toArray() as $m) 
				if ($m["name"] == $action) {
					$type = $m["type"];
					$controller_id = $controller_model->id;
					$action_id = $m["id"];
				}
				
		if (false == $action_id || false == $controller_model)
		{
			$attempts = IntrusionLog::count("type = 6 AND ip = {$this->ip} AND time > '" . date("Y-m-d H:i:s",time() - 60) . "'");
			if ($attempts < 5) {
				$int = new IntrusionLog;
				$int->ip = $this->ip;
				if (isset($this->user_id) && $this->user_id) $int->user_id = $this->user_id;
				$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
				$int->text = "Попытка захода на несуществующий контроллер/экшен";
				$int->type = 6;													// Попытка захода на несуществующий контроллер/экшен
				$int->headers = implode("\n",$this->request->getHeaders());
				$int->save();
			}
			return false;
		}
				
		if (!isset($this->user_id) || !$this->user_id) {
			// эта ветка срабатывает, если пользователь не авторизован
			if ("P" != $type) {
				$this->flash->error("Доступ запрещен");							// Чувак, у тебя нехватает прав
				$dispatcher->forward(
					array(
						'controller' => 'user',
						'action' => 'auth',
						'params' => array(
							'controller' => $controller, 
							'action' => $action,
							'params' => $params)
					)
				);
				return false;
			}
			usleep(500000);
		} else { 
			// эта ветка срабатывает, если пользователь авторизован
			if ($this->session->get('subnets')) $allow_by_ip = -1; else $allow_by_ip = 0;							// эту ветку надо отладить!!!
			
			if (is_array($this->session->get('subnets'))) foreach ($this->session->get('subnets') as $subnet) if ($subnet == ($this->ip & $subnet)) $allow_by_ip++;
			
			if ($allow_by_ip < 0) {
				$this->flash->error("Попытка входа с запрещенного ip");
				
				$attempts = IntrusionLog::count("type = 3 AND ip = {$this->ip} AND time > '" . date("Y-m-d H:i:s",time() - 60) . "'");
				if ($attempts < 5) {
					$int = new IntrusionLog;
					$int->ip = $this->ip;
					$int->user_id = $this->user_id;
					$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
					$int->text = "Попытка входа с запрещенного ip";
					$int->type = 3;
					$int->headers = implode("\n",$this->request->getHeaders());
					$int->save();
				}
				$this->cookies->set(session_name(),"");							// В экшене на 1.2.4 работал $this->cookies->delete, тут delete не работал, поэтому написали так. В будущих версиях это надо проверить
				$this->response->redirect("user/auth");
				return false;
			}
			
			$ips = $this->session->get('ip');
			
			if (!isset($ips[$this->ip])) {
				if (count($ips) > 10) unset($ips[key($ips)]);
				$event = new SysEvents;
				$event->source_id = $this->user_id;
				$event->text = "У пользователя сменился ip " . $this->request->getClientAddress();
				$event->action = $action_id;
				$event->save();
				$user = Users::findFirst($this->user_id);
				
				if ("Y" == $this->session->get("get_loc_by_ip")) {																// Если в сессии стоит параметр "Отслеживать мое местоположении"
					if ($zone = Ip2locationDb11::findFirst("{$this->ip} >= ip_from AND {$this->ip} <= ip_to"))									// Обращаюсь к модели описывающей соответствие ip и часового пояса и пытаюсь вычислить часовой пояс
						$ips[$this->ip] = ($zone->time_zone ? $zone->time_zone : ($user->time_zone ? $user->time_zone : "+00:00"));	// Если был успешно вычислен часовой пояс - присваиваю его элементу сессионного массива ips по ключу
																																// ip. Если он не был успешно вычислен, присваиваю - значение, полученное из бд. Если не удалось 
																																// получить и его - присваиваю часовой пояс московского времени - '+00:00'
				} else $ips[$this->ip] = ($user->time_zone ? $user->time_zone : "+00:00");
				
				$this->session->set('ip',$ips);
			}

			if ($this->session->get("time") > time()) return false;	// Дали отлуп. слишком часто жал F5 (или бот??)
			
			if ($this->session->get("hits") > 10) // Отлуп еще не дали, но даем
			{					
				$this->session->set("time",time() + 30);			// на 30 секунд
				$this->session->set("hits",0);						// если этого не сделать, отлуп будет навсегда))
				
				$attempts = IntrusionLog::count("type = 1 AND ip = {$this->ip} AND time > '" . date("Y-m-d H:i:s",time() - 30) . "'");
				if ($attempts < 5) {								// Не засираем журнал вторжений
					$int = new IntrusionLog;
					$int->ip = $this->ip;
					$int->user_id = $this->user_id;
					$int->url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
					$int->text = "DDOS";
					$int->type = 1;													// DDOSят
					$int->headers = implode("\n",$this->request->getHeaders());
					$int->save();
				}
				return false;
			}
			
			if ($this->session->get("time") + 30 < time() && $this->session->get("hits") < 10) {
				$this->session->set("hits",0);
				$this->session->set("time",time());
			}
			
			$this->session->set("hits",$this->session->get("hits") + 1);
			
			switch ($type) {
				case "D": if ($this->session->get("class") < 2) return false; break; 
				case "R": if ($this->session->get("class") < 3) return false; break;
			}
			if ("R" == $type)
			{
				$acl = $this->session->get("acl");
				$allowed = $acl->isAllowed("User", $controller, $action);
				if ($allowed != Acl::ALLOW) return false;	
			}
			
		}

		// Проверка get/post
		
		$tmpGet = $this->filterGetPost($_GET, "GET");
		if($tmpGet === false)
			return false;
		else
			$_GET = $tmpGet;
		
		$tmpPost = $this->filterGetPost($_POST, "POST");
		if($tmpPost === false)
			return false;	
		else
			$_POST = $tmpPost;
	}
}
