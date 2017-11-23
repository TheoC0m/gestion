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

class Task extends Model {
	protected $table = 'tasks';
	public $fillable = ['name', 'description', 'start', 'end', 'status', 'priority', 'project_id'];
	protected $hidden = ['deleted'];

	static public function createRules() {
		return [
			'name' => 'required|string|max:30',
			'description' => 'present|string|max:300',
			'start' => 'required|date',
			'end' => 'required|date',
			'status' =>  ['required', Rule::in(['in_progress', 'paused', 'finished', 'stoped'])],
			'priority' => 'required|integer',
			'project_id' => 'required|integer'
		];
	}

	static public function patchRules() {
		return [
			'name' => 'filled|string|max:30',
			'description' => 'string|max:300',
			'start' => 'filled|date',
			'end' => 'filled|date',
			'status' =>  ['filled', Rule::in(['in_progress', 'paused', 'finished', 'stoped'])],
			'priority' => 'filled|integer',
			'project_id' => 'filled|integer'
		];
	}

	public function project()
	{
		return $this->belongsTo('App\Project');
	}

}