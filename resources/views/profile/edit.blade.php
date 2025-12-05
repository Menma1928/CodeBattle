@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <x-page-title>Mi Perfil</x-page-title>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Administra tu información personal y configuración de cuenta</p>
        </div>

        <div class="space-y-6">
            <!-- Profile Information -->
            <x-card>
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </x-card>

            <!-- Update Password -->
            <x-card>
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </x-card>

            <!-- Delete Account -->
            <x-card class="border-red-200 dark:border-red-800">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
