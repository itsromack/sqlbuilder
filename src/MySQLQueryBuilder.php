<?php
namespace DesignPattern;

use DesignPattern\SQLQueryBuilder;

define('QUERY_TYPE_SELECT', 'select');

class MySQLQueryBuilder implements SQLQueryBuilder
{
	protected $query;

	public function reset()
	{
		$this->query = new \stdClass();
		$this->query->base = null;
		$this->query->joins = [];
		$this->query->type = null;
		$this->query->where = [];
		$this->query->limit = null;
		$this->query->offset = null;
	}

	public function select($table, $fields)
	{
		$this->reset();
		$this->query->base = "SELECT ";
		$this->query->base .= implode(', ', $fields);
		$this->query->base .= " FROM " . $table;
		$this->query->type = QUERY_TYPE_SELECT;

		return $this;
	}

	public function where($field, $operator, $value)
	{
		$filter = "$field {$operator} '{$value}'";
		array_push($this->query->where, $filter);

		return $this;
	}

	public function limit($limit = null, $offset = null)
	{
		$this->query->limit = $limit;
		$this->query->offset = $offset;

		return $this;
	}

	public function join($table, $field1, $field2)
	{
		$join_expression = " JOIN {$table} ON ({$field1}={$field2})";
		array_push($this->query->joins, $join_expression);

		return $this;
	}

	public function getSQL()
	{
		$query = $this->query;
		$sql = $query->base;

		if (!empty($query->joins)) {
			$sql .= implode('', $query->joins);
		}

		if (!empty($query->where)) {
			$sql .= " WHERE ";
			$sql .= implode(' AND ', $query->where);
		}

		if (!is_null($query->limit)) {
			$sql .= " LIMIT " . $query->limit;
		}

		if (!is_null($query->offset)) {
			$sql .= " , " . $query->offset;
		}

		return $sql;
	}
}
