<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login - Multidaya Inti Persada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-image: url('https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10, 25, 47, 0.85) 0%, rgba(15, 35, 60, 0.9) 50%, rgba(20, 45, 70, 0.85) 100%);
            z-index: 0;
        }

        .relative-container {
            position: relative;
            z-index: 1;
        }

        .glass-card {
            background: rgba(15, 35, 60, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(56, 189, 248, 0.2);
        }

        .input-field {
            background: rgba(30, 58, 80, 0.5);
            border: 1px solid rgba(56, 189, 248, 0.2);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: rgba(56, 189, 248, 0.6);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f2b3d 100%);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e3a5f 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.3);
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>

<body>
    <div class="relative-container min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-[420px]">
            <!-- Logo dan Brand - Lebih compact -->
            <div class="text-center mb-6">
                <div
                    class="inline-flex items-center justify-center bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-2xl p-3 mb-3 border border-blue-400/30">
                    <i class="fas fa-chart-line text-blue-400 text-3xl"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-1 tracking-tight">Multidaya</h1>
                <p class="text-blue-300 text-sm font-medium">Inti Persada</p>
                <p class="text-white/50 text-xs mt-3">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Form Login - Card lebih kecil -->
            <div class="glass-card rounded-2xl shadow-2xl p-6">
                @if ($errors->any())
                    <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-3 mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-red-400 text-sm"></i>
                            <p class="text-xs text-red-100">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Username Field -->
                    <div class="mb-4">
                        <label class="block text-blue-200 text-xs font-semibold mb-1.5 uppercase tracking-wider">
                            <i class="fas fa-user mr-1 text-[10px]"></i> Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-circle text-blue-400/60 text-sm"></i>
                            </div>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                                class="input-field w-full pl-10 pr-3 py-2.5 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/30 transition text-sm"
                                placeholder="Masukkan username">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-5">
                        <label class="block text-blue-200 text-xs font-semibold mb-1.5 uppercase tracking-wider">
                            <i class="fas fa-lock mr-1 text-[10px]"></i> Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-blue-400/60 text-sm"></i>
                            </div>
                            <input type="password" name="password" required
                                class="input-field w-full pl-10 pr-3 py-2.5 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/30 transition text-sm"
                                placeholder="Masukkan password">
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="rounded border-blue-400/30 bg-white/10 text-blue-500 focus:ring-blue-500/50 w-3.5 h-3.5">
                            <span class="ml-2 text-xs text-white/70">Ingat saya</span>
                        </label>
                        <a href="#" class="text-xs text-blue-400/70 hover:text-blue-300 transition">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="btn-login w-full text-white font-bold py-2.5 rounded-xl transition-all duration-200 shadow-lg flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-sign-in-alt text-xs"></i>
                        <span>Login</span>
                    </button>
                </form>

                <!-- Informasi Demo - Lebih compact -->
                <div class="mt-5 pt-4 border-t border-blue-500/20">
                    <div class="bg-black/20 rounded-xl p-3">
                        <p class="text-white/50 text-[10px] text-center mb-1.5 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-1"></i> Demo Credentials
                        </p>
                        <div class="text-white/50 text-[10px] text-center space-y-0.5">
                            <p>Username: <span
                                    class="text-blue-300 font-mono bg-black/30 px-2 py-0.5 rounded text-[10px]">admin</span>
                            </p>
                            <p>Password: <span
                                    class="text-blue-300 font-mono bg-black/30 px-2 py-0.5 rounded text-[10px]">password123</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer - Lebih minimalis -->
            <div class="text-center mt-6">
                <p class="text-white/30 text-[10px]">
                    <i class="fas fa-copyright"></i> 2024 Multidaya Inti Persada. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
