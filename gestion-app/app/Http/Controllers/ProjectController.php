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

	public function __construct() {
		//
	}

	public function index(Request $request) {
		$projects = Project::all()->where('deleted', 0);

		return response()->json($projects, 200, [], JSON_PRETTY_PRINT);
	}

	public function getProject($id) {
		try {
		$project = Project::where('deleted', 0)->findOrFail($id);
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
			$project = Project::where('deleted', 0)->findOrFail($id);

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

			$project = Project::where('deleted', 0)->findOrFail($id);
			$project->fill($request->all());
			$project->save();

			return $this->customJsonStatusResponse('success', 'project', 'patched', $project);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function deleteProject($id) {
		try {
			$project = Project::where('deleted', 0)->findOrFail($id);


			$project->deleted = 1;
			$project->save();

			return $this->customJsonStatusResponse('success', 'project', 'deleted');
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}


	public function getUsers($id) {
		try {
			$users = Project::where('projects.deleted', 0)->findOrFail($id) //project existant num $id
			->users()->where('users.deleted', 0) //ses users existants
			->orderBy('name', 'asc')->get(); //order par nom user asc

			return response()->json($users, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function getTasks($id) {
		try {
			$tasks = Project::where('projects.deleted', 0)->findOrFail($id) //user existant num $id
			->tasks()->where('tasks.deleted', 0) //ses projects existants
			->orderBy('start', 'asc')->get(); //order par task chronologique

			return response()->json($tasks, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}


}

?>