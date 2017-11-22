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

class ProjectController extends Controller {

	public function __construct() {
		//
	}

	public function index(Request $request) {
		$projects = Project::all()->where('deleted', 0);
		return response()->json($projects);
	}

	public function getProject($id) {
		$project = Project::where('deleted', 0)->find($id);
		if ($project instanceof Project) {
			return response()->json($project, 200, [], JSON_PRETTY_PRINT);
		}
		else {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function createProject(Request $request) {

		$this->validate($request, Project::rules());

		$project = Project::create($request->all());
		return $this->customJsonStatusResponse('success', 'project', 'created', $project);
		//return response()->json($project);
	}

	public function updateProject(Request $request, $id) {
		$this->validate($request, Project::rules());
		$project = Project::where('deleted', 0)->find($id);

		if ($project instanceof Project) {
			$project->name = $request->input('name');
			$project->email = $request->input('email');
			if ($request->has('description')) {
				$project->description = $request->input('description');
			}
			$project->save();
			return $this->customJsonStatusResponse('success', 'project', 'updated', $project);
		}
		else {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

	public function deleteProject($id) {
		$project = Project::where('deleted', 0)->find($id);

		if ($project instanceof Project) {
			$project->deleted = 1;
			$project->save();
			return $this->customJsonStatusResponse('success', 'project', 'deleted');
		}
		else {
			return $this->customJsonStatusResponse('error', 'project', 'not found');
		}
	}

}

?>