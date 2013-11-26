<?php

error_reporting(E_ALL);

try {
	set_error_handler(function ($errno, $errstr, $errfile, $errline) {
		$session = new Phalcon\Session\Adapter\Files;
		$error = New Errors;
		$error->user_id = $session->get("id");
		$error->source_line = $errline;
		$error->error_text = $errfile . ' ' . $errstr;
		$error->error_type = "PHP error " . $errno;
		$error->save();
		echo "error " . $errstr;
		die();
	});
	/**
	 * Read the configuration
	 */
	$config = include __DIR__ . "/../app/config/config.php";

	/**
	 * Read auto-loader
	 */
	include __DIR__ . "/../app/config/loader.php";

	/**
	 * Read services
	 */
	include __DIR__ . "/../app/config/services.php";

    include __DIR__ . '/../app/lib/ic_waywe.php';
    include __DIR__ . '/../app/lib/WayweModel.php';

    /**
	 * Handle the request
	 */
	$application = new \Phalcon\Mvc\Application();

	$application->setDI($di);

	echo $application->handle()->getContent();

} catch (\Exception $e) {
	$session = new Phalcon\Session\Adapter\Files;
	$error = New Errors;
	$error->user_id = $session->get("id");
	$error->source_line = $e->getLine();
	$error->error_text = $e->getFile() . ' ' . $e->getMessage();
	$error->error_type = "PHP exception " . $e->getCode();
	$error->save();
	print_r($e);
	die();
} 
