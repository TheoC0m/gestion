<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\JsonResponse;

class Controller extends BaseController {

	protected $request;

	/*
	 * Les classes enfant passe leur request au constructeur
	 * place request dans un attribut pour etre + facilement accessible
	 */
	public function __construct(Request $request) {
		$this->request = $request;

	}

	// me permet de customiser la reponse json en cas d'echec de la validation de params
	protected function buildFailedValidationResponse(Request $request, array $errors) {
		// return ["code"=> 406 , "message" => "forbidden" , "errors" =>$errors];

		return response()->json(['error' => ['message' => $errors, 'type' => 'wrong parameter(s)', 'code' => '422']], 422, [], JSON_PRETTY_PRINT);

	}

	/*
	 * Analyse les query param et retourne
	 * des 'bouts' de requete qui seront ajoutes au sein d'une requete bdd
	 * ->where(..) etc..
	 */
	protected function queryString($q){
		//print($this->request->getQueryString());

		//print($q);
		//var_dump($q);
		//var_dump($q->getModel() );

		//On recupere le model sur lequel porte la requete
		$model = $q->getModel();

		$queryParams = [];
		//parcours la query string ( ?foo=bar&toto=bidule) et la place dans le tableau $queryParams
		parse_str($this->request->getQueryString(), $queryParams);

		//on attribue la requete d'origine a dataBquery ainsi si il n'y a pas de queryparam on renvoie juste la requete d'origine
		$dataBaseQuery = $q;
		$sortData = array('created_at','asc');

		//pour chaque paire de query/value du tableau
		foreach ($queryParams as $query => $value){
			//print($query . "=" . $value . "\n");

			//si la query (le filtre) fait partie de ceux disponibles
			switch ($query){

				case 'status':
					//on ajoute une clause where a la requete DB
					 $dataBaseQuery = $dataBaseQuery->where('status', $value);
					break;

				case 'project_id':
					//on ajoute une clause where a la requete DB
					$dataBaseQuery = $dataBaseQuery->where('project_id', $value);
					break;

				case 'task_id':
					//on ajoute une clause where a la requete DB
					$dataBaseQuery = $dataBaseQuery->where('task_id', $value);
					break;

				case 'user_id':
					//on ajoute une clause where a la requete DB
					$dataBaseQuery = $dataBaseQuery->where('user_id', $value);
					break;

				case 'asc':
					if(Schema::hasColumn($model->getTable(), $value)) {
						$sortData[0] = $value;
						$sortData[1] = 'asc';
					}
					break;

				case 'desc':
					if(Schema::hasColumn($model->getTable(), $value)) {
						$sortData[0] = $value;
						$sortData[1] = 'desc';
					}
					break;

				default:
					//print($dataBaseQuery);
					break;
			}
		}
		return $dataBaseQuery->orderBy($sortData[0], $sortData[1]);
	}

	/* permet de creer mes messages de retour cusotmises en json
	 * @param $status status de la reponse error ou success
	 * @param $ressourceName nom de la ressource que l'on manipule
	 * @param $action l'action que l'on voulait effectuer sur la ressource
	 * @param $data donnees additionnelles ex: l'objet cree ou mis a jour
	 */
	protected function customJsonStatusResponse(String $status, String $ressourceName, String $action, $data = null) {
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
