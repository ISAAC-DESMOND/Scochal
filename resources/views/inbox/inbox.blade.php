<x-app-layout>
<div id="messenger" class="container">
    <div class="row">
        <!-- Users List -->
        <div class="col-md-4">
            <h4 class="bg-white dark:bg-gray-800 shadow font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mx-auto py-6 px-4 sm:px-6 lg:px-8 w-full">Chats</h4>
            <ul id="user_list" class="block mt-1 w-full">
                @if($users!=[])
                @foreach ($users as $user)
                    <li class="block mt-1 w-full bg-white dark:bg-gray-800 shadow text-gray-500 dark:text-gray-400  mx-auto py-6 px-4 sm:px-6 lg:px-8 " data-id="{{ $user->id }}">
                        {{ $user->name }} : {{$last_msgs[$user->id]}}
                    </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded',onload);
    function onload() {

        const user_div = document.getElementById('user_list');
        // Handle user item click
        fetch(`/inbox/all`)
            .then(response => response.json())
            .then(users => {
                        user_div.innerHTML = ''; // Clear previous messages
                        
                        if(!users||users.length==0){
                            return;
                        }
                        else{
                        users.forEach(user => {
                            const user_name=document.createElement("li");
                            user_name.style.margin='1%';
                            user_name.innerHTML=`<li class="block w-full bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded-md font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:scale-120 transition-transform duration-300 ease-in-out" user-id="{{$user->id}}">
                            {{$user->name }} : {{$last_msgs[$user->id]}}
                            </li>`;
                            user_name.addEventListener('click', function () {
                                var recipient_id = {{$user->id}};
                                window.location.href = `/inbox/create/${recipient_id}`;
                            });
                            user_div.appendChild(user_name);
                        });
                    }
            });

        setTimeout(onload,5000);
    }
</script>
@endsection
</x-app-layout>
