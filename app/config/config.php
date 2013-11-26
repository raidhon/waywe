<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => 'Mysql',
		'host'        => 'localhost',
		'username'    => 'root',
		'password'    => '2501',
		'dbname'      => 'waywe'
	),
	'application' => array(
		'controllersDir' => __DIR__ . '/../../app/controllers/',
		'modelsDir'      => __DIR__ . '/../../app/models/',
		'viewsDir'       => __DIR__ . '/../../app/views/',
		'pluginsDir'     => __DIR__ . '/../../app/plugins/',
		'libraryDir'     => __DIR__ . '/../../app/library/',
		'cacheDir'       => __DIR__ . '/../../app/cache/',
		'baseUri'        => '/waywe/',
	),
	'regexps' => array(
		'fio' => '/^[- 0-9a-zA-Z\'\.АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯІЇЄЃабвгдеёжзийклмнопрстуфхцчшщьыъэюяабвгдеёжзийклмнопрстуфхцчшщьыъэюяіїєѓ]+$/i',
		'date' => '/^\d{4}\-\d{2}\-\d{2}$/'
		)
));
