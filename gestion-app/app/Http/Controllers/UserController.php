<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 02:00
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

	public function __construct() {
		//
	}

	public function index(Request $request) {
		$users = User::all()->where('deleted', 0);
		return response()->json($users);
	}

	public function getUser($id) {
		$user = User::where('deleted', 0)->find($id);
		if ($user instanceof User) {
			return response()->json($user, 200, [], JSON_PRETTY_PRINT);
		}
		else {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

	public function createUser(Request $request) {

		$this->validate($request, User::rules());

		$user = User::create($request->all());
		return $this->customJsonStatusResponse('success', 'user', 'created', $user);
		//return response()->json($user);
	}

	public function updateUser(Request $request, $id) {
		$this->validate($request, User::rules());
		$user = User::where('deleted', 0)->find($id);

		if ($user instanceof User) {
			$user->name = $request->input('name');
			$user->email = $request->input('email');
			//on evite de supprimer la description optionelle si elle n'est pas envoyee
			if ($request->has('description')) {
				$user->description = $request->input('description');
			}
			$user->save();
			return $this->customJsonStatusResponse('success', 'user', 'updated', $user);
		}
		else {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

	public function deleteUser($id) {
		$user = User::where('deleted', 0)->find($id);

		if ($user instanceof User) {
			$user->deleted = 1;
			$user->save();
			return $this->customJsonStatusResponse('success', 'user', 'deleted');
		}
		else {
			return $this->customJsonStatusResponse('error', 'user', 'not found');
		}
	}

}

?>