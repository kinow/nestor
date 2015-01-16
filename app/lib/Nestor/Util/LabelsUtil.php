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
			$label = NULL;
			foreach ($existingLabels as $existingLabel) {
				if ($labelName == $existingLabel['name']) {
					$found = TRUE;
					$label = $existingLabel;
					break;
				}
			}

			if (!$found) {
				$newLabelsNames[] = $labelName;
			} else {
				$oldLabels[] = $label;
			}
		}

		return array($newLabelsNames, $oldLabels);
	}

}