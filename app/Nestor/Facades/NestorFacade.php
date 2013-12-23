<?php namespace Nestor\Facades;

use Illuminate\Support\Facades\Facade;

class NestorFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'Nestor'; }

}
