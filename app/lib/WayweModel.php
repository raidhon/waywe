<?php

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Validator\Regex as RegexValidator;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\InclusionIn;
use Phalcon\DI\FactoryDefault;


/**
Добавленная функциональность относительно стандартной фальконовской модели:

-- Метаинформация о человекочитаемых названиях таблицы и её колонок

Сейчас каждый конкретный класс модели запрашивает метаинформацию для себя сам и хранит её в $mi_info с ключом из своего имени.
Можно в дальнейшем избрать другую стратегию запросов -- при первом создании экземпляра любой модели класса wayweModel
прочитывать в $mi_info информацию по _всем_ моделям в базе. Но пока не вижу в этом смысла, поскольку метаинформация нам нужна не для
всех таблиц. С другой стороны, в текущей реализации при создании всякой модели такая информация всё равно считывается, что заставляет задуматься 
об однократном считывании с экономией количества запросов.
*/
class WayweModel extends \Phalcon\Mvc\Model
{
	
	//!	Использовать ли валидацию при сохренении данных? По умолчанию -- да
	protected $use_validation = true;
	
	/**
	Переменная для кэширования метаинформации таблицы на уровне статических членов класса
	Поскольку она разделяется _всеми_ моделями, наследующими wayweModel, она индексируется именем таблицы, получаемым из Model::getSource()
	
	Структура переменной $mi_info, версия 0.01 от 13:13 23.11.2013
	1) Имя таблицы, $this->tab_name -- индекс верхнего уровня, вся остальная информация вкладывается уже внутрь подмассива-хэша с этим индексом. 
	Иными словами, всякая конкретная модель работает не с $mi_info, а с $mi_info[$this->tab_name]
	Этот индекс мы получаем так: $this->tab_name = $this->getSource();
	
	2) Внутри $mi_info[$this->tab_name] лежит хэш с _пока_ единственным ключом -- 'columns'. Можно было бы не заворачивать внутрь 'columns',
	но я предполагаю возможность появления новых ключей для таблицы, не являющихся поколоночной информацией, а характеризующих таблицу в целом, 
	поэтому данный индекс не убираю
	
	3) Внутри ['columns'] лежит хэш с доступом по именам колонок, по элементу для каждой. Будем в дальнейшей записи отмечать этот уровень как [<cur_col>],
	текущая колонка
	
	4) Внутри [<cur_col>] на данный момент есть 2 необязательных поля: [show_name] и [validations]. 
	[show_name] содержит имя, предназначенное для вывода и используется в частности в системе сообщений об ошибках при валидации.
	[validations] -- хэш со следущими ключами: ("флаг" обозначает значение без параметров, важно то, что он _задан_)
		're'		-- регулярное выражение, создаёт валидатор RegexValidator
		'unique'	-- флаг, создаёт валидатор UniquenessValidator
		'email'		-- флаг, создаёт валидатор EmailValidator
		'incl'		-- получает массив для валидатора InclusionIn, этот массив идёт в параметр 'domain' этого валидатора
		'mustbe'	-- флаг, создаёт валидатор PresenceOf
	*/
	
	protected static $mi_info = NULL;
	
	//! Имя таблицы модели. Его заполненность используется как маркер инициализации $mi_info для данной модели
	protected $tab_name = NULL;

	//! Id в таблице метаинформации
	protected $mi_tabId = NULL;
	
	//! Имя таблицы модели
	protected $show_name = NULL;
	
	/**
	Метод принудительно перегружает метаинформацию
	Ничего не возвращает
	*/
	protected function mi_reload()
	{
        $config = $this->getDi()->getShared('config');

        print('I am here: ' . __FILE__ . '<br>');
//        pre_dump($config);
        //pre_dump($config->regexps->toArray());

        /*
        $vals = $colVal['validations'];
        $curFldShowName = $colVal['show_name'];
        */

        $cfgRe = $config->regexps->toArray();
        if(!is_array(self::$mi_info))
			self::$mi_info = array();
		$this->tab_name = $this->getSource();
		$tabInfo = MiTabShowNames::findFirst(array("tab_name = '$this->tab_name'", "cache" => array("lifetime" => 86400, "key" => "{$this->tab_name}_id")))->toArray();
		$this->mi_tabId = $tabInfo['id'];
		$this->show_name = $tabInfo['show_name'];

		$colInfo = MiColShowNames::find(array(
			//"tab_id = {$tabInfo['id']}", 
			"tab_id = {$this->mi_tabId}", 
			"columns" => 'col_name, show_name, validations',
			"cache" => array("lifetime" => 86400, "key" => "{$this->tab_name}_fields")))->toArray();
		
	
		$aColInfo = array();
		foreach($colInfo as $curColInfo)
		{
			$colName = $curColInfo['col_name'];
			$showColName = $curColInfo['show_name'];
			if(empty($showColName))
			{
				$showColName = $colName;
				//$showColName = sprintf("[%s] - Ошибка, имя для показа не определено!", $colName);
				// Здесь мы будем логировать ошибку
				//$error = New Errors;
				//$error->source_line = 0;
				//$error->error_text = "Нет записи в таблице MiColShowNames о поле $colName";
				//$error->error_type = "Ошибка работы с моделью";
				//$error->save();
			}
			$aColInfo[$colName] = array('show_name' => $showColName);
			if(!empty($curColInfo['validations'])) {
				//$aColInfo[$colName]['validations'] = unserialize($curColInfo['validations']);
				$aVals = unserialize($curColInfo['validations']);
				if(isset($aVals['re'])) {
					$reVal = braced($aVals['re']);
					if(!is_null($reVal)) {
						if(isset($cfgRe[$reVal]))
							$aVals['re'] = $cfgRe[$reVal];
						else {
							$error = New Errors;
							$error->user_id = $session->get("id");
							$error->source_line = __LINE__;
							$error->error_text = __FILE__ . ' : ' . $curColInfo['col_name'] . " содержит ссылку на несуществующий регэксп в конфиге: [$reVal]";
							$error->error_type = "Regexp error";
							$error->save();
							echo  "Regexp error, template '$reVal'";
							die();
						}
					}
				}
				$aColInfo[$colName]['validations'] = $aVals;
			}
		}
		self::$mi_info[$this->tab_name]['columns'] = $aColInfo;
	}
	
	
	/**
	Отключить валидацию
	*/
	public function offValidation()
	{
		$this->use_validation = false;
		return $this;
	}

