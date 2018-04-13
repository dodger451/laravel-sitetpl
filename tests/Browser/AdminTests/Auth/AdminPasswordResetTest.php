<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;



class AdminPasswordResetTest extends DuskTestCase
{

    /**
     * PasswordResetRejectsInvalidEmail
     *
     * @return void
     */
    public function testPasswordResetRejectsInvalidEmail()
    {

        $this->browse(function ($browser) {
            $browser->visit('/admin/password/reset')
                ->type('@email-input', 'noemail')
                ->click('@reset-button')
                ->assertMissing('@success-message');
        });
    }
    /**
     * PasswordResetRejectsUnknownEmail
     *
     * @return void
     */
    public function testPasswordResetRejectsUnknownEmail()
    {

        $this->browse(function ($browser) {
            $browser->visit('/admin/password/reset')
                ->type('@email-input', 'invalid@sitetpl.com')
                ->click('@reset-button')
                ->assertVisible('@error-message');
        });
    }
    /**
     * PasswordResetRejectsUnknownEmail
     *
     * @return void
     */
    public function testPasswordResetAcceptsRegisteredEmail()
    {

        $user = factory(Admin::class)->create();
        
        $this->browse(function ($browser) use ($user){
            $browser->visit('/admin/password/reset')
                ->type('@email-input', $user->email)
                ->click('@reset-button')
                ->assertVisible('@success-message');
        });
    }
    /**
     * PasswordResetRejectsUnknownEmail
     *
     * @return void
     */
    public function testPasswordResetTokenAllowsResetEmail()
    {

        $user = factory(Admin::class)->create();
        $token = app('auth.password')->broker()->createToken($user);
        $this->browse(function ($browser) use ($token, $user){
            $browser->visit(route('admin.password.reset', $token, false))
                ->type('@email-input', $user->email)
                ->type('@password-input', 'newPassword')
                ->type('@password_confirmation-input', 'newPassword')
                ->click('@reset-button')
                ->assertPathIs('/admin/home')
                ->assertVisible('@success-message');
        });
    }

}
