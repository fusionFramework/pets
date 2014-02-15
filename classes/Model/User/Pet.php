<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * User pet model
 *
 * @package    fusionFramework/pets
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) Maxim Kerstens
 */
class Model_User_Pet extends ORM {

	protected $_table_columns = array(
		'id'          => NULL,
		'user_id'       => NULL,
		'created'    => NULL,
		'abandoned'    => NULL,
		'active'      => NULL,
		'name'      => NULL,
		'gender'      => NULL,
		'specie_id'      => NULL,
		'color_id'      => NULL,
		'hunger'      => NULL,
		'mood'      => NULL
	);

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE,

	);

	protected $_belongs_to = array(
		'specie' => array(
			'model' => 'Pet_Specie',
			'foreign_key' => 'specie_id',
		),
		'color' => array(
			'model' => 'Pet_Color',
			'foreign_key' => 'color_id',
		),
		'user' => array(
			'model' => 'User',
			'foreign_key' => 'user_id',
		),
	);

	public function rules()
	{
		return array(
			'user_id' => array(
				array('Model_User_Pet::user_limit', array(':value'))
			),
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
				array(array($this, 'unique'), array(':field', ':value')),
				array('regex', array(':value', '/^[a-zA-Z0-9-_]++$/iD')),
			),
			'specie_id' => array(
				array('Model_User_Pet::specie_exists', array(':value'))
			),
			'color_id' => array(
				array('Model_User_Pet::color_exists', array(':value')),
				array('Model_User_Pet::color_available', array(':value', ':model'))
			),
			'hunger' => array(
				array('digit')
			),
			'mood' => array(
				array('digit')
			)
		);
	}

	/**
	 * Form the URL to the pet's image
	 *
	 * @return string
	 */
	public function img()
	{
		return URL::base() . 'm/pets/'.$this->specie->dir.'/'.$this->color->image;
	}

	/**
	 * Check if the specie exists.
	 *
	 * @param $id
	 * @return bool
	 */
	public static function specie_exists($id)
	{
		$specie = DB::select(array(DB::expr('COUNT(`id`)'), 'amount'))
			->from('pet_species')
			->where('id', '=', $id)
			->execute();

		return (bool) $specie->amount;
	}

	/**
	 * Check if the color exists
	 *
	 * @param $id
	 * @return bool
	 */
	public static function color_exists($id)
	{
		$color = DB::select(array(DB::expr('COUNT(`id`)'), 'amount'))
			->from('pet_colors')
			->where('id', '=', $id)
			->execute();

		return (bool) $color->amount;
	}

	/**
	 * Check if the color is available for this specie.
	 *
	 * @param $id
	 * @param $model
	 * @return bool
	 */
	public static function color_available($id, $model)
	{
		$color = DB::select(array(DB::expr('COUNT(`color_id`)'), 'amount'))
			->from('pet_species_colors')
			->where('specie_id', '=', $model->specie_id)
			->and_where('color_id', '=', $id)
			->execute();

		return (bool) $color->amount;
	}

	/**
	 * Check if the color is 'in circulation'
	 *
	 * @param $id
	 * @return bool
	 */
	public static function color_free($id)
	{
		$color = ORM::factory('Pet_Color', $id);

		return $color->locked == 0;
	}

	/**
	 * Check if the user can adopt another pet.
	 *
	 * @param $user_id
	 * @return bool
	 */
	public static function user_limit($user_id)
	{
		$limit = Kohana::$config->load('pet.limit');

		$pet_count = DB::select(array(DB::expr('COUNT(*)'), 'total'))
			->from('user_pets')
			->where('user_id', '=', $user_id)
			->execute()
			->get('total');

		return ($user_id == 0 OR $pet_count < $limit);
	}

	/**
	 * Used to represent in belongs_to relations when changes are tracked
	 * @return bool|string
	 */
	public function candidate_key()
	{
		if (!$this->loaded()) return FALSE;
		return $this->name;
	}
}
