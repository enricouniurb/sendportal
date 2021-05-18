<?php

declare(strict_types=1);

namespace App\Services\Content;

use Exception;
use Sendportal\Base\Models\Campaign;
use Sendportal\Base\Models\Message;
use Sendportal\Base\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Sendportal\Base\Traits\NormalizeTags;
use Sendportal\Pro\Repositories\AutomationScheduleRepository;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Sendportal\Base\Services\Content\MergeContentService as BaseMergeContentService;

class MergeContentService extends BaseMergeContentService
{

    public function __construct(
        CampaignTenantRepositoryInterface $campaignRepo,
        CssToInlineStyles $cssProcessor
    ) {
        parent::__construct($campaignRepo, $cssProcessor);
    }

    protected function compileTags(string $content): string
    {
        $tags = [
            'email',
            'first_name',
            'last_name',
            'unsubscribe_url',
            'webview_url',
            'text1',
            'text2'
        ];

        foreach ($tags as $tag) {
            $content = $this->normalizeTags($content, $tag);
        }

        return $content;
    }

    protected function mergeSubscriberTags(string $content, Message $message): string
    {
        $tags = [
            'email' => $message->recipient_email,
            'first_name' => $message->subscriber ? $message->subscriber->first_name : '',
            'last_name' => $message->subscriber ? $message->subscriber->last_name : '',
            'text1' => $message->subscriber ? $message->subscriber->text1 : '',
            'text2' => $message->subscriber ? $message->subscriber->text2 : ''
        ];

        foreach ($tags as $key => $replace) {
            $content = str_ireplace('{{' . $key . '}}', $replace, $content);
        }

        return $content;
    }

  
}
