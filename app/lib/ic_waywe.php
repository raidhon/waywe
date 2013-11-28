<?php

/**
������� ��������� �������� � �������, ���� � �� �� ���� ����� ��������, � ������������ ��� ����, � ���������� ������,
���� ��� ��� ����.
@param[in,out] $arr ������, � �������� �� ��������� ��������
@param[in] $val �������� ��� ����������
@param[in] $lim ������ ����� �������. ���� -1 -- �� �����������, ������ ����� �������������.
@return ����� ����� ����� �������� � �������, ��� -1 ��� ������ -- ��������, ���� ���������� $arr �� ���� ��������.
*/
function aAddUp(&$arr, $val, $lim = -1)
{
if(!is_array($arr))
 return -1;
$a_sz = count($arr);
$res = array_search($val, $arr);
// ���������: 1) ������� �� ��������; 2) �� �� ��������� �� ��� _���_ �����
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
// ��� ����� ����� ������ -- ����� ������� ����� �������� � ����� �������
$res = $a_sz - 1;
return $res;
}

/**
������� �������� �������� $val � ������ �������. ���� ��� �������� ��� �������������� � �������, ��� ������������ � ������
@param[in] $arr
@param[in] $val
@return ���������� ���������������� ������
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
������� ������������� _�����_ ����� ���������� �� ���������� � ������� var_dump,
�������� ���� � ���� <pre>...</pre>
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
������� ������������� _�����_ ����� ���������� �� ���������� � ������� print_r,
�������� ���� � ���� <pre>...</pre>
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
������� ��������� ������������ ������� - �.�. ��, ��� �� ���������������
��������������� �� 0 �� count($a) - 1
@param[in] $a �������� ������
@return true, ���� �������� �������� ���������� ��������,
� false - � ��������� ������
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
  // ����� ��������� _��_������������_, ����� �� ������ ���������� ���� ������
  // � ����� � �������� ����, � ���������� ���� ����� �����
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
������� ���������� �� ��������� JSON Escape-������������������
� ������-���������.
@param[in] $s ������-��������
@return ��������������� ������
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
������ json_encode ��� ������� ����������� (������) � ������ � �����������,
��������� �� UTF
@param[in] $a ���������� ������
@param[in] $indStr ������ ������������ �������, � ������ ������ ������ -- ���������� ����������� ���������� JSON
@param[in] $indDepth ������� �������
@return ���������� ��������� ������������� Json
*/
function ic_jsonEncode($a, $indStr = '  ', $indDepth = 0)
{
// ���������� �����������
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
   // ��� �������
   $aBrack='[]';
   $res = array();
   foreach($a as $val)
    $res[] = ic_jsonEncode($val, $indStr, $indDepth+1);
   // ����� ���������� ������� - ���� �� ����������
   }
  else
   {
   // ��� �������
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
    // ����� �� ����� ��������� ������
    // ��� ���������� -- ��� ������� �����
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
������� ������� �������, ���� ���� ����, � �������� �� ��������� -
���� ������ ����� � ������� ���.
@param[in] $arr - ������, � ������� ���� ����
@param[in] $key - ������� ����
@param[in] $def - ��������, ������������, ���� ���� �� ������
@return �������� ������� �� �����, ���� ���� ���� ����,
� $def - � ��������� ������
*/
function ic_ArrKeyDef($arr, $key, $def = '')
{
$res = $def;
if(is_array($arr) && array_key_exists($key, $arr))
  $res = $arr[$key];
return $res;
}


/**
������� ���� ���������, ����������� � ���� �� ������������ � ������������ �������
@param[in] $s �������� ������
@param[in] $br -- ���� �� ������������ � ������������ ��������, ��������, '{}', ��� ��������� ����� ���, ��������, '{}..[]()<>'. 
	����������� � ����������� ������� ����� ���������, ��� �� ������� ������� ������� -- ������� �� ��������� "��������� ������"
	� ������, � ������� ������ ������� ������� ����� trim()
	
@return ������ ����� ��������� $br, ���� $s -- ������, ���������� ��������� $br, � NULL � ��������� ������
@remark �� ���� ���������� �������� $s ������������ trim()
@TODO
�������� ����������� ���������� � ��������� $br ����� ����� ���� ��������
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
����� �� stackoverflow
���������� ������� ������ � ������ �� ������� ������� ������
@param[in] $object ������ �� ������ (��������, ������� � ��� �������, �� �������� -- ���� ������ method_exists, ����� ������� �����������) 
@param[in] $method ��� ������
*/
function parent_method_exists($object, $method)
{
foreach(class_parents($object) as $parent)
	if(method_exists($parent, $method))
	   return true;
return false;
}


?>