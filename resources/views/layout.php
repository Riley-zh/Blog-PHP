<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '现代PHP博客' ?></title>
    <link href="https://cdn.staticfile.org/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex space-x-7">
                    <div>
                        <a href="/" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-500 text-lg">现代PHP博客</span>
                        </a>
                    </div>
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="/" class="py-4 px-2 text-green-500 border-b-2 border-green-500 font-semibold">首页</a>
                        <a href="/about" class="py-4 px-2 text-gray-500 font-semibold hover:text-green-500 transition duration-300">关于我们</a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="/login" class="py-2 px-4 text-gray-700 hover:text-green-500 transition duration-300">登录</a>
                    <a href="/register" class="py-2 px-4 bg-green-500 hover:bg-green-600 text-white rounded transition duration-300">注册</a>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-green-500 bg-gray-50">首页</a>
                <a href="/about" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-500 hover:bg-gray-50">关于我们</a>
                <a href="/login" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-500 hover:bg-gray-50">登录</a>
                <a href="/register" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-500 hover:bg-gray-50">注册</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto flex-grow px-4 py-8">
        <?= $content ?? '' ?>
    </main>

    <footer class="bg-white border-t mt-auto">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <p class="text-center text-gray-500">© 2025 现代PHP博客. 保留所有权利.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>