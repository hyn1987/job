<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

use DB;

use Wawjob\User;
use Wawjob\HourlyLogMap;
use Wawjob\Wallet;

use Wawjob\FeeSettings;

class Transaction extends Model {

  protected $table = 'transactions';

  /**
   * Transaction types
   */
  const TYPE_FIXED = 1;
  const TYPE_HOURLY = 2;
  const TYPE_BONUS = 3;
  const TYPE_CHARGE = 4;
  const TYPE_WITHDRAWAL = 5;
  const TYPE_REFUND = 6;

  // Fee
  const TYPE_FEE = 9;

  /**
  * Who is this action performed for?
  */
  const FOR_BUYER = 1;
  const FOR_FREELANCER = 2;
  const FOR_WAWJOB = 3;

  /**
   * Transaction status
   */
  const STATUS_PENDING = 0;
  const STATUS_AVAILABLE = 1;

  protected static $str_transaction_type = array(
    self::TYPE_FIXED      => 'Fixed', 
    self::TYPE_HOURLY     => 'Hourly', 
    self::TYPE_BONUS      => 'Bonus', 
    self::TYPE_CHARGE     => 'Charge', 
    self::TYPE_WITHDRAWAL => 'Withdrawal', 
    self::TYPE_REFUND     => 'Refund', 
  );

  protected static $str_status = [
    self::STATUS_PENDING  => 'Pending',
    self::STATUS_AVAILABLE => 'Available',
  ];

  public function type_string() 
  {
    if ($this->for == self::FOR_WAWJOB) {
      return "Fee";
    }

    if (isset(self::$str_transaction_type[$this->type])) {
      return self::$str_transaction_type[$this->type];
    }
    return "";
  }

  /**
  * Converts type in string format to Model constant
  *
  * @author paulz
  * @created Mar 30, 2016
  * @param string or integer $type: Transaction type
  */
  public static function parseType($type)
  {
    if (is_numeric($type)) {
      return $type;
    }

    $map = array_flip(self::$str_transaction_type);
    $type = ucfirst($type);
    if (!isset($map[$type])) {
      return false;
    }

    return $map[$type];
  }

  public function status_string()
  {
    if (isset(self::$str_status[$this->status])) {
      return self::$str_status[$this->status];
    }
    return "";
  }

  public function isPending()
  {
    return $this->status == self::STATUS_PENDING;
  }

  /**
  * Options for the <select> of "for whom"
  *
  * @author paulz
  * @created Mar 29, 2016
  */
  public static function getForOptions()
  {
    return [
      'Buyer' => self::FOR_BUYER,
      'Freelancer' => self::FOR_FREELANCER,
      'Fee' => self::FOR_WAWJOB
    ];
  }

  /**
  * Returns array for Transaction type <select> tag
  *
  * @author paulz
  * @created Mar 29, 2016
  *
  * @param string $for: all | contract | buyer | freelancer
  */
  public static function getTypeOptions($for = 'all')
  {
    $options = [];

    switch ($for) {
    case "all":
    case "buyer":
    case "freelancer":
      $options = array_flip(self::$str_transaction_type);
      break;

    case "hourly_contract":
    case "fixed_contract":
      if ($for == "hourly_contract") {
        $vs = [
          self::TYPE_HOURLY, self::TYPE_BONUS, self::TYPE_REFUND
        ];
      } else {
        $vs = [
          self::TYPE_FIXED, self::TYPE_BONUS, self::TYPE_REFUND
        ];
      }

      foreach($vs as $v) {
        $options[self::$str_transaction_type[$v]] = $v;
      }
      break;
    }
      
    return $options;
  }

  /**
  * Get Statement between two days
  *
  * @author nada
  * @created Mar 30, 2016
  * @params opts = [user_id, from, to]
  * @return 
  *  $statement = array(
  *      'beginning' => 0,
  *      'in'       => 0, 
  *      'out'      => 0, 
  *      'change'   => 0, 
  *      'ending'   => 0
  *    );
  */
  public static function getStatement($opts) {
    $statement = array(
        'beginning' => self::getBeginningBalance($opts['user_id'], $opts['from']),
        'in'       => 0, 
        'out'      => 0, 
        'change'   => 0, 
        'ending'   => 0
      );

    $from = date("Y-m-d", strtotime($opts['from'])) . " 00:00:00";
    $to   = date("Y-m-d", strtotime($opts['to'])) . " 23:59:59";

    $query = self::select( DB::raw('SUM(amount) as amount'))
                 ->where('user_id', '=', $opts['user_id'])
                 ->where('status', '=', self::STATUS_AVAILABLE)
                 ->where('done_at', '>=', $from)
                 ->where('done_at', '<=', $to);

    $in_query = clone $query;
    $out_query= clone $query;

    $data = $in_query->where('amount', '>', 0)->first();
    $statement['in'] = $data->amount;

    $data = $out_query->where('amount', '<', 0)->first();
    $statement['out'] = $data->amount;

    $statement['change'] = $statement['in'] + $statement['out'];
    $statement['ending'] = $statement['beginning'] + $statement['change'];

    return $statement;
  }

