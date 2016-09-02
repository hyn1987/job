<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
use DB;

class Ticket extends Model {

  /**
   * Ticket types
   */
  const TYPE_NOTIFY = 0;
  const TYPE_NORMAL = 1;
  const TYPE_DISPUTE = 2;
  const TYPE_QUESTION = 3;
  const TYPE_SUSPENSION = 4;
  const TYPE_MAINTANANCE = 5;

  /**
   * Ticket priorities
   */
  const PRIORITY_CRITICAL = 0;
  const PRIORITY_HIGH = 1;
  const PRIORITY_MEDIUM = 2;
  const PRIORITY_LOW = 3;

  /**
   * Ticket statuses
   */
  const STATUS_OPEN = 0;
  const STATUS_ASSIGNED = 1;
  const STATUS_SOLVED = 2;
  const STATUS_CLOSED = 3;

  /**
   * Get the user which create ticket.
   *
   * @return mixed
   */
  public function user()
  {
    return $this->belongsTo('Wawjob\User', 'user_id');
  }

  /**
   * Get the admin which process ticket.
   *
   * @return mixed
   */
  public function admin()
  {
    return $this->belongsTo('Wawjob\User', 'admin_id');
  }

  /**
   * Get the comments associated with the ticket.
   *
   * @return mixed
   */
  public function comments()
  {
    return $this->hasMany('Wawjob\TicketComment', 'ticket_id');
  }

  /**
  * @author paulz
  * @created Mar 7, 2016
  */
  public function comments_count()
  {
    $n = DB::table('ticket_comments')
            ->where('ticket_id', '=', $this->id)
            ->count();

    return $n;
  }

  /**
  * Returns array for each <select> tag
  *
  * @author paulz
  * @created Mar 7, 2016
  */
  public static function getOptions($type)
  {
    switch ($type) {
    case "status":
      $options = [
        "Open"     => self::STATUS_OPEN,
        "Assigned" => self::STATUS_ASSIGNED,
        "Solved"   => self::STATUS_SOLVED,
        "Closed"   => self::STATUS_CLOSED
      ];
      break;

    case "priority":
      $options = [
        "Critical" => self::PRIORITY_CRITICAL,
        "High"     => self::PRIORITY_HIGH,
        "Medium"   => self::PRIORITY_MEDIUM,
        "Low"      => self::PRIORITY_LOW
      ];
      break;

    case "type":
      $options = [
        "Notify"      => self::TYPE_NOTIFY,
        "Normal"      => self::TYPE_NORMAL,
        "Dispute"     => self::TYPE_DISPUTE,
        "Question"    => self::TYPE_QUESTION,
        "Suspension"  => self::TYPE_SUSPENSION,
        "Maintenance" => self::TYPE_MAINTANANCE
      ];
      break;

    default:
      $options = [];
    }

    return $options;
  }

  /**
  * Converts constant to human-readable string
  *
  * @author paulz
  * @created Mar 9, 2016
  *
  * @param: $type - Field type
  * @param: $value - Integer value to be converted to string
  */
  public static function toString($type, $value)
  {
    $arr = self::getOptions($type);
    if ( !$arr ) {
      return false;
    }

    $options = array_flip($arr);
    if ( !isset($options[$value]) ) {
      return false;
    }

    return $options[$value];
  }

  /**
  * @author paulz
  * @created Mar 12, 2016
  */
  public function renderAttachmentHtml()
  {
    $url = '';

    $dir = getTicketUploadDir($this->id);

    if ( !is_dir($dir) ) {
      return '';
    }

    // Get first valid file
    if ($handle = opendir($dir)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && !is_dir($dir."/".$entry)) {
          $url = resourceUrl('ticket', $this->id, $entry);
          break;
        }
      }
    }
    closedir($handle);

    if (!$url) {
      return '';
    }

    $faClass = getFileIconClass($entry);

    $html = '<div class="attachment">';
    $html .= '<span class="fa '.$faClass.'"></span>';
    $html .= ' <a href="'.$url.'" target="_blank">'.$entry.'</a>';
    $html .= '</div>';

    return $html;
  }

}