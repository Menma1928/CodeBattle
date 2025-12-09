<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            html {
                scroll-behavior: smooth;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }

            .float-animation {
                animation: float 3s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-purple-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-900 dark:to-indigo-950">
        @include('layouts.navigation')

        <!-- Hero Section -->
        <section class="min-h-screen flex flex-col justify-center items-center px-4 py-12">
            <div class="max-w-6xl mx-auto text-center">
                <div class="mb-8 float-animation">
                    <img src="/logo.png" alt="CodeBattle Logo" class="w-32 h-32 mx-auto rounded-full shadow-xl border-4 border-white dark:border-gray-700 bg-white object-contain" />
                </div>

                <h1 class="text-5xl md:text-6xl font-bold mb-4">
                    <span class="bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">CodeBattle</span>
                </h1>

                <p class="text-xl md:text-2xl text-gray-700 dark:text-gray-300 mb-8 font-medium">
                    Donde la competencia impulsa la excelencia
                </p>

                <p class="text-lg text-gray-600 dark:text-gray-400 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Plataforma innovadora de competencias de programación que conecta equipos, proyectos y talento.
                    Participa en eventos, demuestra tus habilidades y compite con los mejores desarrolladores.
                </p>

                @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-gradient-to-r hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Comenzar Ahora
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Iniciar Sesión
                    </a>
                </div>
                @else
                <div class="flex justify-center mb-12">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-gradient-to-r hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Ir al Dashboard
                    </a>
                </div>
                @endguest

                <div class="text-gray-600 dark:text-gray-400 text-sm">
                    <a href="#features" class="inline-block animate-bounce">
                        <svg class="w-8 h-8 mx-auto text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        ¿Qué ofrecemos?
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        Una plataforma completa para organizar y participar en competencias de programación
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Eventos</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Crea y participa en eventos de programación con reglas personalizadas y fechas flexibles
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Equipos</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Forma equipos colaborativos, gestiona miembros y trabaja en proyectos conjuntos
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Proyectos</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Presenta tus proyectos, recibe retroalimentación y compite por los primeros lugares
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sistema de Jurados</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Evaluación profesional con jurados calificados y criterios objetivos de calificación
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-800/50 dark:to-indigo-950/50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Estadísticas de la Plataforma
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Stat 1 -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm border border-purple-100 dark:border-gray-700 p-8 text-center">
                        <div class="text-5xl font-extrabold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent mb-2">250+</div>
                        <div class="text-gray-900 dark:text-white font-semibold text-lg">Desarrolladores</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm mt-2">Participantes activos</div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm border border-purple-100 dark:border-gray-700 p-8 text-center">
                        <div class="text-5xl font-extrabold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent mb-2">50+</div>
                        <div class="text-gray-900 dark:text-white font-semibold text-lg">Eventos Realizados</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm mt-2">Competencias completadas</div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm border border-purple-100 dark:border-gray-700 p-8 text-center">
                        <div class="text-5xl font-extrabold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent mb-2">100+</div>
                        <div class="text-gray-900 dark:text-white font-semibold text-lg">Proyectos Presentados</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm mt-2">Soluciones innovadoras</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-700 dark:to-indigo-700 rounded-2xl shadow-xl p-12 text-center">
                    @guest
                    <h2 class="text-4xl font-bold text-white mb-6">
                        ¿Listo para el desafío?
                    </h2>
                    <p class="text-xl text-purple-100 dark:text-purple-200 mb-8">
                        Únete a nuestra comunidad de desarrolladores y demuestra tu talento
                    </p>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-10 py-4 bg-white dark:bg-gray-100 border border-transparent rounded-lg font-semibold text-purple-600 dark:text-purple-700 uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-purple-600 transition ease-in-out duration-150">
                        Registrarse Gratis
                    </a>
                    @else
                    <h2 class="text-4xl font-bold text-white mb-6">
                        ¡Bienvenido de vuelta, {{ Auth::user()->name }}!
                    </h2>
                    <p class="text-xl text-purple-100 dark:text-purple-200 mb-8">
                        Continúa explorando eventos y desafíos en tu dashboard
                    </p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-10 py-4 bg-white dark:bg-gray-100 border border-transparent rounded-lg font-semibold text-purple-600 dark:text-purple-700 uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-purple-600 transition ease-in-out duration-150">
                        Ir al Dashboard
                    </a>
                    @endguest
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-gray-950 text-white py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- Column 1 -->
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold">CodeBattle</span>
                        </div>
                        <p class="text-gray-400 dark:text-gray-500 text-sm">
                            La plataforma líder en competencias de programación. Conectando talento con oportunidades.
                        </p>
                    </div>

                    <!-- Column 2 -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Enlaces Rápidos</h3>
                        <ul class="space-y-2 text-gray-400 dark:text-gray-500 text-sm">
                            <li><a href="#features" class="hover:text-white dark:hover:text-gray-300 transition">Características</a></li>
                            @guest
                            <li><a href="{{ route('login') }}" class="hover:text-white dark:hover:text-gray-300 transition">Iniciar Sesión</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white dark:hover:text-gray-300 transition">Registrarse</a></li>
                            @else
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white dark:hover:text-gray-300 transition">Dashboard</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="hover:text-white dark:hover:text-gray-300 transition">Mi Perfil</a></li>
                            @endguest
                        </ul>
                    </div>

                    <!-- Column 3 -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Síguenos</h3>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 bg-gray-800 dark:bg-gray-800/50 rounded-full flex items-center justify-center hover:bg-purple-600 dark:hover:bg-purple-600 transition duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 dark:bg-gray-800/50 rounded-full flex items-center justify-center hover:bg-purple-600 dark:hover:bg-purple-600 transition duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 dark:bg-gray-800/50 rounded-full flex items-center justify-center hover:bg-purple-600 dark:hover:bg-purple-600 transition duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 dark:bg-gray-800/50 rounded-full flex items-center justify-center hover:bg-purple-600 dark:hover:bg-purple-600 transition duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-800 dark:border-gray-800/50 pt-8 text-center text-gray-400 dark:text-gray-500 text-sm">
                    <p>&copy; 2025 CodeBattle. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
