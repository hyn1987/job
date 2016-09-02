<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
//use Wawjob\Ticket;

class TicketComment extends Model {

	//

  /**
   * Get the user who commented.
   *
   * @return mixed
   */
  public function commentor()
  {
    return $this->hasOne('Wawjob\User', 'id', 'commentor_id');
  }

  public function renderAttachmentHtml()
  {    
    $url = '';

    $dir = getTicketCommentUploadDir($this->ticket_id, $this->id);

    if ( !is_dir($dir) ) {
      return '';
    }

    // Get first valid file
    if ($handle = opendir($dir)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          $url = resourceUrl('tcomment', $this->id, $entry);
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
