<?php

declare(strict_types=1);

namespace App\Faker;


use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FakerImageProvider extends Base
{
    public function randomTestImage(string $base_dir, string $images_dir): string|null
    {
        if (Str::length($base_dir) > 0 and Str::length($images_dir) > 0) {
            if (!Storage::exists($images_dir)) {
                Storage::makeDirectory($images_dir);
            }

            $imageFile = \Faker\Factory::create()->file(
                base_path($base_dir),
                Storage::path(Str::after(trim($images_dir, "/"), 'app/public/')),
                false
            );

            return "/storage/" . trim(
                    Str::after(Storage::path($images_dir), 'storage'),
                    "\\/"
                ) . "/" . $imageFile;
        }
        return null;
    }

}
