<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Pet color model
 *
 * @package    fusionFramework/pets
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) Maxim Kerstens
 */
class Model_Pet_Color extends ORM {

	protected $_table_columns = array(
		'id'            => NULL,
		'default'        => NULL,
		'name'          => NULL,
		'description'   => NULL,
		'image'         => NULL
	);

	protected $_has_many = array(
		'species' => array(
			'model' => 'Pet_Specie',
			'through' => 'pet_species_colors',
			'foreign_key' => 'color_id',
			'far_key' => 'specie_id'
		)
	);

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty')
			),
			'default' => array(
				array('not_empty')
			),
			'description' => array(
				array('not_empty')
			),
			'image' => array(
				array('not_empty')
			)
		);
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

	use Formo_ORM;
	protected $_primary_val = 'name';

	/**
	 * Define form fields based on model properties.
	 *
	 * @param Formo $form
	 */
	public function formo(Formo $form)
	{
		if($form->find('name') != null)
		{
			$form->name->set('label', 'Name')
				->set('driver', 'input')
				->set('attr.class', 'form-control');
		}

		if($form->find('description') != null)
		{
			$form->description->set('label', 'Description')
				->set('driver', 'textarea')
				->set('attr.class', 'form-control');
		}

		if($form->find('default') != null)
		{
			$form->default->set('label', 'Default?')
				->set('driver', 'radios')
				->set('opts', ['0' => 'No', '1' => 'Yes'])
				->set('attr.class', 'form-control')
				->set('message', 'Is this a default color that\'s available at creation?');
		}

		if($form->find('image') != null)
		{
			$form->image->set('label', 'Image name')
				->set('driver', 'input')
				->set('attr.class', 'form-control');
		}
	}
}
