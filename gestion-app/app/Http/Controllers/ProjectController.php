<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 02:00
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectController extends Controller {

	/*
 * Constructeur qui reçoit la request
 * puis la passe au constructeur de la classe parent : Controller
 * (qui va placer la request dans l'attribut $this->request pour etre + facilement accessible)
 */
	public function __construct(Request $request) {

		parent::__construct($request);

	}

	public function index(Request $request) {
		try{
			$projects = $this->queryString(Project::query())->get();
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}

		return response()->json($projects, 200, [], JSON_PRETTY_PRINT);
	}

	public function getProject($id) {
		try {
		$project = Project::findOrFail($id);
			return response()->json($project, 200, [], JSON_PRETTY_PRINT);
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function createProject(Request $request) {

		$this->validate($request, Project::createRules());

		$project = Project::create($request->all());

		return $this->customJsonStatusResponse('success', 'project', 'created', $project);
		//return response()->json($project);
	}

	public function updateProject(Request $request, $id) {
		$this->validate($request, Project::createRules());

		try {
			$project = Project::findOrFail($id);

			$project->name = $request->input('name');
			$project->start = $request->input('start');
			$project->end = $request->input('end');
			$project->status = $request->input('status');
			$project->real_end = $request->input('real_end');
			$project->description = $request->input('description');

			$project->save();

			return $this->customJsonStatusResponse('success', 'project', 'updated', $project);
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function patchProject(Request $request, $id) {
		$this->validate($request, Project::patchRules());
		try {

			$project = Project::findOrFail($id);
			$project->fill($request->all());
			$project->save();

			return $this->customJsonStatusResponse('success', 'project', 'patched', $project);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function deleteProject($id) {
		try {
			$project = Project::findOrFail($id);


			$project->delete();

			return $this->customJsonStatusResponse('success', 'project', 'deleted');
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}


	public function getUsers($id) {
		try {
			$users = Project::where('projects.deleted', 0)->findOrFail($id); //project existant num $id

			$users = $this->queryString($users->users()->where('users.deleted', 0))->get(); //ses users lies + querystring


			return response()->json($users, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function getTasks($id) {
		try {
			$tasks = Project::where('projects.deleted', 0)->findOrFail($id); //user existant num $id
			$tasks = $this->queryString($tasks->tasks()->where('tasks.deleted', 0))->get(); //ses projects lies + querystring


			return response()->json($tasks, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}


}

?>