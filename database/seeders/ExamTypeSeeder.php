<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamType;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run()
    {
        $examTypes = [
            ['name' => 'YAYA QUIZ', 'description' => 'Quiz for Youth', 'sort_order' => 1],
            ['name' => 'ADULT QUIZ', 'description' => 'Quiz for Adults', 'sort_order' => 2],
            ['name' => 'CHILDREN QUIZ', 'description' => 'Quiz for Children', 'sort_order' => 3],
        ];

        foreach ($examTypes as $type) {
            ExamType::create($type);
        }
    }
}
