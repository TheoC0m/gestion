<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 01/12/17
 * Time: 11:05
 */
use App\User;

return [
	'defaults' => [
		'guard' => 'api',
		'passwords' => 'users',
	],

	'guards' => [
		'api' => [
			'driver' => 'passport',
			'provider' => 'users',
		],
	],

	'providers' => [
		'users' => [
			'driver' => 'eloquent',
			'model' => User::class
		]
	]
];