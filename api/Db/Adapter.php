<?php
/*
 * used to save data to sqlite
 */
namespace Db;
class Adapter {
	private $_adapter = null;
	const SQLITE_DB_FILENAME = '/usr/share/nginx/data/fitbit_listener';
	protected static $_instance; 
	protected function __construct() {
		if(!$adapter) {
			$this->_adapter =  new SQLite3(SQLITE_DB_FILENAME);
		}
	}
	public static function getInstance() {
		if (! isset ( self::$_instance )) {
			self::$_instance = new Db\Adapter();
		}
		
		return self::$_instance;
	}
	public function getUserData($user_id) {
		$results = $this->_adapter->query('SELECT * FROM users');
			while ($row = $results->fetchArray()) {
			    print_r($row);
			}
	} 
	public function saveUser($uid, $data) {
		if($uid == null) {
			throw new Exception("Illegal Arguments no $uid");
 		}
 		$statement = $$this->_adapter->exec("Insert into users values ($uid, $data)");
	}
}