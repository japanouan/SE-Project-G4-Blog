<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
                <form action="{{ route('admin.users.index') }}" method="GET" style="margin-bottom: 1rem;">
                    {{-- คงค่า orderBy และ direction ถ้ามีอยู่ --}}
                    <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                    <input type="hidden" name="direction" value="{{ request('direction') }}">

                    <label>Filter by Role:</label><br>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="userType[]" value="admin" id="filterAdmin"
                            {{ is_array(request('userType')) && in_array('admin', request('userType')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="filterAdmin">Admin</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="userType[]" value="customer" id="filterCustomer"
                            {{ is_array(request('userType')) && in_array('customer', request('userType')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="filterCustomer">Customer</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="userType[]" value="shop owner" id="filterShopOwner"
                            {{ is_array(request('userType')) && in_array('shop owner', request('userType')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="filterShopOwner">Shop Owner</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="userType[]" value="photographer" id="filterPhotographer"
                            {{ is_array(request('userType')) && in_array('photographer', request('userType')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="filterPhotographer">Photographer</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="userType[]" value="make-up artist" id="filterMake-upArtist"
                            {{ is_array(request('userType')) && in_array('make-up artist', request('userType')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="filterMake-upArtist">Make-up Artist</label>
                    </div>

                    <button type="submit" class="bg-blue-500 py-1 px-3 rounded mt-2">Apply Filter</button>
                </form>

                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="user_id">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                               <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
                                        <button type="submit" class="btn btn-info">
                                            ID
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="name">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                               <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
                                        <button type="submit" class="btn btn-info">
                                            Fullname
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="email">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                                <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
                                        <button type="submit" class="btn btn-info">
                                            Email
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="phone">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                                <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
                                        <button type="submit" class="btn btn-info">
                                            Phone
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="username">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                                <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
                                        <button type="submit" class="btn btn-info">
                                            Username
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="userType">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                                <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
                                        <button type="submit" class="btn btn-info">
                                            Role
                                        </button>
                                    </form>
                                </th>
                                <th style="padding: 10px;">
                                    <form action="{{ route('admin.users.index') }}" method="GET" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="orderBy" value="status">
                                        <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                                <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
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
                                        <!-- ส่งค่า filter ที่เลือก -->
                                        <input type="hidden" name="orderBy" value="{{ request('orderBy') }}">
                                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                                        @if(request()->has('userType'))
                                            @foreach(request('userType') as $type)
                                                <input type="hidden" name="userType[]" value="{{ $type }}">
                                            @endforeach
                                        @endif
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