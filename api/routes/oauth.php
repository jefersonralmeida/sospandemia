<?php

Route::middleware(['web', 'auth', 'verified'])->group(function() {
    Route::get('authorize', 'AuthorizationController@authorize')->name('passport.authorizations.authorize');
    Route::post('authorize', 'ApproveAuthorizationController@approve')->name('passport.authorizations.approve');
    Route::delete('authorize', 'DenyAuthorizationController@deny')->name('passport.authorizations.deny ');
    Route::post('clients', 'ClientController@store')->name('passport.clients.store');
    Route::get('clients', 'ClientController@forUser')->name('passport.clients.index');
    Route::delete('clients/{client_id}', 'ClientController@destroy')->name('passport.clients.destroy');
    Route::put('clients/{client_id}', 'ClientController@update')->name('passport.clients.update');
    Route::post('personal-access-tokens ', 'PersonalAccessTokenController@store')->name('passport.personal.tokens.store');
    Route::get('personal-access-tokens', 'PersonalAccessTokenController@forUser')->name('passport.personal.tokens.index');
    Route::delete('personal-access-tokens/{token_id}', 'PersonalAccessTokenController@destroy')->name('passport.personal.tokens.destroy');
    Route::get('scopes', 'ScopeController@all')->name('passport.scopes.index');
    Route::post('token/refresh', 'TransientTokenController@refresh')->name('passport.token.refresh');
    Route::get('tokens', 'AuthorizedAccessTokenController@forUser')->name('passport.tokens.index');
    Route::delete('tokens/{token_id}', 'AuthorizedAccessTokenController@destroy')->name('passport.tokens.destroy');
});

Route::middleware('throttle')->post('token', 'AccessTokenController@issueToken')->name('passport.token');