  /**
  * Get Begining Balance
  *
  * @author nada
  * @created Mar 30, 2016
  * @params 
  * @return 
  * 
  */
  public static function getBeginningBalance($user_id, $begin_date) {
    $begin_date = date_format(date_create($begin_date), 'Y-m-d');
    $last = DB::table('wallet_history')->where('user_id', '=', $user_id)
                         ->where('date', '<=', $begin_date)
                         ->orderBy('date', 'desc')
                         ->first();

    $begin_date = date_format(date_create($begin_date), 'Y-m-d 00:00:00');
    $t = self::selectRaw('SUM(amount) as amount')
             ->where('user_id', '=', $user_id)
             ->where('status', '=', self::STATUS_AVAILABLE)
             ->where('done_at', '<', $begin_date);
    if ($last) {
      $last_date = date_format(date_create($last->date), 'Y-m-d 00:00:00');
      $t->where('done_at', '>=', $last_date);
    }
    $data = $t->first();
    
    $balance = $data->amount;
    if ($last) {
      $balance += $last->balance;
    }
    
    return $balance;
  }

  /**
  * Search transaction data
  *
  * @author paulz
  * @created Mar 29, 2016
  */
  public static function search($opts)
  {
    $query = self::orderBy('transactions.status', 'asc')
        ->orderBy('transactions.done_at', 'desc')
        ->orderBy('transactions.created_at', 'desc');

    if (isset($opts['user_id'])) {    
      $query->where('user_id', $opts['user_id']);
    }

    // Transaction type
    if ( !empty($opts['type']) && $opts['type'] != 'all' ) {
      if (is_array($opts['type'])) {
        $query->whereIn('transactions.type', $opts['type']);
      } else {
        $query->where('transactions.type', $opts['type']);
      }
    }


    // created_at >=== from
    if (isset($opts['from'])) {
      $from = date("Y-m-d", strtotime($opts['from'])) . " 00:00:00";
      $to = date("Y-m-d", strtotime($opts['to'])) . " 23:59:59";

      $query->whereRaw("((transactions.status=".self::STATUS_PENDING." AND transactions.created_at >= '$from') OR 
                         (transactions.status=".self::STATUS_AVAILABLE." AND transactions.done_at >= '$from')) AND
                        ((transactions.status=".self::STATUS_PENDING." AND transactions.created_at <= '$to') OR 
                         (transactions.status=".self::STATUS_AVAILABLE." AND transactions.done_at <= '$to'))");
    }

    // Contract
    $query->leftJoin('contracts', 'contracts.id', '=', 'contract_id');    
    if ( !empty($opts['contract_id']) ) {
      $query->where('contract_id', $opts['contract_id']);
    }

    // by project_id
    if ( !empty($opts['project_id']) ) {
      $query->leftJoin('projects', 'projects.id', '=', 'contracts.project_id')
        ->where('projects.id', $opts['project_id']);
    }

    // for/by whom
    if ( !empty($opts['for']) ) {
      $for = $opts['for'];
      if (is_string($for)) {
        $map = [
          'buyer' => self::FOR_BUYER,
          'freelancer' => self::FOR_FREELANCER,
          'fee' => self::FOR_WAWJOB
        ];

        if (isset($map[$for])) {
          $for = $map[$for];
        } else {
          $for = 0;
        }
      }

      if ($for > 0) {
        $query->where('transactions.for', $for);
      }
    }

    if ( isset($opts['status']) ) {
      $query->where('transactions.status', $opts['status']);
    }

    $selectRaw = "transactions.*, contracts.buyer_id, contracts.contractor_id, contracts.title as c_title, contracts.type as c_type";

    $query->selectRaw($selectRaw);
    
    $rows = $query->get();

    $uids = [];
    foreach($rows as $row) {
      if ( !in_array($row->user_id, $uids) ) {
        $uids[] = $row->user_id;
      }

      if ( !in_array($row->buyer_id, $uids) ) {
        $uids[] = $row->buyer_id;
      }

      if ( !in_array($row->contractor_id, $uids) ) {
        $uids[] = $row->contractor_id;
      }
    }

    $users = User::whereIn('id', $uids)
        ->get()
        ->keyBy('id');

    foreach($rows as $k => $row) {
      if (isset($users[$row->user_id]) ) {
        $user = $users[$row->user_id];
        $row->user_type = $user->isBuyer() ? "Buyer": ($user->isFreelancer() ? "Freelancer" : "-");
        $row->user_fullname = $user->fullname();
      } else {
        $row->user_type = '';
        $row->user_fullname = '';
      }

      if (isset($users[$row->buyer_id]) ) {
        $row->buyer_fullname = $users[$row->buyer_id]->fullname();
      } else {
        $row->buyer_fullname = '';
      }

      if (isset($users[$row->contractor_id]) ) {
        $row->contractor_fullname = $users[$row->contractor_id]->fullname();
      } else {
        $row->contractor_fullname = '';
      }

      $desc = '';
      if ($row->type == self::TYPE_HOURLY) {
        $desc .= $row->c_title . "\n";
        $desc .= date("m/d/Y", strtotime($row->hourly_from)) . " - " . date("m/d/Y", strtotime($row->hourly_to));
        $desc .= " (".formatMinuteInterval($row->hourly_mins) . " hrs)";
      } else if ($row->type == self::TYPE_FIXED || $row->type == self::TYPE_BONUS) {
        $desc .= $row->c_title;
        if ($row->note) {
          $desc .= "\n<i>" . $row->note . "</i>";
        }
      } else if ($row->for == self::FOR_WAWJOB) {
        $desc = "for #" . $row->ref_id;
      }

      $row->desc = $desc;
    }

    return $rows;
  }

