<?php

declare(strict_types=1);

namespace App\Traits\Models;


use Illuminate\Database\Eloquent\Model;

trait HasSlug
{

    private static int $counter = 0;

    protected static function bootHasSlug()
    {
        static::creating(
            function (Model $item) {
                $item->slug = $item->slug
                    ?? str($item->{self::slugFrom()})->append(self::getPrefix($item))->slug();
            }
        );
    }

    public static function slugFrom(): string
    {
        return 'title';
    }

    private static function getPrefix(Model $item): string
    {
        return $item->query()->where(self::slugFrom(), '=', $item->{self::slugFrom()})->exists()
            ? $item->{self::slugFrom()} . "-" . self::$counter++
            : "";
    }

}