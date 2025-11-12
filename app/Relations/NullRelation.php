<?php

namespace Modules\Helpcenter\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class NullRelation extends Relation
{
    public function __construct(Model $parent)
    {
        parent::__construct($parent->newQuery(), $parent);
    }
    public function addConstraints() {}
    public function addEagerConstraints(array $models) {}
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $m) $m->setRelation($relation, new Collection());
        return $models;
    }
    public function match(array $models, Collection $results, $relation) { return $models; }
    public function getResults() { return new Collection(); }
}
