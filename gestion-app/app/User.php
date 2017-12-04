<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 01:44
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\HasApiTokens;


class User extends Model implements AuthenticatableContract, AuthorizableContract {
	use Authenticatable, Authorizable, HasApiTokens;

	protected $table = 'users';
	public $fillable = ['name', 'email', 'description', 'password'];
	protected $hidden = ['deleted, password'];

	static public function createRules() {
		return [
			'name' => 'required|string|max:30',
			'email' => 'required|email|max:254|unique:users,',
			'description' => 'present|string|max:140'
		];
	}

	static public function patchRules() {
		return [
			'name' => 'filled|string|max:30',
			'email' => 'filled|email|max:254',
			'description' => 'string|max:140'
		];
	}

	//relation n-n avec project : on utilise 'worksOn' pour designer la table pivot et on utilise sa colonne estimation
	public function projects() {
		return $this->belongsToMany('App\Project')->as('works_on')->withPivot('deleted', 'created_at', 'updated_at')->withTimestamps();
	}

	//relation n-n avec task : on utilise 'worksOn' pour designer la table pivot et on utilise sa colonne estimation
	public function tasks() {
		return $this->belongsToMany('App\Task')->as('works_on')->withPivot('estimation', 'created_at', 'updated_at')->withTimestamps();
	}

	public function findForPassport($username) {
		return $this->where('email', $username)->first();
	}


	//surcharge de la methode de hashage de passport
	//ici j'ai desactive le hachage car les psswd ne sont pour l'instant pas hashes en bd
	public function validateForPassportPasswordGrant($password)
	{
		/*$hasher = new HSAUserHasher(); // Or whomever does your hashing

		$result = $hasher->create_hash($password, $this->salt);
		$hashedPassword = $result['password'];

		return $hashedPassword == $this->password;*/

		return $password;
	}

}