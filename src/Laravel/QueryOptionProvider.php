<?php

namespace DoneSuperApp\QueryOption\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use DoneSuperApp\QueryOption\QueryOption;
use DoneSuperApp\QueryOption\QueryOptionFactory;

class QueryOptionProvider extends ServiceProvider
{
    public function register()
    {
        Request::macro('queryOption', function () {
            return QueryOptionFactory::createFromIlluminateRequest(app('request'));
        });

        $this->app->bind(QueryOption::class, function () {
            return QueryOptionFactory::createFromIlluminateRequest(app('request'));
        });
    }
}
