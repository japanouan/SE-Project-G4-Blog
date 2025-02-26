<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>


    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Shops') }}
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form action="{{ route('admin.shops.acceptance') }}" method="GET" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-info">
                            {{ "Acceptance" }}
                        </button>
                    </form>
                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="shop_id">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            shop id
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="shop_name">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            shop name
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="shop_description">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            shop_description
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="shop_location">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            shop_location
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="rental_terms">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            rental_terms
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="status">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">

                                        <button type="submit" class="btn btn-info">
                                            status
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="created_at">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">

                                        <button type="submit" class="btn btn-info">
                                            created at
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.shops.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="shop_owner_id ">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">

                                        <button type="submit" class="btn btn-info">
                                            shop_owner_id
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shops as $shop)
                            <tr>
                                <td style="padding: 10px;">{{ $shop->shop_id }}</td>
                                <td style="padding: 10px;">{{ $shop->shop_name }}</td>
                                <td style="padding: 10px;">{{ $shop->shop_description }}</td>
                                <td style="padding: 10px;">{{ $shop->shop_location }}</td>
                                <td style="padding: 10px;">{{ $shop->rental_terms }}</td>
                                <td style="padding: 10px;">{{ $shop->status }}</td>
                                <td style="padding: 10px;">{{ $shop->created_at }}</td>
                                <td style="padding: 10px;">{{ $shop->shop_owner_id }}</td>
                                <td styles="padding: 10px;">
                                    <form action="{{ route('admin.shops.edit', $shop->shop_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-info">
                                            {{ "Edit" }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.shops.toggleStatus', $shop->shop_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $shop->status == 'active' ? 'inactive' : 'active' }}">
                                        <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                                        <input type="hidden" name="direction" value="{{ request('direction') }}">

                                        <button type="submit" class="btn btn-info">
                                            {{ $shop->status == 'active' ? 'Deactivate' : 'Activate' }}
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
</body>

</html>