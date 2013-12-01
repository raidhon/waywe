<?php

// Почему-то файл хранился в вин-кодировке, надо перепушить в UTF-8

/**
Функция добавляет значение к массиву, если в нём не было этого значения, и переставляет его вниз, к последнему адресу,
если оно там было.
@param[in,out] $arr Массив, к которому мы добавляем значение
@param[in] $val Значение для добавления
@param[in] $lim Предел роста массива. Если -1 -- не проверяется, массив растёт неограниченно.
@return новое место этого значения в массиве, или -1 при ошибке -- например, если переменная $arr не была массивом.
*/
function aAddUp(&$arr, $val, $lim = -1)
{
if(!is_array($arr))
 return -1;
$a_sz = count($arr);
$res = array_search($val, $arr);
// Проверяем: 1) найдено ли значение; 2) не на последнем ли оно _уже_ месте
if($res === FALSE)
 {
 $arr[] = $val;
 if($a_sz == $lim)
  array_shift($arr);
 else
  $a_sz++;
 }
else if($res != $a_sz - 1)
 {
 $tmp = $arr[$res];
 $arr[$res] = $arr[$a_sz-1];
 $arr[$a_sz-1] = $tmp;
 }
// Это будет верно всегда -- новый элемент будет заведомо в конце массива
$res = $a_sz - 1;
return $res;
}

/**
Функция помещает значение $val в начало массива. Если это значение уже присутствовало в массиве, оно перемещается в начало
@param[in] $arr
@param[in] $val
@return Возвращает модифицированный массив
*/
function aValToFront($arr, $val)
{
$res = array_search($val, $arr);
if($res === FALSE)
  array_unshift($arr, $val);
else if($res != 0) {
  $tmp = $arr[$res];
  $arr[$res] = $arr[0];
  $arr[0] = $tmp;
  }
return $arr;
}


/**
Функция распечатывает _любое_ число переданных ей параметров с помощью var_dump,
заключая дамп в теги <pre>...</pre>
*/
// class DebTools
function pre_dump()
{
$args = func_get_args();
echo ('<pre>');
foreach($args as $val)
 var_dump($val);
echo ('</pre>');
}

/**
Функция распечатывает _любое_ число переданных ей параметров с помощью print_r,
заключая дамп в теги <pre>...</pre>
*/
// class DebTools
function pre_pr()
{
$args = func_get_args();
echo ('<pre>');
foreach($args as $val)
 print_r($val);
echo ('</pre>');
}

/**
Функция проверяет ординарность массива - т.е. то, что он последовательно
проиндексирован от 0 до count($a) - 1
@param[in] $a Исходный массив
@return true, если аргумент является ординарным массивом,
и false - в противном случае
*/
function ic_is_ordarr($a)
{
if(!is_array($a))
 $res = false;
else
 {
 $res = true;
 $i = 0;
 foreach($a as $key => $val)
  {
  // Нужно сравнение _на_идентичность_, иначе он делает приведение типа строки
  // к числу и получает ноль, в результате чего имеем херню
  if($key !== $i)
   {
   $res = false;
   break;
   }
  else
   $i++;
  }
 }
return $res;
}

/**
Функция экранирует по стандарту JSON Escape-последовательности
в строке-аргументе.
@param[in] $s строка-аргумент
@return преобразованная строка
*/
function ic_jsonEscape($s)
{
$aStrSrch = array(
	"'",   
	"\"",  
	"\\",  
	"/",   
	"\x08",
	"\n",  
	"\r",  
	"\t",  
	"\f"  
	);

$aStrRepl = array(
  "\\'", 
  "\\\"",
  "\\\\",
  "\\/", 
  "\\b", 
  "\\n", 
  "\\r", 
  "\\t", 
  "\\f"  
  );

$aRepl = array
 (
 "\"" => "\\\"",
 "\\" => "\\\\",
 "/"  => "\\/",
 "\x08" => "\\b",
 "\n" => "\\n",
 "\r" => "\\r",
 "\t" => "\\t",
 "\f" => "\\f"
 );
if(is_string($s))
 $res = str_replace($aStrSrch, $aStrRepl, $s);
else
 $res = $s;
return $res;
}

