<nav x-data="{ open: false }" class="bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-logo class="block h-10 w-auto fill-current text-indigo-950" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Sengaja dikosongkan untuk estetika butik --}}
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    {{-- TAMPILAN JIKA SUDAH LOGIN --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-xl text-gray-500 bg-gray-50 hover:text-indigo-950 focus:outline-none transition ease-in-out duration-150">
                                <div class="flex flex-col items-end me-2">
                                    <span class="text-[9px] font-black text-[#CFB53B] uppercase tracking-[0.2em] leading-none mb-1">Customer</span>
                                    <span class="text-sm font-bold text-indigo-950 tracking-tight">{{ Auth::user()->name }}</span>
                                </div>

                                <div class="ms-1 text-[#CFB53B]">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="font-bold text-xs uppercase tracking-widest text-gray-600">
                                {{ __('Edit Profil') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link href="/orders" class="font-bold text-xs uppercase tracking-widest text-gray-600">
                                {{ __('Pesanan Saya') }}
                            </x-dropdown-link>

                            <hr class="border-gray-100 my-1">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="font-bold text-xs uppercase tracking-widest text-red-500"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- TAMPILAN JIKA GUEST (BELUM LOGIN) --}}
                    <div class="flex items-center gap-6">
                        <a href="{{ route('login') }}" class="text-[11px] font-black text-indigo-950 uppercase tracking-[0.2em] hover:text-[#CFB53B] transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-[11px] font-black bg-indigo-950 text-white px-6 py-3 rounded-full uppercase tracking-[0.2em] hover:bg-[#CFB53B] transition-all shadow-lg active:scale-95">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-50">
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4 mb-4">
                    <div class="font-black text-indigo-950 uppercase tracking-tighter italic text-lg">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="/orders">
                        {{ __('Pesanan Saya') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="p-4 space-y-3">
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 text-sm font-black uppercase tracking-widest border border-indigo-950 text-indigo-950 rounded-xl">Masuk</a>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 text-sm font-black uppercase tracking-widest bg-indigo-950 text-white rounded-xl shadow-md">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</nav>