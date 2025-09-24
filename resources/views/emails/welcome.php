<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>欢迎来到<?= $siteName ?? '我们的网站' ?></title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2d3748; border-bottom: 2px solid #4299e1; padding-bottom: 10px;">
            欢迎来到<?= $siteName ?? '我们的网站' ?>！
        </h1>
        
        <p><?= $name ?? '您好' ?>，</p>
        
        <p>感谢您加入我们！我们很高兴您成为我们社区的一员。</p>
        
        <div style="background-color: #f7fafc; padding: 15px; border-left: 4px solid #4299e1; margin: 20px 0;">
            <p><strong>开始使用：</strong></p>
            <ul>
                <li>探索我们的<a href="<?= $siteUrl ?? '#' ?>" style="color: #4299e1;">功能</a></li>
                <li>查看我们的<a href="<?= $siteUrl ?? '#' ?>/docs" style="color: #4299e1;">文档</a></li>
                <li>加入我们的<a href="<?= $siteUrl ?? '#' ?>/community" style="color: #4299e1;">社区</a></li>
            </ul>
        </div>
        
        <p>如果您有任何问题，请随时<a href="<?= $siteUrl ?? '#' ?>/contact" style="color: #4299e1;">联系我们</a>。</p>
        
        <p>此致<br>
        <?= $siteName ?? '我们的网站' ?>团队</p>
        
        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #e2e8f0;">
        
        <p style="font-size: 12px; color: #718096;">
            此邮件发送至<?= $email ?? '您的邮箱地址' ?>。 
            如果您没有创建账户，可以<a href="<?= $siteUrl ?? '#' ?>/unsubscribe" style="color: #718096;">取消订阅</a>。
        </p>
    </div>
</body>
</html>