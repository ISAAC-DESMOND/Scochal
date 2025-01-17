<x-app-layout>
    
    @php
        $team_id = App\Models\member_joins::where('user_id',Auth::id())->where('status', 'member')->pluck('team_id')->first();
        $team_name =App\Models\team::where('id', $team_id)->pluck('name')->first();
    @endphp
@team_manager($team_id)
    <div class="container mx-auto my-6">
        <h3 class="bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Manage Team: {{ $team_name}}</h3>
        <div>
            <h5 class="bg-white dark:bg-gray-700 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">Members</h5>
            <div class="space-y-4">
            @if($members!=[])
                @foreach($members as $member)
                    @if (in_array($member['user_id'], $members_ids))
                    <div class="bg-white dark:bg-gray-700 shadow flex items-center justify-between p-4 border rounded shadow" style="margin:0.5em;">
                        <p class="text-lg text-gray-800 dark:text-gray-200">{{ $member['user_name'] }}</p>
                        <form action="{{ route('team.chat', [$member['user_id']]) }}" method="GET">
                            @csrf
                            <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                {{ __('Message Member') }}
                            </x-primary-button>
                        </form>
                        <form action="{{ route('team.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" id="team" name="team_id" value="{{$member['team_id']}}">
                            <input type="hidden" id="user_id" name="user_id" value="{{$member['user_id']}}">
                            <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                {{ __('Remove Member') }}
                            </x-primary-button>
                        </form>
                    </div>
                    @else
                        <div class="bg-white dark:bg-gray-700 shadow flex items-center justify-between p-4 border rounded shadow" style="margin:0.5em;">
                            <p class="text-lg text-gray-800 dark:text-gray-200">{{ $member['user_name'] }}</p>
                            <form action="{{ route('team.accept')}}" method="POST">
                                @csrf
                                <input type="hidden" id="team" name="team_id" value="{{$member['team_id']}}">
                                <input type="hidden" id="user_id" name="user_id" value="{{$member['user_id']}}">
                                <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                    {{ __('Accept Join Request') }}
                                </x-primary-button>
                            </form>
                            <form action="{{ route('team.decline')}}" method="POST">
                                @csrf
                                <input type="hidden" id="team" name="team_id" value="{{$member['team_id']}}">
                                <input type="hidden" id="user_id" name="user_id" value="{{$member['user_id']}}">
                                <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                    {{ __('Decline Join Request') }}
                                </x-primary-button>
                            </form>
                            <form action="{{ route('team.chat', [$member['user_id']]) }}" method="GET">
                                @csrf
                                <x-primary-button class="mt-1 justify-center px-8 w-full" id="submit_btn">
                                    {{ __('Message Member') }}
                                </x-primary-button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

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