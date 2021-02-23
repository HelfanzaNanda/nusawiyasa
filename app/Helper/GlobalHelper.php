<?php

namespace App\Helper;

class GlobalHelper
{
	public static function convertSeparator($number)
	{
	    $number = str_replace(',', '', $number);
	    if ($number > 0) {
	    	return $number;
	    }
	    return 0;
	}
}