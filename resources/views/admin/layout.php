<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '管理面板' ?> - 现代PHP博客</title>
    <link href="https://cdn.staticfile.org/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <div class="flex flex-1">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white hidden md:block">
            <div class="p-4">
                <h1 class="text-2xl font-bold">管理面板</h1>
            </div>
            <nav class="mt-6">
                <a href="/admin" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= ($_SERVER['REQUEST_URI'] === '/admin' || $_SERVER['REQUEST_URI'] === '/admin/') ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> 仪表盘
                </a>
                <a href="/admin/posts" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/posts') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-file-alt mr-2"></i> 文章
                </a>
                <a href="/admin/categories" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-folder mr-2"></i> 分类
                </a>
                <a href="/admin/tags" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/tags') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-tag mr-2"></i> 标签
                </a>
                <a href="/admin/users" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-users mr-2"></i> 用户
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center p-4">
                    <div>
                        <button id="sidebarToggle" class="text-gray-500 focus:outline-none md:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button class="flex items-center text-gray-700 focus:outline-none">
                                <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" alt="管理员">
                                <span class="mx-2 text-sm">管理员</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 bg-gray-100">
                <?= $content ?? '' ?>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t">
                <div class="max-w-6xl mx-auto px-4 py-6">
                    <p class="text-center text-gray-500 text-sm">© 2025 现代PHP博客管理面板. 保留所有权利.</p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile sidebar -->
    <div id="mobile-sidebar" class="fixed inset-0 z-50 hidden md:hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative flex-1 flex flex-col w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">管理面板</h1>
            </div>
            <nav class="mt-6 flex-1">
                <a href="/admin" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= ($_SERVER['REQUEST_URI'] === '/admin' || $_SERVER['REQUEST_URI'] === '/admin/') ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> 仪表盘
                </a>
                <a href="/admin/posts" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/posts') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-file-alt mr-2"></i> 文章
                </a>
                <a href="/admin/categories" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-folder mr-2"></i> 分类
                </a>
                <a href="/admin/tags" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/tags') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-tag mr-2"></i> 标签
                </a>
                <a href="/admin/users" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0 ? 'bg-gray-700 text-white' : '' ?>">
                    <i class="fas fa-users mr-2"></i> 用户
                </a>
            </nav>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.toggle('hidden');
        });

        // Close mobile sidebar when clicking on overlay
        document.querySelector('#mobile-sidebar > div:first-child').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.add('hidden');
        });
    </script>
</body>
</html>