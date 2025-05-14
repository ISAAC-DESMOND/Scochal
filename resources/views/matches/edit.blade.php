<x-app-layout>
    <div class="container bg-white dark:bg-gray-800 shadow font-semibold text-xl text-gray-800 dark:text-gray-200 mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded">
        <h4 class="text-gray-500 dark:text-gray-400 py-6 px-4 sm:px-6 lg:px-8 w-full">Edit Match: {{$match->home->name}} v {{$match->away->name}}</h4>

        <form action="{{ route('match.negotiate')}}" method="POST">
            @csrf
	    <input type="hidden" id="match" name="match_id" value="{{$match->id}}">
            <div class="py-4 px-4 sm:px-6 lg:px-8">
                <x-input-label for="match_date" :value="__('Match Date')" class="text-gray-500 dark:text-gray-400"/>
                <input type="datetime-local" name="match_date" id="match_date"
                       value="{{ old('match_date', $match->match_date->format('Y-m-d\TH:i')) }}"
                       class="block mt-1 w-full text-gray-900 dark:text-gray-800 bg-white rounded border dark:border-gray-600" required>
            </div>

            <div class="py-4 px-4 sm:px-6 lg:px-8">
                <x-input-label for="court_location" :value="__('Court Location')" class="text-gray-500 dark:text-gray-400"/>
                <input type="text" name="court_location" id="court_location"
                       value="{{ old('court_location', $match->court_location) }}"
                       class="block mt-1 w-full text-gray-900 dark:text-gray-800 bg-white rounded border dark:border-gray-600">
            </div>

            <div class="flex flex-row gap-4 py-4 px-4 sm:px-6 lg:px-8">
                <div class="w-1/2">
                    <x-input-label for="home_team_score" :value="__('Home Team Score')" class="text-gray-500 dark:text-gray-400"/>
                    <input type="number" name="home_team_score" id="home_team_score"
                           value="{{ old('home_team_score', $match->home_team_score) }}"
                           min="0"
                           class="block mt-1 w-full text-gray-900 dark:text-gray-800 bg-white rounded border dark:border-gray-600">
                </div>
                <div class="w-1/2">
                    <x-input-label for="away_team_score" :value="__('Away Team Score')" class="text-gray-500 dark:text-gray-400"/>
                    <input type="number" name="away_team_score" id="away_team_score"
                           value="{{ old('away_team_score', $match->away_team_score) }}"
                           min="0"
                           class="block mt-1 w-full text-gray-900 dark:text-gray-800 bg-white rounded border dark:border-gray-600">
                </div>
            </div>

            <x-primary-button class="mt-6 block w-full justify-center">
                {{ __('Submit') }}
            </x-primary-button>
        </form>
    </div>
</x-app-layout>

