<?php namespace Jinggo\Elasticsearch;

use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider {

	protected $defer = false;

	public function boot()
	{
		$this->package('jinggo/elasticsearch');
	}

	public function register()
	{
		$this->app['elasticsearch'] = $this->app->share(function($app)
		{
			return new Elasticsearch;
		});

		$this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Elasticsearch', 'Jinggo\Elasticsearch\Facades\Elasticsearch');
        });
	}

	public function provides()
	{
		return array();
	}

}