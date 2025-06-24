@extends('components.layouts.app')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">{{ isset($user) ? 'Edit User' : 'Create User' }}</h1>
            </div>
        </div>
        <div class="mt-8">
            <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div>
                    <x-label for="name" value="Name" />
                    <x-input id="name" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus class="mt-1 block w-full" />
                    <x-error field="name" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-label for="email" value="Email" />
                    <x-input id="email" type="email" name="email" :value="old('email', $user->email ?? '')" required class="mt-1 block w-full" />
                    <x-error field="email" class="mt-2" />
                </div>

                @if(!isset($user))
                <div class="mt-4">
                    <x-label for="password" value="Password" />
                    <x-input id="password" type="password" name="password" required class="mt-1 block w-full" />
                    <x-error field="password" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="Confirm Password" />
                    <x-input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 block w-full" />
                </div>
                @endif

                <div class="mt-4">
                    <x-label for="role" value="Role" />
                    <x-select id="role" name="role" :value="old('role', $user->role ?? '')" required class="mt-1 block w-full">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </x-select>
                    <x-error field="role" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button type="button" onclick="window.history.back()" class="mr-4">
                        Cancel
                    </x-button>
                    <x-button>
                        {{ isset($user) ? 'Update' : 'Create' }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection 