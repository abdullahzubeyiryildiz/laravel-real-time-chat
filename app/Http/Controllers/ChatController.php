<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use App\Events\UserOnline;
use App\Events\MessageSent;
use App\Events\UserOffline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getUsers() {
        $users = User::where('id', '!=', auth()->user()->id)->with('lastmessage')->orderBy('updated_at','desc')->get();

        return response(['data'=>$users, 'user_count'=> $users->count()]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('chat.users');
    }

    public function detail($uuid)
    {
        $user = User::where('uuid',$uuid)->first();
        return view('chat.chat',compact('user'));
    }

    public function messageList(Request $request)
    {
        $authid = auth()->user()->id;
        $usersent = User::where('uuid', $request->user)->first();

        $userid =  $usersent->id;

        if(!empty($request->date) && $request->date == 'today') {
            $date = Carbon::today('Europe/Istanbul');
        } else {
            $date = Carbon::parse($request->date)->format('Y-m-d');
        }

        $messages = Message::orWhere(function($query) use ($userid,$authid,$date) {
            $query->where('sent_to_id', $authid);
            $query->where('sender_id', $userid);
            if(!empty($date))  {
                $query->whereDate('created_at', $date);
            }
        })
        ->orWhere(function($query) use ($userid,$authid,$date) {
            $query->where('sent_to_id', $userid);
            $query->where('sender_id', $authid);
            if(!empty($date))  {
                $query->whereDate('created_at', $date);
            }
        })
        ->with('user')
        ->orderBy('id','asc')
        ->get();


        Message::orWhere(function($query) use ($userid,$authid) {
            $query->where('sent_to_id', $authid);
            $query->where('sender_id', $userid);
        })
        ->orWhere(function($query) use ($userid,$authid) {
            $query->where('sent_to_id', $userid);
            $query->where('sender_id', $authid);
        })->update(['read_at'=>'1']);



        $lastdate =   Message::orWhere(function($query) use ($userid,$authid,$date) {
            $query->where('sent_to_id', $authid);
            $query->where('sender_id', $userid);
            if(!empty($date))  {
                $query->whereDate('created_at', '<', $date);
            }
        })
        ->orWhere(function($query) use ($userid,$authid,$date) {
            $query->where('sent_to_id', $userid);
            $query->where('sender_id', $authid);
            if(!empty($date))  {
                $query->whereDate('created_at', '<', $date);
            }
        })->select(['id','created_at'])->orderBy('id','desc')->first();

        return response(['success'=>true , 'data'=>$messages,'last_date'=> !empty($lastdate) ? Carbon::parse($lastdate->created_at)->format('d-m-Y') : '']);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'sent_to_id' => 'required',
            'message' => 'required',
        ]);

       $user = User::where('uuid',$request->sent_to_id)->firstOrFail();
       $sendMessage = $request->message;

       if($user) {
          $messageCreate =  Message::create([
                'sender_id'=>auth()->user()->id,
                'sent_to_id'=> $user->id,
                'message'=> $sendMessage,
            ]);


            $message = Message::where('id', $messageCreate->id)->with('user')->first();
            event(new MessageSent($message));
       }
       return response(['success'=>true , 'message'=>$sendMessage]);
    }




    public function logout(Request $request)
    {
       $user = User::where('id', auth()->user()->id)->with('lastmessage')->first();

       $user->update(['is_online'=>'offline','socket_id'=>null]);

        event(new UserOnline($user));

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function loginsocket(Request $request)
    {
       $user = User::where('id', auth()->user()->id)->first();

       $user->update(['is_online'=>'online','socket_id'=>$request->soket_id]);

       return response()->json(['success'=>true,'message'=>'Login']);
    }

}
