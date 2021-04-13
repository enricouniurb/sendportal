<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaigns;

use Exception;
use Illuminate\Contracts\View\View as ViewContract;
use App\Http\Middleware\OwnsCurrentWorkspace;
use App\Repositories\FromEmailRepository;
use App\Models\FromEmail;
use Sendportal\Base\Facades\Sendportal;
use Sendportal\Base\Models\EmailService;
use Sendportal\Base\Repositories\EmailServiceTenantRepository;
use Sendportal\Base\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Sendportal\Base\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Sendportal\Base\Repositories\TagTenantRepository;
use Sendportal\Base\Repositories\TemplateTenantRepository;
use Sendportal\Base\Services\Campaigns\CampaignStatisticsService;
use Sendportal\Base\Http\Controllers\Campaigns\CampaignsController as BaseCampaignsController;

class CampaignsController extends BaseCampaignsController
{    

    /** @var FromEmailRepository */
    protected $fromEmails;

    public function __construct(
        CampaignTenantRepositoryInterface $campaigns,
        TemplateTenantRepository $templates,
        TagTenantRepository $tags,
        EmailServiceTenantRepository $emailServices,
        SubscriberTenantRepositoryInterface $subscribers,
        CampaignStatisticsService $campaignStatisticsService, 
        FromEmailRepository $fromEmails
    ) {
        parent::__construct($campaigns,$templates,$tags,$emailServices,$subscribers,$campaignStatisticsService);

        $this->fromEmails = $fromEmails;
    }

        /**
     * @throws Exception
     */
    public function create(): ViewContract
    {
        $workspaceId = Sendportal::currentWorkspaceId();
        $templates = [null => '- None -'] + $this->templates->pluck($workspaceId);
        $fromEmails = $this->fromEmails->all(Sendportal::currentWorkspaceId(), 'id');
        $emailServices = $this->emailServices->all(Sendportal::currentWorkspaceId(), 'id', ['type'])
            ->map(static function (EmailService $emailService) {
                $emailService->formatted_name = "{$emailService->name} ({$emailService->type->name})";
                return $emailService;
            });

        return view('sendportal::campaigns.create', compact('templates', 'emailServices','fromEmails'));
    }

     /**
     * @throws Exception
     */
    public function edit(int $id): ViewContract
    {
        $workspaceId = Sendportal::currentWorkspaceId();
        $campaign = $this->campaigns->find($workspaceId, $id);
        $fromEmails = $this->fromEmails->all(Sendportal::currentWorkspaceId(), 'id');
        $emailServices = $this->emailServices->all($workspaceId, 'id', ['type'])
            ->map(static function (EmailService $emailService) {
                $emailService->formatted_name = "{$emailService->name} ({$emailService->type->name})";
                return $emailService;
            });
        $templates = [null => '- None -'] + $this->templates->pluck($workspaceId);

        return view('sendportal::campaigns.edit', compact('campaign', 'emailServices', 'templates','fromEmails'));
    }

}