<?php

namespace App\Http\Controllers;

use App\Models\team;
use App\Events\notifyEvent;
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
	$team_ids = member_joins::where('user_id', Auth::id())
        ->where('status', 'member')
        ->pluck('team_id')
        ->toArray();

    $requested_team_ids = member_joins::where('user_id', Auth::id())
        ->where('status', 'requested')
        ->pluck('team_id')
        ->toArray();

    $teams = team::orderBy('created_at','desc')->get();

    return view('teams.teams', compact('teams', 'team_ids', 'requested_team_ids'));
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

        return redirect()->route('team.all')->with('notification', [
            'type' => 'success',
            'message' => 'New team created!',
        ]);
    }

    public function join_team(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        if(member_joins::where('team_id', $request->team_id)->where('user_id', Auth::id())->where('status', 'requested')->exists()){    
            return redirect()->route('team.all');
        }
        member_joins::create([
            'team_id' => $request->team_id,
            'user_id' => Auth::id(),
            'status' => 'requested',
        ]);

        $team=team::find($request->team_id);

        broadcast(new NotifyEvent($team->user_id,'Someone has requested to join '.$team->name,'info'));

        return redirect()->route('team.all')->with('notification', [
            'type' => 'success',
            'message' => 'You have requested to join!',
        ]);
    }

    public function accept(Request $request){

        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $member=member_joins::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        $member->update(['status' => 'member']);

        $team=team::find($request->team_id);

        broadcast(new NotifyEvent($request->user_id,'Your request to join '.$team->name.' has been accepted','success'));

        return redirect(route('team.edit',['team_id' => $request->team_id]))->with('notification', [
            'type' => 'success',
            'message' => 'Join Request Accepted!',
        ]);
    }

    public function decline(Request $request){
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $member=member_joins::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        $member->delete();

	$team=team::find($request->team_id);

	broadcast(new NotifyEvent($request->user_id,'Your request to join '.$team->name.' has been declined','info'));


        return redirect(route('team.edit',['team_id' => $request->team_id]))->with('notification', [
            'type' => 'info',
            'message' => 'Join Request Declined!',
        ]);
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

        $team=team::find($team_id);

	$teamIds = member_joins::where('team_id', $team_id)
	    ->where('status', 'member')
	    ->pluck('user_id');
	foreach($teamIds as $id){
	        broadcast(new NotifyEvent($id,'new message on the '.$team->name.' team chatroom','success'));
	}

        return redirect(route('team.show',['team_id' => $team_id]));
    }

    public function edit($team_id)
    {
        $team = Team::find($team_id);
        $manager_id = $team->user_id;
    
        $all = member_joins::where('team_id', $team_id)
            ->with('user') // Eager load user data (name, etc.)
            ->get();
    
        $manager = $all->firstWhere('user_id', $manager_id);
        $members = $all->filter(function ($member) use ($manager_id) {
            return $member->status === 'member' && $member->user_id !== $manager_id;
        });
        $candidates = $all->filter(function ($member) {
            return $member->status !== 'member';
        });
    
        return view('teams.members', compact('manager','members','candidates','team_id'));
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
        $team=team::find($request->team_id);

        broadcast(new NotifyEvent($request->user_id,'You have been removed from '.$team->name,'info'));

        return redirect()->route('team.edit', $request->team_id)->with('notification', [
            'type' => 'info',
            'message' => 'User has been removed from this team!',
        ]);
    }

    public function destroy($team_id)
    {
        $team=team::find($team_id);

        $teamIds = member_joins::where('team_id', $team_id)
            ->where('status', 'member')
            ->pluck('user_id');
        foreach($teamIds as $id){
                broadcast(new NotifyEvent($id,'The team '.$team->name.' has been deleted','info'));
        }


        $team->delete();
        return redirect()->route('team.all')->with('notification', [
            'type' => 'info',
            'message' => 'your team has been deleted!',
        ]);
    }
}
