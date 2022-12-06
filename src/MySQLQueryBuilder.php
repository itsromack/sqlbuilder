<?php
namespace DesignPattern;

use DesignPattern\SQLQueryBuilder;

define('QUERY_TYPE_SELECT', 'select');
define('QUERY_TYPE_INSERT', 'insert');
define('QUERY_TYPE_UPDATE', 'update');
define('QUERY_TYPE_DELETE', 'delete');

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
		$this->query->orders = [];
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
		if (!in_array($this->query->type, [QUERY_TYPE_SELECT, QUERY_TYPE_UPDATE, QUERY_TYPE_DELETE])) {
			return false;
		}
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

	public function orderBy($field, $order = 'ASC')
	{
		$order_expression = " {$field} {$order}";
		array_push($this->query->orders, $order_expression);

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

		if (!empty($query->orders)) {
			$sql .= " ORDER BY ";
			$sql .= implode(', ', $query->orders);
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
