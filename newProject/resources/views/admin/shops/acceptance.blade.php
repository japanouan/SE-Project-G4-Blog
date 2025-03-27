@extends('layouts.admin-layout')

@section('title', 'Shop Acceptance')

@section('content')

<div class="container mx-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Acceptance</h1>
                <a href="{{ route('admin.shops.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Shops
                </a>
            </div>
        
            <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden w-full table-auto" id="usersTable">
                            <thead class="bg-gray-200">
                                    <tr>
                                        <th style="padding: 10px;">shop id</th>
                                        <th style="padding: 10px;">shop name</th>
                                        <th style="padding: 10px;">shop_description</th>
                                        <th style="padding: 10px;">shop_location</th>
                                        <th style="padding: 10px;">shop_owner_id</th>
                                        <th style="padding: 10px;">status</th>
                                        <th style="padding: 10px;">actions</th>
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
                                                <button type="submit" class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 acceptButton">
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
    @endsection