/**
Замена json_encode для большей наглядности (отступ) и работы с кодировками,
отличными от UTF
@param[in] $a Кодируемый объект
@param[in] $indStr строка однократного отступа, в случае пустой строки -- возвращаем максимально компактный JSON
@param[in] $indDepth глубина отступа
@return полученное текстовое представление Json
*/
function ic_jsonEncode($a, $indStr = '  ', $indDepth = 0)
{
// Экранируем спецсимволы
$aStrSrch = array(
	"'",   
	"\"",  
	"\\",  
	"/",   
	"\x08",
	"\n",  
	"\r",  
	"\t",  
	"\f"  
	);

$aStrRepl = array(
  "\\'", 
  "\\\"",
  "\\\\",
  "\\/", 
  "\\b", 
  "\\n", 
  "\\r", 
  "\\t", 
  "\\f"  
  );

$aType = gettype ($a);
//$res = '';
switch ($aType)
 {
 case 'boolean':
  $res = $a ? 'true' : 'false';
  break;
 case 'integer':
 case 'double':
  $res = (string) $a;
  break;
 case 'string':
  //$res = sprintf('"%s"', ic_jsonEscape($a));
  $res = sprintf('"%s"', 
    str_replace($aStrSrch, $aStrRepl, $a));
  break;
 case 'array':
  $aOrd = ic_is_ordarr($a);
  if($aOrd)
   {
   // Для массива
   $aBrack='[]';
   $res = array();
   foreach($a as $val)
    $res[] = ic_jsonEncode($val, $indStr, $indDepth+1);
   // Очень упрощенная склейка - лишь бы заработало
   }
  else
   {
   // Для объекта
   $aBrack='{}';
   $res = array();
   foreach($a as $key => $val)
    {
    $cur_val = sprintf('"%s":%s',
     //ic_jsonEscape($key),
     str_replace($aStrSrch, $aStrRepl, $key),
     ic_jsonEncode($val, $indStr, $indDepth+1));
    $res[] = $cur_val;
    }
   }
  
  if($indStr == '')
   	$res = $aBrack[0] . implode(',', $res) . $aBrack[1];
  else
    {
    // Здесь мы будем вписывать индент
    // Так элегантнее -- нет лишнего цикла
    $curIndStr = str_repeat ($indStr, $indDepth);
    $res = $curIndStr . $aBrack[0] . CR . $curIndStr
     . implode(',' . CR . $curIndStr, $res)
     . CR . $curIndStr . $aBrack[1]; //  . CR;
    if($indDepth > 0)
     $res = CR . $res;
    }
 }
return $res;
}


/**
Вернуть элемент массива, если ключ есть, и значение по умолчанию -
если такого ключа в массиве нет.
@param[in] $arr - массив, в котором ищем ключ
@param[in] $key - искомый ключ
@param[in] $def - значение, возвращаемое, если ключ не найден
@return значение массива по ключу, если этот ключ есть,
и $def - в противном случае
*/
function ic_ArrKeyDef($arr, $key, $def = '')
{
$res = $def;
if(is_array($arr) && array_key_exists($key, $arr))
  $res = $arr[$key];
return $res;
}


/**
Функция ищет подстроку, заключённую в пару из открывающего и закрывающего символа
@param[in] $s исходная строка
@param[in] $br -- пара из открывающего и закрывающего символов, например, '{}', или несколько таких пар, например, '{}..[]()<>'. 
	Открывающий и закрывающий символы могут совпадать, это не создаст никаких проблем -- функция не выполняет "глубокого поиска"
	в строке, а смотрит только крайние символы после trim()
	
@return Строка между символами $br, если $s -- строка, обрамлённая символами $br, и NULL в противном случае
@remark До всех вычислений аргумент $s подвергается trim()
@TODO
Дописать возможность передавать в параметре $br более одной пары символов
*/
function braced($s, $br = '{}')
{
$res = NULL;
if(is_string($s))
	{
	$s = trim($s);
	$l = strlen($s);
	$l_br = strlen($br);
	if($l < 2 || $l_br < 2 || $l_br % 2 != 0)
		return $res;
	$ch1 = $s[0]; 
	$ch2 = $s[$l-1]; 
	$nIter = $l_br / 2;
	for($i = 0; $i < $nIter; $i++)
		if($ch1 == $br[$i*2] && $ch2 == $br[$i*2 + 1])
			{
			$res = substr($s, 1, $l - 2);
			break;
			}
	}
return $res;
}

/**
Взято из stackoverflow
Определяет наличие метода у любого из предков данного класса
@param[in] $object ссылка на объект (возможно, годится и имя объекта, не проверял -- надо копать method_exists, через который реализовано) 
@param[in] $method имя метода
*/
function parent_method_exists($object, $method)
{
foreach(class_parents($object) as $parent)
	if(method_exists($parent, $method))
	   return true;
return false;
}


?>