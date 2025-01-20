<x-app-layout>
    <div class="">
        <div class="">
            <form action="{{route('rooms.update', $room)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="">
                    <label class="text-white" for="name">Название чата</label>
                </div>
                <div class="my-2">
                    <input type="text" id="name" name="name" value="{{$room->name}}">
                </div>
                <button class="bg-cyan-500 p-4 rounded" type="submit">Редактировать</button>
            </form>
        </div>
        <div class="my-2">
            <form action="{{route('rooms.destroy', $room)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="focus:outline-none text-white bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                    Удалить
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
