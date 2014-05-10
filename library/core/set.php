<?php

class Set implements Iterator, Serializable {
	
	public $Paginator;
	
	public $table;
	public $primarykey;
	
	// Constructor - sets table name and primary key and loads dependencies.
	
	public function __construct($modelname) {
		$this->Database = Factory::get('Database');
		if (!class_exists($modelname) || !is_subclass_of($modelname, 'Model')) {
			throw new RelationshipException($modelname.' is not a valid Model.');
		}
		$this->table = $modelname;
		$this->primarykey = $modelname.'_id';
	}
	
	
	/* * * * * * * * * * * *
	 DATA STRUCTURE EXTRACTS
	* * * * * * * * * * * */
	
	// Returns an array map in the format of primary key => selected field value.
	// If parent specified will use a field from an associated parent.
	
	public function arraymap($fieldname, $parent = NULL) {
		$arraymap = array();
		foreach ($this as $id => $Model) {
			if ($parent) {
				$arraymap[$id] = $Model->$parent->$fieldname;
			} else {
				$arraymap[$id] = $Model->$fieldname;
			}
		}
		return $arraymap;
	}
	
	
	/* * * * * * * * *
	 QUERY PREPARATION
	* * * * * * * * */
	
	// Constructs SQL SELECT clause.
	
	public function select($properties) {
		if ($properties === '*') {
			$this->fields[] = '*';
			return $this;
		}
		$properties = array_filter(array_map('trim', explode(',', $properties)));
		if (!in_array($this->primarykey, $properties)) array_unshift($properties, $this->primarykey);
		foreach ($properties as $property) {
			if (stristr($property, '(') && (stristr($property, ')'))) {
				$this->fields[] = $property;
				continue;
			}
			$table = strtok($property, '_');
			$this->fields[] = $table.'.'.$property;
			if ($table != $this->table && !isset($this->joins[$table])) {
				$ancestors = $this->traverseparents($table);
				$count = count($ancestors);
				if (!$count) throw new RelationshipException($property.' not found: '.$table.' is not a parent of '.$this->table);
				for ($i=1; $i<$count; $i++) {
					if (!isset($this->joins[$ancestors[$i]])) {
						$field = $ancestors[$i].'_id';
						$this->joins[$ancestors[$i]] = 'JOIN '.$ancestors[$i].' ON '.$ancestors[$i].'.'.$field.'='.$ancestors[$i-1].'.'.$field;
					}
				}
			}
		}
		return $this;
	}
	
	// Constructs SQL WHERE clause.
	
	public function where($condition, $variable = NULL, $variabletype = 's') {
		if ($variable !== NULL) {
			$condition = str_replace('?', ':param'.count($this->paramvars), $condition);
			$this->paramvars[] = $variable;
			$this->paramtypes[] = $variabletype;
		}
		$this->where[] = $condition;
		return $this;
	}
	
	// Constructs SQL LIMIT clause.
	
	public function limit($limit) {
		$this->limit = ' LIMIT :param'.count($this->paramvars);
		$this->paramvars[] = ($limit >= 0) ? $limit : 1;
		$this->paramtypes[] = 'i';
		return $this;
	}
	
	// Constructs SQL OFFSET clause.
	
	public function offset($offset) {
		$this->offset = ' OFFSET :param'.count($this->paramvars);
		$this->paramvars[] = ($offset >= 0) ? $offset : 1;
		$this->paramtypes[] = 'i';
		return $this;
	}
	
	// Constructs SQL ORDER BY clause. Sorts by the given field in ascending order.
	
	public function sortasc($field) {
		$this->orderby[] = $field.' ASC';
		return $this;
	}
	
	// Constructs SQL ORDER BY clause. Sorts by the given field in descending order.
	
	public function sortdesc($field) {
		$this->orderby[] = $field.' DESC';
		return $this;
	}
	
	// Separates results into pages. Creates this collection's Paginator.
	
	public function paginate($perpage = 10) {
		$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
		$totalresults = $this->totalresults();
		$totalpages = ceil($totalresults / $perpage);
		$this->limit($perpage);
		if ($page > $totalpages) $page = $totalpages;
		$this->offset(($page - 1) * $perpage);
		$this->Paginator = new Paginator(array(
			'currentpage' => $page,
			'totalpages' => $totalpages,
			'firstresult' => ($page > 0) ? ($page-1) * $perpage+1 : 0,
			'lastresult' => (($last = $page * $perpage) > $totalresults) ? $totalresults : $last,
			'totalresults' => $totalresults
		));	
		return $this;
	}
	
