<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $seen = [];

        User::query()->orderBy('id')->get()->each(function (User $user) use (&$seen): void {
            $base = Str::slug($user->name);

            if ($base === '') {
                $base = 'user';
            }

            if (! isset($seen[$base])) {
                $seen[$base] = 1;
                $slug = $base;
            } else {
                $seen[$base]++;
                $slug = $base.'-'.$seen[$base];
            }

            while (User::query()->where('id', '!=', $user->id)->where('slug', $slug)->exists()) {
                $seen[$base]++;
                $slug = $base.'-'.$seen[$base];
            }

            if ($user->slug !== $slug) {
                $user->forceFill(['slug' => $slug])->save();
            }
        });
    }

    public function down(): void
    {
        // noop
    }
};
