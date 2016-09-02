<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

use DB;
use Auth;

use Wawjob\Project;
use Wawjob\Wallet;

class User extends Model implements Authenticatable {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'users';

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = array('password', 'remember_token');

  /**
   * The user type list.
   *
   * @var array
   */
  public static $userTypeList = ['user_sadmin', 'user_admin', 'user_buyer', 'user_freelancer'];

  /**
   * The user status list.
   *
   * @var array
   */
  public static $userStatusList = [0, 1, 2, 3, 4, 9];


  /* User Roles */
  const ROLE_USER = 1;
  const ROLE_USER_SADMIN = 2;
  const ROLE_USER_ADMIN = 3;
  const ROLE_USER_BUYER = 4;
  const ROLE_USER_FREELANCER = 5;

  /**
   * Get the unique identifier for the user.
   *
   * @return mixed
   */
  public function getAuthIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Get the password for the user.
   *
   * @return string
   */
  public function getAuthPassword()
  {
    return $this->password;
  }

  /**
   * Get the token value for the "remember me" session.
   *
   * @return string
   */
  public function getRememberToken()
  {
    return $this->{$this->getRememberTokenName()};
  }

  /**
   * Set the token value for the "remember me" session.
   *
   * @param  string  $value
   * @return void
   */
  public function setRememberToken($value)
  {
    $this->{$this->getRememberTokenName()} = $value;
  }

  /**
   * Get the column name for the "remember me" token.
   *
   * @return string
   */
  public function getRememberTokenName()
  {
    return 'remember_token';
  }

  /*
  |--------------------------------------------------------------------------
  | The methods associated with roles.
  |--------------------------------------------------------------------------
  */
  /**
   * The roles that belong to the user.
   *
   * @return mixed
   */
  public function roles()
  {
    return $this->belongsToMany('Wawjob\Role', 'users_roles', 'user_id', 'role_id');
  }

  /**
   * Check if the user has the specific role.
   *
   * @param mixed $roles The role list
   * @return mixed
   */
  public function hasRole($roles)
  {
    if (!is_array($roles)) {
      $roles = [$roles];
    }

    foreach ($this->roles as $role) {
      if (in_array($role->slug, $roles) !== false) {
        return true;
      }
    }

    return false;
  }

  /**
   * Check if the user is an admin or super admin.
   *
   * @return mixed
   */
  public function isSuperAdmin()
  {
    return $this->hasRole('user_sadmin');
  }

  /**
   * Check if the user is an admin or super admin.
   *
   * @return mixed
   */
  public function isAdmin()
  {
    return $this->hasRole(['user_sadmin', 'user_admin']);
  }

  /**
   * Check if the user is a buyer.
   *
   * @return mixed
   */
  public function isBuyer()
  {
    return $this->hasRole('user_buyer');
  }

  /**
   * Check if the user is a freelancer.
   *
   * @return mixed
   */
  public function isFreelancer()
  {
    return $this->hasRole('user_freelancer');
  }

  /**
   * Get the user type.
   *
   * @return mixed
   */
  public function userType()
  {
    
    foreach ($this->roles as $role) {
      if (in_array($role->slug, self::$userTypeList) !== false) {
        return $role->slug;
      }
    }

    return 'user_guest';
  }

  /**
   * get role ids.
   *
   * @param mixed $roles The role id list from roles table.
   * @return mixed
   */
  public function getRoleSlugs()
  {
    $roles = [];
    foreach($this->roles->all() AS $role) {
      $roles[] = $role->slug;
    }
    return $roles;
  }

  /**
   * get roles.
   *
   * @param mixed $roles The role id list from roles table.
   * @return mixed
   */
  public function getRoleIds()
  {
    $roles = [];
    foreach($this->roles->all() AS $role) {
      $roles[] = $role->id;
    }
    return $roles;
  }

  /**
   * Add roles.
   *
   * @param mixed $roles The role id list from roles table.
   * @return mixed
   */
  public function syncRoles($roles)
  {
    if (!is_array($roles)) {
      $roles = [$roles];
    }
    return $this->roles()->sync($roles);
  }

