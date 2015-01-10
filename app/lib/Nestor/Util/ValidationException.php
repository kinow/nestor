<?php 
namespace Nestor\Util;

use \Illuminate\Support\MessageBag;

class ValidationException extends \Exception 
{

	private $errors;

	public function __construct(MessageBag $errors)
	{
		$this->errors = $errors;
		parent::__construct(null);
	}

	public function getErrors()
	{
		return $this->errors;
	}
}