<div class="max-w-7xl mx-auto">
  <!-- Error/Warning Alert -->
  @if(session('error'))
    <div class="mb-6 rounded-lg border border-warning/40 bg-warning/10 px-4 py-3 text-sm text-warning dark:border-warning/30 dark:bg-warning/15">
      <div class="flex gap-3">
        <span class="material-symbols-outlined text-xl mt-0.5 flex-shrink-0">warning</span>
        <div>
          <p class="font-semibold">{{ session('error') }}</p>
        </div>
      </div>
    </div>
  @endif

  <!-- Header -->
  <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
    <div class="flex min-w-72 flex-col gap-2">
      <p class="text-gray-900 dark:text-white text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em]">
        {{ __('patient.dashboard.welcome', ['name' => Auth::user()->nombre]) }}
      </p>
      <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">
        {{ __('patient.dashboard.summary') }}
      </p>
    </div>
    <a href="{{ route('prescription.upload.step1') }}"
      class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white gap-2 text-base font-bold leading-normal tracking-[0.015em] shadow-sm hover:bg-primary/90">
      <span class="material-symbols-outlined">upload_file</span>
      <span class="truncate">{{ __('patient.dashboard.upload_prescription') }}</span>
    </a>
  </div>

  <div class="space-y-8">
    <!-- Active Orders Section -->
    <section>
      <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
        {{ __('patient.dashboard.active_orders.title') }}
      </h2>
      <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
        @forelse($pedidosActivosConProgreso as $item)
          <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-800' : '' }}">
            <div class="flex-1">
              <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                Order #{{ str_pad($item['pedido']->pedido_id, 5, '0', STR_PAD_LEFT) }}
              </p>
              <p class="text-gray-900 dark:text-white font-semibold mt-1">
                {{ $item['progress']['label'] }}
              </p>
              <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                {{ $item['pedido']->fecha_pedido->format('d/m/Y') }}
              </p>
            </div>
            <div class="flex-1 w-full">
              <div class="relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                <div class="absolute top-0 left-0 h-2 {{ $item['progress']['color'] }} rounded-full"
                  style="width: {{ $item['progress']['width'] }};"></div>
              </div>
            </div>
            <a class="text-primary font-bold text-sm whitespace-nowrap"
              href="{{ route('patient.orders') }}">{{ __('patient.dashboard.active_orders.view_details') }}</a>
          </div>
        @empty
          <div class="text-center py-8">
            <span class="material-symbols-outlined text-gray-400 text-5xl mb-2">receipt_long</span>
            <p class="text-gray-500 dark:text-gray-400">
              {{ __('patient.dashboard.active_orders.no_orders') }}
            </p>
          </div>
        @endforelse
      </div>
    </section>

    <!-- Recent History & Account Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Recent History -->
      <section class="lg:col-span-2">
        <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
          {{ __('patient.dashboard.recent_history.title') }}
        </h2>
        <div
          class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
          <!-- History Items -->
          @forelse($historialReciente as $pedido)
            <div class="flex items-center gap-4 p-4 border-b border-gray-200 dark:border-gray-800 last:border-b-0">
              <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">
                  {{ $pedido->estatus === 'entregado' ? 'check_circle' : 'cancel' }}
                </span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-gray-900 dark:text-white text-sm font-bold truncate">
                  Order #{{ str_pad($pedido->pedido_id, 5, '0', STR_PAD_LEFT) }}
                </p>
                <p class="text-gray-500 dark:text-gray-400 text-xs">
                  {{ $pedido->estatus === 'entregado' ? __('patient.dashboard.recent_history.completed') : __('patient.dashboard.recent_history.cancelled') }}
                  - {{ $pedido->fecha_pedido->format('d/m/Y') }}
                </p>
              </div>
              <p class="text-gray-900 dark:text-white text-sm font-bold whitespace-nowrap">
                ${{ number_format($pedido->costo_total, 2) }}
              </p>
            </div>
          @empty
            <div class="text-center py-8">
              <p class="text-gray-500 dark:text-gray-400">
                {{ __('patient.dashboard.recent_history.no_history') }}
              </p>
            </div>
          @endforelse

          @if ($historialReciente->count() > 0)
            <div class="p-4 text-center">
              <a href="{{ route('patient.orders.history') }}" class="text-primary font-bold text-sm">
                {{ __('patient.dashboard.recent_history.view_all') }}
              </a>
            </div>
          @endif
        </div>
      </section>

      <!-- Account Summary -->
      <section>
        <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
          {{ __('patient.dashboard.account_summary.title') }}
        </h2>
        <div
          class="bg-white dark:bg-gray-900/50 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 space-y-4">
          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              {{ __('patient.dashboard.account_summary.active_orders') }}
            </p>
            <p class="text-gray-900 dark:text-white font-bold">{{ $pedidosActivosConProgreso->count() }}</p>
          </div>
          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              {{ __('patient.dashboard.account_summary.completed') }}
            </p>
            <p class="text-gray-900 dark:text-white font-bold">{{ $pedidosCompletados }}</p>
          </div>
          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              {{ __('patient.dashboard.account_summary.penalties') }}
            </p>
            <p
              class="text-{{ $paciente->penalizacion > 0 ? 'red' : 'gray' }}-900 dark:text-{{ $paciente->penalizacion > 0 ? 'red' : 'white' }}-500 font-bold">
              ${{ number_format($paciente->penalizacion, 2) }}
            </p>
          </div>
          <a href="{{ route('patient.profile') }}"
            class="flex items-center justify-center h-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-bold text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition mt-4">
            {{ __('patient.dashboard.account_summary.view_profile') }}
          </a>
        </div>
      </section>
    </div>
  </div>
</div>