  /*
  |--------------------------------------------------------------------------
  | The methods associated with extend properties of user.
  |--------------------------------------------------------------------------
  */
  /**
   * Get the full name.
   *
   * @return mixed
   */
  public function fullname()
  {
    if ($this->contact) {
      return $this->contact->first_name . ($this->contact->first_name && $this->contact->last_name ? ' ' : '') . $this->contact->last_name;
    }

    return false;
  }

  /**
   * Get the contact record associated with the user.
   *
   * @return mixed
   */
  public function contact()
  {
    return $this->hasOne('Wawjob\UserContact', 'user_id');
  }

  /**
   * Get the tokens.
   *
   * @return mixed
   */
  public function tokens()
  {
    return $this->hasOne('Wawjob\UserToken', 'user_id');
  }

  /**
   * Get the profile record associated with the user.
   *
   * @return mixed
   */
  public function profile()
  {
    return $this->hasOne('Wawjob\UserProfile', 'user_id');
  }

  /**
  * @author: paulz
  * @created: Apr 3, 2016
  *
  * @return boolean: Whether need to refresh total_mins and total_score (interval = 1 day)
  */
  public function needRefreshMeter()
  {
    $need_refresh = true;

    $p = $this->profile;
    if ($p->metered_at) {
      $m = new \DateTime($p->metered_at);
      $now = new \DateTime('now');
      $diff = $m->diff($now);
      $need_refresh = ($diff->days > 0);
    }

    return $need_refresh;
  }

  /**
  * @author: paulz
  * @created: Mar 31, 2016
  *
  * @param string $type: total | last6
  */
  public function howManyHours($type = 'total')
  {  
    $mins = 0;

    $total_mins = 0;
    $last6_total_mins = 0;

    if ($this->needRefreshMeter()) {
      // calculate total_mins & last6_total_mins again
      if ($this->isBuyer()) {
        $field = 'buyer_id';
      } else if ($this->isFreelancer()) {
        $field = 'contractor_id';
      } else {
        // for Admins, return 0 hour
        return 0;
      }

      // calc total_mins from `contracts`
      $total_mins = Contract::leftJoin('contract_meters', 'contracts.id', '=', 'contract_meters.id')
        ->where($field, $this->id)
        ->where('contracts.type', Project::TYPE_HOURLY)
        ->sum('contract_meters.total_mins');

      // calc last6_total_mins from `hourly_log_maps`
      $before6_on = date("Y-m-d", strtotime("-6 months"));
      $last6_total_mins = Contract::leftJoin('hourly_log_maps', 'contract_id', '=', 'contracts.id')
        ->where($field, $this->id)
        ->where('hourly_log_maps.date', '>=', $before6_on)
        ->sum('hourly_log_maps.mins');

      $this->profile->total_mins = $total_mins;
      $this->profile->last6_total_mins = $last6_total_mins;
      $this->profile->metered_at = date("Y-m-d H:i:s");
      $this->profile->save();
    } else {
      $total_mins = $this->profile->total_mins;
      $last6_total_mins = $this->profile->last6_total_mins;
    }

    if ($type == "total") {
      $mins = $total_mins;
    } else if ($type == "last6") {
      $mins = $last6_total_mins;
    }

    return intval($mins / 60);
  }

  /**
  * @author: paulz
  * @created: Mar 30, 2016
  */
  public function howManyJobs()
  {
    return $this->freelancerContracts->count();
  }

  /**
  * @author: paulz
  * @created Apr 3, 2016
  */
  public function totalScore()
  {
    // SUM(contract_total_amount * freelancer_score) / SUM(contract_total_amount) where contract_total_amount > 0 and contract_feedbacks.freelancer_score IS NOT NULL
    $need_refresh = $this->needRefreshMeter();

    if ($need_refresh) {
      if ($this->isAdmin()) {
        $score = 0;
      } else {
        $query = DB::table('contracts')
          ->leftJoin('contract_meters', 'contracts.id', '=', 'contract_meters.id')
          ->leftJoin('contract_feedbacks', 'contract_feedbacks.contract_id', '=', 'contracts.id');

        if ($this->isBuyer()) {
          // Buyer
          $query->whereNotNull('contract_feedbacks.buyer_score')
            ->where('buyer_id', $this->id)
            ->selectRaw('SUM(buyer_score * total_amount) as v1, SUM(total_amount) as v2');
        } else {
          // Freelancer
          $query->whereNotNull('contract_feedbacks.freelancer_score')
            ->where('contractor_id', $this->id)
            ->selectRaw('SUM(freelancer_score * total_amount) as v1, SUM(total_amount) as v2');
        }

        $result = $query->first();

        if ( empty($result->v2) ) {
          $score = 0;
        } else {
          $score = $result->v1 / $result->v2;
          $score = intval($score * 100) / 100;
        }
      }

      $this->profile->total_score = $score;
      $this->profile->save();
    }

    return $this->profile->total_score;
  }

