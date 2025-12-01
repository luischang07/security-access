@extends('layouts.pharmacy')

@section('title', __('pharmacy.inventory.title'))

@section('pharmacy-content')
  <div class="max-w-7xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
          {{ __('pharmacy.inventory.title') }}
        </h1>
        <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">
          {{ __('pharmacy.dashboard.summary') }}
        </p>
      </div>
      <!-- Add New Button (Placeholder for future functionality) -->
      <button type="button"
        class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition flex items-center gap-2 font-medium shadow-sm">
        <span class="material-symbols-outlined text-xl">add</span>
        {{ __('pharmacy.inventory.add_new_medication') }}
      </button>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
      <div class="rounded-lg bg-danger/10 p-4 text-danger border border-danger/20">
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Search and Filter Bar -->
    <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-4">
      <form action="{{ route('pharmacy.inventory') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="relative flex-1">
          <span
            class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-neutral-text dark:text-neutral-text-dark">search</span>
          <input type="text" name="search" value="{{ $search ?? '' }}"
            placeholder="{{ __('pharmacy.inventory.search_placeholder') }}"
            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none">
        </div>
        <button type="submit"
          class="px-6 py-2 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark transition font-medium">
          {{ __('pharmacy.inventory.search') }}
        </button>
      </form>
    </div>

    <!-- Inventory Table -->
    <div
      class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark overflow-hidden flex flex-col">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-background-light dark:bg-background-dark border-b border-border-light dark:border-border-dark">
              <th class="p-4 text-sm font-semibold text-neutral-text dark:text-neutral-text-dark">
                {{ __('pharmacy.inventory.medication') }}
              </th>
              <th class="p-4 text-sm font-semibold text-neutral-text dark:text-neutral-text-dark">
                {{ __('pharmacy.inventory.product_code') }}
              </th>
              <th class="p-4 text-sm font-semibold text-neutral-text dark:text-neutral-text-dark">
                {{ __('pharmacy.inventory.current_stock') }}
              </th>
              <th class="p-4 text-sm font-semibold text-neutral-text dark:text-neutral-text-dark">
                {{ __('pharmacy.inventory.unit_price') }}
              </th>
              <th class="p-4 text-sm font-semibold text-neutral-text dark:text-neutral-text-dark">
                {{ __('pharmacy.inventory.status') }}
              </th>
              <th class="p-4 text-sm font-semibold text-neutral-text dark:text-neutral-text-dark text-right">
                {{ __('pharmacy.inventory.actions') }}
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border-light dark:divide-border-dark">
            @forelse ($inventario as $item)
              <tr x-data="{
                                editing: false,
                                stock: '{{ $item->stock_disponible }}',
                                price: '{{ $item->precio_unitario }}',
                                min: '{{ $item->minimo ?? 0 }}',
                                max: '{{ $item->maximo ?? 0 }}',
                                toggleEdit() {
                                    this.editing = !this.editing;
                                    if (!this.editing) {
                                        this.stock = '{{ $item->stock_disponible }}';
                                        this.price = '{{ $item->precio_unitario }}';
                                        this.min = '{{ $item->minimo ?? 0 }}';
                                        this.max = '{{ $item->maximo ?? 0 }}';
                                    }
                                }
                            }" class="hover:bg-background-light dark:hover:bg-background-dark transition-colors group">
                <td class="p-4">
                  <div class="flex flex-col">
                    <span class="font-medium text-body-text dark:text-body-text-dark">
                      {{ $item->medicamento->nombre }}
                    </span>
                    <span class="text-xs text-neutral-text dark:text-neutral-text-dark">
                      {{ $item->medicamento->descripcion ?? 'N/A' }}
                    </span>
                  </div>
                </td>
                <td class="p-4 text-sm text-body-text dark:text-body-text-dark">
                  {{ $item->medicamento_id }}
                </td>
                <td class="p-4">
                  <div x-show="!editing" class="flex items-center gap-2">
                    <span class="font-medium text-body-text dark:text-body-text-dark">
                      {{ $item->stock_disponible }}
                    </span>
                    <span class="text-xs text-neutral-text dark:text-neutral-text-dark">
                      {{ __('pharmacy.inventory.units') }}
                    </span>
                  </div>
                  <div x-show="editing" x-cloak>
                    <input type="number" x-model="stock" form="update-form-{{ $item->medicamento_id }}"
                      name="stock_disponible" min="0" required
                      class="w-24 px-2 py-1 text-sm rounded border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
                  </div>
                </td>
                <td class="p-4 text-sm text-body-text dark:text-body-text-dark">
                  <span x-show="!editing">${{ number_format($item->precio_unitario, 2) }}</span>
                  <div x-show="editing" x-cloak class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-xs text-neutral-text">$</span>
                    <input type="number" x-model="price" form="update-form-{{ $item->medicamento_id }}"
                      name="precio_unitario" min="0" step="0.01" required
                      class="w-24 pl-5 pr-2 py-1 text-sm rounded border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
                  </div>
                </td>
                <td class="p-4">
                  <div x-show="!editing">
                    @if ($item->stock_disponible > 10)
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                        {{ __('pharmacy.inventory.stock_status.in_stock') }}
                      </span>
                    @elseif($item->stock_disponible > 0)
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                        {{ __('pharmacy.inventory.stock_status.low_stock') }}
                      </span>
                    @else
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">
                        {{ __('pharmacy.inventory.stock_status.out_of_stock') }}
                      </span>
                    @endif
                  </div>
                  <div x-show="editing" x-cloak class="flex flex-col gap-1">
                    <div class="flex items-center gap-1">
                      <span class="text-[10px] text-neutral-text">Min:</span>
                      <input type="number" x-model="min" form="update-form-{{ $item->medicamento_id }}" name="minimo"
                        min="0"
                        class="w-16 px-1 py-0.5 text-xs rounded border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
                    </div>
                    <div class="flex items-center gap-1">
                      <span class="text-[10px] text-neutral-text">Max:</span>
                      <input type="number" x-model="max" form="update-form-{{ $item->medicamento_id }}" name="maximo"
                        min="0"
                        class="w-16 px-1 py-0.5 text-xs rounded border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
                    </div>
                  </div>
                </td>
                <td class="p-4 text-right">
                  <!-- Hidden Update Form -->
                  <form id="update-form-{{ $item->medicamento_id }}"
                    action="{{ route('pharmacy.inventory.update', $item->medicamento_id) }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                  </form>

                  <!-- Action Buttons -->
                  <div class="flex items-center justify-end gap-2">
                    <!-- Edit Mode Buttons -->
                    <template x-if="editing">
                      <div class="flex items-center gap-2">
                        <button type="submit" form="update-form-{{ $item->medicamento_id }}"
                          class="p-1.5 rounded-md text-success hover:bg-success/10 transition-colors"
                          title="{{ __('pharmacy.inventory.save') }}">
                          <span class="material-symbols-outlined text-lg">check</span>
                        </button>
                        <button type="button" @click="toggleEdit()"
                          class="p-1.5 rounded-md text-danger hover:bg-danger/10 transition-colors"
                          title="{{ __('pharmacy.inventory.cancel') }}">
                          <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                      </div>
                    </template>

                    <!-- View Mode Buttons -->
                    <template x-if="!editing">
                      <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" @click="toggleEdit()"
                          class="p-1.5 rounded-md text-neutral-text dark:text-neutral-text-dark hover:bg-primary/10 hover:text-primary transition-colors"
                          title="{{ __('pharmacy.inventory.actions') }}">
                          <span class="material-symbols-outlined text-lg">edit</span>
                        </button>

                        <form action="{{ route('pharmacy.inventory.delete', $item->medicamento_id) }}" method="POST"
                          onsubmit="return confirm('{{ __('pharmacy.inventory.delete_confirmation') }}');">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="p-1.5 rounded-md text-neutral-text dark:text-neutral-text-dark hover:bg-danger/10 hover:text-danger transition-colors"
                            title="{{ __('pharmacy.inventory.actions') }}">
                            <span class="material-symbols-outlined text-lg">delete</span>
                          </button>
                        </form>
                      </div>
                    </template>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="p-8 text-center text-neutral-text dark:text-neutral-text-dark">
                  <div class="flex flex-col items-center justify-center">
                    <span class="material-symbols-outlined text-4xl mb-2">inventory_2</span>
                    <p>{{ __('pharmacy.orders.no_medications') }}</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if ($inventario->hasPages())
        <div class="p-4 border-t border-border-light dark:border-border-dark">
          {{ $inventario->appends(['search' => $search ?? ''])->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection