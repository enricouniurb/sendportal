<?php

declare(strict_types=1);

namespace App\Http\Controllers\Subscribers;

use App\Http\Middleware\OwnsCurrentWorkspace;
use Sendportal\Base\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Sendportal\Base\Repositories\TagTenantRepository;
use Sendportal\Base\Http\Controllers\Subscribers\SubscribersController as BaseSubscribersController;

class SubscribersController extends BaseSubscribersController
{    

    public function __construct(SubscriberTenantRepositoryInterface $subscriberRepo, TagTenantRepository $tagRepo)
    {
        parent::__construct($subscriberRepo, $tagRepo);

        $this->middleware(OwnsCurrentWorkspace::class)->only([
            'create',
            'store',
            'edit',            
            'update',
            'destroy'
        ]);
    }

}