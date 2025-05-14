<x-app-layout>
<x-slot name="header">
    <div class="flex flex-row items-center justify-between space-x-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teams') }}
        </h2>

        @created(Auth::id())
            <a href="{{ route('match.create') }}" class="bg-white dark:bg-gray-700 rounded shadow py-2 px-4 text-sm text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                Create Match
            </a>
        @endcreated

        <a href="{{ route('team.create') }}" class="bg-white dark:bg-gray-700 rounded shadow py-2 px-4 text-sm text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            Create Team
        </a>
    </div>
</x-slot>

    <div id="messenger" class="container w-full">
        <div class="row w-full">
            <div class="col-md-4 w-full">
                <ul id="team_list" class="block mt-1 w-full" style="display:flex;flex-direction:row;flex-wrap:wrap;margin:0.5em;">

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
                        <li class="user bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded-md font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight hover:scale-120 transition-transform duration-300 ease-in-out" style="margin:0.5em;">
                            Team Name: {{ $team->name }}</br> Sport Played: {{$team->description}}
				@if(in_array($team->id, $team_ids))
				    <form action="{{ route('team.show', $team->id) }}" method="GET">
				        @csrf
				        <input type="hidden" name="team_id" value="{{ $team->id }}">
				        <x-primary-button class="mt-1 justify-center px-4 w-full">
				            {{ __('Team Chatroom') }}
				        </x-primary-button>
				    </form>

				    <form action="{{ route('team.edit', $team->id) }}" method="GET">
				        @csrf
				        <input type="hidden" name="team_id" value="{{ $team->id }}">
				        <x-primary-button class="mt-1 justify-center px-4 w-full">
				            {{ __('View Members') }}
				        </x-primary-button>
				    </form>
				
					@elseif(in_array($team->id, $requested_team_ids))
				    		<div class="flex items-center justify-center text-yellow-500 mt-2">
				        	<svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"></path>
					        </svg>
					        Request Pending
					    	</div>

					@else
					    <form action="{{ route('team.join') }}" method="POST">
					        @csrf
					        <input type="hidden" name="team_id" value="{{ $team->id }}">
					        <x-primary-button class="mt-1 justify-center px-8 w-full">
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
