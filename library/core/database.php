<?php

class Database {
	
	// Constructor - connects to database.
	
	public function __construct() {
		require(ROOT.DS.'config'.DS.'database.php');
		if (!$this->connect(DB_USER, DB_PASS))
			fatalerror('Unable to connect to database.');
	}
	
	
	/* * * * * * *
	 QUERY HANDLING
	* * * * * * */
	
	// Fetches the results from an SQL query with the given parameters. If classname specified, will create objects of that class.
	
	public function fetch($query, array $paramvars = array(), array $paramtypes = array(), $classname = NULL, array $constructargs = array()) {
		Debug::note('Database: Fetching query "'.$query.'"');
		if (!empty($paramvars)) Debug::note('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Binding parameters '.implode(',', $paramvars));
		try {
			$statement = $this->buildstatement($query, $paramvars, $paramtypes);
			$statement->execute();
			if ($classname !== NULL && class_exists($classname)) {
				return $statement->fetchAll(PDO::FETCH_CLASS, $classname, $constructargs);
			} else {
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
		} catch(PDOException $e) {
			throw new QueryException($query.': '.$e->getMessage());
		}
	}
	
	// Executes an SQL query with the given parameters.
	
	public function execute($query, array $paramvars = array(), array $paramtypes = array()) {
		Debug::note('Database: Executing query "'.$query.'"');
		try {
			$statement = $this->buildstatement($query, $paramvars, $paramtypes);
			$statement->execute();
			return $statement;
		} catch(PDOException $e) {
			throw new QueryException($query.': '.$e->getMessage());
		}
	}
	
	// Retrieves the primary key (id) of the last inserted record.
	
	public function insertid() {
		try {
			return $this->Driver->lastInsertId();
		} catch (PDOException $e) {
			throw new QueryException('No records inserted, unable to find last inserted id.');
		}
	}
	
	
	/* * * * * * * * * * *
	 TRANSACTION HANDLING
	* * * * * * * * * * */
	
	// Begins a transaction.
	// No database changes will be finalized until committransaction() is called.
	
	public function begintransaction() {
		try {
			return $this->Driver->beginTransaction();
		} catch (PDOException $e) {
			throw new QueryException('Unable to begin transaction.');
		}
	}
	
	// Discards any database changes that occurred in the current transaction.
	// Also ends the current transaction.
	
	public function discardtransaction() {
		try {
			return $this->Driver->rollBack();
		} catch (PDOException $e) {
			throw new QueryException('Unable to roll back transaction.');
		}
	}
	
	// Commits all database changes that occurred in the current transaction.
	// Also ends the current transaction.
	
	public function committransaction() {
		try {
			return $this->Driver->commit();
		} catch (PDOException $e) {
			throw new QueryException('Unable to commit transaction.');
		}
	}
	
	
	/* * * *
	 BACKUP
	* * * */
	
	// Returns a string containing all the necessary SQL information to recreate and repopulate all the tables in this database.
	// Also disables foreign key checks to allow for InnoDB tables to be rebuilt without foreign key errors.
	
	public function backup() {		
		$output = "SET FOREIGN_KEY_CHECKS=0;\n\n";
		$Tables = $this->fetchtables();
		foreach ($Tables as $Table) {
			$output .= $Table->backup();
		}
		return $output."SET FOREIGN_KEY_CHECKS=1;";
	}
	
	// Retrieves the names of all the tables in this database.
	// Returns an array of Table objects.
	
	public function fetchtables() {
		$result = $this->fetch('SHOW TABLES');
		$tables = array();
		foreach ($result as $row) {
			$tables[] = new Table(array_shift($row));
		}
		return $tables;
	}
	
	// Escapes a string for use with raw SQL queries.
	//	$str (string) - string to be escaped to preserve SQL syntax validity.
	
	public function escape($str) {
		return $this->Driver->quote($str);
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $Driver;
	
	protected function connect($user, $pass) {
		try {
			$this->Driver = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, $user, $pass);
			if (DEVELOPMENT == true) $this->Driver->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return true;
		} catch(PDOException $e) {
			return false;
		}
	}
	
	protected function buildstatement($query, array $paramvars, array $paramtypes) {
		$statement = $this->Driver->prepare($query);
		$count = count($paramvars);
		for ($i=0; $i<$count; $i++) {
			$this->bindstatement($statement, ':param'.$i, $paramvars[$i], $paramtypes[$i]);
		}
		return $statement;
	}
	
	protected function bindstatement($statement, $param, $var, $type = NULL) {
		switch ($type) {
			case 'i':
				$datatype = PDO::PARAM_INT;
				break;
			case 'b':
				$datatype = PDO::PARAM_BOOL;
				break;
			case 'n':
				$datatype = PDO::PARAM_NULL;
				break;
			case 's':
			default:
				$datatype = PDO::PARAM_STR;
		}
		return $statement->bindParam($param, $var, $datatype);
	}	
	
}

?>