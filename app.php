<?php

// Client Code

require "vendor/autoload.php";

use DesignPattern\MySQLQueryBuilder;

$builder = new MySQLQueryBuilder();


# 1
$query1 = $builder->select('goal', ['*'])
	->where('player', 'LIKE', '%Bender')
	->getSQL();

echo $query1;

echo "\n\n-------------------------\n\n";

# 2
$query2 = $builder->select('game', ['game.id', 'game.mdate', 'game.stadium', 'goal.player', 'goal.gtime'])
		->join('goal', 'game.id', 'goal.matchid')
		->where('game.stadium', 'LIKE' ,'%National%')
		->getSQL();

echo $query2;

echo "\n\n-------------------------\n\n";

var_dump($builder);
