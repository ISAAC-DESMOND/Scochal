<x-app-layout>
<x-slot name="header">
    <div class="flex flex-row items-center justify-between space-x-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chats') }}
     	</h2>
</x-slot>

<div id="messenger" class="container">
    <div class="row">
        <!-- Users List -->
        <div class="col-md-4">
            <ul id="user_list" class="block mt-1 w-full">
                @if($users!=[])
                @foreach ($users as $user)
                    <li class="block mt-1 w-full bg-white dark:bg-gray-800 shadow text-gray-500 dark:text-gray-400  mx-auto py-6 px-4 " data-id="{{ $user->id }}">
                       <h2> {{ $user->name }}</h2>
			<h4> {{$last_msgs[$user->id]}}</h4>
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
            .then(data => {
                        user_div.innerHTML = ''; // Clear previous messages
                        
                        if(!data.users||data.users.length==0){
                            return;
                        }
                        else{
                       data.users.forEach(user => {
                            const user_name=document.createElement("li");
                            user_name.style.margin='1%';
                            user_name.innerHTML=`<li class="block w-full bg-white dark:bg-gray-800 shadow mx-auto py-6 px-4 sm:px-6 lg:px-8 rounded-md font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:scale-120 transition-transform duration-300 ease-in-out" user-id=${user.id}>
                           <h2> ${user.name}</h2>
			   <h4> ${data.last_msgs[user.id] || "click here to message this user"}<h4>
                            </li>`;
                            user_name.addEventListener('click', function () {
                                var recipient_id = user.id;
                                window.location.href = `/inbox/create/${recipient_id}`;
                            });
                            user_div.appendChild(user_name);
                        });
                    }
            });

        setTimeout(onload,1000);
    }
</script>
@endsection
</x-app-layout>
