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

	/*
	 * Constructeur qui reçoit la request
	 * puis la passe au constructeur de la classe parent : Controller
	 * (qui va placer la request dans l'attribut $this->request pour etre + facilement accessible)
	 */
	public function __construct(Request $request) {

		parent::__construct($request);

	}

	public function index(Request $request) {
		try {
			$tasks = $this->queryString(Task::where('deleted', 0))->get();
			return response()->json($tasks, 200, [], JSON_PRETTY_PRINT);
		}
		catch(ModelNotFoundException $modelNotFoundException){
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}


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
				//return $this->customJsonStatusResponse('error', 'task\'s project', 'not found');
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
			$users = Task::where('tasks.deleted', 0)->findOrFail($id); //task existant num $id


			$users = $this->queryString($users->users()->where('users.deleted', 0))->get(); //les users associés en appliquant les eventuels querystrings

			return response()->json($users, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

	public function getProjects($id) {
		$projects = Task::where('tasks.deleted', 0)->findOrFail($id); //task existant num $id

		$projects = $this->queryString($projects->project()->where('projects.deleted', 0))->get(); //les projects associé + application querystring
		try {

			return response()->json($projects, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

}

?>