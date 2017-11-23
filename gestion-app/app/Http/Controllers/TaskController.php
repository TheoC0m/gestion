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

		$task = Task::create($request->all());

		return $this->customJsonStatusResponse('success', 'task', 'created', $task);
		//return response()->json($task);
	}

	public function updateTask(Request $request, $id) {
		$this->validate($request, Task::createRules());

		try {
			$task = Task::where('deleted', 0)->findOrFail($id);

			$task->name = $request->input('name');
			$task->description = $request->input('description');
			$task->start = $request->input('start');
			$task->end = $request->input('end');
			$task->status = $request->input('status');
			$task->priority = $request->input('priority');

			$task->save();

			return $this->customJsonStatusResponse('success', 'task', 'updated', $task);
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'task', 'not found');
		}
	}

	public function patchTask(Request $request, $id) {
		$this->validate($request, Task::patchRules());
		try {

			$task = Task::where('deleted', 0)->findOrFail($id);
			$task->fill($request->all());
			$task->save();

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

}

?>