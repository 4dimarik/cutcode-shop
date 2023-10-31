<?php

declare(strict_types=1);

namespace App\Traits\Models;


use Illuminate\Database\Eloquent\Model;

trait HasSlug
{

    protected static function bootHasSlug()
    {
        static::creating(
            function (Model $item) {
                $item->makeSlug();
            }
        );
    }

    protected function makeSlug(): void
    {
        $this->{$this->slugColumn()} = $this->{$this->slugColumn()}
            ?? $this->slugUnique(str($this->{$this->slugFrom()})->slug()->value());
    }

    public function slugColumn(): string
    {
        return 'slug';
    }

    public function slugFrom(): string
    {
        return 'title';
    }

    private function slugUnique(string $slug): string
    {
        $originalSlug = $slug;
        $i = 0;

        while ($this->isSlugExists($slug)) {
            $i++;
            $slug = $originalSlug . '-' . $i;
        }
        return $slug;

    }

    private function isSlugExists(string $slug): bool
    {
        $query = $this->newQuery()
            ->where(self::slugColumn(), $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes();
        return $query->exists();
    }
}
