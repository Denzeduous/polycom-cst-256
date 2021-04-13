<?php

// Polycom v0.1
// DBConnector v0.1
// Creates and destroyed connections to the database.

namespace App\Service\Generic;

class DBConnector {
	public static function GetConnection () {
		return mysqli_connect (
			$_ENV ['DB_HOST'    ],
			$_ENV ['DB_USERNAME'],
			$_ENV ['DB_PASSWORD'],
			$_ENV ['DB_DATABASE'],
			$_ENV ['DB_PORT'],
		);
	}
	
	public static function CloseConnection ($conn) {
		mysqli_close ($conn);
	}
}

