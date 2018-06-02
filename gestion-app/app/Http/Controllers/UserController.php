<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 02:00
 */

namespace App\Http\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {


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
			//on n'utilise pas all() car renvoie une collection et non une query
		$users = $this->queryString(User::query())->get();
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}

		return response()->json($users, 200, [], JSON_PRETTY_PRINT);
	}

	public function getUser($id) {

		//$this->queryString();



		try {
			$user = User::findOrFail($id);

			return response()->json($user, 200, [], JSON_PRETTY_PRINT);
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

	public function createUser(Request $request) {

		$this->validate($request, User::createRules());

		$user = User::create($request->all());

		return $this->customJsonStatusResponse('success', 'user', 'created', $user);
		//return response()->json($user);
	}

	public function updateUser(Request $request, $id) {
		$this->validate($request, User::createRules());

		try {
			$user = User::findOrFail($id);

			$user->name = $request->input('name');
			$user->email = $request->input('email');
			$user->description = $request->input('description');

			$user->save();

			return $this->customJsonStatusResponse('success', 'user', 'updated', $user);
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

	public function patchUser(Request $request, $id) {
		$this->validate($request, User::patchRules());
		try {

			$user = User::findOrFail($id);
			$user->fill($request->all());
			$user->save();

			return $this->customJsonStatusResponse('success', 'user', 'patched', $user);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

	public function deleteUser($id) {
		try {
			$user = User::findOrFail($id);


			$user->delete();


			return $this->customJsonStatusResponse('success', 'user', 'deleted');
		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

	public function getProjects($id) {
		try {
			$projects = User::findOrFail($id);

			//user existant num $id
			$projects = $this->queryString($projects->projects())->get(); //projects liés + querystring

			return response()->json($projects, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

	public function getTasks($id) {
		try {
			$tasks = User::findOrFail($id); //user existant num $id

			$tasks = $this->queryString($tasks->tasks())->get(); //ses projects lies + querystring


			return response()->json($tasks, 200, [], JSON_PRETTY_PRINT);

		} catch (ModelNotFoundException $modelNotFoundException) {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
		catch (QueryException $queryException) {
			//var_dump($queryException);
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

}

?>