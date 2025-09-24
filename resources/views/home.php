<?php 
$layout = 'layout';
$title = $title ?? '欢迎来到我们的博客';
?>
<div class="max-w-4xl mx-auto">
    <div class="text-center py-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4"><?= $title ?></h1>
        <p class="text-xl text-gray-600 mb-8"><?= $message ?? '发现精彩内容' ?></p>
        <a href="/posts" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full text-lg transition duration-300">
            查看文章
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">高性能</h2>
            <p class="text-gray-600">采用现代PHP实践构建，实现最佳性能和可扩展性。</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">现代设计</h2>
            <p class="text-gray-600">响应式设计，在所有设备上都表现出色。</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">易于使用</h2>
            <p class="text-gray-600">直观的界面，管理员和访问者都易于使用。</p>
        </div>
    </div>
</div>