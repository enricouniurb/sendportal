<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Sendportal\Base\Facades\Sendportal;
use App\Models\Message;
use Sendportal\Base\Repositories\Messages\MessageTenantRepositoryInterface;
use App\Services\Content\MergeContentService;
use Sendportal\Base\Services\Content\MergeSubjectService;
use Sendportal\Base\Services\Messages\DispatchMessage;
use Sendportal\Base\Http\Controllers\MessagesController as BaseMessagesController;

class MessagesController extends BaseMessagesController
{

    public function __construct(
        MessageTenantRepositoryInterface $messageRepo,
        DispatchMessage $dispatchMessage,
        MergeContentService $mergeContentService,
        MergeSubjectService $mergeSubjectService
    ) {

        parent::__construct($messageRepo, $dispatchMessage, $mergeContentService, $mergeSubjectService);    

    }
  
}
