<?php

return array(

	/**
	 * Configuration Name
	 *
	 * You use this name when initializing a new MangoDB instance
	 *
	 * $db = MangoDB::instance('default');
	 */
	'default' => array(

		/**
		 * Connection Setup
		 * 
		 * See http://www.php.net/manual/en/mongo.construct.php for more information
		 *
		 * or just edit / uncomment the keys below to your requirements
		 */
		'connection' => array(

			/** hostnames, separate multiple hosts by commas **/
			'hostnames' => 'localhost',

			/** database to connect to **/
			'database'  => 'blog',

			/** authentication **/
			'username'  => 'sriedel',
			'password'  => 'XGM7xPJvkfKN2cZ3GZnk5owXj',

			/** connection options (see http://www.php.net/manual/en/mongo.construct.php) **/
			//'options'   => array(
				// 'persist'    => 'persist_id',
				// 'timeout'    => 1000, 
				// 'replicaSet' => TRUE
			//)
		),

		/**
		 * Whether or not to use profiling
		 *
		 * If enabled, profiling data will be shown through Kohana's profiler library
		 */
		'profiling' => TRUE
	),
	'demo' => array(
		'connection' => array(
			'hostnames' => 'localhost',
			'database'  => 'demo'
		)
	)
);