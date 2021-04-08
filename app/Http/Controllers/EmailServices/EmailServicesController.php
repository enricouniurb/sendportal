<?php

declare(strict_types=1);

namespace App\Http\Controllers\EmailServices;

use App\Http\Middleware\OwnsCurrentWorkspace;
use Sendportal\Base\Repositories\EmailServiceTenantRepository;
use Sendportal\Base\Http\Controllers\EmailServices\EmailServicesController as BaseEmailServiceController;

class EmailServicesController extends BaseEmailServiceController
{    

    public function __construct(EmailServiceTenantRepository $emailServices)
    {
        parent::__construct($emailServices);

        $this->middleware(OwnsCurrentWorkspace::class)->only([
            'create',
            'store',
            'edit',            
            'update',
            'delete'
        ]);
    }

}