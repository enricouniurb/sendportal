<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tags;

use App\Http\Middleware\OwnsCurrentWorkspace;
use Sendportal\Base\Repositories\TagTenantRepository;
use Sendportal\Base\Http\Controllers\Tags\TagsController as BaseTagsController;

class TagsController extends BaseTagsController
{    

    public function __construct(TagTenantRepository $tagRepository)
    {
        parent::__construct($tagRepository);

        $this->middleware(OwnsCurrentWorkspace::class)->only([
            'create',
            'store',
            'edit',            
            'update',
            'destroy'
        ]);
    }

}