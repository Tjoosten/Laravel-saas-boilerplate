<?php

Auth::routes();

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {

	Route::get('/impersonate', 'ImpersonateController@index')->name('impersonate.index');
	Route::post('/impersonate', 'ImpersonateController@start')->name('impersonate.start');
	Route::post('/impersonate', 'ImpersonateController@start')->name('impersonate.start');
});

Route::delete('/admin/impersonate', 'Admin\ImpersonateController@destroy')->name('admin.impersonate.destroy');

Route::group(['middleware' => ['auth', 'subscription.active']], function() {

	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});

Route::group(['middleware' => 'guest'], function() {
    Route::get('/login/twofactor', 'Auth\TwoFactorLoginController@index')->name('login.twofactor.index');
    Route::post('/login/twofactor', 'Auth\TwoFactorLoginController@verify')->name('login.twofactor.verify');
});

Route::group(['prefix' => 'account', 'middleware' => ['auth'], 'as' => 'account.'], function() {
	Route::get('/', 'Account\AccountController@index')->name('index');

	/**
	 * profile
	 */
	Route::get('profile', 'Account\ProfileController@index')->name('profile.index');
	Route::post('profile', 'Account\ProfileController@store')->name('profile.store');

	/**
	 * password
	 */
	Route::get('password', 'Account\PasswordController@index')->name('password.index');
	Route::post('password', 'Account\PasswordController@store')->name('password.store');

	/**
	 * Tokens
	 */
	Route::get('tokens', 'Account\TokenController@index')->name('tokens.index');

	/**
	 * Deactivate
	 */
	Route::get('deactivate', 'Account\DeactivateController@index')->name('deactivate.index');
	Route::post('deactivate', 'Account\DeactivateController@store')->name('deactivate.store');

	/**
	 * Two factor
	 */
	Route::get('twofactor', 'Account\TwoFactorController@index')->name('twofactor.index');
	Route::post('twofactor', 'Account\TwoFactorController@store')->name('twofactor.store');
	Route::post('twofactor/verify', 'Account\TwoFactorController@verify')->name('twofactor.verify');
	Route::delete('twofactor', 'Account\TwoFactorController@destroy')->name('twofactor.destroy');

/**
 * Account Activation
 */

Route::group(['prefix' => 'activation', 'as' => 'activation.', 'middleware' => ['guest']], function() {
    Route::get('/resend', 'Auth\ActivationResendController@index')->name('resend');
    Route::post('/resend', 'Auth\ActivationResendController@store')->name('resend.store');
    Route::get('/{confirmation_token}', 'Auth\ActivationController@activate')->name('activate');
});

/**
 * Plan Activation
 */

Route::group(['prefix' => 'plans', 'as' => 'plans.', 'middleware' => ['subscription.inactive']], function() {
    Route::get('/', 'Subscription\PlanController@index')->name('index');
    Route::get('/teams', 'Subscription\PlanTeamController@index')->name('teams.index');
});


/**
 * Webhooks
 */
Route::post('/webhooks/stripe', 'Webhooks\StripeWebhookController@handleWebhook')->name('webhook');

