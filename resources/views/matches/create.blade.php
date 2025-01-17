<x-app-layout>
    <div class="container bg-white dark:bg-gray-800 shadow font-semibold text-xl text-gray-800 dark:text-gray-200 mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded">
        <h4 class="bg-white dark:bg-gray-800 shadow text-gray-500 dark:text-gray-400 mx-auto py-6 px-4 sm:px-6 lg:px-8 w-full">Create Match</h4>
        @created
        <form action="{{ route('match.store') }}" method="POST">
            @csrf
            <div class=" mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded">
                <x-input-label for="home_team_id" :value="__('Home Team')" class="block mt-1 w-full text-gray-500 dark:text-gray-400"/>
                <select name="home_team_id" id="home_team_id" class="form-control block mt-1 w-full text-gray-900 dark:text-gray-800" required>
                    @foreach($user_teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class=" mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded">
                <x-input-label for="away_team_id" :value="__('Away Team')" class="block mt-1 w-full text-gray-500 dark:text-gray-400"/>
                <select name="away_team_id" id="away_team_id" class="form-control block mt-1 w-full text-gray-900 dark:text-gray-800" required>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
    
            <div  class=" mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded" style="margin-top: 0.8em;display:flex;flex-direction:row;">
                <x-input-label for="match_date" :value="__('Match Date')" class="block mt-1 w-full text-gray-500 dark:text-gray-400"/>
                <input type="datetime-local" name="match_date" id="match_date" class="block mt-1 w-full text-gray-900 dark:text-gray-800" required>
            
                <x-input-label for="court_location" :value="__('Court Location')" class="block mt-1 w-full text-gray-500 dark:text-gray-400"/>
                <input type="text" name="court_location" id="court_location" class="block mt-1 w-full text-gray-900 dark:text-gray-800"  style="width:500em;">
            </div>
    
            <x-primary-button class="block mt-1 w-full justify-center" style="margin-top: 3%">
                {{ __('Send Request') }}
            </x-primary-button>
        </form>
    </div>
    @endcreated
</x-app-layout>