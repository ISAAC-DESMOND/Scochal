<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>

<div class="container mx-auto p-6">
    <!-- Post Form -->
    <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 border rounded p-3 mt-1" style="display:flex;flex-direction:row;">
        @csrf
        <textarea name="content" rows="2" class=" bg-white dark:bg-gray-700 border rounded p-3 mt-1 w-full"  style="width:86em;margin:1%;" placeholder="What's on your mind?..."></textarea>
        <input type="file" name="image" class="mt-2" id="img_file">
        <label for="img_file" class="file-label">
            <span style="font-size: 5em;">&#128206;</span>
          </label>
        <x-primary-button class="mt-1" id="submit_btn">
            {{ __('Post') }}
        </x-primary-button>
    </form>

    <!-- Feed -->
    <div id="feed" style="display:flex;flex-direction:row;margin:0.5em;flex-wrap:wrap;">
        @if($posts!=[])
        @foreach($posts as $post)
            <div class="bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded-md font-semibold w-1/3" style="margin:0.5em;">
                <p class="font-semibold text-m text-gray-800 dark:text-gray-200">{{$post->user->name}}</p>
                <hr>
                <p class="font-semibold text-l text-gray-800 dark:text-gray-200">{{$post->content}}</p>
                @if($post->image_url)
                    <img src="{{ asset('storage/'.$post->image_url)}}" alt="Post Image" class="mt-2 w-full">
                @endif
                <div class="flex items-center mt-4">
                    <button class="text-green-500 mr-4">
                        &#128077;({{$post->like_count}})
                    </button>
                    <button class="text-red-500">
                        &#128078; ({{$post->dislike_count}})
                    </button>
                </div>
            </div>
        @endforeach
        @endif
    </div>
</div>
</x-app-layout>
