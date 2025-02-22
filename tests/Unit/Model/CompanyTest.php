<?php

use Crater\Models\Company;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
    Artisan::call('db:seed', ['--class' => 'DemoSeeder', '--force' => true]);
});

test('company has one customer', function () {
    $company = Company::factory()->hasCustomer()->create();

    $this->assertTrue($company->customer()->exists());
});

test('company has many company setings', function () {
    $company = Company::factory()->hasSettings(5)->create();

    $this->assertCount(5, $company->settings);

    $this->assertTrue($company->settings()->exists());
});

test('a company belongs to many users', function () {
    $company = Company::factory()->hasUsers(5)->create();

    $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $company->users);
});
