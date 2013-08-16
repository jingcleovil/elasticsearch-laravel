## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `jinggo/elasticsearch`.

	"require": {
		"laravel/framework": "4.0.*",
		"jinggo/elasticsearch": "dev-master"
	},
	"minimum-stability" : "dev"

Next, update Composer from the Terminal:

    composer update

Once this operation completes, the final step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

    'Jinggo\Elasticsearch\ElasticsearchServiceProvider'