  /**
  * When buyer makes payment for a contract or weekly payment is done by cron job, call this method
  *
  * @author: paulz
  * @created: Mar 30, 2016
  * 
  * @param array $opts
  *     integer $cid: Contract ID
  *     decimal $amount: Buyer amount | Contractor amount to refund
  *     string | integer $type: Transaction type
  * [optional] 
  *     string $hourly_from: YYYY-MM-DD (date)
  *     string $hourly_to: YYYY-MM-DD (date)
  *     integer $hourly_mins: Weekly minutes of this week
  *     string $note: Note for fixed-price or bonus transaction (by buyer)
  * 
  * @note: when type is REFUND, $amount means how much contractor should refund to buyer. Otherwise, $amount means buyer amount (fee + contractor_amount)
  *
  * Example: 
  *     Transaction::pay([
  *       'cid' => 5,
  *       'amount' => 80,
  *       'type' => 'hourly',
  *       'hourly_from' => '2016-03-21',
  *       'hourly_to' => '2016-03-27',
  *       'hourly_mins' => 320,
  *       'note' => 'This is bonus.'
  *     ]);
  */
  public static function pay($opts)
  {
    $defaults = [
      'cid' => 0,
      'amount' => 0,  // 
      'type' => '', # fixed | bonus | hourly | refund
    ];

    $opts = array_merge($defaults, $opts);
    extract($opts);

    $type = self::parseType($type);

    if ( !$type ) {
      error_log("[Transaction::pay()] Error: Invalid transaction type is given.");
      return false;      
    }

    if ( !$cid ) {
      error_log("[Transaction::pay()] Error: Contract ID is not given.");
      return false;
    }

    if ($amount <= 0) {
      error_log("[Transaction::pay()] Error: Invalid amount to pay.");
      return false;
    }

    if ( !isset($note) ) {
      $note = '';
    }

    $c = Contract::find($cid);
    if ( !$c ) {
      error_log("[Transaction::pay()] Error: Contract #{$cid} is not found.");
      return false;
    }

    $fee_rate = FeeSettings::getFeeRate_Contract($cid);
    // Calculate amount to pay contractor and fee
    if ($type == self::TYPE_REFUND) {
      $contractor_amount = floor(-$amount * 100) / 100;
      $fee_amount = floor($contractor_amount * (1 - FEE_RATE) / FEE_RATE * 100) / 100;

      $amount = -($contractor_amount + $fee_amount); // buyer
    } else {
      $contractor_amount = floor($amount * FEE_RATE * 100) / 100;
      $fee_amount = $amount - $contractor_amount;
      
      $amount = -$amount;
    }

    // Create buyer transaction
    $bt = new Transaction;
    $bt->type = $type;
    $bt->for = self::FOR_BUYER;
    $bt->user_id = $c->buyer_id;
    $bt->contract_id = $cid;
    $bt->note = $note;
    $bt->amount = $amount;
    $bt->status = self::STATUS_PENDING;
    if ($type == self::TYPE_HOURLY) {
      if ( !isset($hourly_from) || !isset($hourly_to) ) {
        error_log("[Transaction::pay()] Error: Hourly log range is not given.");
        return false;
      }

      if ( empty($hourly_mins) ) {
        error_log("[Transaction::pay()] Error: Invalid weekly minutes is given.");
        return false;
      }

      $bt->hourly_from = $hourly_from;
      $bt->hourly_to = $hourly_to;
      $bt->hourly_mins = $hourly_mins;
    }

    $bt->save();

    // Create contractor transaction
    $ct = $bt->replicate();
    $ct->for = self::FOR_FREELANCER;
    $ct->user_id = $c->contractor_id;
    $ct->amount = $contractor_amount;
    $ct->ref_id = $bt->id; // buyer transaction id
    $ct->save();

    // Create fee transaction
    $ft = $bt->replicate();
    $ft->for = self::FOR_WAWJOB;
    $ft->user_id = SUPERADMIN_ID; // means Wawjob
    $ft->amount = $fee_amount;
    $ft->ref_id = $bt->id;
    $ft->save();

    // Save contractor transaction ID as ref ID of buyer transaction
    $bt->ref_id = $ct->id;
    $bt->save();

    // @todo: Send notification to buyer and freelancer
    $buyer_noti_types = [
      self::TYPE_BONUS => Notification::BUYER_PAY_BONUS,
      self::TYPE_FIXED => Notification::BUYER_PAY_FIXED,
      self::TYPE_REFUND => Notification::BUYER_REFUND
    ];

    $freelancer_noti_types = [
      self::TYPE_BONUS => Notification::PAY_BONUS,
      self::TYPE_FIXED => Notification::PAY_FIXED,
      self::TYPE_REFUND => Notification::REFUND
    ];

    // Send notification to buyer
    Notification::send($buyer_noti_types[$type], SUPERADMIN_ID, $c->buyer_id, [
        'freelancer_name' => $c->contractor->fullname(),
        'amount' => -$amount
      ]
    );

    // Send notification to freelancer
    Notification::send($freelancer_noti_types[$type], SUPERADMIN_ID, $c->contractor_id, [
        'buyer_name' => $c->buyer->fullname(),
        'amount' => -$amount
      ]
    );
    
    return true;
  }

