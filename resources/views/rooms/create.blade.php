<x-app-layout>
    <div class="m-2 p-4">
        <form action="{{route('rooms.store')}}" method="POST">
            @csrf
            <div class="">
                <label class="text-white" for="name">Название чата</label>
            </div>
            <div class="">
                <input type="text" id="name" name="name">
            </div>
            <div class="my-4 text-white">
                Добавить пользователей(пользователей также можно будет добавить позже)
                @foreach($users as $user)
                    <div class="my-2">
                        <input type="checkbox" id="user_{{$user->id}}" name="users[]" value="{{$user->id}}">
                        <label for="user_{{$user->id}}">{{$user->login}}</label>
                    </div>
                @endforeach
            </div>
            <button class="bg-cyan-500 p-4 rounded" type="submit">Создать</button>
        </form>
    </div>
</x-app-layout>
