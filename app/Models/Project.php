<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
use DB;

use Wawjob\Notification;

class Project extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'projects';

  const TYPE_FIXED  = 0;
  const TYPE_HOURLY = 1;

  // Summary length of Job description
  const SUMMARY_MAX_LENGTH = 200;

  protected static $str_project_type;

  const STATUS_PRIVATE  = 0;
  const STATUS_PUBLIC   = 1;
  protected static $str_project_is_public = array(
    self::STATUS_PUBLIC   => 'public',
    self::STATUS_PRIVATE  => 'private', 
  );

  const STATUS_CLOSED  = 0;
  const STATUS_OPEN    = 1;
  protected static $str_project_status = array(
    self::STATUS_OPEN   => 'open',
    self::STATUS_CLOSED => 'closed', 
  );

  const DUR_MT6M = 'MT6M';
  const DUR_3T6M = '3T6M';
  const DUR_1T3M = '1T3M';
  const DUR_LT1M = 'LT1M';
  const DUR_LT1W = 'LT1W';
  protected static $str_project_duration;

  const WL_FULLTIME = 'FT';
  const WL_PARTTIME = 'PT';
  const WL_ASNEEDED = 'AN';
  protected static $str_project_workload;

  function __construct()
  {
    self::$str_project_type = array(
      self::TYPE_HOURLY => trans('common.hourly'), 
      self::TYPE_FIXED  => trans('common.fixed_price')
    );

    self::$str_project_workload = array(
      self::WL_FULLTIME => trans('common.full_time'),
      self::WL_PARTTIME => trans('common.part_time'),
      self::WL_ASNEEDED => trans('common.as_needed'),
    );

    self::$str_project_duration = array(
      self::DUR_MT6M => trans('common.mt6m'),
      self::DUR_3T6M => trans('common.3t6m'),
      self::DUR_1T3M => trans('common.1t3m'),
      self::DUR_LT1M => trans('common.lt1m'),
      self::DUR_LT1W => trans('common.lt1w'),
    );
  }

  /**
   * Get the record of the client who posted this job
   */
  public function client()
  {
    return $this->hasOne('Wawjob\User', 'id', 'client_id');
  }

  /**
   * Get the skills.
   *
   * @return mixed
   */
  public function skills()
  {
    return $this->belongsToMany('Wawjob\Skill', 'project_skills', 'project_id', 'skill_id')
      ->withPivot(
        'order',
        'level'
      );
  }
  /**
   * Get the applications.
   *
   * @return mixed
   */
  public function applications()
  {
    return $this->hasMany('Wawjob\ProjectApplication', 'project_id');
  }

  /**
   * Get All Proposals.
   * Auth by SomSak Ri
   *
   * @return mixed
   */
  public function allProposals()
  {
    $ac = ProjectApplication::
              where('project_id', '=', $this->id)
            ->get();
    return $ac;
  }

  /**
   * Get All Proposals.
   * Auth by SomSak Ri
   *
   * @return mixed
   */
  public function allProposalsCount()
  {
    $count = ProjectApplication::
              where('project_id', '=', $this->id)
            ->count();
    return $count;
  }

  public function openApplications($is_count = false, $user_id=0) {
    $query = ProjectApplication::where('project_id', '=', $this->id)
                               ->wherein('status', [
                                  ProjectApplication::STATUS_NORMAL,
                                  ProjectApplication::STATUS_ACTIVE,
                                  ProjectApplication::STATUS_INVITED,
                                  ProjectApplication::STATUS_HIRED,
                                  ProjectApplication::STATUS_OFFER
                                  ]);
    if ($user_id) {
      $query->where('user_id', '=', $user_id);
    }
    if ($is_count) {
      return $query->count();
    }

    return $query->get();
  }

  /**
   * Count Hired Contracts.
   * Auth by SomSak Ri
   *
   * @return mixed
   */
  public function hiredContractsCount()
  {
    $count = Contract::
              where('project_id', '=', $this->id)
            ->where('status', '<>', Contract::STATUS_OFFER)
            ->count();
    return $count;
  }

  /**
   * Get NORMAL applications
   *
   * @return mixed
   */
  public function normalApplications($per_page = 0)
  {
    $ac = ProjectApplication::
              where('project_id', '=', $this->id)
            ->where('status', '=', ProjectApplication::STATUS_NORMAL)
            ->orderBy('id', 'desc');
    if ($per_page) {
      $ac = $ac->paginate($per_page);
    } else {
      $ac = $ac->get();
    }

    return $ac;
  }
  public function normalApplicationsCount()
  {
    $ac = ProjectApplication::
              where('project_id', '=', $this->id)
            ->where('status', '=', ProjectApplication::STATUS_NORMAL)
            ->count();

    return $ac;
  }

  /**
   * Get MESSAGED applications
   *  ACTIVE, INVITED
   * @return mixed
   */
  public function messagedApplications($per_page=0)
  {
    $ac = ProjectApplication::
              whereRaw('project_id=? AND (status=? OR status=?)', 
                       [$this->id, 
                        ProjectApplication::STATUS_ACTIVE, 
                        ProjectApplication::STATUS_INVITED])
            ->orderBy('updated_at', 'desc');
    if ($per_page) {
      $ac = $ac->paginate($per_page);
    } else {
      $ac = $ac->get();
    }

    return $ac;
  }
  public function messagedApplicationsCount()
  {
    $ac = ProjectApplication::
              whereRaw('project_id=? AND (status=? OR status=?)', 
                       [$this->id, 
                        ProjectApplication::STATUS_ACTIVE, 
                        ProjectApplication::STATUS_INVITED])
            ->orderBy('updated_at', 'desc')
            ->count();

    return $ac;
  }

  /**
   * Get the count of hired contracts
   * 
   * @return mixed
   */
  public function offerHiredContracts($per_page=0)
  {
    $ac = Contract::
              whereRaw('project_id=? AND (status=? OR status=?)', 
                       [$this->id, 
                        Contract::STATUS_OPEN,
                        Contract::STATUS_OFFER])
            ->orderBy('status', 'asc')
            ->orderBy('updated_at', 'desc');

    if ($per_page) {
      $ac = $ac->paginate($per_page);
    } else {
      $ac = $ac->get();
    }

    return $ac;
  }
  public function offerHiredContractsCount()
  {
    $ac = Contract::
              whereRaw('project_id=? AND (status=? OR status=?)', 
                       [$this->id, 
                        Contract::STATUS_OPEN,
                        Contract::STATUS_OFFER])
            ->count();
    return $ac;
  }

  /**
   * Get the count of ARCHIVED applications
   * 
   * @return mixed
   */
  public function archivedApplications($per_page=0)
  {
    $ac = ProjectApplication::
              whereRaw('project_id=? AND (status=? OR status=? OR status=? OR status=? OR status=?)', 
                       [$this->id, 
                        ProjectApplication::STATUS_CLIENT_DCLINED, 
                        ProjectApplication::STATUS_FREELANCER_DECLINED, 
                        ProjectApplication::STATUS_PROJECT_CANCELLED, 
                        ProjectApplication::STATUS_PROJECT_EXPIRED, 
                        ProjectApplication::STATUS_HIRING_CLOSED])
            ->orderBy('updated_at', 'desc');

    if ($per_page) {
      $ac = $ac->paginate($per_page);
    } else {
      $ac = $ac->get();
    }

    return $ac;
  }
  public function archivedApplicationsCount()
  {
    $ac = ProjectApplication::
              whereRaw('project_id=? AND (status=? OR status=? OR status=? OR status=? OR status=?)', 
                       [$this->id, 
                        ProjectApplication::STATUS_CLIENT_DCLINED, 
                        ProjectApplication::STATUS_FREELANCER_DECLINED, 
                        ProjectApplication::STATUS_PROJECT_CANCELLED, 
                        ProjectApplication::STATUS_PROJECT_EXPIRED, 
                        ProjectApplication::STATUS_HIRING_CLOSED])
            ->count();

    return $ac;
  }

  /**
   * Close All Open Applications
   * Normal, Active, Invited, Offer
   * 
   * @return mixed
   */
  public function closeAllOpenApplications($status) {
    $applications = ProjectApplication::where('project_id', '=', $this->id)
                                      ->wherein('status', [
                                          ProjectApplication::STATUS_NORMAL,
                                          ProjectApplication::STATUS_ACTIVE,
                                          ProjectApplication::STATUS_INVITED,
                                          ProjectApplication::STATUS_OFFER
                                        ])
                                      ->get();
    foreach ($applications as $app) {
      $app->status = $status;
      $app->save();
      
      Notification::send(Notification::FREELANCER_JOB_CANCELED, 
                             SUPERADMIN_ID, 
                             $app->user_id,
                             ["job_title"      => $job->subject]);
    }
    return;

  }

  /**
   * Check if User is author(client) of this project
   * 
   * @return mixed
   */
  public function checkIsAuthor($user_id) {
    return ($this->client_id == $user_id);
  }

  /**
   * Get the contracts.
   *
   * @return mixed
   */
  public function contracts()
  {
    return $this->hasMany('Wawjob\Contracts', 'project_id');
  }

  public function contracts_hired_count()
  {
    $data = Contract::
              where('project_id', '=', $this->id)
            ->where('status', '=', Contract::STATUS_OPEN)
            ->count();

    return $data;
  }

  /**
   * Get the files associated with the project.
   *
   * @return mixed
   */
  public function files()
  {
    return $this->belongsToMany('Wawjob\File', 'project_files', 'project_id', 'file_id')
      ->wherePivot('is_active', '=', '1');
  }

  /**
   * Hourly | Fixed
   *
   * @return mixed
   */
  public function type_string() {
    if (isset(self::$str_project_type[$this->type])) {
      return self::$str_project_type[$this->type];
    }
    return "";
  }

  /**
   * Public | Private
   *
   * @return mixed
   */
  public function is_public_string($trans=true) {
    if (isset(self::$str_project_is_public[$this->is_public])) {
      if ($trans) {
        return trans('job.' . self::$str_project_is_public[$this->is_public]);
      } else {
        return self::$str_project_is_public[$this->is_public];
      }
    }
    return "";
  }

  /**
   * Open | Closed
   *
   * @return mixed
   */
  public function is_open_string($trans=true) {
    if (isset(self::$str_project_status[$this->status])) {
      if ($trans) {
        return trans('job.'.self::$str_project_status[$this->status]);
      } else {
        return self::$str_project_status[$this->status];
      }
      
    }
    return "";
  }

  /**
   * Mar 2, 2015 - paulz
   */
  public function is_open() {
    return $this->status == self::STATUS_OPEN;
  }

  /**
  * Public | Private
  */
  public function status_string($trans=true) {
    if ($this->status == self::STATUS_OPEN) {
      if (isset(self::$str_project_is_public[$this->is_public])) {
        if ($trans) {
          return trans('job.' . self::$str_project_is_public[$this->is_public]);
        } else {
          return self::$str_project_is_public[$this->is_public];
        }
      }
    }

    return $this->is_open_string($trans);
  }

  /* Mar 2, 2016 - paulz
  *
  * Converts the given integer for Open | Closed to string
  */
  public static function is_open_to_string($open_or_closed, $trans=true)
  {
    if (isset(self::$str_project_status[$open_or_closed])) {
      if ($trans) {
        return trans('job.'.self::$str_project_status[$open_or_closed]);
      } else {
        return self::$str_project_status[$open_or_closed];
      }
    }

    return "";
  }

  /**
   * Full Time | Part Time | As Needed
   *
   * @return mixed
   */
  public function workload_string() {
    if (isset(self::$str_project_workload[$this->workload])) {
      return self::$str_project_workload[$this->workload];
    }

    return "";
  }

  /**
   * Less than a week | Less than a month | 1 to 3 months | 3 to 6 months | More than 6 months
   *
   * @return mixed
   */
  public function duration_string() {
    if (isset(self::$str_project_duration[$this->duration])) {
      return self::$str_project_duration[$this->duration];
    }
    
    return "";
  }


  /**
  * Returns array for each <select> tag
  *
  * @author paulz
  * @created Mar 10, 2016
  */
  public static function getOptions($type)
  {
    switch ($type) {
    case "is_public":
      $options = array_flip(self::$str_project_is_public);
      break;

    case "is_open":
      $options = array_flip(self::$str_project_status);
      break;

    default:
      $options = [];
    }

    return $options;
  }

  /**
   * Get the record of the client who posted this job
   */
  public function category()
  {
    return $this->hasOne('Wawjob\Category', 'id', 'category_id');
  }

  /**
   * Get the param from $_REQUEST
   */
  public static function input($key, &$param)
  {
    if (isset($param[$key])) {
      return $param[$key];
    } else {
      return false;
    }
  }
}