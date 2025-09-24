<?php 
$layout = 'admin/layout';
$title = '创建文章';
?>
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">创建文章</h1>
        <a href="/admin/posts" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> 返回文章列表
        </a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="/admin/posts" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">标题</label>
                <input type="text" name="title" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label for="featured_image" class="block text-gray-700 text-sm font-bold mb-2">特色图片</label>
                <input type="file" name="featured_image" id="featured_image" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-gray-500 text-xs mt-1">支持格式：JPG, PNG, GIF。最大大小：2MB</p>
            </div>
            
            <div class="mb-4">
                <label for="excerpt" class="block text-gray-700 text-sm font-bold mb-2">摘要</label>
                <textarea name="excerpt" id="excerpt" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">内容</label>
                <textarea name="content" id="content" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">分类</label>
                <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">选择分类</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">标签</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="tag_ids[]" value="<?= $tag['id'] ?>" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-gray-700"><?= htmlspecialchars($tag['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    创建文章
                </button>
            </div>
        </form>
    </div>
</div>