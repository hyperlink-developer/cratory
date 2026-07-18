<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">GSTR-2B Reconciliation</h1>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Upload GSTR-2B CSV</h2>
        
        <form wire:submit.prevent="processUpload" class="space-y-4">
            <div>
                <label for="periodId" class="block text-sm font-medium text-gray-700">Tax Period</label>
                <select id="periodId" wire:model.live="periodId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}">
                            {{ ucfirst($period->period_type) }} - {{ \Carbon\Carbon::parse($period->period_start)->format('M Y') }}
                        </option>
                    @endforeach
                </select>
                @error('periodId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="csvFile" class="block text-sm font-medium text-gray-700">CSV File</label>
                <input type="file" id="csvFile" wire:model="csvFile" accept=".csv" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:border-transparent">
                @error('csvFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" {{ $isProcessing ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="processUpload">
                        Process Reconciliation
                    </span>
                    <span wire:loading wire:target="processUpload">
                        Processing...
                    </span>
                </button>
            </div>
        </form>
    </div>

    @if($items->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Reconciliation Results
            </h3>
            <div class="flex space-x-2 text-sm">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800">
                    Matched: {{ $items->where('match_status', 'matched')->count() }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-red-100 text-red-800">
                    Unmatched: {{ $items->where('match_status', 'unmatched')->count() }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-yellow-100 text-yellow-800">
                    Review: {{ $items->where('match_status', 'manual_review')->count() }}
                </span>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GSTIN</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No.</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Taxable Value</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Local Match</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->match_status === 'matched')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Matched</span>
                                @elseif($item->match_status === 'manual_review')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Review</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unmatched</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->uploaded_gstin }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->uploaded_invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₹{{ number_format($item->uploaded_taxable_value, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₹{{ number_format($item->uploaded_tax_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($item->purchaseInvoice)
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">
                                        #{{ $item->purchaseInvoice->invoice_number }}
                                    </a>
                                    @if($item->match_status === 'manual_review')
                                        <div class="text-xs text-red-500 mt-1">Local: ₹{{ number_format($item->purchaseInvoice->total_taxable_amount, 2) }}</div>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif($periodId)
    <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
        No reconciliation data found for this period. Upload a CSV to begin.
    </div>
    @endif
</div>
