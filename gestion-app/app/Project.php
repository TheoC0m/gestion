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

	static public function rules() {
		return [
			'name' => 'required|max:30',
			'description' => 'nullable',
			'start' => 'required|date',
			'end' => 'required|date',
			'status' =>  ['required', Rule::in(['in_progress', 'paused', 'finished', 'stoped'])],
			'real_end' => 'required|date'
		];
	}

}