<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function vetoes(): HasMany
    {
        return $this->hasMany(MatchVeto::class, 'map_id');
    }
}