	// Adds a one-to-many relationship with the given collection.
	
	public function addchild(Set $Set) {
		if ($this->Reflections === NULL) {
			$Reflection = new ReflectionClass($this->table);
			$this->Reflections = $Reflection->getStaticPropertyValue('children');
		}
		if (in_array($Set->table, $this->Reflections)) {
			$this->children[] = $Set;
			return $this;
		} else throw new RelationshipException($Set->table.' is not a child of '.$this->table);
	}
	
	
	/* * * * * * * *
	 QUERY EXECUTION
	* * * * * * * */
	
	// Fetches and stores the result (including parents and children) as a multi-dimensional array of objects.
	// The objects can be iterated through by using a foreach loop on the whole Set object.
	
	public function load($properties = NULL) {
		$this->select($properties);
		$query = $this->buildquery('select', 'join', 'where', 'orderby', 'groupby', 'limit', 'offset');
		$Models = $this->Database->fetch($query, $this->paramvars, $this->paramtypes, $this->table);
		$this->buildmodels($Models);
		$this->clear();
		return $this;
	}
	
	// Retrieves one database object.
	// Returns the fetched Model.
	
	public function get($properties = NULL) {
		$this->limit(1)->load($properties);
		foreach ($this->Models as $Model) return $Model;
		return false;
	}
	
	// Retrieves one database object by id.
	// Returns the fetched Model.
	
	public function find($id, $properties = NULL) {
		$this->where($this->primarykey.'=?', $id, 'i')->limit(1)->load($properties);
		foreach ($this->Models as $Model) return $Model;
		return false;
	}
	
	// Updates one or more objects in the database.
	// IMPORTANT: Make sure to specify WHERE and/or LIMIT or you'll update everything in the table.
	
	public function update(array $assoc) {
		$query = $this->buildquery($assoc, 'update', 'where', 'limit');
		$this->Database->execute($query, $this->paramvars, $this->paramtypes);
		$this->clear();
		return $this;
	}
	
	// Inserts an object into the database.
	// Returns the newly inserted primary key id.
	
	public function insert(array $assoc) {
		$query = $this->buildquery($assoc, 'insert');
		$this->Database->execute($query, $this->paramvars, $this->paramtypes);
		$this->clear();
		return $this->Database->insertid();
	}
	
	// Deletes this object and children from the database.
	// IMPORTANT: Make sure to specify WHERE and/or LIMIT or you'll delete everything in the table.
	
	public function delete() {
		$query = $this->buildquery('delete', 'where', 'limit');
		$this->Database->execute($query, $this->paramvars, $this->paramtypes);
		$this->clear();
		return $this;
	}
	
	// Deletes all objects and children from the database.
	
	public function truncate() {
		$query = 'TRUNCATE TABLE '.$this->table;
		$this->Database->execute($query, $this->paramvars, $this->paramtypes);
		$this->clear();
		return $this;
	}
	
	// Fetches or executes using a raw SQL query.
	
	public function query($sql, array $paramvars = array(), array $paramtypes = array()) {
		$array = explode(' ', $sql);
		if (!empty($array)) {
			if (strtoupper($array[0]) == 'SELECT') {
				$Models = $this->Database->fetch($sql, $paramvars, $paramtypes, $this->table);
				$this->buildmodels($Models);
			} else {
				$this->Database->execute($sql, $paramvars, $paramtypes);	
			}
			$this->clear();
			return $this;
		}
		return false;
	}
	
	
	/* * * * *
	 ITERATION
	* * * * */
	
	public function rewind() {
		return reset($this->Models);
	}
	
	public function current() {
		return current($this->Models);
	}
	
	public function key() {
		return key($this->Models);
	}
	
	public function next() {
		return next($this->Models);
	}
	
	public function valid() {
		return key($this->Models) !== NULL;
	}
	
	public function count() {
		return count($this->Models);
	}
	
	public function purge() {
		$this->Models = array();
		$this->children = array();
		return $this;
	}
	
	
	/* * * * * * *
	 SERIALIZATION
	* * * * * * */
	
	public function serialize() {
		return serialize($this->Models);
	}
	
