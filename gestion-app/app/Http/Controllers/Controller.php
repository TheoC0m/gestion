<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController {
	// me permet de customiser la reponse json en cas d'echec de la validation de params
	protected function buildFailedValidationResponse(Request $request, array $errors) {
		// return ["code"=> 406 , "message" => "forbidden" , "errors" =>$errors];

		return response()->json(['error' => ['message' => $errors, 'type' => 'wrong parameters', 'code' => '422']], 422, [], JSON_PRETTY_PRINT);

	}

	/* permet de creer mes messages de retour cusotmises en json
	 * @param $status status de la reponse error ou success
	 * @param $ressourceName nom de la ressource que l'on manipule
	 * @param $action l'action que l'on voulait effectuer sur la ressource
	 * @param $data donnees additionnelles ex: l'objet cree ou mis a jour
	 */
	protected function customJsonStatusResponse($status, $ressourceName, $action, $data = null) {
		switch ($status) {

			case 'success' :
				return response()->json([$status => ['message' => $ressourceName . ' was ' . $action, 'type' => 'ressource ' . $action, 'code' => '200', 'data' => $data]], 200, [], JSON_PRETTY_PRINT);
				break;

			case 'error':
				return response()->json(['error' => ['message' => $ressourceName . ' ' . $action, 'type' => 'ressource ' . $action, 'code' => '404']], 404, [], JSON_PRETTY_PRINT);
				break;

			default :
				throw new \BadFunctionCallException($status . ' is not a valid status (success|error)');
				break;
		}
	}
}
