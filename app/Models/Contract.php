<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Wawjob\User;
use Wawjob\Project;
use Wawjob\HourlyLogMap;

class Contract extends Model {

  use SoftDeletes;

  const STATUS_OFFER    = 0;
  const STATUS_OPEN     = 1;
  const STATUS_PAUSED   = 2;
  const STATUS_REJECTED = 8;
  const STATUS_CLOSED   = 9;

  protected static $str_contract_status = array(
    self::STATUS_OFFER      => 'Offer',
    self::STATUS_OPEN       => 'Open',
    self::STATUS_PAUSED     => 'Paused',
    self::STATUS_REJECTED   => 'Rejected',
    self::STATUS_CLOSED     => 'Closed',
  );
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'contracts';

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['created_at', 'started_at', 'ended_at', 'updated_at', 'deleted_at'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * Check if User is author(client) of this contract
   * 
   * @return mixed
   */
  public function checkIsAuthor($user_id) {
    return ($this->buyer_id == $user_id);
  }

  /**
   * Get the project.
   */
  public function project()
  {
    return $this->hasOne('Wawjob\Project', 'id', 'project_id');
  }

  /**
   * Get the application.
   *
   * @return mixed
   */
  public function application()
  {
    return $this->hasOne('Wawjob\ProjectApplication', 'id', 'application_id');
  }

  /**
   * Get the buyer.
   */
  public function buyer()
  {
    return $this->hasOne('Wawjob\User', 'id', 'buyer_id');
  }

  /**
   * Get the contractor.
   */
  public function contractor()
  {
    return $this->hasOne('Wawjob\User', 'id', 'contractor_id');
  }

  /**
   * Get the feedback.
   */
  public function feedback()
  {
    return $this->hasOne('Wawjob\ContractFeedback', 'contract_id', 'id');
  }

  /**
   * Get the meter
   */
  public function meter()
  {
    return $this->hasOne('Wawjob\ContractMeter', 'id', 'id');
  }

  /**
   *
   *
   * @return mixed
   */
  public function status_string()
  {
    if (isset(self::$str_contract_status[$this->status])) {
      return self::$str_contract_status[$this->status];
    }

    return "";
  }

  /**
   * Get the hourly time logs.
   */
  public function timelogs()
  {
    return $this->hasMany('Wawjob\HourlyLog');
  }

  public function isHourly()
  {
    return $this->type == Project::TYPE_HOURLY;
  }

  public function isOpen()
  {
    return $this->status == Project::STATUS_OPEN; 
  }

  public function isClosed()
  {
    return $this->status == Project::STATUS_CLOSED; 
  }

  public function isPaused()
  {
    return $this->status == Project::STATUS_PAUSED; 
  }

  public function workdiaryUrl($date = '')
  {
    if ($date) {
      $strDate = date("Y-m-d", strtotime($date));
    } else {
      $strDate = date("Y-m-d");
    }

    return  "/admin/workdiary/view/" . $this->id . "?wdate=$strDate";
  }

  public function buyerPrice($mins)
  {
    error_log($this->price);
    $price = $this->price * $mins / 60;

    return ceil($price * 100) / 100;
  }

  public function freelancerPrice($mins)
  {
    $price = floor($this->price * 0.9 * 100) * $mins / 6000 ;

    return floor($price * 100) / 100;
  }

  public function freelancerRate()
  {
    $price = $this->price * 0.9;

    return floor($price * 100) / 100;
  }

  public function calcWeekMinutes()
  {
    $rows = HourlyLogMap::getWeekMinutes($this->id, 'this');
    $this->this_week_mins = $rows[$this->id];

    $rows = HourlyLogMap::getWeekMinutes($this->id, 'last');
    $this->last_week_mins = $rows[$this->id];

    $rows = HourlyLogMap::getWeekMinutes($this->id, 'all');
    $this->all_week_mins = $rows[$this->id];
  }

  /**
   * Get Contract Object from Application
   *
   * @author nada
   * @since Apr 12, 2016
   * @version 1.0
   * @return Contract
   */
  public static function getContractFromApplication($application_id) {
    $object = self::where('application_id', '=', $application_id)->first();
    if ($object) {
      return $object;
    }
    return false;
  }

  /**
   * Contract Selector for Buyer Timesheet Page
   *
   * @author nada
   * @since Mar 26, 2016
   * @version 1.0
   * @param  $contract_id : selected contract
   *         $project_id  : selected project
   *         $date: date range - array('start', 'end')
   * @return Response
   */
  public static function buyer_contracts_selector_data($user_id, $contract_id, $project_id, $dates) {
    $user = User::find($user_id);

    $dates['start'] = date('Y-m-d H:i:s', strtotime($dates['start']));
    $dates['end']   = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dates['end'])));
    // Contract Selector
    $_contracts = Contract::whereRaw(
                    'id=? OR (buyer_id=? AND type=? AND (
                        status=? OR status=? OR 
                        (status=? AND ((started_at<? AND ended_at>?) OR (started_at>=? AND started_at<?)))
                      ))', 
                    [$contract_id, $user->id, Project::TYPE_HOURLY, 
                     Contract::STATUS_OPEN, Contract::STATUS_PAUSED, 
                     Contract::STATUS_CLOSED, $dates['start'], $dates['start'], $dates['start'], $dates['end']]
                  )->get();

    $_contracts = $_contracts->groupBy('project_id');
    $contracts = array();
    foreach ($_contracts as $c_project_id=>$p_contracts) {
      $contracts[$c_project_id] = array( 
          'project'   => Project::find($c_project_id), 
          'contracts' => $p_contracts, 
        );
    }

    if ($project_id && !isset($contracts[$project_id])) {
      $contracts[$project_id] = array(
          'project'   => Project::find($project_id), 
          'contracts' => array(),
        );
    }

    return $contracts;
  }

  public static function buyer_opened_contracts($buyer_id, $dates) {
    $dates['start'] = date('Y-m-d H:i:s', strtotime($dates['start']));
    $dates['end']   = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dates['end'])));
    // Contract Selector
    $_contracts = Contract::whereRaw(
                    'buyer_id=? AND type=? AND (
                        ((status=? OR status=?) AND (started_at<?)) OR 
                        (status=? AND ((started_at<? AND ended_at>?) OR (started_at>=? AND started_at<?)))
                      )', 
                    [$buyer_id, Project::TYPE_HOURLY, 
                     Contract::STATUS_OPEN, Contract::STATUS_PAUSED, $dates['end'], 
                     Contract::STATUS_CLOSED, $dates['start'], $dates['start'], $dates['start'], $dates['end']]
                  )->get();

    return $_contracts;
  }
  /**
   * Contract Selector for Freelancer Timesheet Page
   *
   * @author Ri Chol Min
   * @since Mar 31, 2016
   * @version 1.0
   * @param  $contract_id : selected contract
   *         $project_id  : selected project
   *         $date: date range - array('start', 'end')
   * @return Response
   */
  public static function freelancer_contracts_selector_data($user_id, $contract_id, $project_id, $dates) {
    $user = User::find($user_id);

    $dates['start'] = date('Y-m-d H:i:s', strtotime($dates['start']));
    $dates['end']   = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dates['end'])));
    // Contract Selector
    $_contracts = Contract::whereRaw(
                    'id=? OR (contractor_id=? AND type=? AND (
                        status=? OR status=? OR 
                        (status=? AND ((started_at<? AND ended_at>?) OR (started_at>=? AND started_at<?)))
                      ))', 
                    [$contract_id, $user->id, Project::TYPE_HOURLY, 
                     Contract::STATUS_OPEN, Contract::STATUS_PAUSED, 
                     Contract::STATUS_CLOSED, $dates['start'], $dates['start'], $dates['start'], $dates['end']]
                  )->get();

    $_contracts = $_contracts->groupBy('project_id');
    $contracts = array();
    foreach ($_contracts as $c_project_id=>$p_contracts) {
      $contracts[$c_project_id] = array( 
          'project'   => Project::find($c_project_id), 
          'contracts' => $p_contracts, 
        );
    }

    if ($project_id && !isset($contracts[$project_id])) {
      $contracts[$project_id] = array(
          'project'   => Project::find($project_id), 
          'contracts' => array(),
        );
    }

    return $contracts;
  }

  public static function freelancer_opened_contracts($contractor_id, $dates) {
    $dates['start'] = date('Y-m-d H:i:s', strtotime($dates['start']));
    $dates['end']   = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dates['end'])));
    // Contract Selector
    $_contracts = Contract::whereRaw(
                    'contractor_id=? AND type=? AND (
                        ((status=? OR status=?) AND (started_at<?)) OR 
                        (status=? AND ((started_at<? AND ended_at>?) OR (started_at>=? AND started_at<?)))
                      )', 
                    [$contractor_id, Project::TYPE_HOURLY, 
                     Contract::STATUS_OPEN, Contract::STATUS_PAUSED, $dates['end'], 
                     Contract::STATUS_CLOSED, $dates['start'], $dates['start'], $dates['start'], $dates['end']]
                  )->get();

    return $_contracts;
  }
}