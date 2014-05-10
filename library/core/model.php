<?php

abstract class Model {
	
	// Constructor - if $id specified, will perform operations on the Model with that id.
	
	public function __construct($id = NULL) {
		if ($id !== NULL) {
			$this->{get_class($this).'_id'} = $id;
		}
	}
	
	
	/* * * * * * * * * *
	 OBJECT MANIPULATION
	* * * * * * * * * */
	
	// Loads the given properties into this Model.
	// Can also load parent properties and child Models.
	// Returns true if successful, false otherwise.
	
	public function load($properties = NULL, array $children = array()) {
		if (isset($this->{get_class($this).'_id'})) {
			$table = get_class($this);
			$Models = new Set($table);
			foreach ($children as $child) {
				if (!($child instanceof Set)) throw new RelationshipException($child.' is not a child of '.$table);
				$Models->addchild($child);
			}
			$Models->find($this->{get_class($this).'_id'}, $properties);
			foreach ($Models as $Model) {
				foreach ($Model as $name => $value)
					$this->$name = $value;
				return true;
			}
		}
		return false;
	}
	
	// Saves this Model to the database.
	// If a Model with this id exists in the database, will update fields.
	// Otherwise will insert a new Model, and return the newly inserted primary key id.
	// Note that changing a Model's primary key ($id) will result in undefined behaviour. You shouldn't be changing it anyway.
	
	public function save() {
		$table = get_class($this);
		$Models = new Set($table);
		$assoc = array();
		foreach ($this as $name => $value) {
			if (!is_object($value)) $assoc[$name] = $value;
		}
		if (isset($this->{get_class($this).'_id'})) {
			$Models->where($table.'_id=?', $this->{get_class($this).'_id'}, 'i')->limit(1)->update($assoc);
		} else {
			$id = $Models->insert($assoc);
			$this->{get_class($this).'_id'} = $id;
			return $id;
		}
	}
	
	// Deletes this Model from the database.
	
	public function delete() {
		if (isset($this->{get_class($this).'_id'})) {
			$table = get_class($this);
			$Models = new Set($table);
			$Models->where($table.'_id=?', $this->{get_class($this).'_id'}, 'i')->limit(1)->delete();
		}
	}
	
	
	/* * * * * * *
	 MAGIC METHODS
	* * * * * * */
	
	public function __set($name, $value) {
		$table = strtok($name, '_');
		$field = strtok('_');
		if (strpos($name, '_') !== false && $table != get_class($this)) {
			if (!isset($this->$table)) $this->$table = new $table;
			$this->$table->$name = $value;
			if ($field == 'id') $this->$name = $value;
		} else {
			$this->$name = $value;
		}
	}
	
	public function __toString() {
		return '<pre>'.print_r($this, true).'</pre>';
	}
	
}

?>