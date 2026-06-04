<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ade Afwa Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-ade-afwa { background-color: #FDF9F0; }
        .text-ade-afwa-gold { color: #CFB53B; }
        .font-serif-ade { font-family: 'Playfair Display', serif; }
        
        .ade-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #E5E7EB; /* Abu-abu sesuai gambar kamu */
            border: 1px solid #CFB53B; /* Border emas */
            outline: none;
            transition: all 0.2s;
        }
        .ade-input:focus {
            background-color: #FFFFFF;
            box-shadow: 0 0 0 2px rgba(207, 181, 59, 0.3);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-ade-afwa text-gray-900 font-sans antialiased min-h-screen flex flex-col">

    <header class="p-4 flex items-center justify-between bg-white/50 backdrop-blur-sm">
        <a href="/" class="text-sm text-ade-afwa-gold hover:text-gray-900 transition font-medium">← Kembali ke Toko</a>
    </header>

    <div class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Logo Ade Afwa" class="h-20 mx-auto mb-4">
                <div class="h-px bg-ade-afwa-gold w-full opacity-20 mb-8"></div>
            </div>

            <div class="bg-white rounded-lg shadow-2xl p-8 border border-gray-100">
                <h1 class="text-3xl font-serif-ade font-bold text-center text-gray-800 mb-2 uppercase tracking-widest">Login</h1>
                <p class="text-center text-gray-500 text-sm mb-8">Please enter your e-mail and password:</p>

                @if ($errors->any())
                    <div class="mb-4 text-red-600 text-sm text-center font-medium">
                        Email atau password salah. Silakan coba lagi.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="email" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Email</label>
                            <input type="email" id="email" name="email" required autofocus 
                                class="ade-input text-sm">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label for="password" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[10px] text-gray-400 hover:text-ade-afwa-gold underline uppercase">Forgot Password?</a>
                                @endif
                            </div>
                            <input type="password" id="password" name="password" required 
                                class="ade-input text-sm">
                        </div>

                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-ade-afwa-gold focus:ring-ade-afwa-gold">
                            <span class="ml-2 text-xs text-gray-500">Ingat saya</span>
                        </div>

                        <button type="submit" class="w-full bg-[#D4AF37] text-white py-3 font-bold uppercase tracking-widest hover:bg-yellow-700 transition shadow-md">
                            Login
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-widest">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-bold text-gray-800 hover:text-ade-afwa-gold underline ml-1">Create One</a>
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