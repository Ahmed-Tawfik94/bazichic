<?php

use Monolog\Logger;

	return [
			'settings' => [
				'displayErrorDetails' =>  $_ENV['APP_DEBUG'] === "1",
				 // Only set this if you need access to route within middleware
                'determineRouteBeforeAppMiddleware' => true,
                'appName' => $_ENV['APP_NAME'],
				'logger' => [
					'name' => 'slim-app',
					'level' => Logger::DEBUG,
					'path' => __DIR__ . '/../logs/app.log',
				],
				// 'view' => [
				// 	'path' => __DIR__ . '/resources/views',
				// 	'twig' => [
				// 	'cache' => false
				// 	]
				// ],
			]
	];
