<?php 
$layout = 'layout';
$title = '用户资料';
?>
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">用户资料</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">资料信息</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">用户名</label>
                        <p class="mt-1 text-gray-900"><?= htmlspecialchars($user['username']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">邮箱</label>
                        <p class="mt-1 text-gray-900"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">账户设置</h3>
                <div class="space-y-4">
                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        编辑资料
                    </button>
                    <button class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        修改密码
                    </button>
                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <a href="/logout" class="text-red-500 hover:text-red-700 font-medium transition duration-300">
                <i class="fas fa-sign-out-alt mr-2"></i>退出登录
            </a>
        </div>
    </div>
</div>