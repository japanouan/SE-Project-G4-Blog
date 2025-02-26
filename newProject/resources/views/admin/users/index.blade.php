<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>


    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Users') }}
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded" id="acceptanceButton">
                        Acceptance
                    </button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- {{ __("You're logged in!") }} -->
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index_order') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="user_id">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            ID
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index_order') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="name">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            Fullname
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index_order') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="email">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            Email
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index_order') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="phone">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            Phone
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index_order') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="username">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            Username
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index_order') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="userType">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            Role
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index_order') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="status">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        <button type="submit" class="btn btn-info">
                                            Status
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td style="padding: 10px;">{{ $user->user_id }}</td>
                                <td style="padding: 10px;">{{ $user->name }}</td>
                                <td style="padding: 10px;">{{ $user->email }}</td>
                                <td style="padding: 10px;">{{ $user->phone }}</td>
                                <td style="padding: 10px;">{{ $user->username }}</td>
                                <td style="padding: 10px;">{{ $user->userType }}</td>
                                <td style="padding: 10px;">{{ $user->status }}</td>
                                <td styles="padding: 10px;">
                                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('admin.users.toggleStatus', $user->user_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $user->status == 'active' ? 'inactive' : 'active' }}">
                                        <button type="submit" class="btn btn-info">
                                            {{ $user->status == 'active' ? 'Deactivate' : 'Activate' }}
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('acceptanceButton').addEventListener('click', function() {
                window.location.href = "{{ route('admin.users.acceptance') }}";
            });
        });
    </script>
</body>

</html>