<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Acceptance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="usersTable">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th style="padding: 10px;">shop id</th>
                                <th style="padding: 10px;">shop name</th>
                                <th style="padding: 10px;">shop_description</th>
                                <th style="padding: 10px;">shop_location</th>
                                <th style="padding: 10px;">shop_owner_id</th>
                                <th style="padding: 10px;">status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shop as $s)
                            <tr id="shopRow-{{ $s->user_id }}">
                                <td style="padding: 10px;">{{ $s->shop_id }}</td>
                                <td style="padding: 10px;">{{ $s->shop_name }}</td>
                                <td style="padding: 10px;">{{ $s->shop_description }}</td>
                                <td style="padding: 10px;">{{ $s->shop_location }}</td>
                                <td style="padding: 10px;">{{ $s->shop_owner_id }}</td>
                                <td style="padding: 10px;" id="status-{{ $s->user_id }}">{{ $s->status }}</td>
                                <td style="padding: 10px;">
                                    <form action="{{ route('admin.shops.updateStatus', $s->shop_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="{{'active'}}">
                                        <button type="submit" class="btn btn-info">
                                            {{ 'Accept' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.shops.updateStatus', $s->shop_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="{{'inactive'}}">
                                        <button type="submit" class="btn btn-info">
                                            {{ 'Decline' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>