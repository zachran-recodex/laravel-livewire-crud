<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
    @role('Super Admin')
    <!-- Cards Section -->
    <div class="grid auto-rows-min gap-6 md:grid-cols-4">
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Total Users</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Number of registered users</p>
                </div>
                <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/20">
                    <flux:icon.users class="h-6 w-6 text-blue-600" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" data-users-count="{{ $this->totalUsers }}">{{ $this->totalUsers }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Total Roles</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Number of system roles</p>
                </div>
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/20">
                    <flux:icon.shield-check class="h-6 w-6 text-green-600" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" data-roles-count="{{ $this->totalRoles }}">{{ $this->totalRoles }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Total Permissions</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Number of system permissions</p>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/20">
                    <flux:icon.key class="h-6 w-6 text-purple-600" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" data-permissions-count="{{ $this->totalPermissions }}">{{ $this->totalPermissions }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Total Activities</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Number of logged activities</p>
                </div>
                <div class="rounded-full bg-orange-100 p-3 dark:bg-orange-900/20">
                    <flux:icon.clipboard-document-list class="h-6 w-6 text-orange-600" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" data-activities-count="{{ $this->totalActivities }}">{{ $this->totalActivities }}</p>
            </div>
        </div>
    </div>


    <!-- Info Section -->
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Recent Users</h3>
            <div class="space-y-3">
                @foreach($this->recentUsers as $user)
                    <div class="flex items-center justify-between rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800/50">
                        <div class="flex items-center space-x-3">
                            <flux:avatar circle initials="{{ $user->initials() }}" />
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $this->recentUsers->links('custom.pagination') }}
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Recent Activities</h3>
                <flux:button :href="route('admin.activity-log')" size="sm" variant="ghost" icon="arrow-right" wire:navigate>
                    View All
                </flux:button>
            </div>
            <div class="space-y-3">
                @forelse($this->recentActivities as $activity)
                    <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800/50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $activity->description }}</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    @if($activity->causer)
                                        <span class="text-xs text-zinc-600 dark:text-zinc-400">by {{ $activity->causer->name }}</span>
                                    @else
                                        <span class="text-xs text-zinc-600 dark:text-zinc-400">by System</span>
                                    @endif
                                    <span class="text-xs text-zinc-500">•</span>
                                    <flux:badge variant="outline" size="sm">
                                        {{ ucfirst($activity->log_name) }}
                                    </flux:badge>
                                </div>
                            </div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 ml-2">
                                {{ $activity->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-zinc-500 dark:text-zinc-400 py-4">
                        <flux:icon.clipboard-document-list class="h-8 w-8 mx-auto mb-2 text-zinc-400" />
                        <p class="text-sm">No activities yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">System Statistics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Active Users</span>
                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $this->activeUsers }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Available Roles</span>
                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $this->totalRoles }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Registered Permissions</span>
                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $this->totalPermissions }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">New Users (7 days)</span>
                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $this->newUsersThisWeek }}</span>
                </div>
                <hr class="border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Activities Today</span>
                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $this->activitiesToday }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Activities (7 days)</span>
                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $this->activitiesThisWeek }}</span>
                </div>
            </div>
        </div>
    </div>
    @endrole
</div>
