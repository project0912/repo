<?php

/**
 * connector to the mongo database
 *
 * A singleton to connect to mongo database
 * Do not remove this file
 * copy with the name MDB.php and change what you want
 * MDB.php under git ignore (see .gitignore)
 *
 * @category    connector
 * @author      Dmytro
 * @since       0.0.1
 */
class MDB{
	protected static $instance;
	public static function alloc(){
		if(!self::$instance) self::$instance = new Mongo();
		$db = self::$instance->selectDB(MONGO_noSQL);
		return $db;
	}
}