<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['index' => null, 'value' => '', 'medicationId' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['index' => null, 'value' => '', 'medicationId' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-data="{
    query: '<?php echo e($value); ?>',
    localMedicationId: '<?php echo e($medicationId); ?>',
    suggestions: [],
    showSuggestions: false,
    highlightedIndex: -1,

    init() {
        // Sync with parent x-model if provided
        if (this.$el.closest('[x-data]').__x) {
             // If bound to a parent property via x-model on the component tag (not standard Blade but simulating behavior)
             // Actually, the parent will bind to the input inside.
        }
        
        this.$watch('query', value => {
            // Update parent model if bound
            // We assume the parent binds to the input's x-model or listens to input event
            
            if (value.length < 2) {
                this.suggestions = [];
                this.showSuggestions = false;
                return;
            }
            this.fetchSuggestions(value);
        });
    },

    fetchSuggestions(query) {
        fetch(`<?php echo e(route('prescription.medications.search')); ?>?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                this.suggestions = data;
                this.showSuggestions = data.length > 0;
                this.highlightedIndex = -1;
            });
    },

    selectSuggestion(suggestion) {
        this.query = suggestion.nombre;
        this.localMedicationId = suggestion.id;
        this.showSuggestions = false;
        
        // Dispatch events for parent to catch
        this.$dispatch('medication-selected', { 
            id: suggestion.id, 
            name: suggestion.nombre 
        });
        
        // Also update the hidden input and text input
        // The text input x-model 'query' is already updated
    },

    closeSuggestions() {
        setTimeout(() => {
            this.showSuggestions = false;
        }, 200);
    }
}" 
@clear-autocomplete.window="query = ''; localMedicationId = '';"
class="relative">
    
    
    <input type="text"
           id="medication-name-input"
           x-model="query"
           @query-input="$dispatch('query-input', query)" 
           @focus="if(query.length >= 2) showSuggestions = true"
           @click.away="showSuggestions = false"
           @keydown.escape="showSuggestions = false"
           @keydown.arrow-down.prevent="highlightedIndex = (highlightedIndex + 1) % suggestions.length"
           @keydown.arrow-up.prevent="highlightedIndex = (highlightedIndex - 1 + suggestions.length) % suggestions.length"
           @keydown.enter.prevent="if(highlightedIndex >= 0) selectSuggestion(suggestions[highlightedIndex])"
           <?php echo e($attributes->merge(['class' => 'w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50'])); ?>

           placeholder="<?php echo e(__('prescription.upload_step1.medication_name_placeholder')); ?>"
           autocomplete="off"
    />

    
    <?php if($index !== null): ?>
        <input type="hidden" name="medications[<?php echo e($index); ?>][medication_id]" x-model="localMedicationId">
    <?php endif; ?>

    
    <div x-show="showSuggestions"
         x-transition
         class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
         style="display: none;">
        <template x-for="(suggestion, i) in suggestions" :key="suggestion.id">
            <div @click="selectSuggestion(suggestion)"
                 :class="{ 'bg-gray-100 dark:bg-gray-700': i === highlightedIndex }"
                 class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-200">
                <div class="font-medium" x-text="suggestion.nombre"></div>
                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="suggestion.unidad_medida"></div>
            </div>
        </template>
    </div>
</div><?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\components\medication-autocomplete.blade.php ENDPATH**/ ?>