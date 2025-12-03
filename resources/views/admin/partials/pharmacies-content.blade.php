<!-- Pharmacies Management -->
<div class="flex justify-between items-center mb-6">
  <div>
    <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
      {{ __('admin.pharmacies.title') }}
    </h1>
    <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
      {{ __('admin.pharmacies.subtitle') }}
    </p>
  </div>
  <button
    class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
    <span class="material-symbols-outlined">add</span>
    {{ __('admin.pharmacies.add_pharmacy') }}
  </button>
</div>

<!-- Filters & Search -->
<div x-data="adminFilters({{ json_encode($filters) }})" x-init="init()"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6 p-4">
  <form x-ref="filterForm" action="{{ route('admin.pharmacies') }}" method="GET">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <div class="md:col-span-2">
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('common.filters.search') }}
        </label>
        <input type="text" id="search" name="search" x-model="filters.search"
          placeholder="{{ __('admin.pharmacies.search_placeholder') }}"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('admin.pharmacies.filter_chain') }}
        </label>
        <select name="chain" x-model="filters.chain"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="">{{ __('common.filters.all') }}</option>
          @foreach ($chains as $chainId => $chainName)
            <option value="{{ $chainId }}">{{ $chainName }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('admin.pharmacies.filter_status') }}
        </label>
        <select name="status" x-model="filters.status"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="">{{ __('common.filters.all') }}</option>
          <option value="active">{{ __('common.status.active') }}</option>
          <option value="inactive">{{ __('common.status.inactive') }}</option>
        </select>
      </div>
      <div class="flex items-end">
        <button type="button" @click="clearFilters()"
          class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
          {{ __('common.actions.clear') }}
        </button>
      </div>
    </div>
    <input type="hidden" name="per_page" x-model="filters.per_page">
  </form>
</div>

<!-- Stats Grid -->
<div id="spa-stats" x-data="adminStats({{ json_encode($stats) }})" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.pharmacies.total_pharmacies') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.total_pharmacies"></p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.pharmacies.active_pharmacies') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.active_pharmacies"></p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.pharmacies.avg_rating') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.avg_rating"></p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.pharmacies.avg_fulfillment') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.avg_fulfillment_hours + 'h'">
    </p>
  </div>
</div>

<!-- Pharmacies Table -->
<div id="spa-results"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden relative">
  <!-- Loading Overlay -->
  <div x-show="loading"
    class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 flex items-center justify-center z-10 backdrop-blur-sm"
    style="display: none;"
    x-transition.opacity>
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-background-light dark:bg-background-dark">
        <tr>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.pharmacies.table.pharmacy') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.pharmacies.table.chain') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.pharmacies.table.location') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.pharmacies.table.orders') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.pharmacies.table.rating') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.pharmacies.table.status') }}
          </th>
          <th
            class="px-6 py-3 text-right text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('common.actions.title') }}
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border-light dark:divide-border-dark">
        @forelse ($pharmacies as $pharmacy)
          <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                  <span class="material-symbols-outlined text-primary">storefront</span>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                    {{ $pharmacy->nombre }}
                  </div>
                  <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                    ID: {{ $pharmacy->sucursal_id }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                {{ $pharmacy->cadena_name ?? 'N/A' }}
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                {{ Str::limit($pharmacy->calle . ' ' . $pharmacy->numero_ext . ($pharmacy->numero_int ? ' Int. ' . $pharmacy->numero_int : '') . ', ' . $pharmacy->colonia, 40) }}
              </div>
              <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                {{ $pharmacy->latitud }}, {{ $pharmacy->longitud }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">{{ $pharmacy->total_orders }}
              </div>
              <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                {{ __('admin.pharmacies.this_month') }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <span class="material-symbols-outlined text-yellow-500 text-lg">star</span>
                <span class="ml-1 text-sm text-body-text dark:text-body-text-dark">
                  4.5
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pharmacy->total_orders > 0 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                {{ $pharmacy->total_orders > 0 ? __('common.status.active') : __('common.status.inactive') }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button class="text-primary hover:text-primary/80 mr-3">{{ __('common.actions.view') }}</button>
              <button class="text-primary hover:text-primary/80">{{ __('common.actions.edit') }}</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center">
              <div class="text-neutral-text dark:text-neutral-text-dark">
                {{ __('admin.pharmacies.no_results') }}
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div x-data="adminPagination({{ json_encode($pharmacies) }})"
    class="px-6 py-4 border-t border-border-light dark:border-border-dark">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
        {{ __('common.pagination.showing') }} <span class="font-medium" x-text="pagination.from"></span>
        {{ __('common.pagination.to') }} <span class="font-medium" x-text="pagination.to"></span>
        {{ __('common.pagination.of') }} <span class="font-medium" x-text="pagination.total"></span>
        {{ __('common.pagination.results') }}
      </div>
      <div class="flex items-center gap-2">
        <label class="text-sm text-neutral-text dark:text-neutral-text-dark">
          {{ __('common.filters.per_page') }}:
        </label>
        <select x-model="perPage" @change="changePerPage()"
          class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="10">10</option>
          <option value="12">12</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
      </div>
      <div class="flex gap-2">
        <template x-for="link in pagination.links" :key="link.label">
          <button @click="changePage(link.url)" :disabled="!link.url" :class="{
                            'bg-primary text-white': link.active,
                            'border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark':
                                !link.active,
                            'opacity-50 cursor-not-allowed': !link.url
                        }" class="px-3 py-1 rounded-lg transition-colors" x-html="link.label">
          </button>
        </template>
      </div>
    </div>
  </div>
</div>