<?php

declare(strict_types=1);

namespace App\Http\Controllers\Subscribers;

use App\Http\Middleware\OwnsCurrentWorkspace;
use Sendportal\Base\Services\Subscribers\ImportSubscriberService;
use Sendportal\Base\Http\Controllers\Subscribers\SubscribersImportController as BaseSubscribersImportController;

class SubscribersImportController extends BaseSubscribersImportController
{    

    public function __construct(ImportSubscriberService $subscriberService)
    {
        parent::__construct($subscriberService);

        $this->middleware(OwnsCurrentWorkspace::class)->only([
            'show',
            'store',           
        ]);
    }

}