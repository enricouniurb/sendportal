<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;
use Sendportal\Base\Facades\Helper;
use Sendportal\Pro\Models\AutomationSchedule;
use Sendportal\Base\Models\Message as BaseMessage;
use App\Models\Subscriber;

/**
 * @property int $id
 * @property string $hash
 * @property int $workspace_id
 * @property int $subscriber_id
 * @property string $source_type
 * @property int $source_id
 * @property string $recipient_email
 * @property string $subject
 * @property string $from_name
 * @property string $from_email
 * @property ?string $message_id
 * @property ?string $ip
 * @property int $open_count
 * @property int $click_count
 * @property Carbon|null $queued_at
 * @property Carbon|null $sent_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $bounced_at
 * @property Carbon|null $unsubscribed_at
 * @property Carbon|null $complained_at
 * @property Carbon|null $opened_at
 * @property Carbon|null $clicked_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property EloquentCollection $failures
 * @property Subscriber $subscriber
 * @property Campaign $source // NOTE(david): this should be updated to a mixed type when Automations are added.
 *
 * @property-read string $source_string
 *
 * @method static MessageFactory factory
 */
class Message extends BaseMessage
{

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }
  
}
