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

		\Artisan::command('phpcs {targets?*}', function ($targets) {
			CodequalityProvider::phpcsCheck($targets);
		})
			->describe('Check formatting errors via PHPCS');

		\Artisan::command('phpcs:fix {targets?*}', function ($targets) {
			CodequalityProvider::phpcsFix($targets);
		})
			->describe('Fix formatting errors via PHPCBF');

		\Artisan::command('phplint {targets?*}', function ($targets) {
			CodequalityProvider::phpLint($targets);
		})
			->describe('Find syntax errors via php -l on all files');
	}

	/**
	 * PHPCS
	 */
	public static function phpcsCheck($targets)
	{
		$config = CodequalityProvider::getConfig();
		system(
						$config['php'] . ' ' . $config['phpcs'] . ' ' . $config['phpcs_standard'] . ' ' .
						(count($targets) > 0 ? implode(' ', $targets) : $config['phpcs_target']),
						$retval
		);
		echo($config['php'] . ' ' . $config['phpcs']
			. ' ' . $config['phpcs_standard'] . ' ' . (count($targets) > 0
				? implode(' ', $targets) : $config['phpcs_target'])

		);
	}

	/**
	 * PHPCBF
	 *
	 */
	public static function phpcsFix($targets)
	{
		$config = CodequalityProvider::getConfig();
		system(
						$config['php'] . ' ' . $config['phpcbf']
						. ' ' . $config['phpcs_standard'] . ' ' . (count($targets) > 0
						? implode(' ', $targets) : $config['phpcs_target']),
						$retval
		);
	}

	public static function runOnPhpFile($target, $callback)
	{
		if (is_file($target) && preg_match('/^.*\.(php)$/i', $target))
		{
			$callback($target);
			return;
		}
		if (is_dir($target))
		{
			$it = new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS);
			foreach (new \RecursiveIteratorIterator($it) as $file)
			{
				if (is_file($file) && preg_match('/^.*\.(php)$/i', $file))
				{
					$callback($file);
				}
			}
		}
	}

	static protected function _runLint($phpFile, $phpBin)
	{
		$cmd = $phpBin . ' -l ' . ' ' . $phpFile . ' \;';
		//echo $cmd;
		system($cmd, $retval);
		return 0 == $retval;
	}

	/**
	 * php -l
	 *
	 */
	public static function phpLint($targets)
	{
		$config = CodequalityProvider::getConfig();
		$targets = count($targets) > 0 ? $targets : explode(' ', $config['phplint_target']);
		foreach ($targets as $target)
		{
			CodequalityProvider::runOnPhpFile($target, function ($file) use ($config) {
				if (!CodequalityProvider::_runLint($file, $config['php']))
				{
					exit(1);
				}
			});
		}
	}

	/**
	 * All tools configs
	 */
	private static function getConfig()
	{
		return [
			'php' => 'php',
			'find' => 'find',
			'phpcs' => base_path('vendor/squizlabs/php_codesniffer/scripts/phpcs'),
			'phpcbf' => base_path('vendor/squizlabs/php_codesniffer/scripts/phpcbf'),
			'phpcs_standard' => '--standard=vendor/pragmarx/laravelcs/Standards/Laravel/',
			'phpcs_target' => 'tests routes config app',
			'phplint_target' => 'tests routes config app',
		];
	}
}
