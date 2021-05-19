<?php

namespace App\Repositories;

use Carbon\CarbonPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Sendportal\Base\Repositories\Subscribers\MySqlSubscriberTenantRepository as BaseMySqlSubscriberTenantRepository;
use App\Models\Subscriber;

class MySqlSubscriberTenantRepository extends BaseMySqlSubscriberTenantRepository
{
    /** @var string */
    protected $modelName = Subscriber::class;
   
}