	/**
	Разрешает или запрещает валидацию выставлением внутреннего флага
	@param[in] $onOff булевское значение, true -- делать валидацию, false -- не делать
	*/
	public function valOnOff($onOff)
	{
		$this->use_validation = $onOff;
		return $this;
	}
	
	
	/**
	Конструктор предка пока вызывается до основного кода. Потом при необходимости изменим.
	Хренушки нам дадут его вызвать:
	
	Fatal error: Cannot override final method Phalcon\Mvc\Model::__construct() in /home/www/waywe/app/lib/WayweModel.php on line 75
	
	function __construct() 
	{
	parent::__construct();
	if(!self::$mi_info)
		$this->mi_reload();
	}
	*/
	
	
	/**
	Метод является заглушкой, его код или его вызов в дальнейшем пойдёт в конструктор --
	если мы, конечно, найдём способ создать этот конструктор или заменить его )))
	*/
	public function _init()
	{
		// Старое условие -- пока оставим здесь на всякий случай, может пригодиться )))
		// if(!self::$mi_info)

		if(!isset($this->tab_name))
			$this->mi_reload();
	}
	
	/**
	Возвращает отображаемое имя текущей модели на основе запроса к метаинформации из mi_tab_show_names
	@return строка -- показываемое имя таблицы
	*/
	public function getLocalName()
	{
		if(!isset($this->tab_name))
			$this->mi_reload();
		return $this->show_name;
	}

	public function getTest01()
	{
		if(!isset($this->tab_name))
			$this->mi_reload();
		return self::$mi_info[$this->tab_name];
	}

	/**
	Получить показываемые имена колонок для таблицы текущей модели 
	@return хэш: имя_в_модели => показываемое_имя
	*/
	public function getLocalFieldNames()
	{
		if(!isset($this->tab_name))
			$this->mi_reload();
		$res = array();
		$aSz = count(self::$mi_info[$this->tab_name]['columns']);
		foreach(self::$mi_info[$this->tab_name]['columns'] as $key => $val)
				$res[$key] = $val['show_name'];
		return $res;
	}
	
	/**
	Назначает валидаторы для модели на основе массива описания валидации
	*/
	
	public function setValidators()
	{
		if(!isset($this->tab_name))
			$this->mi_reload();
		
		foreach(self::$mi_info[$this->tab_name]['columns'] as $colName => $colVal)
        {
	       $curFldShowName = $colVal['show_name'];

	       if (isset($colVal['validations']))
	       {
	            $vals = $colVal['validations'];



	            if(isset($vals['re']))
					$this->validate(new RegexValidator(array(
						'field' => $colName,
						'message' => sprintf('Недопустимые символы в поле \'%s\'.', $curFldShowName),
						'pattern' => $vals['re']
						)));

				if(isset($vals['unique']))
					$this->validate(new UniquenessValidator(array(
						'field' => $colName,
						'message' => sprintf('Значение в поле \'%s\' не уникально.', $curFldShowName)
						)));

				if(isset($vals['email']))
					$this->validate(new EmailValidator(array(
						'field' => $colName,
						'message' => sprintf('Значение в поле \'%s\' не допустимо для e-mail.', $curFldShowName)
						)));

				if(isset($vals['incl']))
					$this->validate(new InclusionIn(array(
						'field' => $colName,
						'domain' => $vals['incl'],
						'message' => sprintf('Недопустимый выбор в поле \'%s\'.', $curFldShowName)
						)));

				if(isset($vals['mustbe']))
					$this->validate(new PresenceOf(array(
						'field' => $colName,
						'message' => sprintf('Поле \'%s\' должно быть задано.', $curFldShowName)
						)));
				//pre_pr($colName, $colVal['validations']);
				//printf("Key"echo($key . '<br/>');
			}
        }
	}
	
	
	public function validation()
	{
		if($this->use_validation)
		{
					
			$this->setValidators();
		
			if ($this->validationHasFailed() == true) {
				return false;
			}
		} else return true;
	}
	
	
}

?>