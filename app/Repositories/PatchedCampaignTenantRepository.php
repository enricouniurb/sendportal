<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sendportal\Base\Models\Campaign;
use Sendportal\Base\Repositories\Campaigns\MySqlCampaignTenantRepository;

class PatchedCampaignTenantRepository extends MySqlCampaignTenantRepository
{
    public function getCounts(Collection $campaignIds, int $workspaceId): array
    {
        if ($campaignIds->isEmpty()) {
            return [];
        }

        $counts = DB::table('sendportal_campaigns')
            ->where('sendportal_campaigns.workspace_id', $workspaceId)
            ->whereIn('sendportal_campaigns.id', $campaignIds)
            ->leftJoin('sendportal_messages', function ($join) use ($workspaceId) {
                $join->on('sendportal_messages.source_id', '=', 'sendportal_campaigns.id')
                    ->where('sendportal_messages.source_type', Campaign::class)
                    ->where('sendportal_messages.workspace_id', $workspaceId);
            })
            ->select('sendportal_campaigns.id as campaign_id')
            ->selectRaw(sprintf('count(%ssendportal_messages.id) as total', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %ssendportal_messages.opened_at IS NOT NULL then 1 end) as opened', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %ssendportal_messages.clicked_at IS NOT NULL then 1 end) as clicked', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %ssendportal_messages.sent_at IS NOT NULL then 1 end) as sent', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %ssendportal_messages.bounced_at IS NOT NULL then 1 end) as bounced', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %1$ssendportal_messages.id IS NOT NULL and %1$ssendportal_messages.sent_at IS NULL then 1 end) as pending', DB::getTablePrefix()))
            ->groupBy('sendportal_campaigns.id')
            ->orderBy('sendportal_campaigns.id')
            ->get();

        return $counts->keyBy('campaign_id')->toArray();
    }
}
