<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>FreelancerSolutions</title>

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            200: '#fbcfe8',
                            300: '#f9a8d4',
                            400: '#f472b6',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                            800: '#9d174d',
                            900: '#831843',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">

    {{-- Inclui a navegação principal --}}
    @include('layouts.navigation')

    <!-- Conteúdo Principal -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <section class="bg-white rounded-2xl shadow-lg p-10 max-w-7xl mx-auto">
            <header class="mb-12 text-center">
                <h1 class="text-4xl font-extrabold text-primary-600 mb-4">Bem-vindo à FreelancerSolutions</h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Conectamos talentos e oportunidades de forma simples, rápida e segura. Descubra como podemos ajudar você a alcançar seus objetivos profissionais.
                </p>
            </header>

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Card 1 -->
                <div class="p-6 border border-gray-200 rounded-xl hover:shadow-lg transition shadow-sm bg-gradient-to-br from-primary-50 to-white">
                    <h2 class="text-xl font-semibold mb-3 text-primary-700">Para Freelancers</h2>
                    <p class="text-gray-700">
                        Encontre projetos que se encaixam com suas habilidades, trabalhe de onde quiser e receba pagamentos com segurança.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="p-6 border border-gray-200 rounded-xl hover:shadow-lg transition shadow-sm bg-gradient-to-br from-secondary-50 to-white">
                    <h2 class="text-xl font-semibold mb-3 text-secondary-700">Para Clientes</h2>
                    <p class="text-gray-700">
                        Contrate profissionais qualificados para seus projetos, acompanhe o progresso e garanta a entrega no prazo.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="p-6 border border-gray-200 rounded-xl hover:shadow-lg transition shadow-sm bg-gradient-to-br from-primary-50 to-white">
                    <h2 class="text-xl font-semibold mb-3 text-primary-700">Nossa Missão</h2>
                    <p class="text-gray-700">
                        Facilitar conexões verdadeiras entre talentos e oportunidades, valorizando a qualidade, confiança e crescimento mútuo.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-900 text-gray-300 py-12 mt-16">
        <div class="container mx-auto px-4 grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4 text-white">FreelancerSolutions</h3>
                <p>
                    Conectando talentos e oportunidades desde 2023.<br />
                    <span class="text-sm text-gray-400">Seu sucesso é o nosso compromisso.</span>
                </p>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-white">Links Rápidos</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-primary-400 transition">Início</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition">Como Funciona</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition">Termos de Serviço</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition">Política de Privacidade</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-white">Contato</h4>
                <ul class="space-y-2">
                    <li>📧 contato@freelancersolutions.com</li>
                    <li>📞 +55 (11) 99999-9999</li>
                    <li>📍 São Paulo, Brasil</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-white">Redes Sociais</h4>
                <div class="flex space-x-5 text-gray-400">
                    <a href="#" class="hover:text-primary-400 transition" aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                fill-rule="evenodd"
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                    <a href="#" class="hover:text-primary-400 transition" aria-label="Twitter">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"
                            />
                        </svg>
                    </a>
                    <a href="#" class="hover:text-primary-400 transition" aria-label="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 3.807.058h.468c2.456 0 2.784-.011 3.807-.058.975-.045 1.504-.207 1.857-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-3.807v-.468c0-2.456-.011-2.784-.058-3.807-.045-.975-.207-1.504-.344-1.857a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-500 text-sm select-none">
            &copy; 2023 FreelancerSolutions. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
