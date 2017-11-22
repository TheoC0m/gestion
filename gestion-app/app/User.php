<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 22/11/17
 * Time: 01:44
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'users';
    public $fillable = ['name', 'email', 'description'];

}