<?php

namespace App\Http\Controllers;

use App\Models\direct_msg;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\notifyEvent;

class DirectMsgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=[];
        $last_msgs=[];
        if(direct_msg::where('recipient_id', Auth::id())
        ->orWhere('sender_id', Auth::id())->exists()){
            $messages = direct_msg::where('recipient_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->orderBy('created_at', 'desc')->get();

           $user_ids=$messages->pluck('recipient_id')->merge($messages->pluck('sender_id'))->unique()->reject(fn($user_id)=>$user_id==Auth::id())->values();
	   $users= User::whereIn('id', $user_ids)->get() ;

            foreach($users as $user){
                $message=direct_msg::last($user->id);
                $message=base64_decode($message);
                $message=decrypt($message);
                $last_msgs[$user->__get('id')]=$message;
            }


        }
         return response()->json([
        'users' => $users,
        'last_msgs' => $last_msgs,
        ]);
    }

    public function show(){

        $users=[];
        $last_msgs=[];

        if(direct_msg::where('recipient_id', Auth::id())
        ->orWhere('sender_id', Auth::id())->exists()){
            $messages = direct_msg::where('recipient_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->orderBy('created_at', 'desc')->get();

	$user_ids=$messages->pluck('recipient_id')->merge($messages->pluck('sender_id'))->unique()->reject(fn($user_id)=>$user_id==Auth::id())->values();
        $users= User::whereIn('id', $user_ids)->get() ;

            foreach($users as $user){
                $message=direct_msg::last($user->id);
                $message=base64_decode($message);
                $message=decrypt($message);
                $last_msgs[$user->__get('id')]=$message;
            }


        }
        return view('inbox.inbox', compact('users','last_msgs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($recipient_id)
    {
        $messages=$this->show_all($recipient_id);
        return view('inbox.send_message', compact('messages','recipient_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);


        $enc_message=encrypt($request->message);
        $message=base64_encode($enc_message);
        direct_msg::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'message' => $message,
        ]);

        $recipient_id=$request->recipient_id;
	$sendername=User::find(Auth::id())->__get('name');
	event(new notifyEvent( $request->recipient_id, 'New message from '.$sendername,'success'));


        return redirect(route('inbox.create',['recipient_id' => $recipient_id]));
    }

    /**
     * Display the specified resource.
     */

    public function show_all($recipient_id)
    {
        $user_id = Auth::id();
        $messages=[];
        $recipient_name=User::find($recipient_id)->__get('name');
        $_SESSION['recipient']=$recipient_name;

        // Retrieve messages where the authenticated user is involved with the recipient
        if(direct_msg::where('recipient_id', Auth::id())
        ->orWhere('sender_id', Auth::id())->exists()){
            $messages = direct_msg::where(function ($query) use ($user_id, $recipient_id) {
                $query->where('sender_id', $user_id)
                      ->where('recipient_id', $recipient_id);
            })->orWhere(function ($query) use ($user_id, $recipient_id) {
                $query->where('sender_id', $recipient_id)
                      ->where('recipient_id', $user_id);})->orderBy('created_at', 'asc')->get();
            foreach($messages as $message){
                $message->message=base64_decode($message->message);
                $message->message=decrypt($message->message);
            }
            return $messages;
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(direct_msg $direct_msg)
    {
        //
    }
}
