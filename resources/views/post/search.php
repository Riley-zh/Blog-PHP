<?php 
$layout = 'layout';
$title = '搜索结果';
?>
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">"<?= htmlspecialchars($keyword) ?>"的搜索结果</h1>
    
    <?php if (empty($results)): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-600">未找到与您的搜索相关的结果。</p>
            <a href="/posts" class="mt-4 inline-block text-blue-500 hover:text-blue-700">查看所有文章</a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php foreach ($results as $result): ?>
                <div class="border-b border-gray-200 p-6 hover:bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">
                        <a href="/posts/<?= htmlspecialchars($result['data']['slug']) ?>" class="hover:text-blue-600">
                            <?= htmlspecialchars($result['data']['title']) ?>
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-4">
                        <?= htmlspecialchars(substr($result['data']['excerpt'], 0, 150)) ?>...
                    </p>
                    <div class="flex items-center text-sm text-gray-500">
                        <span>发布于 <?= date('Y年m月d日', strtotime($result['data']['published_at'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>