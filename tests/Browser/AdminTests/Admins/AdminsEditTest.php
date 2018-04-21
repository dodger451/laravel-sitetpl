<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Tests\Browser\Pages\AdminLoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Generator as Faker;

class AdminsEditTest extends DuskTestCase
{


    /**
     *
     * @return void
     */
    public function testAdminEditUpdates()
    {
        $password = 'password';
        $user = factory(Admin::class)->create(['password' => bcrypt($password)//
        ]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $newName = '_' . $user->name;
            $newEmail = '_' . $user->email;
            $browser->visit(new AdminLoginPage())
                ->loginWithCreds($user->email, $password);
            $browser->visit('/admin/admins/' . $user->id . '/edit')
                ->assertInputValue('@name-input', $user->name)
                ->type('@name-input', $newName)
                ->assertInputValue('@email-input', $user->email)
                ->type('@email-input', $newEmail)
                ->click('@submit-button')
                ->assertPathIs('/admin/admins');
            $browser->visit('/admin/admins/' . $user->id . '/edit')
                ->assertInputValue('@name-input', $newName)
                ->assertInputValue('@email-input', $newEmail);

            $this->assertDatabaseHas('admins', ['id' => $user->id, 'name' => $newName, 'email' => $newEmail,]);
        });
    }

    /**
     *
     * @return void
     */
    public function testAdminEditValidates()
    {
        $password = 'password';
        $user = factory(Admin::class)->create(['password' => bcrypt($password)//
        ]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser->visit('/admin/admins/' . $user->id . '/edit')
                ->assertInputValue('@name-input', $user->name);
            //name
            $browser->type('@name-input', '')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/' . $user->id . '/edit')
                ->assertVisible('@name-error');

            $otherUser = factory(Admin::class)->create();
            $browser->type('@name-input', $otherUser->name)
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/' . $user->id . '/edit')
                ->assertVisible('@name-error');

            //email
            $browser->type('@email-input', '')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/' . $user->id . '/edit')
                ->assertVisible('@email-error');

            $browser->type('@email-input', 'email.com')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/' . $user->id . '/edit')
                ->assertVisible('@email-error');
        });
    }
}
