<?php namespace Jinggo\Elasticsearch\Facades;

use Illuminate\Support\Facades\Facade;

class Elasticsearch extends Facade {

	protected static function getFacadeAccessor() { return 'elasticsearch'; }
	
}