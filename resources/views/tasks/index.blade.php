<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Bienvenue, {{ Auth::user()->name }} !
                </h2>
                <p class="mt-1 text-sm text-gray-500">Gerez vos taches facilement</p>
            </div>
            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-gray-800 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nouvelle Tache
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Flash message --}}
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabs --}}
            <div x-data="{ tab: 'all' }" class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex gap-1 rounded-lg bg-gray-100 p-1">
                        <button @click="tab = 'all'"
                                :class="tab === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="rounded-md px-4 py-2 text-sm font-medium transition">
                            Toutes
                        </button>
                        <button @click="tab = 'completed'"
                                :class="tab === 'completed' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="rounded-md px-4 py-2 text-sm font-medium transition">
                            Terminees
                        </button>
                        <button @click="tab = 'pending'"
                                :class="tab === 'pending' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="rounded-md px-4 py-2 text-sm font-medium transition">
                            En attente
                        </button>
                    </div>
                    <p class="text-sm text-gray-500">{{ $tasks->count() }} tache(s)</p>
                </div>

                {{-- Task grid --}}
                @if($tasks->isEmpty())
                    <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucune tache</h3>
                        <p class="mt-2 text-sm text-gray-500">Commencez par creer votre premiere tache.</p>
                        <a href="{{ route('tasks.create') }}"
                           class="mt-6 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800 transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Creer une tache
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach($tasks as $task)
                            <div x-show="tab === 'all' || (tab === 'completed' && {{ $task->is_completed ? 'true' : 'false' }}) || (tab === 'pending' && !{{ $task->is_completed ? 'true' : 'false' }})"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-all">

                                {{-- Header: title + status dot --}}
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 {{ $task->is_completed ? 'line-through opacity-60' : '' }}">
                                            {{ $task->title }}
                                        </h3>
                                        <p class="mt-0.5 text-xs text-gray-400">
                                            Tache #{{ $task->id }}
                                            &middot;
                                            {{ $task->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="ml-3 mt-1.5 h-3 w-3 flex-shrink-0 rounded-full {{ $task->is_completed ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                                </div>

                                {{-- Description --}}
                                @if($task->description)
                                    <p class="mt-3 text-sm leading-relaxed text-gray-500">
                                        {{ Str::limit($task->description, 120) }}
                                    </p>
                                @endif

                                {{-- Actions --}}
                                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                        @csrf
                                        @if($task->is_completed)
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3.5 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Terminee
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3.5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="9"/>
                                                </svg>
                                                Marquer terminee
                                            </button>
                                        @endif
                                    </form>

                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                        <a href="{{ route('tasks.edit', $task) }}"
                                           class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                              onsubmit="return confirm('Supprimer cette tache ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
