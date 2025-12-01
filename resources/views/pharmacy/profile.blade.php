@extends('layouts.pharmacy')

@section('title', 'Mi Perfil - Te Acerco Salud')

@section('pharmacy-content')
    <div class="max-w-4xl mx-auto space-y-6 p-6 sm:p-8">
        {{-- Page Header --}}
        <div>
            <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
                Mi Perfil
            </h1>
            <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">
                Información de tu cuenta y datos personales
            </p>
        </div>

        {{-- Profile Card --}}
        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <div class="flex items-center gap-6 mb-6">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-20"
                    style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($user->nombre ?? 'User') }}&background=137fec&color=fff&size=128");'>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-body-text dark:text-body-text-dark">
                        {{ $user->nombre }} {{ $user->apellido }}
                    </h2>
                    <p class="text-neutral-text dark:text-neutral-text-dark">
                        {{ $user->correo }}
                    </p>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-primary/10 text-primary mt-2">
                        Empleado de Farmacia
                    </span>
                </div>
            </div>

            <div class="border-t border-border-light dark:border-border-dark pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Personal Information --}}
                <div>
                    <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                        Información Personal
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Nombre</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">{{ $user->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Apellido</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">{{ $user->apellido }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Correo Electrónico</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">{{ $user->correo }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Rol</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark capitalize">{{ $user->role }}</p>
                        </div>
                    </div>
                </div>

                {{-- Branch Information --}}
                <div>
                    <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                        Información de Sucursal
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Sucursal</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">
                                {{ $empleado->sucursal->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Cadena</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">
                                {{ $empleado->sucursal->cadena->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Dirección</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">
                                {{ $empleado->sucursal->calle ?? '' }} {{ $empleado->sucursal->numero_ext ?? '' }}
                                @if ($empleado->sucursal->numero_int ?? null)
                                    , Int. {{ $empleado->sucursal->numero_int }}
                                @endif
                                <br>
                                {{ $empleado->sucursal->colonia ?? '' }}, {{ $empleado->sucursal->ciudad ?? '' }}
                            </p>
                        </div>
                        @if ($empleado->sucursal->contacto ?? null)
                            <div>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Teléfono de Sucursal</p>
                                <p class="font-medium text-body-text dark:text-body-text-dark">
                                    {{ $empleado->sucursal->contacto }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Account Actions --}}
        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                Acciones de Cuenta
            </h3>
            <div class="flex flex-col sm:flex-row gap-3">
                <button class="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary/90 transition">
                    Cambiar Contraseña
                </button>
                <button
                    class="px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark font-medium hover:bg-background-light dark:hover:bg-background-dark transition">
                    Editar Perfil
                </button>
            </div>
        </div>
    </div>
@endsection
