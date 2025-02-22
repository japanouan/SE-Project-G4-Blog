<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- {{ __("You're logged in!") }} -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="padding: 10px;">ID</th>
                                <th style="padding: 10px;">Fullname</th>
                                <th style="padding: 10px;">Email</th>
                                <th style="padding: 10px;">Phone</th>
                                <th style="padding: 10px;">Username</th>
                                <th style="padding: 10px;">Role</th>
                                <th style="padding: 10px;">Status</th>
                                <th style="padding: 10px;">Actions</th>
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
                                            <button type="submit"  class="btn btn-info">
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
</x-app-layout>
