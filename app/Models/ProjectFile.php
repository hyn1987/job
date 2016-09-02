<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'project_files';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}