<x-app-layout>
    
    @php
        $team_id = request('team_id');
        $team_name =App\Models\team::where('id', request('team_id'))->pluck('name')->first();
    @endphp
    @team_member($team_id)
    <div class="container mx-auto my-6">
        <h3 class="bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Manage Team: {{ $team_name}}</h3>
        <div>
            <h5 class="bg-white dark:bg-gray-700 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">Members</h5>
            <div class="space-y-4">
        <div class="bg-white dark:bg-gray-700 shadow flex items-center justify-between p-4 border rounded shadow" style="margin:0.5em;">
            <p class="text-lg text-gray-800 dark:text-gray-200">
                {{ $manager->user->name }}
 		@if($manager->user_id != Auth::id())
                        <form action="{{ route('team.chat', [$manager->user_id]) }}" method="GET">
                            @csrf
                            <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                {{ __('Message Manager') }}
                            </x-primary-button>
                        </form>
                        @endif
            </p>
	</div> 
            @if($members!=[])
                @foreach($members as $member)
                    <div class="bg-white dark:bg-gray-700 shadow flex items-center justify-between p-4 border rounded shadow" style="margin:0.5em;">
                        <p class="text-lg text-gray-800 dark:text-gray-200">{{ $member->user->name }}</p>
			@if($member->user_id != Auth::id())
                        <form action="{{ route('team.chat', [$member->user_id]) }}" method="GET">
                            @csrf
                            <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                {{ __('Message Member') }}
                            </x-primary-button>
                        </form>
			@endif
			@team_manager($team_id)
                        <form action="{{ route('team.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" id="team" name="team_id" value="{{$team_id}}">
                            <input type="hidden" id="user_id" name="user_id" value="{{$member->user_id}}">
                            <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                {{ __('Remove Member') }}
                            </x-primary-button>
                        </form>
			@endteam_manager
		    </div>
		@endforeach
	</div>

	@team_manager($team_id)
	<h5 class="bg-white dark:bg-gray-700 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">Candidates</h5>            
	<div class="space-y-4">
                    @foreach($candidates as $candidate)
                        <div class="bg-white dark:bg-gray-700 shadow flex items-center justify-between p-4 border rounded shadow" style="margin:0.5em;">
                            <p class="text-lg text-gray-800 dark:text-gray-200">{{ $candidate->user->name }}</p>
                            <form action="{{ route('team.accept')}}" method="POST">
                                @csrf
                                <input type="hidden" id="team" name="team_id" value="{{$team_id}}">
                                <input type="hidden" id="user_id" name="user_id" value="{{$candidate->user_id}}">
                                <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                    {{ __('Accept Join Request') }}
                                </x-primary-button>
                            </form>
                            <form action="{{ route('team.decline')}}" method="POST">
                                @csrf
                                <input type="hidden" id="team" name="team_id" value="{{$team_id}}">
                                <input type="hidden" id="user_id" name="user_id" value="{{$candidate->user_id}}">
                                <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                    {{ __('Decline Join Request') }}
                                </x-primary-button>
                            </form>
                            <form action="{{ route('team.chat', [$candidate->user_id]) }}" method="GET">
                                @csrf
                                <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                    {{ __('Message Member') }}
                                </x-primary-button>
                            </form>
                        </div>
                  @endforeach
            </div>
	@endteam_manager
        </div>
	@endteam_member

	@team_manager($team_id)
        <!-- Delete Team Button (only visible to the team creator) -->
            <div class="mt-6">
                <form action="{{ route('team.delete', [$team_id]) }}" method="POST">
                    @csrf
                    <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                        {{ __('Delete Team') }}
                    </x-primary-button>
                </form>
            </div>

        @endif
    </div>
    @endteam_manager
</x-app-layout>
