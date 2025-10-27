<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects=[
            ['name'=>'Ab', 'start_date'=>'2013-01-20','end_date'=>'2014-01-20', 'user_id'=>'1', 'create_by'=>'1'],
            ['name'=>'ccc', 'start_date'=>'2013-01-20', 'end_date'=>'2014-01-20', 'user_id'=>'1', 'create_by'=>'1'],
        ];

        foreach ($projects as $project){
            Project::create($project);
        }
    }
}
