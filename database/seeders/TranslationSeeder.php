<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        DB::table('translations')->insert([
            ['key' => 'welcome_message', 'locale' => 'en', 'value' => 'Welcome to our website!'],
            ['key' => 'welcome_message', 'locale' => 'my', 'value' => 'ကျွန်ုပ်တို့၏ ဝဘ်ဆိုက်သို့ ကြိုဆိုပါသည်!'],
            ['key' => 'goodbye_message', 'locale' => 'en', 'value' => 'Goodbye!'],
            ['key' => 'goodbye_message', 'locale' => 'my', 'value' => 'နောက်မှတွေ့မယ်!'],
        ]);
    }
}
