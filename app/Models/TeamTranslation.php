<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamTranslation extends Model
{
    protected $guarded = [];

    /**
     * @return BelongsTo<TeamMember, $this>
     */
    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }
}
