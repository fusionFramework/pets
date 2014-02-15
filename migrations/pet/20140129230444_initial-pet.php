<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial pet schema (species, colours, user pets)
 * @author happydemon
 *
 */
class Migration_Pet_20140129230444 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `pet_species` (
			  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `name` varchar(35) NOT NULL,
			  `dir` varchar(40) NOT NULL,
			  `description` text NOT NULL,
			  `status` enum('draft', 'retired', 'released', 'adopt_free', 'adopt_limit') NOT NULL,
			  `adopt_limit` int(8) UNSIGNED NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `pet_colors` (
			  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `default` int(1) NOT NULL,
			  `name` varchar(35) NOT NULL,
			  `description` text NOT NULL,
			  `image` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");


		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `pet_species_colors` (
			  `specie_id` int(11) UNSIGNED NOT NULL,
			  `color_id` int(11) UNSIGNED NOT NULL,
			  KEY `pet_species_colors_specie` (`specie_id`),
			  KEY `pet_species_colors_colour` (`color_id`),
		 	  CONSTRAINT `pet_species_colors_ibfk_1` FOREIGN KEY (`specie_id`) REFERENCES `pet_species` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `pet_species_colors_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `pet_colors` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		 ");

		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `user_pets` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) UNSIGNED DEFAULT NULL,
			  `created` int(10) NOT NULL,
			  `abandoned` int(10) NOT NULL,
			  `active` int(10) NOT NULL,
			  `name` varchar(35) NOT NULL,
			  `gender` enum('male','female') NOT NULL,
			  `specie_id` int(6) UNSIGNED NOT NULL,
			  `color_id` int(6) UNSIGNED NOT NULL,
			  `hunger` int(3) NOT NULL DEFAULT 100,
			  `mood` int(3) NOT NULL DEFAULT 100,
			  PRIMARY KEY (`id`),
			  KEY `user_pets_user` (`user_id`),
		 	  KEY `user_pets_pet` (`specie_id`),
		 	  KEY `user_pets_color` (`color_id`),
		 	  CONSTRAINT `user_pets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `user_pets_ibfk_2` FOREIGN KEY (`specie_id`) REFERENCES `pet_species` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `user_pets_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `pet_colors` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, "DROP TABLE IF EXISTS `pet_species`, `pet_colors`, `user_pets`, `pet_species_colors`;");
	}

}
