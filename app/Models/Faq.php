<?php

namespace Modules\Helpcenter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\People\Models\Person;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'is_published',
        'published_at',
        'author_id',
    ];

    protected array $translatable = [
        'question',
        'answer',
    ];

    protected $casts = [
        'is_published' => 'bool',
        'published_at' => 'datetime',
    ];

    /**
     * Auteur de la FAQ (Person).
     */
    public function author()
    {
        return $this->belongsTo(Person::class, 'author_id');
    }

    /**
     * Tags polymorphiques.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Lier la factory au modèle (important pour les modules).
     */
    protected static function newFactory()
    {
        return \Modules\Helpcenter\Database\Factories\FaqFactory::new();
    }

}
