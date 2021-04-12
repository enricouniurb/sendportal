<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ApiTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sendportal\Base\Models\BaseModel;

/**
 * @property int $id
 * @property string $from_name
 * @property string $from_email
 * @property int $workspace_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static ApiTokenFactory factory
 */
class FromEmail extends BaseModel
{
    use HasFactory;

    /** @var array */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'workspace_id' => 'integer'
    ];

  
}