	public function unserialize($data) {
		$this->Models = unserialize($data);
	}
	
	
	/* * * * * * *
	 MAGIC METHODS
	* * * * * * */
	
	public function __toString() {
		return '<pre>'.$this->table.' Set/'.print_r($this->Models, true).'</pre>';
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $Database, $Reflections;
	protected $Models = array(), $children = array(), $paramvars = array(), $paramtypes = array();
	protected $fields = array(), $joins = array(), $where = array(), $orderby = array(), $limit, $offset;
	
	protected function clear() {
		$this->paramvars = array();
		$this->paramtypes = array();
		$this->fields = array();
		$this->joins = array();
		$this->where = array();
		$this->orderby = array();
		$this->limit = NULL;
		$this->offset = NULL;
	}
	
	protected function totalresults() {
		$query = $this->buildquery('count', 'where', 'limit');
		$result = $this->Database->fetch($query, $this->paramvars, $this->paramtypes);
		if (!empty($result)) return $result[0]['count'];
		return 0;
	}
	
	protected function traverseparents($target, $class = NULL, $path = array()) {
		if (empty($class)) $class = $this->table;
		array_push($path, $class);
		if ($class == $target) return $path;
		$reflection = new ReflectionClass($class);
		$parents = $reflection->getStaticPropertyValue('parents');
		foreach ($parents as $parent) {
			$result = $this->traverseparents($target, $parent, $path);
			if (!empty($result)) return $result;
		}
	}
	
	protected function buildmodels($Models) {
		$keys = array();
		foreach ($Models as $Model) {
			$id = $Model->{$this->primarykey};
			foreach ($this->children as $children) {
				$Model->{Inflector::plural($children->table)} = array();
			}
			$this->Models[$id] = $Model;
			$keys[] = $id;
			
		}
		if (!empty($keys)) {
			foreach ($this->children as $children) {
				$children->where($children->table.'.'.$this->primarykey.' IN ('.implode(',', $keys).')')->load($this->primarykey);
				$array = Inflector::plural($children->table);
				$primary = $children->primarykey;
				foreach ($children as $child) {
					$id = $child->{$this->primarykey};
					$this->Models[$id]->{$array}[$child->$primary] = $child;
				}
			}
		}	
	}
	
	protected function buildquery() {
		$clauses = func_get_args();
		if (is_array($clauses[0])) $assoc = array_shift($clauses);
		$query = array();
		if (in_array('select', $clauses)) {
			$query[] = 'SELECT '.implode(', ', $this->fields).' FROM '.$this->table;
		} elseif (in_array('count', $clauses)) {
			$query[] = 'SELECT COUNT(*) AS count FROM '.$this->table;
		} elseif (in_array('update', $clauses)) {
			$assignments = array();
			$count = count($this->paramvars);
			foreach ($assoc as $field => $value) {
				$this->paramvars[] = $value;
				$this->paramtypes[] = 's';
				$assignments[] = $field.'=:param'.$count++;
			}
			$query[] = 'UPDATE '.$this->table.' SET '.implode(',', $assignments);
		} elseif (in_array('insert', $clauses)) {
			$fields = array();
			$values = array();
			$count = count($this->paramvars);
			foreach ($assoc as $field => $value) {
				$this->paramvars[] = $value;
				$this->paramtypes[] = 's';
				$fields[] = $field;
				$values[] = ':param'.$count++;
			}
			$query[] = 'INSERT INTO '.$this->table.'('.implode(',', $fields).') VALUES('.implode(',', $values).')';
		} elseif (in_array('delete', $clauses)) {
			$query[] = 'DELETE FROM '.$this->table;
		}
		if (in_array('join', $clauses) && !empty($this->joins)) {
			$query[] = implode(' ', $this->joins);
		}
		if (in_array('where', $clauses) && !empty($this->where)) {
			$query[] = 'WHERE '.implode(' AND ', $this->where);
		}
		if (in_array('orderby', $clauses) && !empty($this->orderby)) {
			$query[] = 'ORDER BY '.implode(',', $this->orderby);
		}
		if ($this->limit != NULL && in_array('limit', $clauses)) {
			$query[] = $this->limit;
		}
		if ($this->offset != NULL && in_array('offset', $clauses)) {
			$query[] = $this->offset;
		}
		$result = implode(' ', $query);
		return $result;
	}
	
}

?>