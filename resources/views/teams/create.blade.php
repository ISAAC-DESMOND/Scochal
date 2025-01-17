<x-app-layout>
    <div id='center' class="bg-white dark:bg-gray-800 shadow font-semibold text-xl text-gray-800 dark:text-gray-200 mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded">
    <form action="{{ route('team.create_team') }}" method="POST">
        @csrf
        <div>
            <x-input-label for="name" :value="__('Team')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="description" :value="__('Sport')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required autofocus autocomplete="description" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        <x-primary-button class="block mt-1 w-full justify-center" style="margin-top: 3%">
            {{ __('Create Team') }}
        </x-primary-button>
    </form>
</div>
</x-app-layout>
