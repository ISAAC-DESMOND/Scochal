<?php

namespace App\Http\Controllers;

use App\Models\sport_match;
use App\Models\team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SportMatchController extends Controller
{
    public function create()
    {
        $user_teams=team::where('user_id',Auth::id())->get();
        $teams = team::all();
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

        return redirect()->route('match.all');
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

    public function destroy(sport_match $sport_match)
    {
        //
    }
}
