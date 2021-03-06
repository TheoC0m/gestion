	<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    //return $router->app->version();
    $response = [
        'status' => 1,
        'data' => "Project Management webapp"
    ];
    return response()->json($response, 200, [], JSON_PRETTY_PRINT);
});

$router->get('/v1', function () use ($router) {
    //return $router->app->version();
    $response = [
        'status' => 1,
        'data' => "API v1"
    ];
    return response()->json($response, 200, [], JSON_PRETTY_PRINT);
});


//'namespace' => 'App\Http\Controllers'
// // e5e7a35ac033ecb7508588f9197f68ed


// commentaire pour désactiver l'authentification pendant le dev
//$router->group(['prefix' => 'v1', 'middleware' => 'auth:api'], function() use ($router)

	$router->group(['prefix' => 'v1'], function() use ($router)
{
    /*$app->post('register','UserController@create');
    $app->post('authorize','UserController@auth');
    $app->post('accesstoken','UserController@accesstoken');
    $app->post('refresh','UserController@refresh');
    $app->get('me','UserController@me');
    $app->post('logout','UserController@logout');
    $app->put('users/{id}','UserController@update');
    $app->get('users/{id}','UserController@view');
    $app->delete('users/{id}','UserController@deleteRecord');
    $app->get('users','UserController@index');
    $app->post('employees','EmployeesController@create');
    $app->put('employees/{id}','EmployeesController@update');
    $app->get('employees/{id}','EmployeesController@view');
    $app->delete('employees/{id}','EmployeesController@deleteRecord');
    $app->get('employees','EmployeesController@index');*/



    $router->get('users', 'UserController@index');
    $router->post('users', 'UserController@createUser');
    $router->get('users/{id}', 'UserController@getUser');
    $router->put('users/{id}', 'UserController@updateUser');
    $router->patch('users/{id}', 'UserController@patchUser');
    $router->delete('users/{id}', 'UserController@deleteUser');
    $router->get('users/{id}/projects', 'UserController@getProjects');
    $router->get('users/{id}/tasks', 'UserController@getTasks');

	$router->get('projects', 'ProjectController@index');
	$router->post('projects', 'ProjectController@createProject');
	$router->get('projects/{id}', 'ProjectController@getProject');
	$router->put('projects/{id}', 'ProjectController@updateProject');
	$router->patch('projects/{id}', 'ProjectController@patchProject');
	$router->delete('projects/{id}', 'ProjectController@deleteProject');
	$router->get('projects/{id}/users', 'ProjectController@getUsers');
	$router->get('projects/{id}/tasks', 'ProjectController@getTasks');

	$router->get('tasks', 'TaskController@index');
	$router->post('tasks', 'TaskController@createTask');
	$router->get('tasks/{id}', 'TaskController@getTask');
	$router->put('tasks/{id}', 'TaskController@updateTask');
	$router->patch('tasks/{id}', 'TaskController@patchTask');
	$router->delete('tasks/{id}', 'TaskController@deleteTask');
	$router->get('tasks/{id}/projects', 'TaskController@getProjects');
	$router->get('tasks/{id}/users', 'TaskController@getUsers');
});
