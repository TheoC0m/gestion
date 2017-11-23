<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 17:40
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Project extends Model {
	protected $table = 'projects';
	public $fillable = ['name', 'description', 'start', 'end', 'status', 'real_end'];
	protected $hidden = ['deleted'];

	static public function createRules() {
		return [
			'name' => 'required|string|max:30',
			'description' => 'present|string|max:300',
			'start' => 'required|date',
			'end' => 'required|date',
			'status' =>  ['required', Rule::in(['in_progress', 'paused', 'finished', 'stoped'])],
			'real_end' => 'required|date'
		];
	}

	static public function patchRules() {
		return [
			'name' => 'filled|string|max:30',
			'description' => 'string|max:300',
			'start' => 'filled|date',
			'end' => 'filled|date',
			'status' =>  ['filled', Rule::in(['in_progress', 'paused', 'finished', 'stoped'])],
			'real_end' => 'filled|date'
		];
	}

	public function tasks()
	{
		return $this->hasMany('App\Task');
	}

}