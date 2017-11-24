<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 02:00
 */

namespace App\Http\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Task;
use App\Project;

class TaskController extends Controller {

	public function __construct() {
		//
	}

	public function index(Request $request) {
		$tasks = Task::all()->where('deleted', 0);

		return response()->json($tasks, 200, [], JSON_PRETTY_PRINT);
	}

	public function getTask($id) {

		try {
			$task = Task::where('deleted', 0)->findOrFail($id);

			return response()->json($task, 200, [], JSON_PRETTY_PRINT);
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

	public function createTask(Request $request) {

		$this->validate($request, Task::createRules());

		try {
			//on tente de récupérer le project indiqué auquel apartient la task
			$project = Project::where('deleted', 0)->findOrFail($request->input('project_id'));

			$task = new Task([
				'name' => $request->input('name'),
				'description' => $request->input('description'),
				'start' => $request->input('start'),
				'end' => $request->input('end'),
				'status' => $request->input('status'),
				'priority' => $request->input('priority'),

			]);

			//on lie la task au project
			$project->tasks()->save($task);


		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'bounded project', 'not found');
		}

		return $this->customJsonStatusResponse('success', 'task', 'created', $task);
		//return response()->json($task);
	}


	public function updateTask(Request $request, $id) {
		$this->validate($request, Task::createRules());

		try {
			$task = Task::where('deleted', 0)->findOrFail($id);

			try {
				//on tente de récupérer le project indiqué auquel apartient la task
				$project = Project::where('deleted', 0)->findOrFail($request->input('project_id'));
			} catch (ModelNotFoundException $modelNotFoundException) {
				return $this->customJsonStatusResponse('error', 'task\'s project', 'not found');
			}

			$task->name = $request->input('name');
			$task->description = $request->input('description');
			$task->start = $request->input('start');
			$task->end = $request->input('end');
			$task->status = $request->input('status');
			$task->priority = $request->input('priority');

			//$task->save();
			//on lie la task au project
			$project->tasks()->save($task);

			return $this->customJsonStatusResponse('success', 'task', 'updated', $task);
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

	public function patchTask(Request $request, $id) {
		$this->validate($request, Task::patchRules());


		try {
			$task = Task::where('deleted', 0)->findOrFail($id);



			try {
				//si le project_id est envoyé par le client
				if ($request->has('project_id')) {
					//on tente de récupérer le project indiqué auquel apartient la task
					$project = Project::where('deleted', 0)->findOrFail($request->input('project_id'));
				}
				else {
					//le projet est celui lie a la task : on n'y touche pas
					$project = Project::where('deleted', 0)->findOrFail($task->project_id);
				}
			} catch (ModelNotFoundException $modelNotFoundException) {
				return $this->customJsonStatusResponse('error', 'task\'s project', 'not found');
			}


			


			/*$task->fill([
				'name' => $request->input('name'),
			'description' => $request->input('description'),
			'start' => $request->input('start'),
			'end' => $request->input('end'),
			'status' => $request->input('status'),
			'priority' => $request->input('priority')
			]);*/

			$task->fill($request->all());

			//on lie la task au project
			$project->tasks()->save($task);

			return $this->customJsonStatusResponse('success', 'task', 'patched', $task);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

	public function deleteTask($id) {
		try {
			$task = Task::where('deleted', 0)->findOrFail($id);


			$task->deleted = 1;
			$task->save();

			return $this->customJsonStatusResponse('success', 'task', 'deleted');
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

	public function getUsers($id) {
		try {
			$users = Task::where('tasks.deleted', 0)->findOrFail($id) //task existant num $id
			->users()->where('users.deleted', 0) //ses users existants
			->orderBy('name', 'asc')->get(); //order par nom user asc

			return response()->json($users, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

	public function getProjects($id) {
		try {
			$projects = Task::where('tasks.deleted', 0)->findOrFail($id) //user existant num $id
			->project()->where('projects.deleted', 0) //ses projects existants
			->orderBy('start', 'desc')->get(); //order par debut chronoloqgique

			return response()->json($projects, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

}

?>