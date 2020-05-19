<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($project_id = null)
    {
    	$projects = Project::orderBy('name', 'ASC')
    						->get(['id', 'name']);
    	$viewParameters[] = 'projects';
    	
    	if($project_id === 'anonimous') {
    		$tasks = Task::orderBy('display_order', 'ASC')
    						->whereNull('project_id')
    						->get(['id', 'project_id', 'name', 'display_order', 'priority', 'created_at']);

    	} else if($project_id) {
	    	$tasks = Task::orderBy('display_order', 'ASC')
	    				->where('project_id', $project_id)
	    				->get(['id', 'project_id', 'name', 'display_order', 'priority', 'created_at']);

    	} else {
	    	$tasks = Task::orderBy('display_order', 'ASC')
	    				->get(['id', 'project_id', 'name', 'display_order', 'priority', 'created_at']);
    	}
    	$viewParameters[] = 'tasks';


    	return view('welcome', compact($viewParameters));
    }

    public function store(Request $request)
    {
    	$action = '';
    	if($request->input('id')) {
	    	$task = Task::where('id', $request->input('id'))->first();
	    	$action = 'updated';

    	} else {
	    	$task = new Task;
	    	$action = 'new';
    	}

    	$task->project_id = $request->input('projectId') ?? null;
    	$task->name = $request->input('name');
    	$task->display_order = $request->input('displayOrder');
    	$task->priority = $request->input('priority');

    	$task->save();

    	return response()->json([$task, $action]);

    }


    public function update(Request $request)
    {
    	$updatableData = [];
    	$tasks = $request->input('tasks');
    	//  return response()->json($tasks);

    	foreach($tasks as $key => $task) {
    		if(isset($task['id']))
    			Task::where('id', $task['id'])
    				->update(['display_order' => $key, 'priority' => $key]);
    	}

    	return response()->json('List update done');
    }

    public function destroy(Task $task)
    {
    	$task->delete();

    	return response()->json(['action' => 'deleted']);
    }
}
