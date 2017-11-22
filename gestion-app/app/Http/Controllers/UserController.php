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
class UserController extends Controller
{

    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $users = User::all();
        return response()->json($users);
    }
    
    public function getUser($id){

        $user  = User::find($id);

        return response()->json($user);
    }
    
    public function createUser(Request $request)
    {
        $user=User::create($request->all());
        return response()->json($user);
    }
    
    public function updateUser(Request $request, $id)
    {
        $user=User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->description = $request->input('description');
        $user->save();
        return response()->json($user);
    }
    
    public function deleteUser($id){
        $user  = User::find($id);
        $user->deleted = 1;
        $user->save();
        return response()->json('deleted');
    }

}
?>