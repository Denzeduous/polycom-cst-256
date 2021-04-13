<?php

namespace App\Service;

class Suspension {
	
	/**
	 * Gets a int day offset meant for an administrative suspension form.
	 * @param string $suspension_time The unchanged suspension string.
	 * @return int The amount of days for suspension, 0 on failure.
	 */
	public static function CreateSuspensionDate (string $suspension_time): int {
		if ($suspension_time === '1 Day'  ) return 1;
		if ($suspension_time === '1 Week' ) return 7;
		if ($suspension_time === '1 Month') return 30;
		
		return 0;
	}
}

