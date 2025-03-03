<x-app-layout>
    <div>
        <div>

            <!-- Messages Section -->
            <div class="col-md-8">
                <h4 class="bg-white dark:bg-gray-800 shadow font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mx-auto py-6 px-4 sm:px-6 lg:px-8 w-full">{{$_SESSION['recipient']}}</h4>
                <div id="messages" class="bg-white dark:bg-gray-800 border rounded p-3 block mt-1" style="max-height: 32em; height:auto; overflow-y: scroll;margin:1%;">
                    
                </div>

            <!-- Message Sending Form -->

            <form  method="POST" action="{{ route('inbox.store') }}" class="w-full flex" style="padding:1%;">
                @csrf
                <input type="hidden" id="recipient_id" name="recipient_id" value="{{ $recipient_id ?? '' }}">
                <x-text-input id="message" class=" bg-white dark:bg-gray-800 border rounded p-3 mt-1 flex-grow" style="right-margin:1%;" type="text" name="message"/>
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
            const recipient_id = document.getElementById('recipient_id').value;
            const allmessages = document.getElementById('messages');
            const submitButton=document.getElementById('submit_btn');

            // Fetch messages via AJAX
                fetch(`/inbox/show_all/${recipient_id}`)
                    .then(response => response.json())
                    .then(messages => {
                        allmessages.innerHTML = ''; // Clear previous messages

                        if(!messages||messages.length==0){
                            return;
                        }

                        messages.forEach(message => {
                            const message_box = document.createElement('div');
                            message_box.style.marginBottom = '10px';

                            if (message.sender_id === {{Auth::id()}}) {

                                message_box.classList.add('message', 'sender','border','rounded');
                                    message_box.innerHTML = `<strong>You:</strong></br>${message.message}`;
                            } else {

                                message_box.classList.add('message', 'recipient','border','rounded');
                                message_box.innerHTML = `<strong>{{$_SESSION['recipient']}}:</strong></br> ${message.message}`;
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
