<?php

namespace Modules\Helpcenter;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
    ];
}
