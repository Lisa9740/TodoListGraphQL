<?php

namespace Database\Seeders;

use App\Models\Todo;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Todo::insert([
            [
                'title' => "My task",
                'date_execution' => new DateTime(),
                'priority' => 'faible',
                'description' => "je suis une description",
                'is_executed' => false
            ],
            [
                'title' => "My task for home",
                'date_execution' => new DateTime(),
                'priority' => 'forte',
                'description' => "je suis une description",
                'is_executed' => false
            ],

        ]);

    }
}
