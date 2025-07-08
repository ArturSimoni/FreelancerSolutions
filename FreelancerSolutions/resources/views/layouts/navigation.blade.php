<nav class="bg-white border-b border-gray-200 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">

      <!-- Logo -->
      <div class="flex-shrink-0 flex items-center">
        <a href="{{ route('dashboard') }}">
          <img class="block h-10 w-auto" src="{{ asset('logo.svg') }}" alt="FreelancerSolutions" />
        </a>
      </div>

      <!-- Links principais (desktop) -->
      <div class="hidden sm:flex sm:space-x-10 sm:ml-10">
        <x-nav-link
          :href="route('dashboard')"
          :active="request()->routeIs('dashboard')"
          class="text-gray-700 hover:text-primary-600 transition font-semibold"
        >
          {{ __('Dashboard') }}
        </x-nav-link>

        @auth
          {{-- Usando os métodos isCliente(), isFreelancer() do modelo User --}}
          @if(auth()->user()->isCliente())
            <x-nav-link
              :href="route('projetos.index')" {{-- Link para a lista de projetos do cliente --}}
              :active="request()->routeIs('projetos.*')"
              class="text-gray-700 hover:text-primary-600 transition font-semibold"
            >
              {{ __('Meus Projetos') }}
            </x-nav-link>
            <x-nav-link
              :href="route('projetos.create')" {{-- Link para criar novo projeto --}}
              :active="request()->routeIs('projetos.create')"
              class="text-gray-700 hover:text-primary-600 transition font-semibold"
            >
              {{ __('Publicar Projeto') }}
            </x-nav-link>
          @endif

          @if(auth()->user()->isFreelancer())
            <x-nav-link
              :href="route('projetos.index')" {{-- Link para ver projetos disponíveis --}}
              :active="request()->routeIs('projetos.*')"
              class="text-gray-700 hover:text-primary-600 transition font-semibold"
            >
              {{ __('Projetos Disponíveis') }}
            </x-nav-link>
            <x-nav-link
              :href="route('freelancer.profile.edit')" {{-- Link para editar perfil do freelancer --}}
              :active="request()->routeIs('freelancer.profile.edit')"
              class="text-gray-700 hover:text-primary-600 transition font-semibold"
            >
              {{ __('Meu Perfil') }}
            </x-nav-link>
          @endif

          @if(auth()->user()->isAdministrador())
            <x-nav-link
              :href="route('admin.dashboard')"
              :active="request()->routeIs('admin.*')"
              class="text-gray-700 hover:text-primary-600 transition font-semibold"
            >
              {{ __('Área do Administrador') }}
            </x-nav-link>
          @endif
        @endauth
      </div>

      <!-- Área de login/usuário (desktop) -->
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        @auth
          {{-- Link de Notificações --}}
          <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary me-3 position-relative">
              Notificações
              @php
                  $unreadNotificationsCount = Auth::user()->unreadNotifications->count();
              @endphp
              @if ($unreadNotificationsCount > 0)
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      {{ $unreadNotificationsCount }}
                      <span class="visually-hidden">unread messages</span>
                  </span>
              @endif
          </a>

          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button
                class="flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 transition focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
              >
                <div>{{ Auth::user()->name }}</div>
                <svg
                  class="ml-1 h-4 w-4 text-gray-400"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </x-slot>

            <x-slot name="content">
              <!-- Profile -->
              <x-dropdown-link :href="route('profile.edit')" class="hover:text-primary-600">
                  {{ __('Perfil') }}
              </x-dropdown-link>

              <!-- Logout -->
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link
                  :href="route('logout')"
                  onclick="event.preventDefault(); this.closest('form').submit();"
                  class="hover:text-primary-600"
                >
                  {{ __('Sair') }}
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        @else
          <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-primary-600 transition">
            Entrar
          </a>
          {{-- Links para registro de cliente e freelancer --}}
          <a href="{{ route('register.cliente') }}" class="ml-6 text-sm font-semibold text-gray-700 hover:text-primary-600 transition">
            Registrar como Cliente
          </a>
          <a href="{{ route('register.freelancer') }}" class="ml-6 text-sm font-semibold text-gray-700 hover:text-primary-600 transition">
            Registrar como Freelancer
          </a>
        @endauth
      </div>

      <!-- Botão Mobile -->
      <div class="-mr-2 flex items-center sm:hidden">
        <button
          @click="open = !open"
          class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-primary-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"
          aria-expanded="false"
          aria-label="Toggle menu"
        >
          <svg
            class="h-6 w-6"
            stroke="currentColor"
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              :class="{'hidden': open, 'inline-flex': !open }"
              class="inline-flex"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
            <path
              :class="{'hidden': !open, 'inline-flex': open }"
              class="hidden"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
          <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
              {{ __('Dashboard') }}
          </x-responsive-nav-link>
          @auth
              @if(auth()->user()->isCliente())
                  <x-responsive-nav-link :href="route('projetos.index')" :active="request()->routeIs('projetos.index')">
                      {{ __('Meus Projetos') }}
                  </x-responsive-nav-link>
                  <x-responsive-nav-link :href="route('projetos.create')" :active="request()->routeIs('projetos.create')">
                      {{ __('Publicar Projeto') }}
                  </x-responsive-nav-link>
              @endif
              @if(auth()->user()->isFreelancer())
                  <x-responsive-nav-link :href="route('projetos.index')" :active="request()->routeIs('projetos.index')">
                      {{ __('Projetos Disponíveis') }}
                  </x-responsive-nav-link>
                  <x-responsive-nav-link :href="route('freelancer.profile.edit')" :active="request()->routeIs('freelancer.profile.edit')">
                      {{ __('Meu Perfil') }}
                  </x-responsive-nav-link>
              @endif
              @if(auth()->user()->isAdministrador())
                  <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                      {{ __('Área do Administrador') }}
                  </x-responsive-nav-link>
              @endif
              <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')">
                  {{ __('Notificações') }}
                  @if ($unreadNotificationsCount > 0)
                      <span class="ml-2 badge rounded-pill bg-danger">{{ $unreadNotificationsCount }}</span>
                  @endif
              </x-responsive-nav-link>
          @endauth
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200">
          @auth
              <div class="px-4">
                  <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                  <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
              </div>

              <div class="mt-3 space-y-1">
                  <x-responsive-nav-link :href="route('profile.edit')">
                      {{ __('Perfil') }}
                  </x-responsive-nav-link>

                  <!-- Authentication -->
                  <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <x-responsive-nav-link :href="route('logout')"
                              onclick="event.preventDefault();
                                          this.closest('form').submit();">
                          {{ __('Sair') }}
                      </x-responsive-nav-link>
                  </form>
              </div>
          @else
              <div class="mt-3 space-y-1">
                  <x-responsive-nav-link :href="route('login')">
                      {{ __('Entrar') }}
                  </x-responsive-nav-link>
                  <x-responsive-nav-link :href="route('register.cliente')">
                      {{ __('Registrar como Cliente') }}
                  </x-responsive-nav-link>
                  <x-responsive-nav-link :href="route('register.freelancer')">
                      {{ __('Registrar como Freelancer') }}
                  </x-responsive-nav-link>
              </div>
          @endauth
      </div>
  </div>
</nav>
