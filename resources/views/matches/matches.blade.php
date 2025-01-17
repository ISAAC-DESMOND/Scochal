<x-app-layout>
    <div id="matches" class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="bg-white dark:bg-gray-800 shadow mx-auto py-1 px-1 sm:px-3 lg:px-5 w-full font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="display:flex;flex-direction:row;">
                    <h1 class="bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Matches</h1>
                    <a href={{route('match.create')}} class="mt-1 bg-white dark:bg-gray-700 rounded shadow mx-auto py-6 px-4 sm:px-6 lg:px-8" style="margin-left: auto;">Create Match</a>
                     <a href={{route('team.create')}} class="mt-1 bg-white dark:bg-gray-700 rounded shadow mx-auto py-6 px-4 sm:px-6 lg:px-8" style="margin-left: auto;">Create Team</a>
                    </div>
                <ul id="match_list" class="block mt-1 w-full">
                    
                </ul>
            </div>
        </div
    </div>
    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded',onload);
        function onload() {

            const match_div = document.getElementById('match_list');
            // Handle user item click
            fetch(`/matches/all`)
                .then(response => response.json())
                .then(matches => {
                    if(!matches||matches.length==0){
                            return;
                        }
                    match_div.innerHTML =`
                    @foreach($matches as $match)
                        <li class="bg-white dark:bg-gray-800 shadow w-full mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded-md font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight hover:scale-120 transition-transform duration-300 ease-in-out" style="display:flex;flex-direction:row;margin:0.2em;">
                            <form action="{{route('team.join')}}" method="POST">
                                    @csrf
                                    <input type="hidden" id="team_id" name="team_id" value="{{ $match->home_team_id ?? '' }}">
                                    <x-primary-button class="mt-1 justify-center px-8" id="submit_btn">
                                        {{ __('Join Team') }}
                                    </x-primary-button>
                                </form>
                                <div class="text-center" style="margin-left: auto;">
                                    <div>
                                        {{$match->home->name}}
                                        :
                                        {{$match->away->name}}
                                    </div>
                                    <div>
                                    {{ $match->match_date->format('F j, Y') }}
                                     {{ $match->match_date->format('g:i A') }}
                                    </div>
                                    {{$match->court_location}}
                                </div>
                            <form action="{{route('team.join')}}" method="POST" style="justify-self:end;margin-left: auto;>
                                @csrf
                                <input type="hidden" id="team_id" name="team_id" value="{{ $match->away_team_id ?? '' }}">
                                <x-primary-button class="mt-1 justify-center px-8" id="submit_btn" style="justify-self:end;margin-left: auto;">
                                    {{ __('Join Team') }}
                                </x-primary-button>
                            </form>
                        </li>
                    @endforeach`;

                });
            setTimeout(onload,1000);
        }
    </script>
    @endsection
    </x-app-layout>
