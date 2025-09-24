<?php 
$layout = 'admin/layout';
$title = '仪表盘';
?>
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">仪表盘</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">文章</p>
                    <p class="text-2xl font-bold"><?= $postCount ?? 0 ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                    <i class="fas fa-folder text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">分类</p>
                    <p class="text-2xl font-bold"><?= $categoryCount ?? 0 ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                    <i class="fas fa-tag text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">标签</p>
                    <p class="text-2xl font-bold"><?= $tagCount ?? 0 ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">用户</p>
                    <p class="text-2xl font-bold"><?= $userCount ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">最近活动</h2>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="p-2 bg-gray-100 rounded-full mr-3">
                    <i class="fas fa-plus text-gray-500"></i>
                </div>
                <div>
                    <p class="font-medium">创建了新文章</p>
                    <p class="text-gray-500 text-sm">2小时前</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="p-2 bg-gray-100 rounded-full mr-3">
                    <i class="fas fa-user-plus text-gray-500"></i>
                </div>
                <div>
                    <p class="font-medium">新用户注册</p>
                    <p class="text-gray-500 text-sm">5小时前</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="p-2 bg-gray-100 rounded-full mr-3">
                    <i class="fas fa-comment text-gray-500"></i>
                </div>
                <div>
                    <p class="font-medium">收到新评论</p>
                    <p class="text-gray-500 text-sm">1天前</p>
                </div>
            </div>
        </div>
    </div>
</div>