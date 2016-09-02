<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model {

  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'skills';

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the users.
   */
  public function users()
  {
    return $this->hasMany('Wawjob\UserSkill', 'skill_id');
  }

  /**
   * Get the projects.
   */
  public function projects()
  {
    return $this->hasMany('Wawjob\ProjectSkill', 'skill_id');
  }
}