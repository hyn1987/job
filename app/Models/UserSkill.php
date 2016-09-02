<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_skills';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the skill.
   */
  public function skill()
  {
    return $this->hasOne('Wawjob\Skill', 'skill_id');
  }
}