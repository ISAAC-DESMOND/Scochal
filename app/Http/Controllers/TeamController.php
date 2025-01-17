<?php

namespace App\Http\Controllers;

use App\Models\team;
use App\Models\member_joins;
use App\Models\User;
use App\Models\team_msgs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $team_ids = member_joins::where('user_id',Auth::id())->where('status', 'member')->pluck('team_id')->toArray();
        if(empty($team_ids)){
            $teams=team::orderBy('created_at','desc')->get();
        }
        else{
            $teams = team::orderByRaw(
            "FIELD(id, " . implode(',', $team_ids) . ") DESC, created_at DESC"
        )->get();
        $teams=team::orderBy('created_at','desc')->get();
        }
        
        return view('teams.teams',compact('teams','team_ids'));
    }

    public function team_index()
    {
        $teams=[];
        $teams=team::orderBy('created_at','desc')->get();
        return $teams;
    }

    public function create(){
        return view('teams.create');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create_team(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $team = team::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        member_joins::create([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'status' => 'member',
        ]);

        return redirect()->route('team.teams');
    }

    public function join_team(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        if(member_joins::where('team_id', $request->team_id)->where('user_id', Auth::id())->where('status', 'requested')->exists()){    
            return redirect()->route('team.teams');
        }
        member_joins::create([
            'team_id' => $request->team_id,
            'user_id' => Auth::id(),
            'status' => 'requested',
        ]);

        return redirect()->route('team.teams');
    }

    public function accept(Request $request){

        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $member=member_joins::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        $member->update(['status' => 'member']);

        return redirect(route('team.edit',['team_id' => $request->team_id]));
    }

    public function decline(Request $request){
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $member=member_joins::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        $member->delete();
        return redirect(route('team.edit',['team_id' => $request->team_id]));
    }

    public function show_chats($team_id){
        $messages=[];
        $team_name=team::find($team_id)->__get('name');
        $_SESSION['team']=$team_name;

        if(team_msgs::where('team_id',$team_id)->exists()){
            $messages=team_msgs::where('team_id',$team_id)->orderBy('created_at', 'asc')
            ->with('user:id,name') // Eager load sender's name
            ->get()
            ->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => decrypt(base64_decode($message->message)),
                'user_id' => $message->user_id,
                'user_name' => $message->user->name, // Include sender name
                'created_at' => $message->created_at
                ];
            });
        }
        return $messages;
    }

    public function show($team_id){
        $messages=$this->show_chats($team_id);
        return view('teams.chats',compact('messages','team_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'message' => 'required|string',
        ]);


        $enc_message=encrypt($request->message);
        $message=base64_encode($enc_message);

        team_msgs::create([
            'user_id' => Auth::id(),
            'team_id' => $request->team_id,
            'message' => $message,
        ]);

        $team_id=$request->team_id;

        return redirect(route('team.show',['team_id' => $team_id]));
    }

    public function edit($team_id){

        $members_ids = member_joins::where('team_id',$team_id)->where('status', 'member')->pluck('user_id')->toArray();
        
        $team=team::where('id',$team_id)->first();
        $user_id=$team->user_id;

        if(empty($members_ids)){
            $members=member_joins::where('team_id',$team_id)->orderBy('created_at', 'desc')
            ->with('user:id,name') // Eager load sender's name
            ->get()
            ->map(function ($member) {
            return [
                'team_id' => $member->team_id,
                'team_name' => $member->team->name,
                'user_id' => $member->user_id,
                'user_name' => $member->user->name,    
                'status' => $member->status, 
                'created_at' => $member->created_at
                ];
            })->reject(function($member) use($user_id){return $member['user_id'] == $user_id;});
        }
        else{
            $members = member_joins::where('team_id',$team_id)->orderByRaw(
            "FIELD(id, " . implode(',', $members_ids) . ") DESC, created_at DESC"
            )->get()
            ->map(function ($member) {
                return [
                    'team_id' => $member->team_id,
                    'team_name' => $member->team->name,
                    'user_id' => $member->user_id,
                    'user_name' => $member->user->name, 
                    'status' => $member->status, 
                    'created_at' => $member->created_at
                    ];
                })->reject(function($member) use($user_id) {return $member['user_id'] == $user_id;});
        }
        
        return view('teams.members',compact('members','members_ids'));
    }

    public function chat($user_id)
    {
        return redirect()->route('inbox.create', ['recipient_id' => $user_id]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
        ]);

    $member = Member_joins::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
    $member->delete();

    return redirect()->route('team.edit', $request->team_id);
}

    public function destroy($team_id)
    {
        $team=team::find($team_id);
        $team->delete();
        return redirect()->route('team.teams');
    }
}
