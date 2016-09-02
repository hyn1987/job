<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
use Config;

use Wawjob\ContractMeter;

class FeeSettings {
  public static function getFeeRate() {
    $conf = Config::get('settings');
    $fees = $conf['fee'];
    return $fees;
  }
  public static function getFeeRate_Contract($contract_id) {
    $fees = self::getFeeRate();
    $total_amount = 0;
    $c_meter = ContractMeter::find($contract_id);
    if ($c_meter && isset($c_meter->last_total_amount)) {
      $total_amount = $c_meter->last_total_amount;
    }

    foreach ($fees as $fee) {
      if ($fee['limit'] < 0) {
        return $fee['fee_rate'];
      }
      else if ($total_amount <= $fee['limit']) {
        return $fee['fee_rate'];
      }
    }
  }
}