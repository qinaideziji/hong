<?php
$encodedUrl = isset($_GET['u']) ? $_GET['u'] : '';

if (empty($encodedUrl)) {
    die('缺少必要参数');
}

// 解码URL
$originalUrl = base64_decode($encodedUrl);

// 验证URL
if (!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
    die('无效的URL');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>加载中...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#165DFF',
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }
            .loader {
                border-top-color: #165DFF;
                animation: spinner 1s linear infinite;
            }
            @keyframes spinner {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- 加载页面 -->
    <div id="loadingPage" class="fixed inset-0 flex flex-col items-center justify-center bg-white z-50">
        <div class="loader w-16 h-16 border-4 border-gray-200 rounded-full mb-6"></div>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">页面加载中</h2>
        <p class="text-gray-600">请稍候，正在为您打开网页...</p>
        <div class="mt-8 text-sm text-gray-500">
            <p>如果长时间无法加载，请点击下方链接直接访问</p>
            <a href="<?php echo htmlspecialchars($originalUrl); ?>" target="_blank" class="text-primary hover:underline mt-2 inline-block">
                <i class="fa fa-external-link mr-1"></i> 直接访问
            </a>
        </div>
    </div>

    <!-- iframe容器 -->
    <div class="hidden" id="iframeContainer">
        <iframe src="<?php echo htmlspecialchars($originalUrl); ?>" frameborder="0" class="w-full h-screen"></iframe>
    </div>

    <script>
        // 监听iframe加载状态
        window.addEventListener('load', function() {
            // 创建iframe
            const iframe = document.createElement('iframe');
            iframe.src = '<?php echo htmlspecialchars($originalUrl); ?>';
            iframe.frameBorder = '0';
            iframe.className = 'w-full h-screen';
            
            // 监听iframe加载完成事件
            iframe.onload = function() {
                // 隐藏加载页面，显示iframe
                document.getElementById('loadingPage').classList.add('opacity-0');
                setTimeout(() => {
                    document.getElementById('loadingPage').classList.add('hidden');
                    document.getElementById('iframeContainer').classList.remove('hidden');
                }, 300);
            };
            
            // 如果iframe加载失败，显示错误信息
            iframe.onerror = function() {
                const loadingPage = document.getElementById('loadingPage');
                loadingPage.querySelector('.loader').classList.add('hidden');
                loadingPage.querySelector('h2').textContent = '加载失败';
                loadingPage.querySelector('p').textContent = '无法加载该网页，请点击下方链接直接访问';
            };
            
            // 将iframe添加到容器中
            document.getElementById('iframeContainer').appendChild(iframe);
            
            // 设置超时，如果10秒后仍未加载完成，提示用户
            setTimeout(() => {
                if (!document.getElementById('iframeContainer').classList.contains('hidden')) {
                    return;
                }
                
                const loadingPage = document.getElementById('loadingPage');
                loadingPage.querySelector('p').textContent = '加载时间过长，请点击下方链接直接访问';
            }, 10000);
        });
    </script>
</body>
</html>    