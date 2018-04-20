<?php
namespace Sitetpl\Providers;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

	/**
	 * Class PHPCSProvider was initally a copy of https://github.com/nunun/laravel-phpcs
	 *
	 * Modified to use phpcs pragmarx standard.
	 *
	 * @see https://github.com/nunun/laravel-phpcs
	 * @package Sitetpl\Providers
	 */
class CodequalityProvider extends ServiceProvider
{
	/**
	 * Define the commands for the application.
	 *
	 * @return void
	 */
	public function map()
	{
		\Artisan::command('phpcs', function () {
			CodequalityProvider::phpcsCheck();
		})->describe('Check syntax errors via PHPCS');

		\Artisan::command('phpcs:fix', function () {
			CodequalityProvider::phpcsFix();
		})->describe('Fix syntax errors via PHPCBF');
	}

	/**
	 * PHPCS
	 */
	public static function phpcsCheck()
	{
		$config = CodequalityProvider::getConfig();
		system($config['php']. ' '. $config['phpcs']. ' '. $config['phpcs_args'], $retval);
		echo('Exit code ' . $retval . PHP_EOL);
	}
	/**
	 * PHPCBF
	 *
	 */
	public static function phpcsFix()
	{
		$config = CodequalityProvider::getConfig();
		system($config['php']. ' '. $config['phpcbf']. ' '. $config['phpcs_args'], $retval);
	}
	/**
	 *
	 */
	private static function getConfig()
	{
		return [
			'php'    => 'php',
			'phpcs'  => base_path('vendor/squizlabs/php_codesniffer/scripts/phpcs'),
			'phpcbf' => base_path('vendor/squizlabs/php_codesniffer/scripts/phpcbf'),
			'phpcs_args'   => '--standard=vendor/pragmarx/laravelcs/Standards/Laravel/ tests routes config app',
		];
	}
}