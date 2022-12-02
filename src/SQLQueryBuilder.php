<?php

namespace DesignPattern;

interface SQLQueryBuilder
{
	public function select($table, $fields);
	public function where($field, $value, $operator);
	public function limit($start, $offset);
}
