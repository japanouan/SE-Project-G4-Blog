@extends('layouts.admin-layout')

@section('title', 'User Acceptance')

@section('content')

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 justify-self-center">Acceptance</h1>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden" id="usersTable">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th style="padding: 10px;">ID</th>
                                    <th style="padding: 10px;">Fullname</th>
                                    <th style="padding: 10px;">Email</th>
                                    <th style="padding: 10px;">Phone</th>
                                    <th style="padding: 10px;">Username</th>
                                    <th style="padding: 10px;">Role</th>
                                    <th style="padding: 10px;">Status</th>
                                    <th style="padding: 10px;">Identify</th>
                                    <th style="padding: 10px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr id="userRow-{{ $user->user_id }}" class="border-b">
                                    <td style="padding: 10px;">{{ $user->user_id }}</td>
                                    <td style="padding: 10px;">{{ $user->name }}</td>
                                    <td style="padding: 10px;">{{ $user->email }}</td>
                                    <td style="padding: 10px;">{{ $user->phone }}</td>
                                    <td style="padding: 10px;">{{ $user->username }}</td>
                                    <td style="padding: 10px;">{{ $user->userType }}</td>
                                    <td style="padding: 10px;" id="status-{{ $user->user_id }}">{{ $user->status }}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        <a href="{{ asset($user->identity_path) }}" target="_blank" 
                                        class="text-blue-500 underline">
                                            View Document
                                        </a>
                                    </td>
                                    <td class="flex justify-center p-2 ">
                                        <button class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 acceptButton" data-user-id="{{ $user->user_id }}">Accept</button>
                                        <button class="focus:outline-none text-white bg-red-500  hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 declineButton" data-user-id="{{ $user->user_id }}">Decline</button>
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
                            if (status === 'active') {
                                document.getElementById(`userRow-${userId}`).remove();
                            }
                        } else {
                            alert('Failed to update status.');
                        }
                    });
            }
        });
    </script>
@endsection