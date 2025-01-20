<x-app-layout>
    <div class="fixed top-16 left-0 right-0 z-50 bg-gray-800">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between text-white">
                <div class="m-auto">
                    <form action="{{route('rooms.leave', $room)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit">Назад</button>
                    </form>
                </div>
                <div class="grid grid-cols-1 text-center">
                    <div class="">
                        {{$room->users()->count()}} Пользователей
                    </div>
                    <div id="typing">
                        <!-- Информация о наборе текста -->
                    </div>
                </div>
                <div class="m-auto">
                    <a href="{{route('rooms.show', $room)}}">О комнате</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-white flex w-full flex-col items-center m-4" id="messages-container">
        @php
            $authUser = auth()->id();
        @endphp
        @foreach($room->messages as $message)
            <div class="{{ $message->sender->id == $authUser ? 'text-end' : 'text-start' }} w-1/2 my-2">
                <div class="text-sm text-cyan-400 mt-1">
                    {{$message->sender->login}}
                </div>
                <div
                    class="{{ $message->sender->id == $authUser ? 'bg-blue-500' : 'bg-gray-700' }} text-white p-3 rounded-lg">
                    {{$message->text}}
                </div>
            </div>
        @endforeach
    </div>
    <div class="fixed bottom-0 w-full flex justify-center p-4">
        <div class="flex w-1/2 items-center space-x-2">
            <input
                type="text"
                id="message-input"
                class="flex-1 p-2 border border-gray-300 rounded"
                placeholder="Введите сообщение..."
                oninput="handleTyping()"
            >
            <button
                id="send-button"
                class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none"
            >
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6">
                    <path
                        d="M9.91158 12H7.45579H4L2.02268 4.13539C2.0111 4.0893 2.00193 4.04246 2.00046 3.99497C1.97811 3.27397 2.77209 2.77366 3.46029 3.10388L22 12L3.46029 20.8961C2.77983 21.2226 1.99597 20.7372 2.00002 20.0293C2.00038 19.9658 2.01455 19.9032 2.03296 19.8425L3.5 15"
                        stroke="#000000"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
        let typingTimer;
        const typingDelay = 500;

        function handleTyping() {
            clearTimeout(typingTimer);

            const csrfToken =
                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                document.querySelector('input[name="_csrf"]')?.value ||
                document.cookie.replace(/(?:(?:^|.*;\s*)XSRF-TOKEN\s*\=\s*([^;]*).*$)|^.*$/, "$1");

            typingTimer = setTimeout(() => {
                const messageInput = document.getElementById('message-input');

                if (messageInput.value.trim() !== '') {
                    fetch('/rooms/{{$room->id}}/typing', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        }
                    })
                        .catch(error => {
                            console.error('Ошибка при отправке статуса набора текста:', error);
                        });
                }
            }, typingDelay);
        }
    </script>
    <script>
        document.getElementById('send-button').addEventListener('click', async function () {
            const input = document.getElementById('message-input');
            const message = input.value.trim();

            if (message) {
                try {
                    await fetch('{{ route("messages.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({text: message, room_id: {{ $room->id }}})
                    });

                    input.value = '';
                } catch (error) {
                    console.error('Ошибка:', error);
                    alert('Ошибка при отправке сообщения.');
                }
            }
        });
    </script>
    @vite('resources/js/app.js')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.join("rooms.{{ $room->id }}")
                    .listen('.new_message', (e) => {
                        const messagesContainer = document.getElementById('messages-container');
                        const isAuthUser = e.message.sender_id === {{ auth()->id() }};
                        const messageHTML = `
            <div class="${isAuthUser ? 'text-end' : 'text-start'} w-1/2 my-2">
                <div class="${isAuthUser ? 'bg-blue-500' : 'bg-gray-700'} text-white p-3 rounded-lg">
                    ${e.message.text}
                </div>
                <div class="text-sm text-cyan-400 mt-1">
                    ${e.sender.login}
                </div>
            </div>
        `;
                        document.getElementById('typing').textContent = '';
                        messagesContainer.innerHTML += messageHTML;
                    });
                window.Echo.private("rooms.{{$room->id}}")
                    .listen('.typing', (e) => {
                        typingInfo = document.getElementById('typing');
                        if (e.user.id !== {{$authUser}}){
                            typingInfo.textContent = e.user.login + ' печатает';
                        }
                    });
            } else {
                console.error('Echo is not initialized');
            }
        });
    </script>
</x-app-layout>
