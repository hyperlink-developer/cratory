<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Recurring Invoice Template</h1>
            <p class="mt-2 text-sm text-gray-700">Set up a template to automatically generate invoices.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-8 divide-y divide-gray-200">
        <div class="space-y-6 sm:space-y-5">
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900">Schedule & Customer</h3>
            </div>

            <div class="space-y-6 sm:space-y-5">
                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                    <label for="contact_id" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Customer</label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <select wire:model="contact_id" id="contact_id" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm">
                            <option value="">Select a customer</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                        @error('contact_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                    <label for="frequency" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Frequency</label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <select wire:model="frequency" id="frequency" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm">
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        @error('frequency') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                    <label for="next_run_date" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">First Invoice Date</label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input type="date" wire:model="next_run_date" id="next_run_date" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm">
                        @error('next_run_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 space-y-6 sm:space-y-5">
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900">Line Items</h3>
            </div>
            
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Qty</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Price</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Tax</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Total</th>
                            <th scope="col" class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($items as $index => $item)
                        <tr>
                            <td class="px-3 py-4">
                                <input type="text" wire:model.live="items.{{ $index }}.name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Description">
                            </td>
                            <td class="px-3 py-4">
                                <input type="number" wire:model.live="items.{{ $index }}.quantity" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </td>
                            <td class="px-3 py-4">
                                <input type="number" wire:model.live="items.{{ $index }}.unit_price" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </td>
                            <td class="px-3 py-4">
                                <select wire:model.live="items.{{ $index }}.tax_rate_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">None</option>
                                    @foreach($taxRates as $tax)
                                        <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-900">
                                ₹{{ number_format($item['line_total'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 text-right">
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    <button type="button" wire:click="addItem" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                        + Add Item
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-5 flex justify-end items-start gap-8">
            <div class="w-64 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="text-gray-900 font-medium">₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tax</span>
                    <span class="text-gray-900 font-medium">₹{{ number_format($tax_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-base border-t border-gray-200 pt-3">
                    <span class="text-gray-900 font-bold">Total</span>
                    <span class="text-gray-900 font-bold">₹{{ number_format($grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="pt-5">
            <div class="flex justify-end">
                <a href="{{ route('invoices.recurring.index') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</a>
                <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save Template</button>
            </div>
        </div>
    </form>
</div>
