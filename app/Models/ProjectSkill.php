<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class ProjectSkill extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'project_skills';

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