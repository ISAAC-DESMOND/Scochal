<x-slot name="header">
    <div class="flex flex-row items-center justify-between space-x-4">
        <!-- Header Title -->
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Matches') }}
        </h2>

        @created(Auth::id())
            <a href="{{ route('match.create') }}" class="bg-white dark:bg-gray-700 rounded shadow py-2 px-4 text-sm  text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                Create Match
            </a>
        @endcreated

        <a href="{{ route('team.create') }}" class="bg-white dark:bg-gray-700 rounded shadow py-2 px-4 text-sm text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            Create Team
        </a>
    </div>
</x-slot>

    <div id="matches" class="container w-full">
        <div class="row w-full">
            <div class="col-md-4 w-full">
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
                                                        <form action="{{route('inbox.create',[$match->home->user_id])}}" method="GET">
                                    @csrf
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $match->home->user_id ?? '' }}">
                                    @team_manager($match->away_team_id)
                                    <x-primary-button class="mt-1 justify-center px-8" id="submit_btn">
                                        {{ __('Message Manager') }}
                                    </x-primary-button>
                                    @endteam_manager
                                </form>
                            <form action="{{route('match.edit',[$match->id])}}" method="GET">
                                    @csrf
                                    <input type="hidden" id="match_id" name="match_id" value="{{ $match->id ?? '' }}">
                                    @team_manager($match->home_team_id)
                                    <x-primary-button class="mt-1 justify-center px-8" id="submit_btn">
                                        {{ __('Edit Match') }}
                                    </x-primary-button>
                                    @endteam_manager
                                </form>

                                <div class="text-center" style="margin-left: auto;">
                                    <div>
                                        {{$match->home->name}} [{{$match->home_team_score}}]
                                        :
                                         [{{$match->away_team_score}}] {{$match->away->name}}
                                    </div>
                                    <div>
                                    {{ $match->match_date->format('F j, Y') }} ,
                                     {{ $match->match_date->format('g:i A') }}
                                    </div>
                                    {{$match->court_location}} , {{$match->home->description}}
                                </div>
                            <form action="{{route('inbox.create',[$match->away->user_id])}}" method="GET" style="justify-self:end;margin-left: auto;>
                                @csrf
                                <input type="hidden" id="user_id" name="user_id" value="{{ $match->away->user_id ?? '' }}">
                                @team_manager($match->home_team_id)
                                <x-primary-button class="mt-1 justify-center px-8" id="submit_btn" style="justify-self:end;margin-left: auto;">
                                    {{ __('Message Manager') }}
                                </x-primary-button>
                                @endteam_manager
                            </form>
                            <form action="{{route('match.edit',[$match->id])}}" method="GET">
                                    @csrf
                                    <input type="hidden" id="match_id" name="match_id" value="{{ $match->id ?? '' }}">
                                    @team_manager($match->away_team_id)
                                    <x-primary-button class="mt-1 justify-center px-8" id="submit_btn">
                                        {{ __('Edit Match') }}
                                    </x-primary-button>
                                    @endteam_manager
                                </form>
                        </li>
                    @endforeach`;

                });
            setTimeout(onload,1000);
        }
    </script>
    @endsection
    </x-app-layout>
