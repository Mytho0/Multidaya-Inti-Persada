<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login - Multidaya Inti Persada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="font-['Inter']">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <!-- Logo dan Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-2xl p-4 mb-4">
                    <i class="fas fa-chart-line text-white text-4xl"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-white mb-2">Multidaya</h1>
                <p class="text-white/80 text-lg">Inti Persada</p>
                <p class="text-white/60 text-sm mt-4">Silakan login untuk melanjutkan</p>
            </div>
            
            <!-- Form Login -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 border border-white/20">
                @if ($errors->any())
                    <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                            <p class="text-sm text-red-100">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Username Field -->
                    <div class="mb-6">
                        <label class="block text-white text-sm font-semibold mb-2">
                            <i class="fas fa-user mr-2"></i> Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-circle text-white/60"></i>
                            </div>
                            <input type="text" name="username" value="{{ old('username') }}" required 
                                   autofocus
                                   class="w-full pl-10 pr-3 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition"
                                   placeholder="Masukkan username">
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="mb-6">
                        <label class="block text-white text-sm font-semibold mb-2">
                            <i class="fas fa-lock mr-2"></i> Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-white/60"></i>
                            </div>
                            <input type="password" name="password" required 
                                   class="w-full pl-10 pr-3 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition"
                                   placeholder="Masukkan password">
                        </div>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-8">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-white/30 bg-white/20 text-white focus:ring-white/50">
                            <span class="ml-2 text-sm text-white/80">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-white/80 hover:text-white transition">
                            Lupa password?
                        </a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-white text-purple-600 font-bold py-3 px-4 rounded-xl hover:bg-white/90 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </form>
                
                <!-- Informasi Demo -->
                <div class="mt-8 pt-6 border-t border-white/20">
                    <div class="bg-black/20 rounded-lg p-4">
                        <p class="text-white/70 text-xs text-center mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Demo Credentials
                        </p>
                        <div class="text-white/60 text-xs text-center space-y-1">
                            <p>Username: <span class="text-white font-mono bg-black/30 px-2 py-1 rounded">admin</span></p>
                            <p>Password: <span class="text-white font-mono bg-black/30 px-2 py-1 rounded">password123</span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-white/50 text-xs">
                    <i class="fas fa-copyright"></i> 2024 Multidaya Inti Persada. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>