<?php


namespace Sda\Jem\Admin\Helper;


use FOF30\Date\Date;

abstract class DateHelper
{

	public static function getDateValue($value)
	{
		if ($value == null || $value == "0000-00-00")
		{
			return null;
		}

		// Make sure it's not a Date already
		if (is_object($value) && ($value instanceof Date))
		{
			return $value;
		}

		// Return the data transformed to a Date object
		return new Date($value);
	}

}