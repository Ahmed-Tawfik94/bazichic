<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\Constants;
use App\Models\Notification;

class NotificationController extends BaseController
{
    public function index(Request $request, Response $response)
    {

        return $this->view->render($response, 'admin/notifications.twig',[
            'page'=>[
                'name'=>'notification'
            ]
        ]);

    }
    public function Delete(Request $request, Response $response, $args)
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        $id = $request->getParam('noti_id');
        $res = Notification::deleteNotification($id);
        if ($res) {
            $output["error"] = false;
            $output["message"] = "Notification has been deleted successfully. ";
            $output["id"] = $id;
            $jsonData = json_encode($output);
            $response->getBody()->write($jsonData);
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to delete Notification. Please try again.";
            $jsonData = json_encode($output);
            $response->getBody()->write($jsonData);
        }
        return $response;
    }
    public function Blast(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $title = $data['title'] ?? '';
        $message= $data['message'] ?? '';
        if(empty($title)){
            return $this->jsonResponse($response,['message'=>'Title is a required fields'],400);
        }
        if (empty($message)){
            return  $this->jsonResponse($response,['message'=>'Message is a required field'],400);
        }
        $res = Notification::createNotification((object)[
            'title'=>$title,
            'message'=>$message,
            'sender_id'=>$_SESSION['userID'],
            'seen'=>0
        ]);
        if($res['code'] == Constants::INSERT_FAILURE){
            return $this->jsonResponse($response,['message'=>$res['msg']],400);
        }
        return $this->jsonResponse($response,['message'=>"Notification has been sent successfully"],200);
    }
    public function read_notification(Request $request,Response $response,$args)
    {
        $notification_id= $args['id'];

        if (empty($notification_id)){
            return $this->jsonResponse($response,['message'=>'notification with'.$notification_id.' is not found'],404);
        }

        try {
            $notification=Notification::find($notification_id);

            $notification->seen = 1;
            $notification->save();

            return $this->jsonResponse($response,['message'=> 'message set to seen','id'=>$notification->id],200);
        }catch (\Exception $e){
            return $this->jsonResponse($response,['message'=>'Something wrong happened:'.$e->getMessage()],400);
        }

    }

    public function view_notification(Request $request, Response $response,$args){
        $page = $request->getParams()['page'] ?? 1;
        $perPage= 10;

//        $notifications = Notification::wit/h('sender')->orderBy('date_created', 'desc')->get();
        $notifications = Notification::with('sender')->orderBy('date_created', 'desc')->paginate($perPage, ['*'], 'page', $page);
        Notification::where('seen', 0)->update(['seen' => 1]);
        $vars=[
            'page'=>[
                'name'=>'notification'
            ],
            'notifications'=> $notifications->items(),
            'num_of_unread_notifications'=>Notification::where('seen',0)->count(),
            'num_of_all_notifications'=>Notification::count(),
            'pagination' => [
                'links'=>$notifications->linkCollection(),
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'next_page_url' => $notifications->nextPageUrl(),
                'prev_page_url' => $notifications->previousPageUrl(),
            ],
        ];
        return $this->view->render($response,'notifications.twig',$vars);
    }
}
