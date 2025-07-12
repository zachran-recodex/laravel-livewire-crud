<x-container title="Activity Log" flow="true">
    <x-input wire:model.live="search" placeholder="Search activity log..." class="mb-4" />

    <x-table>
        <x-thead>
            <x-tr>
                <x-th sortable wire:click="sortBy('description')" :direction="$sortField === 'description' ? ($sortAsc ? 'asc' : 'desc') : null">Description</x-th>
                <x-th sortable wire:click="sortBy('log_name')" :direction="$sortField === 'log_name' ? ($sortAsc ? 'asc' : 'desc') : null">Log Name</x-th>
                <x-th sortable wire:click="sortBy('subject_type')" :direction="$sortField === 'subject_type' ? ($sortAsc ? 'asc' : 'desc') : null">Subject Type</x-th>
                <x-th>Subject ID</x-th>
                <x-th sortable wire:click="sortBy('causer_type')" :direction="$sortField === 'causer_type' ? ($sortAsc ? 'asc' : 'desc') : null">Causer Type</x-th>
                <x-th>Causer ID</x-th>
                <x-th sortable wire:click="sortBy('created_at')" :direction="$sortField === 'created_at' ? ($sortAsc ? 'asc' : 'desc') : null">Time</x-th>
            </x-tr>
        </x-thead>
        <x-tbody>
            @forelse($activities as $activity)
                <x-tr wire:key="{{ $activity->id }}">
                    <x-td>{{ $activity->description }}</x-td>
                    <x-td>{{ $activity->log_name }}</x-td>
                    <x-td>{{ class_basename($activity->subject_type) }}</x-td>
                    <x-td>{{ $activity->subject_id }}</x-td>
                    <x-td>{{ class_basename($activity->causer_type) }}</x-td>
                    <x-td>{{ $activity->causer_id }}</x-td>
                    <x-td>{{ $activity->created_at->diffForHumans() }}</x-td>
                </x-tr>
            @empty
                <x-tr>
                    <x-td colspan="7" class="text-center">No activity found.</x-td>
                </x-tr>
            @endforelse
        </x-tbody>
    </x-table>

    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</x-container>