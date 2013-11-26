<?php

use Phalcon\DI\FactoryDefault,
	Phalcon\Mvc\View,
	Phalcon\Mvc\Url as UrlResolver,
	Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
	Phalcon\Mvc\View\Engine\Volt as VoltEngine,
	Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
	Phalcon\Session\Adapter\Files as SessionAdapter;


//require_once('ic_waywe.php');

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

$di->set('config', $config);
/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function() use ($config) {

	$view = new View();

	$view->setViewsDir($config->application->viewsDir);

	$view->registerEngines(array(
		'.volt' => function($view, $di) use ($config) {

			$volt = new VoltEngine($view, $di);

			$volt->setOptions(array(
				'compiledPath' => $config->application->cacheDir,
				'compiledSeparator' => '_'
			));

			return $volt;
		},
		'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	));

	return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
	return new DbAdapter(array(
		'host' => $config->database->host,
		'username' => $config->database->username,
		'password' => $config->database->password,
		'dbname' => $config->database->dbname,
		"options" => array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	)));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function() {
	return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function() {
	$session = new SessionAdapter();
	return $session;
});


$di->set('dispatcher', function() use ($di) {

        $eventsManager = $di->getShared('eventsManager');

        $security = new Security($di);

                /**
                 * We listen for events in the dispatcher using the Security plugin
                 */
        $eventsManager->attach('dispatch', $security);

        $dispatcher = new Phalcon\Mvc\Dispatcher();
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
});

      $di->set('flash', function(){
                return new Phalcon\Flash\Direct(array(
                        'error' => 'alert alert-error',
                        'success' => 'alert alert-success',
                        'notice' => 'alert alert-info',
                ));
        });

$di->set('modelsCache', function() {

    //Cache data for one day by default
    $frontCache = new \Phalcon\Cache\Frontend\Data(array(
        "lifetime" => 86400
    ));

    //Memcached connection settings
    $cache = new \Phalcon\Cache\Backend\Memcache($frontCache, array(
        "host" => "localhost",
        "port" => "11211"
    ));

    return $cache;
});

$di->set('crypt', function() {
    $crypt = new Phalcon\Crypt();
    $crypt->setKey('dngdfjhgudfhngidfg'); // Используйте свой собственный ключ!
    return $crypt;
});




//$di->set('wConf', $wConf);


