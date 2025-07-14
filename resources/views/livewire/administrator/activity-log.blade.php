<div>
    <header class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Activity Log</flux:heading>
            <flux:subheading>Monitor all activities and changes in the system</flux:subheading>
        </div>
    </header>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <flux:field>
                <flux:label>Search Description</flux:label>
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search activities..." icon="magnifying-glass" />
            </flux:field>

            <flux:field>
                <flux:label>Causer (User)</flux:label>
                <flux:input wire:model.live.debounce.300ms="filterCauser" placeholder="Filter by user name..." />
            </flux:field>

            <flux:field>
                <flux:label>Log Type</flux:label>
                <flux:select wire:model.live="filterLogName" placeholder="Select log type...">
                    <option value="">All Types</option>
                    @foreach($this->logNames as $logName)
                        <option value="{{ $logName }}">{{ ucfirst($logName) }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Event</flux:label>
                <flux:select wire:model.live="filterEvent" placeholder="Select event...">
                    <option value="">All Events</option>
                    @foreach($this->events as $event)
                        <option value="{{ $event }}">{{ ucfirst($event) }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Date From</flux:label>
                <flux:input wire:model.live="filterDateFrom" type="date" />
            </flux:field>

            <flux:field>
                <flux:label>Date To</flux:label>
                <flux:input wire:model.live="filterDateTo" type="date" />
            </flux:field>
        </div>

        <div class="mt-4 flex justify-end">
            <flux:button wire:click="clearFilters" variant="ghost" icon="x-mark">
                Clear Filters
            </flux:button>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Causer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Log Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->activities as $activity)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $activity->description }}
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            @if($activity->subject)
                                <div>
                                    <div class="font-medium">{{ class_basename($activity->subject_type) }}</div>
                                    @if($activity->subject_type === 'App\Models\User' && $activity->subject)
                                        <div class="text-xs">{{ $activity->subject->name ?? 'N/A' }}</div>
                                    @elseif($activity->subject_type === 'App\Models\Product' && $activity->subject)
                                        <div class="text-xs">{{ $activity->subject->name ?? 'N/A' }}</div>
                                    @else
                                        <div class="text-xs">ID: {{ $activity->subject_id }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-zinc-400">No subject</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            @if($activity->causer)
                                <div>
                                    <div class="font-medium">{{ $activity->causer->name ?? 'N/A' }}</div>
                                    <div class="text-xs">{{ $activity->causer->email ?? 'N/A' }}</div>
                                </div>
                            @else
                                <span class="text-zinc-400">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            @if($activity->event)
                                <flux:badge variant="outline" size="sm">
                                    {{ ucfirst($activity->event) }}
                                </flux:badge>
                            @else
                                <span class="text-zinc-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <flux:badge variant="primary" size="sm">
                                {{ ucfirst($activity->log_name) }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <div>
                                <div>{{ $activity->created_at->format('d F Y') }}</div>
                                <div class="text-xs">{{ $activity->created_at->format('H:i:s') }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:modal.trigger name="activity-details-{{ $activity->id }}">
                                <flux:button size="sm" variant="primary" color="green" icon="eye">
                                    Details
                                </flux:button>
                            </flux:modal.trigger>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            No activities found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $this->activities->links('custom.pagination') }}
    </div>

    <!-- Activity Details Modals -->
    @foreach ($this->activities as $activity)
        <flux:modal name="activity-details-{{ $activity->id }}" class="min-w-3xl max-w-4xl">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Activity Details</flux:heading>
                    <flux:text class="mt-2">
                        Detailed information about this activity
                    </flux:text>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <flux:label>Description</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg">
                                {{ $activity->description }}
                            </div>
                        </div>

                        <div>
                            <flux:label>Event</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg">
                                {{ $activity->event ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <flux:label>Log Type</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg">
                                {{ $activity->log_name }}
                            </div>
                        </div>

                        <div>
                            <flux:label>Date & Time</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg">
                                {{ $activity->created_at->format('d F Y, H:i:s') }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <flux:label>Subject</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg">
                                @if($activity->subject)
                                    <div>Type: {{ class_basename($activity->subject_type) }}</div>
                                    <div>ID: {{ $activity->subject_id }}</div>
                                    @if($activity->subject_type === 'App\Models\User' && $activity->subject)
                                        <div>Name: {{ $activity->subject->name ?? 'N/A' }}</div>
                                    @elseif($activity->subject_type === 'App\Models\Product' && $activity->subject)
                                        <div>Name: {{ $activity->subject->name ?? 'N/A' }}</div>
                                    @endif
                                @else
                                    No subject
                                @endif
                            </div>
                        </div>

                        <div>
                            <flux:label>Causer</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg">
                                @if($activity->causer)
                                    <div>Name: {{ $activity->causer->name ?? 'N/A' }}</div>
                                    <div>Email: {{ $activity->causer->email ?? 'N/A' }}</div>
                                    <div>ID: {{ $activity->causer_id }}</div>
                                @else
                                    System
                                @endif
                            </div>
                        </div>

                        @if($activity->properties && $activity->properties->isNotEmpty())
                        <div>
                            <flux:label>Properties</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg max-h-48 overflow-y-auto">
                                <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                        @endif

                        @if($activity->changes && $activity->changes->isNotEmpty())
                        <div>
                            <flux:label>Changes</flux:label>
                            <div class="text-sm text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-800 p-3 rounded-lg max-h-48 overflow-y-auto">
                                <pre class="whitespace-pre-wrap">{{ json_encode($activity->changes, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>