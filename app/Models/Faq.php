<?php

namespace Modules\Helpcenter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Helpcenter\Database\Factories\AddressFactory;
use Modules\People\Models\Person;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Faq extends Model
{
    use HasSpatial;
    use HasFactory;

    protected $fillable = [
        'person_id',
        'line1','line2','city','region','postal_code','country_code',
        'location'
        // 'location' est géré via cast Point
    ];

    protected $casts = [
        'location' => Point::class,
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }
}
