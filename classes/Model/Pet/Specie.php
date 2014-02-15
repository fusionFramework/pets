<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Pet specie model
 *
 * @package    fusionFramework/pets
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) Maxim Kerstens
 */
class Model_Pet_Specie extends ORM {
	protected $_table_columns = array(
		'id'            => NULL,
		'name'          => NULL,
		'dir'           => NULL,
		'description'   => NULL,
		'status'        => NULL,
		'adopt_limit'   => NULL
	);

	protected $_has_many = array(
		'colors' => array(
			'through'     => 'pet_species_colors',
			'model'       => 'Pet_Color',
			'foreign_key' => 'specie_id',
			'far_key'     => 'color_id'
		)
	);

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty')
			),
			'dir' => array(
				array('not_empty')
			),
			'description' => array(
				array('not_empty')
			),
			'adopt_limit' => array(
				array('digit')
			),
			'status' => array(
				array('not_empty'),
				array('in_array', array(':value', array('draft', 'retired', 'released', 'adopt_free', 'adopt_limit')))
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

		if($form->find('dir') != null)
		{
			$form->dir->set('label', 'Directory name')
				->set('driver', 'input')
				->set('attr.class', 'form-control');
		}

		if($form->find('status') != null)
		{
			$form->status->set('label', 'Status')
				->set('driver', 'select')
				->set('opts', ['draft' => 'Draft', 'adopt_free' => 'Free adoption', 'adopt_limit' => 'Limited adoption', 'released' => 'No adoption', 'retired' => 'Retired'])
				->set('attr.class', 'form-control');
		}

		if($form->find('adopt_limit') != null)
		{
			$form->adopt_limit->set('label', 'Adoption limit')
				->set('driver', 'input')
				->set('attr.class', 'form-control')
				->set('attr.type', 'number')
				->set('message', 'The amount of available adoptions this pet has, if it has the \'Limited adoption\' status');
		}
	}
}
