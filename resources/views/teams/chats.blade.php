<x-app-layout>
    <div>
        <div>

            <div class="col-md-8">
                <h4 class="bg-white dark:bg-gray-800 shadow text-gray-500 dark:text-gray-400 mx-auto py-6 px-4 sm:px-6 lg:px-8 w-full">{{$_SESSION['team']}}</h4>
                <div id="messages" class="bg-white dark:bg-gray-800 border rounded p-3 block mt-1" style="max-height: 32em; height:auto; overflow-y: scroll;margin:1%;">
                    
                </div>

            <form  method="POST" action="{{ route('team.store') }}">
                @csrf
                <input type="hidden" id="team_id" name="team_id" value="{{ $team_id ?? '' }}">
                <x-text-input id="message" class=" bg-white dark:bg-gray-800 border rounded p-3 mt-1" style="width:86em;margin:1%;" type="text" name="message"/>
                    <x-primary-button class="ms-4" id="submit_btn">
                        {{ __('Send') }}
                    </x-primary-button>
            </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadchats);

        var scrol=false;

        function loadchats() {
            const allmessages = document.getElementById('messages');
            const submitButton=document.getElementById('submit_btn');

                fetch(`/team/show_all/{{$team_id}}`)
                    .then(response => response.json())
                    .then(messages => {
                        allmessages.innerHTML = ''; 
                        if(!messages||messages.length==0){
                            return;
                        }
                        messages.forEach(message => {
                            const message_box = document.createElement('div');
                            message_box.style.marginBottom = '10px';

                            if (message.user_id === {{Auth::id()}}) {

                                message_box.classList.add('message', 'sender','border','rounded');
                                    message_box.innerHTML = `<strong>You:</strong></br>${message.message}`;
                            } else {

                                message_box.classList.add('message', 'recipient','border','rounded');
                                message_box.innerHTML = `<strong>${message.user_name}:</strong></br> ${message.message}`;
                            }

                            allmessages.appendChild(message_box);
                        });

                        if(scrol==false){
                            allmessages.scrollTop = allmessages.scrollHeight;
                        }

                        submitButton.addEventListener('click',downed);
                    allmessages.addEventListener('scroll', allow);
                });
            setTimeout(loadchats,1000);


        }

        function allow(){
            scrol=true;
        }
        function downed(){
            scrol=false;
        }

    </script>
</x-app-layout>
