<?php defined('SYSPATH') OR die('No direct script access.');

class Task_Pet_Hunger extends Minion_Task
{
    protected $_options = array(
        'max_decrement' => 4,
    );

    /**
     * This is a task to decrease pet hunger
     *
     * @return NULL
     */
	protected function _execute(array $params)
	{
		$min = 1;
		$max = $params['max_decrement'] - $min * 10;

		DB::update('user_pets')->set(array('hunger' => DB::expr("hunger - ROUND(RAND() * ".$max." + ".$min.")")))->execute();
	}
}
