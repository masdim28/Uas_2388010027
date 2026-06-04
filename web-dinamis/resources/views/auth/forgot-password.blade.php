<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recover Password - Ade Afwa Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-ade-afwa { background-color: #eafdfe; }
        .text-ade-afwa-gold { color: #CFB53B; }
        .font-serif-ade { font-family: 'Playfair Display', serif; }
        
        .ade-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #efe9c8; /* Abu-abu sesuai desain Anda */
            border: 1px solid #efe684; /* Border emas */
            outline: none;
            transition: all 0.2s;
        }
        .ade-input:focus {
            background-color: #c4b47b;
            box-shadow: 0 0 0 2px rgba(207, 181, 59, 0.3);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-ade-afwa text-gray-900 font-sans antialiased min-h-screen flex flex-col justify-between">

    <header class="p-6 flex items-center justify-between border-b border-gray-100" style="background-color: #FAF8F5;">
        <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-[#CFB53B] border border-transparent rounded-sm font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Toko
        </a>
        <div class="flex-1 flex justify-center mr-28">
            <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Ade Afwa Boutique" class="h-16 object-contain">
        </div>
        <div></div> </header>

    <div class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">

            <div class="bg-white rounded-lg shadow-2xl p-8 border border-gray-100">
                <h1 class="text-3xl font-serif-ade font-bold text-center text-gray-800 mb-2 uppercase tracking-widest">RECOVER PASSWORD</h1>
                <p class="text-center text-gray-500 text-sm mb-8">Please enter your email:</p>

                @if (session('status'))
                    <div class="mb-4 text-green-600 text-sm text-center font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="email" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Email</label>
                            <input type="email" id="email" name="email" :value="old('email')" required autofocus 
                                class="ade-input text-sm">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs" />
                        </div>

                        <button type="submit" class="w-full bg-[#D4AF37] text-white py-3 font-bold uppercase tracking-widest hover:bg-yellow-700 transition shadow-md">
                            {{ __('RECOVER') }}
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-widest">
                        Remember your password? 
                        <a href="{{ route('login') }}" class="font-bold text-gray-800 hover:text-ade-afwa-gold underline ml-1">Back to login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <footer class="p-6 text-center text-[10px] text-gray-400 uppercase tracking-[0.2em]">
        &copy; {{ date('Y') }} Ade Afwa Boutique
    </footer>

</body>
</html>