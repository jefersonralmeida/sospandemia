<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('secret', 100)->nullable();
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->boolean('auto_authorize')->default(false);
            $table->timestamps();
        });

        // create the root Web UI app as a public PKCE client
        $now = Carbon::now();
        $redirectUrl = env('APP_URL');
        if (($uiPort = env('APP_UI_PORT', null)) !== null) {
            $redirectUrl = preg_replace('/:[0-9]+$/', ":$uiPort", $redirectUrl);
        }
        $redirectUrl .= '/oauth-callback';

        DB::table('oauth_clients')->insert(
            [
                'user_id' => null,
                'name' => 'Web App',
                'secret' => null,
                'redirect' => $redirectUrl,
                'personal_access_client' => false,
                'password_client' => false,
                'revoked' => false,
                'auto_authorize' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_clients');
    }
}
