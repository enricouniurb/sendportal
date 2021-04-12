<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FromEmail;
use Sendportal\Base\Repositories\BaseTenantRepository;

class FromEmailRepository extends BaseTenantRepository
{
    /** @var string */
    protected $modelName = FromEmail::class;
}
