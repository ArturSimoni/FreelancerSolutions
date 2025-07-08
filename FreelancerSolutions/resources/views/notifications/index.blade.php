<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Minhas Notificações') }} 🔔
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 dark:bg-green-900 p-4 text-sm text-green-700 dark:text-green-200" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">


                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Notificações
                    </h4>
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Marcar todas como lidas
                            </button>
                        </form>
                    @endif
                </div>


                <div>
                    @if ($notifications->isEmpty())
                        <p class="p-8 text-center text-gray-500 dark:text-gray-400">
                            Você não tem notificações no momento.
                        </p>
                    @else
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($notifications as $notification)
                                <div class="p-4 flex items-center gap-4 transition-colors duration-200 {{ !$notification->read_at ? 'bg-primary-50 dark:bg-gray-800/50' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">


                                    <div class="flex-shrink-0">
                                        @unless ($notification->read_at)
                                            <span class="h-2.5 w-2.5 bg-primary-500 rounded-full block" title="Não lida"></span>
                                        @else
                                            <span class="h-2.5 w-2.5 bg-gray-300 dark:bg-gray-600 rounded-full block" title="Lida"></span>
                                        @endunless
                                    </div>


                                    <div class="flex-grow">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            @if (isset($notification->data['link']))
                                                <a href="{{ route('notifications.markAsReadAndRedirect', $notification->id) }}" class="hover:underline focus:outline-none focus:ring-2 focus:ring-primary-500 rounded">
                                                    {{ $notification->data['message'] }}
                                                </a>
                                            @else
                                                {{ $notification->data['message'] }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>


                                    <div class="flex-shrink-0">
                                        @unless ($notification->read_at)
                                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-primary-500" title="Marcar como lida">
                                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        @if ($notifications->hasPages())
                           <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
