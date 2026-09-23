<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships {
    // lets instances load relationships if they're queried in the URL

    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for, // for what should it load relationships
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany {
        $relations = $relations ?? $this->relations ?? []; // if parameter not present, use field from the class

        foreach($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                function($q) use ($for, $relation) {
                    if($for instanceof Model) {
                        $for->load($relation); // load() and $for because it is already loaded
                    }
                    else {
                        $q->with($relation); // with because it's just initializing
                    }
                }
            );
        }

        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool {
        $include = request()->query('include');

        if(!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
}

