<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>

    <body class="bg-gray-100">

        <div class="container mx-auto p-6 ">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 justify-self-center">Acceptance</h1>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden" id="usersTable">
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
                                    <tr id="shopRow-{{ $s->user_id }}" class="border-b">
                                        <td style="padding: 10px;">{{ $s->shop_id }}</td>
                                        <td style="padding: 10px;">{{ $s->shop_name }}</td>
                                        <td style="padding: 10px;">{{ $s->shop_description }}</td>
                                        <td style="padding: 10px;">{{ $s->shop_location }}</td>
                                        <td style="padding: 10px;">{{ $s->shop_owner_id }}</td>
                                        <td style="padding: 10px;" id="status-{{ $s->user_id }}">{{ $s->status }}</td>
                                        <td class="p-2">
                                            <form action="{{ route('admin.shops.updateStatus', $s->shop_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="{{'active'}}">
                                                <button type="submit" class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    {{ 'Accept' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.shops.updateStatus', $s->shop_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="{{'inactive'}}">
                                                <button type="submit" class="focus:outline-none text-white bg-red-500  hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 declineButton">
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
        </div>
    </body>

</html>