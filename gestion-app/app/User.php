<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 01:44
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class User extends Model {
	protected $table = 'users';
	public $fillable = ['name', 'email', 'description'];
	protected $hidden = ['deleted'];

	static public function createRules() {
		return [
			'name' => 'required|string|max:30',
			'email' => 'required|email|max:254',
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

}