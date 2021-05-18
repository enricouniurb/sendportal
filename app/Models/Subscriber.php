<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\SubscriberFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;
use Sendportal\Base\Models\Subscriber as BaseSubscriber;

/**
 * @property int $id
 * @property int $workspace_id
 * @property string $hash
 * @property string $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property array|null $meta
 * @property Carbon|null $unsubscribed_at
 * @property int|null $unsubscribed_event_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property EloquentCollection $tags
 * @property EloquentCollection $messages
 *
 * @property string $text1
 * @property string $text2
 * @property array $data
 * 
 * @property-read string $full_name
 *
 * @method static SubscriberFactory factory
 */
class Subscriber extends BaseSubscriber
{
    /** @var string[] */
    protected $fillable = [
        'hash',
        'email',
        'first_name',
        'last_name',
        'meta',
        'unsubscribed_at',
        'unsubscribe_event_id',
        'text1',
        'text2',
        'data'
    ];

}