  /**
  * Processes pending transaction to apply changes to `wallets`
  *
  * @author paulz
  * @created Mar 30, 2016
  */
  public static function processPending($opts)
  {
    $query = self::where('status', self::STATUS_PENDING);

    if (isset($opts['ids'])) {
      $ids = $opts['ids'];
      if (is_array($ids)) {
        $query->whereIn('id', $ids);
      } else {
        $query->where('id', $ids);
      }
    }

    // fixed | bonus | hourly
    if (isset($opts['type'])) {
      $type = $opts['type'];
      if ($type == 'fixed') {
        $query->where(function($query) {
          $query->where('type', self::TYPE_FIXED)
            ->orWhere('type', self::TYPE_BONUS);
        });
      } else if ($type == 'hourly') {
        $query->where('type', self::TYPE_HOURLY);
      } else if ($type == 'refund') {
        $query->where('type', self::TYPE_REFUND);
      }
    }

    $ts = $query->get();

    foreach($ts as $t) {
      if ($t->amount == 0 || $t->user_id == 0) {
        continue;
      }

      // Adjust amount from user (buyer or freelancer)
      Wallet::where('user_id', $t->user_id)->increment('amount', $t->amount);

      // Update contract_meter for fixed, bonus and refund
      if ($t->for == self::FOR_BUYER) {
        if ($t->type == self::TYPE_FIXED || $t->type == self::TYPE_BONUS) {
          DB::table('contract_meters')->where('id', $t->contract_id)
            ->increment('total_amount', -$t->amount);
        }
      }

      self::where('id', $t->id)->update([
        'status' => self::STATUS_AVAILABLE,
        'done_at' => date("Y-m-d H:i:s")
      ]);

    }
  }

