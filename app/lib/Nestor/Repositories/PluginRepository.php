<?php namespace Nestor\Repositories;

interface PluginRepository {

	public function installed();

	public function findByName($name);

}