<?php

namespace App\Http\Controllers;

use App\Models\sport_match;
use App\Events\notifyEvent;
use App\Models\team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SportMatchController extends Controller
{
    public function create()
    {
        $user_teams=team::where('user_id',Auth::id())->get();
	$teams_ids=team::where('user_id',Auth::id())->pluck('id');
        $teams = team::whereNotIn('id',$teams_ids)->get();
        return view('matches.create', compact('teams','user_teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id',
            'match_date' => 'required|date',
            'court_location' => 'nullable|string|max:255',
        ]);

        $match = sport_match::create([
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'match_date' => $request->match_date,
            'court_location' => $request->court_location,
        ]);

	$homeIds = member_joins::where('team_id', $match->home_team_id)
	    ->where('status', 'member')
	    ->pluck('user_id');

	$awayIds = member_joins::where('team_id', $match->away_team_id)
	    ->where('status', 'member')
	    ->pluck('user_id');

	$message = "New match scheduled between {$match->home->name} and {$match->away->name} on {$match->match_date->format('F j, Y g:i A')}";
	$type = 'success';

	foreach ($homeIds as $Id) {
	    broadcast(new NotifyEvent($Id, $message, $type));
	}


	foreach ($awayIds as $Id) {
	    broadcast(new NotifyEvent($Id, $message, $type));
	}

        return redirect()->route('match.all')->with('notification', [
            'type' => 'success',
            'message' => 'Match request sent!',
        ]);
    }

    public function index()
    {
        $matches=$this->list_all();

        return view('matches.matches', compact('matches'));

    }

    public function list_all()
    {
        $matches=[];
        if(sport_match::exists()){
            $matches = sport_match::orderBy('match_date', 'asc')->get();
        }
        return $matches;
    }

    public function negotiate(Request $request)
    {
        $match = sport_match::findOrFail($request->match_id);
	$validated = $request->validate([
            'court_location' => 'nullable|string|max:255',
            'match_date' => 'required|date',
            'home_team_score' => 'nullable|integer|min:0',
            'away_team_score' => 'nullable|integer|min:0',
        ]);

        $match->update($validated);

        $homeIds = member_joins::where('team_id', $match->home_team_id)
            ->where('status', 'member')
            ->pluck('user_id');

        $awayIds = member_joins::where('team_id', $match->away_team_id)
            ->where('status', 'member')
            ->pluck('user_id');

        $message = "Match details changed for {$match->home->name} v {$match->away->name}";
        $type = 'info';

        foreach ($homeIds as $Id) {
            broadcast(new NotifyEvent($Id, $message, $type));
        }


        foreach ($awayIds as $Id) {
            broadcast(new NotifyEvent($Id, $message, $type));
        }


	return redirect()->route('match.all')->with('notification', [
            'type' => 'success',
            'message' => 'Match details changed!',
        ]);
    }

    public function edit($match_id){
        $match = sport_match::findOrFail($match_id);
        return view('matches.edit', compact('match'));
    }

    public function destroy(sport_match $sport_match)
    {
        //
    }
}
