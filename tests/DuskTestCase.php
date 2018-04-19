<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestResult;

/**
* Class DuskTestCase
* @method run(TestResult $result = null): TestResult
* @package Tests
*/
abstract class DuskTestCase extends BaseTestCase
{
	use CreatesApplication;
	use DatabaseMigrations;//Remove and replace with fixtures

	/**
	 * Prepare for Dusk test execution.
	 *
	 * @beforeClass
	 * @return void
	 */
	public static function prepare()
	{
		static::startChromeDriver();
	}

	/**
	 * Create the RemoteWebDriver instance.
	 *
	 * @return \Facebook\WebDriver\Remote\RemoteWebDriver
	 */
	protected function driver()
	{
		$options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);

		return RemoteWebDriver::create(
						'http://localhost:9515',
						DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options)
		);
	}
}
