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
                                <tr id="userRow-{{ $user->user_id }}">
                                    <td style="padding: 10px;">{{ $user->user_id }}</td>
                                    <td style="padding: 10px;">{{ $user->name }}</td>
                                    <td style="padding: 10px;">{{ $user->email }}</td>
                                    <td style="padding: 10px;">{{ $user->phone }}</td>
                                    <td style="padding: 10px;">{{ $user->username }}</td>
                                    <td style="padding: 10px;">{{ $user->userType }}</td>
                                    <td style="padding: 10px;" id="status-{{ $user->user_id }}">{{ $user->status }}</td>
                                    <td style="padding: 10px;">
                                        <button class="btn btn-success acceptButton" data-user-id="{{ $user->user_id }}">Accept</button>
                                        <button class="btn btn-danger declineButton" data-user-id="{{ $user->user_id }}">Decline</button>
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
            document.querySelectorAll('.acceptButton').forEach(button => {
                button.addEventListener('click', function() {
                    updateUserStatus(this.dataset.userId, 'active');
                });
            });

            document.querySelectorAll('.declineButton').forEach(button => {
                button.addEventListener('click', function() {
                    updateUserStatus(this.dataset.userId, 'inactive');
                });
            });

            function updateUserStatus(userId, status) {
                fetch(`/admin/users/${userId}/${status}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`status-${userId}`).textContent = status;
                        if(status === 'active'){
                            document.getElementById(`userRow-${userId}`).remove();
                        }
                    } else {
                        alert('Failed to update status.');
                    }
                });
            }
        });
    </script>
</x-app-layout>