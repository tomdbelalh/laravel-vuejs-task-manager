<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
    	$results = [];
    	$projects = Project::orderBy('name', 'ASC')
    						->get(['id', 'name']);


    	foreach($projects as $project) {
    		$results[] = [
    			'id' => $project->id,
    			'name' => $project->name,
    			'url' => route('tasks.dashboard', ['project_id' => $project->id]),
    			'taskCount' => $project->tasks->count(),
    		];
    	}

    	// dd($projects);
    	return response()->json($results);
    }
}