  public function myBalance() {
    $wallet = Wallet::where('user_id', $this->id)->first();

    if ( !$wallet ) {
      return 0;
    }

    return $wallet->amount;
  }
  /**
   * Get the employments associated with the user.
   *
   * @return mixed
   */
  public function employments()
  {
    return $this->hasMany('Wawjob\UserEmployment', 'user_id');
  }

  /**
   * Get the contracts as freelancer.
   *
   * @return mixed
   */
  public function freelancerContracts()
  {
    return $this->hasMany('Wawjob\Contract', 'contractor_id');
  }

  /**
   * Get the contracts as buyer.
   *
   * @return mixed
   */
  public function buyerContracts()
  {
    return $this->hasMany('Wawjob\Contract', 'buyer_id');
  }

  /**
   * Get the educations associated with the user.
   *
   * @return mixed
   */
  public function educations()
  {
    return $this->hasMany('Wawjob\UserEducation', 'user_id');
  }
  /**
   * Get the educations associated with the user.
   *
   * @return mixed
   */
  public function portfolios()
  {
    return $this->hasMany('Wawjob\UserPortfolio', 'user_id');
  }
  /**
   * Get the skills.
   *
   * @return mixed
   */
  public function skills()
  {
    return $this->belongsToMany('Wawjob\Skill', 'user_skills', 'user_id', 'skill_id')
      ->withPivot(
        //'order',
        'level'
      );
  }

  /**
   * Get the languages associated with the user.
   *
   * @return mixed
   */
  public function languages()
  {
    return $this->belongsToMany('Wawjob\Language', 'users_languages', 'user_id', 'lang_id');
  }


  public function getLanguageList()
  {
    $k = $this->languages->lists('name');

    return $k;
  }

  /**
   * Get locale.
   *
   * @return mixed
   */
  public function getLocale()
  {
    return $this->locale;
  }

  /**
   * Get user's rating as percent value
   *
   * @return int
   */
  public function getRatingPercent()
  {
    return 30;
  }

  /**
  * Contract stats
  *
  * @author Ray
  * @create April 7, 2016
  */
  public function getContractCount($status = false) {

    $v = 0;
    $contracts = [];

    // return 0;
    
    if($this->isBuyer()) {
      $contracts = $this->buyerContracts;
    } else if($this->isFreelancer()) {
      $contracts = $this->freelancerContracts;
    }
    

    if ($status === false) {
      $v = count($contracts);
    } else {

      foreach($contracts AS $c) {
        if ((is_array($status) && in_array($c->status, $status)) || ($c->status == $status)) {
          $v++;
        }
      }

    }

    
    return $v;
  }

  public function successPercent()
  {
    // @todo
    return 95;
  }


  /**
  * Fetch the list of admininistrators having keyword in their full name, username or email
  *
  * @author paulz
  * @create Mar 7, 2015
  */
  public static function getAdmins($keyword = '')
  {
    if ($keyword) {
      $strKeyword = " AND (user_contacts.`first_name` LIKE '%".$keyword."%' OR user_contacts.`last_name` LIKE '%".$keyword."%')";
    } else {
      $strKeyword = '';
    }

    $users = DB::table("users_roles")
      ->leftJoin('user_contacts', 'users_roles.user_id', '=', 'user_contacts.user_id')
      ->whereRaw("users_roles.role_id = '".self::ROLE_USER_ADMIN."'".$strKeyword)
      ->get();

    return $users;
  }
}