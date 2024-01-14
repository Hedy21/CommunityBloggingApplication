<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Programming;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            "နည်းလမ်းများ","Tutorial","Tips","Website","Frontend"
        ];
        $programmings = [
          "php", "laravel", "java", "j2ee", "python"
        ];

        foreach($tags as $t){
            Tag::create([
                'slug'=>Str::slug($t),
                'name'=>$t,
            ]);
        }
        foreach($programmings as $t){
            Programming::create([
                'slug'=>Str::slug($t),
                'name'=>$t,
            ]);
        }
    }
}
