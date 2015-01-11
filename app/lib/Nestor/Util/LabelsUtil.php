<?php
namespace Nestor\Util;

class LabelsUtil 
{

	private function __construct() {}

	public static function splitNewLabels($existingLabels, $allLabels)
	{
		$newLabelsNames = array();
		$oldLabels = array();
		for($i = 0; $i < count($allLabels); ++$i) {
			$labelName = $allLabels[$i];
			$found = FALSE;
			foreach ($existingLabels as $existingLabel) {
				if ($labelName == $existingLabel['name']) {
					$found = TRUE;
					$label = $existingLabel;
					break;
				}
			}

			if (!$found) {
				$newLabelsNames[] = $labelName;
			}
		}

		foreach ($existingLabels as $existingLabel) {
			if (!in_array($existingLabel['name'], $newLabelsNames)) {
				$oldLabels[] = $existingLabel;
			}
		}

		return array($newLabelsNames, $oldLabels);
	}

}