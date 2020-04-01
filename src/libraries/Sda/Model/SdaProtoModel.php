<?php

namespace Sda\Model;

use FOF30\Model\DataModel;

class SdaProtoModel extends DataModel
{
	/**
	 * @param string $filter could be any field of a location
	 * @return array
	 * @since 1.1.0
	 */
	public function getFilters(string $filter):array {
		$db = $this->getDbo();

		$fieldlist = $db->qn(array($filter)); // add the field names to an array
		$fieldlist[0] = 'distinct ' . $fieldlist[0]; //prepend the distinct keyword to the first field name

		$query = $db->getQuery(true);
		$query->select($fieldlist)
			->from($this->getTableName());
		$db->setQuery($query);
		$result = $db->loadColumn();
		return $result;
	}
}