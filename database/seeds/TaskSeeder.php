<?php

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
        	[
        		'project_id' => 1,
        		'name' => 'A1 Do something',
        	],
        	[
        		'project_id' => 1,
        		'name' => 'A2 Do something more',
        	],
        	[
        		'project_id' => null,
        		'name' => 'Anonymous Do work ABC',
        	],
        	[
        		'project_id' => null,
        		'name' => 'Anonymous Do work DEF',
        	],
       		[	
        		'project_id' => null,
        		'name' => 'Anonymous Do work GHI',
        	],

        ]);
    }
}
