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
        exit($retval);
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
        exit($retval);
    }
    
    /**
     * php -l
     *
     */
    public static function phpLint($targets)
    {
        $config = CodequalityProvider::getConfig();
        $targets = count($targets) > 0 ? $targets : explode(' ', $config['phplint_target']);
        foreach ($targets as $target) {
            CodequalityProvider::runRecurseOnPhpFiles($target, function ($file) use ($config) {
                system($config['php'] . ' -l ' . ' ' . $file . ' \;', $retval);
                
                if ( 0 != $retval) {
                    exit(1);
                }
            });
        }
    }
    
    protected static function runRecurseOnPhpFiles($target, $callback)
    {
        if (is_file($target) && preg_match('/^.*\.(php)$/i', $target)) {
            $callback($target);
            return;
        }
        if (is_dir($target)) {
            $it = new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS);
            foreach (new \RecursiveIteratorIterator($it) as $file) {
                if (is_file($file) && preg_match('/^.*\.(php)$/i', $file)) {
                    $callback($file);
                }
            }
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
            'phpcs' => base_path('vendor/squizlabs/php_codesniffer/bin/phpcs'),
            'phpcbf' => base_path('vendor/squizlabs/php_codesniffer/bin/phpcbf'),
            'phpcs_standard' => '--standard=config/phpcs/',
            'phpcs_target' => 'tests routes config app',
            'phplint_target' => 'tests routes config app',
        ];
    }
}
