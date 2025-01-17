<x-app-layout>
    <div id="messenger" class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="bg-white dark:bg-gray-800 shadow mx-auto py-1 px-1 sm:px-3 lg:px-5 w-full font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="display:flex;flex-direction:row;">
                    <h1 class="bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Teams</h1>
                    @created
                    <a href={{route('match.create')}} class="mt-1 bg-white dark:bg-gray-700 rounded shadow mx-auto py-6 px-4 sm:px-6 lg:px-8" style="margin-left: auto;">Create Match</a>
                    @endcreated 
                    <a href={{route('team.create')}} class="mt-1 bg-white dark:bg-gray-700 rounded shadow mx-auto py-6 px-4 sm:px-6 lg:px-8" style="margin-left: auto;">Create Team</a>
                    </div>
                <ul id="team_list" class="block mt-1 w-full" style="display:flex;flex-direction:row;">
                    
                </ul>
            </div>
        </div
    </div>
    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded',onload);
        function onload() {

            const team_div = document.getElementById('team_list');
            // Handle user item click
            fetch(`/teams/all`)
                .then(response => response.json())
                .then(teams => {
                    if(!teams||teams.length==0){
                            return;
                        }
                    team_div.innerHTML =`
                    @foreach($teams as $team)
                        <li class="user bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded-md font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight hover:scale-120 transition-transform duration-300 ease-in-out">
                            Team Name: {{ $team->name }}</br> Sport Played: {{$team->description}}
                            @if (in_array($team->id, $team_ids))
                                <form action="{{route('team.show',$team->id)}}" method="GET">
                                    @csrf
                                    <input type="hidden" id="team_id" name="team_id" value="{{ $team->id ?? '' }}">
                                    <x-primary-button class="mt1 justify-center px-4 w-full" id="submit_btn" style="margin-top:0.4em;">
                                        {{ __('Enter Team') }}
                                    </x-primary-button>
                                </form>
                                @if(Auth::id() === $team->user_id)
                                <form action="{{route('team.edit',$team->id)}}" method="GET">
                                    @csrf
                                    <input type="hidden" id="team_id" name="team_id" value="{{ $team->id ?? '' }}">
                                    <x-primary-button class="mt1 justify-center px-4 w-full" id="submit_btn" style="margin-top:0.4em;">
                                        {{ __('Edit Team') }}
                                    </x-primary-button>
                                </form>
                                @endif
                            @else
                                <form action="{{route('team.join')}}" method="POST">
                                    @csrf
                                    <input type="hidden" id="team_id" name="team_id" value="{{ $team->id ?? '' }}">
                                    <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                        {{ __('Join Team') }}
                                    </x-primary-button>
                                </form>
                            @endif
                        </li>
                    @endforeach`;

                });
            setTimeout(onload,1000);
        }
    </script>
    @endsection
    </x-app-layout>
