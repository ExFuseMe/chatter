<x-app-layout>
    <div class="text-white w-full">
        @foreach($userRooms as $room)
            <div class="my-4 grid grid-cols-4 gap-2 w-1/2">
                <div class="">
                    {{$room->name}}
                </div>
                @php
                    $key = 'room.' . $room->id . '.users';
                @endphp
                <div class="mx-4 flex">Пользователей в сети:
                    <div class="" id="{{$key}}">{{Cache::has($key) ? Cache::get($key)->count() : 0}}</div>
                </div>
                <div class="ml-2 text-green-500">
                    <a href="{{route('rooms.enter', $room)}}">Войти</a>
                </div>
                <div class="text-yellow-500">
                    <a href="{{route('rooms.show', $room)}}">Редактировать</a>
                </div>
            </div>
        @endforeach
        <div class="">
            {{$userRooms->links()}}
        </div>
    </div>
    @vite('resources/js/app.js')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.private("rooms")
                    .listen('.user_joined_room', (e) => {
                        usersCount = document.getElementById('room.' + e.room + '.users');
                        usersCount.textContent = e.usersCount;
                    });
                window.Echo.private("rooms")
                    .listen('.user_left_room', (e) => {
                        usersCount = document.getElementById('room.' + e.room + '.users');
                        usersCount.textContent = e.usersCount;
                    });
            } else {
                console.error('Echo is not initialized');
            }
        });
    </script>
</x-app-layout>
