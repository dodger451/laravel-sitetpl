<?php

namespace Tests\Browser;


use Sitetpl\Models\User;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Generator as Faker;

class ProfileEditTest extends DuskTestCase
{
    /**
     *
     * @return void
     */
    public function testProfileEditUpdates()
    {
        $password = 'password';
        $user = factory(User::class)->create(['password' => bcrypt($password)//
        ]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $newName = '_' . $user->name;
            $newEmail = '_' . $user->email;
            $browser->visit(new LoginPage())
                ->loginWithCreds($user->email, $password);
            $browser->visit('/profile/edit')
                ->assertInputValue('@name-input', $user->name)
                ->type('@name-input', $newName)
                ->assertInputValue('@email-input', $user->email)
                ->type('@email-input', $newEmail)
                ->click('@submit-button')
                ->assertPathIs('/home');
            $browser->visit('/profile/edit')
                ->assertInputValue('@name-input', $newName)
                ->assertInputValue('@email-input', $newEmail);

            $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $newName, 'email' => $newEmail,]);
        });
    }

    /**
     *
     * @return void
     */
    public function testProfileEditValidates()
    {
        $password = 'password';
        $user = factory(User::class)->create(['password' => bcrypt($password)//
        ]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser->visit('/profile/edit')
                ->assertInputValue('@name-input', $user->name);
            //name
            $browser->type('@name-input', '')
                ->click('@submit-button')
                ->assertPathIs('/profile/edit')
                ->assertVisible('@name-error');

            $otherUser = factory(User::class)->create();
            $browser->type('@name-input', $otherUser->name)
                ->click('@submit-button')
                ->assertPathIs('/profile/edit')
                ->assertVisible('@name-error');

            //email
            $browser->type('@email-input', '')
                ->click('@submit-button')
                ->assertPathIs('/profile/edit')
                ->assertVisible('@email-error');

            $browser->type('@email-input', 'email.com')
                ->click('@submit-button')
                ->assertPathIs('/profile/edit')
                ->assertVisible('@email-error');
        });
    }
}
