<?php

namespace DoneSuperApp\QueryOption\Laravel;

use Illuminate\Support\Facades\App;
use DoneSuperApp\QueryOption\QueryOption;

abstract class AbstractQueryOptionCriteria
{
    use UsesQueryOption;

    abstract public function getFilterName(): string;

    public function getQueryOption(): QueryOption
    {
        return App::make(QueryOption::class);
    }
}
