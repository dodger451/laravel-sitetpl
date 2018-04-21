<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Sitetpl\Models\Admin;

class CreateRoot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        factory(Admin::class)->create(['name' => 'root', 'email' => 'root@sitetpl.com', 'password' => '$2y$10$.2JzU1hHs4hDESDUlZ3miehbetlGQW3CWuPrW2WqJkVGfwcD0o66u'//'password'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Admin::where('name', 'root')
            ->delete();
    }
}
