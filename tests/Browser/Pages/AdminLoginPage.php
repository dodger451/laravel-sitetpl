<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class AdminLoginPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return ['@element' => '#selector',];
    }

    public function loginWithCreds(Browser $browser, $email, $password)
    {
        $browser->type('@email-input', $email)
            ->type('@password-input', $password)
            ->click('@login-button');
    }
}