  /**
  * Charges (typically buyer's) credit to his/her account
  *
  * @author paulz
  * @created Mar 30, 2016
  */
  public static function charge($uid, $amount)
  {
    if ( !$uid || $amount <= 0 ) {
      return false;
    }

    $u = User::find($uid);

    // Add amount to wallet
    Wallet::where('user_id', $uid)->increment('amount', $amount);

    // Add transaction
    $t = new Transaction;
    $t->type = self::TYPE_CHARGE;
    $t->for = $u->hasRole('user_buyer') ? self::FOR_BUYER : self::FOR_FREELANCER;
    $t->user_id = $uid;
    $t->amount = $amount;
    $t->status = self::STATUS_AVAILABLE;
    $t->done_at = date("Y-m-d H:i:s");
    $t->save();

    // @todo: send notification, send email

    return true;
  }

  /**
  * Withdraws (typically freelancer's) credit from his/her account
  *
  * If you withdraw $9,999, then $10,000 will be decreased from your wallet (including withdrawal fee $1)
  *
  * @author paulz
  * @created Mar 30, 2016
  */
  public static function withdraw($uid, $amount)
  {
    // two transactions - fee, withdraw
    if ( !$uid || $amount < MIN_WITHDRAW_AMOUNT || $amount > MAX_WITHDRAW_AMOUNT ) {
      return false;
    }

    $u = User::find($uid);

    // Check whether the wallet of this user has enough amount to withdraw
    $wallet = Wallet::where('user_id', $uid)->first();
    if ($wallet->amount < $amount + WITHDRAW_FEE) {
      error_log("[Transaction::withdraw()] Failed: User #uid has not enough wallet to withdraw \$$amount.");
      return false;
    }

    // Decrease amount from wallet
    Wallet::where('user_id', $uid)->decrement('amount', $amount + WITHDRAW_FEE);

    // Withdraw transaction
    $t = new Transaction;
    $t->type = self::TYPE_WITHDRAWAL;
    $t->for = $u->hasRole('user_buyer') ? self::FOR_BUYER : self::FOR_FREELANCER;
    $t->user_id = $uid;
    $t->amount = -$amount;
    $t->status = self::STATUS_AVAILABLE;
    $t->done_at = date("Y-m-d H:i:s");
    $t->save();

    // Fee transaction
    $t = new Transaction;
    $t->type = self::TYPE_WITHDRAWAL;
    $t->for = self::FOR_WAWJOB;
    $t->user_id = SUPERADMIN_ID;
    $t->amount = WITHDRAW_FEE;
    $t->status = self::STATUS_AVAILABLE;
    $t->done_at = date("Y-m-d H:i:s");
    $t->save();

    // @todo: send notification, send email

    return true;
  }

  /**
  * Check hourly log and add weekly payment transaction for last week
  *
  * @author paulz
  * @created Mar 30, 2016
  */
  public static function payLastHourlyContracts()
  {
    $cids = HourlyLogMap::getActiveHourlyContractIds('last');
    if (empty($cids)) {
      error_log("[Transaction::payHourlyContracts] No hourly contracts have logs for last week.");
      return false;
    }

    $query = Contract::select(['id', 'price']);
    if (count($cids) > 1) {
      $query->whereIn('id', $cids);
    } else {
      $query->where('id', $cids[0]);
    }

    $contracts = $query->get()->keyBy('id');

    list($from, $to) = weekRange('-1 weeks');
    $from = date("Y-m-d", strtotime($from));
    $to = date("Y-m-d", strtotime($to));
    
    $map = HourlyLogMap::getWeekMinutes($cids, 'last');
    foreach($map as $cid => $mins) {
      $buyer_price = $contracts[$cid]->buyerPrice($mins);

      self::pay([
        'cid' => $cid,
        'amount' => $buyer_price,
        'type' => 'hourly',
        'hourly_from' => $from,
        'hourly_to' => $to,
        'hourly_mins' => $mins,
      ]);
    }
    
    return true;
  }

  public static function lastWithdrawalAmount($user_id)
  {
    $row = self::where('user_id', $user_id)
      ->where('type', self::TYPE_WITHDRAWAL)
      ->where('for', '<>', self::FOR_WAWJOB)
      ->orderBy('created_at', 'desc')
      ->select(['user_id', 'amount'])
      ->first();

    if (!$row) {
      return 0;
    }

    return -$row->amount;
  }
}