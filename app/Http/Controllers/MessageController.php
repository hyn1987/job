<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Auth;
use Storage;
use Config;

// Models
use Wawjob\User;
use Wawjob\ProjectMessageThread;
use Wawjob\ProjectMessage;
use Wawjob\ProjectApplication;
use Wawjob\Notification;

//DB
use DB;

class MessageController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Retrieve Message list
   *
   * @param  Request $request
   * @return Response
   */
  public function all(Request $request)
  {
    $user = Auth::user();
    if (!$user) {
      return redirect()->route('user.login');
    }
    
    $userId = Auth::user()->id;
    
    if ($request->ajax()) {

      switch ($request->input("callType")) {
        case 'detail':
          $threadId = $request->input('threadId');
          $projectMessageThread = ProjectMessageThread::find($threadId);
          
          return response()->json([
            'success'             => true,
            'strGroupMessageList' => $this->groupMessageList($threadId, $userId),
            'strMessageSummary'   => $this->messageSummary($projectMessageThread),
            'threadId'            => $threadId,
          ]);

          break;
        
        case 'master':
          //get the message thread list
          $subject = $request->input("searchTitle");
          
          if (!$subject) {
            $messageThreadList = ProjectMessageThread::where('sender_id','=',  $userId)
                                                ->orWhere('receiver_id', '=',  $userId)
                                                ->get();

            if ($request->input("sortSel") == "new") {
              $messageThreadList = ProjectMessageThread::where('sender_id','=',  $userId)
                                                ->orderBy('created_at','desc')
                                                ->orWhere('receiver_id', '=',  $userId)
                                                ->get();
            } else if ($request->input("sortSel") == "old") {
              $messageThreadList = ProjectMessageThread::where('sender_id','=',  $userId)
                                                ->orderBy('created_at','asc')
                                                ->orWhere('receiver_id', '=',  $userId)
                                                ->get();
            } 
                                                
          } else {
            $messageThreadList = ProjectMessageThread::
                                        where('subject','like', '%' . $subject .'%')
                                      ->whereRaw('(receiver_id = ? or sender_id = ?)', [$userId, $userId])
                                      ->orderBy('created_at','desc')
                                      ->get();
          }
          
          
          $strHtmlMessageThreadList = '';
          $strGroupMessageList = '';
          $strMessageSummary = '';
          $strSendMessageForm = '';
          $threadId = '';

          if (!$messageThreadList->isEmpty()) {
            $threadId = $messageThreadList->first()->id;

            foreach ($messageThreadList as $key => $messageThread) {

              $strHtmlMessageThreadList = $strHtmlMessageThreadList .
                        view('pages.message.messageThread', [
                          'messageThread' => $messageThread,
                          'threadId' => $threadId,
                        ])->render();
            }

            $projectMessageThread = ProjectMessageThread::find($threadId);
            $strGroupMessageList = $this->groupMessageList($threadId, $userId);
            $strMessageSummary   = $this->messageSummary($projectMessageThread);
          }

          return  response()->json([
            'success'                   => true,
            'strHtmlMessageThreadList'  => $strHtmlMessageThreadList,
            'strGroupMessageList'       => $strGroupMessageList,
            'strMessageSummary'         => $strMessageSummary,
            'threadId'                  => $threadId,
          ]);

          break;

        case 'save':

          $this->validate($request, [
            'message' => 'required',      
          ]);

          $thread_id = $request->input("threadId");
          $thread = ProjectMessageThread::find($thread_id);
          $app    = ProjectApplication::find($thread->application_id);
          if ($app) {
            $msg = strip_tags($request->input('message'));
            $app->sendMessage($msg, $user->id);
          }

          return  response()->json([
            'success'   => true,
            'threadId'  => $request->input('threadId'),
          ]);

          break;

        case 'read_message': 
          $message = ProjectMessage::find($request->input('messageId'));
          $message->received_at = date('Y-m-d H:i:s');
          $message->save();

        default:
          
          break;
      }
    } else {
      $threadId = $request->input('thread');
      //get the message thread list
      $messageThreadList = ProjectMessageThread::where('sender_id','=', $userId)
                                  ->orWhere('receiver_id', '=', $userId)
                                  ->orderBy('created_at','desc')
                                  ->get();
                                  

      $groupMessageList = [];
      $messageThread = false;
      
      if ( !$threadId ) {
        if (!$messageThreadList->isEmpty()) {

          //set the 'threadId' to first id of the thread list
          $messageThread = $messageThreadList->first();
          
          //get the message list by the first thread-id of the given list 
          $messageList = ProjectMessage::where('thread_id', '=', $messageThread->id)
                                      ->orderBy('created_at','asc')
                                      ->get();
          //group bu the created time
          $groupMessageList = $messageList->groupBy( function ($item, $key) {
            return substr($item->created_at, 0, 10);
          });   
        }     
      } else {
        //set the 'threadId' 
        $messageThread = ProjectMessageThread::find($threadId);

        //get the message list by the first thread-id of the given list 
        $messageList = ProjectMessage::where('thread_id', '=', $threadId)
                                    ->orderBy('created_at','asc')
                                    ->get();
        //group bu the created time
        $groupMessageList = $messageList->groupBy( function ($item, $key) {
          return substr($item->created_at, 0, 10);
        });               
      }

      $messageThreadId = $messageThread ? $messageThread->id : '';

      return view('pages.message.list', [
          'page'                  => 'message.list',
          'messageThreadList'     => $messageThreadList,
          'groupMessageList'      => $groupMessageList,
          'summaryMessageThread'  => $messageThread,
          'threadId'              => $messageThreadId,
          'userId'                => $userId,
        ]);
    }
  }

  private function groupMessageList($threadId, $userId) {
    //get the message list by the first thread-id of the given list 
    $messageList = ProjectMessage::where('thread_id', '=', $threadId)
                                  ->orderBy('created_at','asc')
                                  ->get();

    //group by the created time
    $groupMessageList = $messageList->groupBy( function ($item, $key) {
      return substr($item->created_at, 0, 10);
    });

    return view('pages.message.groupMessageList', 
              ['groupMessageList' => $groupMessageList, 
                'userId' => $userId
                ])
              ->render();    
  }

  private function sendMessageForm($threadId) {
    
    return view('pages.message.sendMessageForm', 
                ['threadId' => $threadId])
              ->render();   
  } 

  private function messageSummary($projectMessageThread) {
    
    return view('pages.message.messageSummary', 
                ['summaryMessageThread' => $projectMessageThread])
              ->render();   
  }  
}