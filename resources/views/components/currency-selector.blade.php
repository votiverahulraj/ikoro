<div class="currency-selector" x-data="{ open: false }">
    <button @click="open = !open" 
            class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        @if($showFlag)
            <span>{{ $currencies[$currentCurrency]['flag'] ?? '' }}</span>
        @endif
        <span>{{ $currencies[$currentCurrency]['symbol'] ?? '' }}</span>
        <span class="hidden sm:inline">{{ $currentCurrency }}</span>
        <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="open = false"
         class="absolute right-0 z-50 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg">
        <div class="py-1 max-h-64 overflow-y-auto">
            @foreach($currencies as $code => $currency)
                <form method="POST" action="{{ route('currency.switch') }}" class="currency-form">
                    @csrf
                    <input type="hidden" name="currency" value="{{ $code }}">
                    <button type="submit" 
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $code === $currentCurrency ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        @if($showFlag)
                            <span class="mr-2">{{ $currency['flag'] }}</span>
                        @endif
                        <span class="mr-2 font-medium">{{ $code }}</span>
                        <span class="text-gray-500">{{ $currency['symbol'] }}</span>
                        @if($code === $currentCurrency)
                            <svg class="w-4 h-4 ml-auto text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </button>
                </form>
            @endforeach
        </div>
    </div>
</div>

<style>
.currency-selector {
    position: relative;
    display: inline-block;
}

.currency-form {
    margin: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions with AJAX for better UX
    document.querySelectorAll('.currency-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const currency = formData.get('currency');
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ currency: currency })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page to show updated prices
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error switching currency:', error);
                // Fallback to regular form submission
                this.submit();
            });
        });
    });
});
</script>