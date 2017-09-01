<?php

namespace CaT\Ilse\Configurators;

/**
 * Helper class for shared configuration functions
 */
trait ConfigHelper {
	/**
	* get obj id for role name
	*
	* @param string $role_name
	*
	* @return int
	*/
	protected function getRoleId($role_name) {
		assert('is_string($role_name)');

		return $this->getObjIdByTitle("role", $role_name);
	}

	/**
	* get obj_id for title and type
	*
	* @param string $type
	* @param string $title
	*
	* @return int
	*/
	protected function getObjIdByTitle($type, $title) {
		assert('is_string($type)');
		assert('is_string($title)');

		$query = "SELECT obj_id FROM object_data WHERE type = ".$this->gDB->quote($type, 'text')." AND title = ".$this->gDB->quote($title, 'text')."";
		$res = $this->gDB->query($query);

		assert('$this->gDB->numRows($res) == 1');

		$row = $this->gDB->fetchAssoc($res);

		return (int)$row["obj_id"];
	}
}