<?php 
$layout = 'layout';
$title = $title ?? '关于我们';
?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6"><?= $title ?></h1>
        <div class="prose prose-lg">
            <p class="text-gray-600 mb-4">
                <?= $content ?? '这是一个现代化的PHP博客CMS，注重性能和可扩展性。' ?>
            </p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">我们的技术栈</h2>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>PHP 8.1+ 现代特性</li>
                <li>Composer 依赖管理</li>
                <li>MySQL 数据库与 PDO</li>
                <li>TailwindCSS 样式设计</li>
                <li>PSR-4 自动加载</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">功能特性</h2>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>高性能路由</li>
                <li>安全认证系统</li>
                <li>数据库抽象层</li>
                <li>现代MVC架构</li>
                <li>可扩展设计</li>
            </ul>
        </div>
    </div>
</div>