<div class="max-w-5xl mx-auto">
  <!-- Header -->
  <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
    <div class="flex min-w-72 flex-col gap-2">
      <p class="text-gray-900 dark:text-white text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em]">
        {{ __('patient.profile.title') }}
      </p>
      <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">
        {{ __('patient.profile.subtitle') }}
      </p>
    </div>
  </div>

  <!-- Success/Error Messages -->
  @if (session('success'))
    <div
      class="alert-message mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
      <p class="text-green-800 dark:text-green-200 text-sm font-medium">{{ session('success') }}</p>
    </div>
  @endif

  @if (session('error'))
    <div class="alert-message mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
      <p class="text-red-800 dark:text-red-200 text-sm font-medium">{{ session('error') }}</p>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert-message mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
      <p class="text-red-800 dark:text-red-200 text-sm font-bold mb-2">
        {{ __('patient.profile.messages.validation_error') }}
      </p>
      <ul class="list-disc list-inside text-red-700 dark:text-red-300 text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Personal Information Card -->
    <section class="lg:col-span-2">
      <div
        class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        <!-- Card Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-800">
          <h2 class="text-gray-900 dark:text-white text-xl font-bold">
            {{ __('patient.profile.personal_info.title') }}
          </h2>
          <button type="button" id="edit-profile-btn"
            class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition">
            <span class="material-symbols-outlined text-lg">edit</span>
            <span>{{ __('patient.profile.edit_profile') }}</span>
          </button>
        </div>

        <!-- Display Mode -->
        <div id="profile-display-mode" class="p-6 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">
                {{ __('patient.profile.personal_info.first_name') }}
              </p>
              <p class="text-gray-900 dark:text-white text-base font-semibold">
                {{ $user->nombre }}
              </p>
            </div>
            <div>
              <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">
                {{ __('patient.profile.personal_info.last_name') }}
              </p>
              <p class="text-gray-900 dark:text-white text-base font-semibold">
                {{ $user->apellido }}
              </p>
            </div>
          </div>
          <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">
              {{ __('patient.profile.personal_info.email') }}
            </p>
            <p class="text-gray-900 dark:text-white text-base font-semibold">
              {{ $user->correo }}
            </p>
          </div>
        </div>

        <!-- Edit Mode -->
        <div id="profile-edit-mode" class="hidden p-6">
          <form id="profile-form" method="POST" action="{{ route('patient.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="nombre" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    {{ __('patient.profile.personal_info.first_name') }}
                  </label>
                  <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-primary/50"
                    placeholder="{{ __('patient.profile.placeholders.first_name') }}">
                </div>
                <div>
                  <label for="apellido" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    {{ __('patient.profile.personal_info.last_name') }}
                  </label>
                  <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $user->apellido) }}"
                    required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-primary/50"
                    placeholder="{{ __('patient.profile.placeholders.last_name') }}">
                </div>
              </div>

              <div>
                <label for="correo" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                  {{ __('patient.profile.personal_info.email') }}
                </label>
                <input type="email" id="correo" name="correo" value="{{ old('correo', $user->correo) }}" required
                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-primary/50"
                  placeholder="{{ __('patient.profile.placeholders.email') }}">
              </div>

              <div class="flex gap-3 pt-4">
                <button type="submit"
                  class="flex-1 flex items-center justify-center gap-2 h-11 px-5 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition">
                  <span class="material-symbols-outlined text-lg">save</span>
                  <span>{{ __('patient.profile.save_changes') }}</span>
                </button>
                <button type="button" id="cancel-edit-btn"
                  class="flex-1 flex items-center justify-center gap-2 h-11 px-5 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                  <span class="material-symbols-outlined text-lg">close</span>
                  <span>{{ __('patient.profile.cancel') }}</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- Account Statistics Card -->
    <section>
      <div
        class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 space-y-6">
        <h2 class="text-gray-900 dark:text-white text-xl font-bold">
          {{ __('patient.profile.account_stats.title') }}
        </h2>

        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              {{ __('patient.profile.account_stats.total_orders') }}
            </p>
            <p class="text-gray-900 dark:text-white font-bold text-lg">{{ $totalOrders }}</p>
          </div>

          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              {{ __('patient.profile.account_stats.active_penalties') }}
            </p>
            <p
              class="text-{{ $activePenalties > 0 ? 'red' : 'gray' }}-900 dark:text-{{ $activePenalties > 0 ? 'red' : 'white' }}-500 font-bold text-lg">
              {{ $activePenalties }}
            </p>
          </div>

          <div class="border-t border-gray-200 dark:border-gray-800 pt-4">
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">
              {{ __('patient.profile.account_stats.member_since') }}
            </p>
            <p class="text-gray-900 dark:text-white font-semibold">
              {{ $user->created_at->format('d/m/Y') }}
            </p>
          </div>

          <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">
              {{ __('patient.profile.account_stats.last_login') }}
            </p>
            <p class="text-gray-900 dark:text-white font-semibold">
              {{ $user->ultimo_login ? $user->ultimo_login->format('d/m/Y H:i') : 'N/A' }}
            </p>
          </div>
        </div>

        @if ($activePenalties > 0)
          <a href="{{ route('patient.penalties') }}"
            class="flex items-center justify-center h-10 w-full rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-sm font-bold text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
            {{ __('patient.dashboard.penalty.view_details') }}
          </a>
        @endif
      </div>
    </section>
  </div>
</div>

@vite(['resources/js/patient/profile.js'])