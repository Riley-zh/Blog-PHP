<?php 
$layout = 'layout';
$title = '登录';
?>
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">登录您的账户</h2>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">邮箱</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">密码</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    登录
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                还没有账户？ 
                <a href="/register" class="text-green-500 hover:text-green-700 font-bold transition duration-300">立即注册</a>
            </p>
        </div>
    </div>
</div>