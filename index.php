<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网址防红系统</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#165DFF',
                        secondary: '#36D399',
                        neutral: '#F3F4F6',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
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
            .bg-gradient-blue {
                background: linear-gradient(135deg, #165DFF 0%, #3B82F6 100%);
            }
            .shadow-soft {
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- 导航栏 -->
        <nav class="bg-white shadow-md sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="#" class="flex-shrink-0 flex items-center">
                            <i class="fa fa-link text-primary text-2xl mr-2"></i>
                            <span class="text-xl font-bold text-gray-900">网址防红系统</span>
                        </a>
                    </div>
                    <div class="flex items-center">
                        <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            <i class="fa fa-question-circle mr-1"></i>帮助
                        </a>
                        <a href="#" class="ml-4 text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            <i class="fa fa-user-circle mr-1"></i>登录
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- 主内容区 -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h1 class="text-[clamp(2rem,5vw,3.5rem)] font-bold text-gray-900 mb-4">
                        简单高效的<span class="text-primary">网址防红</span>解决方案
                    </h1>
                    <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                        将您的网址转换为防红链接，避免被屏蔽或拦截，提高访问成功率
                    </p>
                </div>

                <!-- 功能卡片 -->
                <div class="bg-white rounded-2xl shadow-soft p-8 max-w-4xl mx-auto transform hover:-translate-y-1 transition-all duration-300">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">生成防红链接</h2>
                        <form id="urlForm" class="space-y-6">
                            <div class="space-y-2">
                                <label for="url" class="block text-sm font-medium text-gray-700">输入网址</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-globe text-gray-400"></i>
                                    </div>
                                    <input type="url" id="url" name="url" placeholder="https://example.com" 
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-200"
                                        required>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="custom_html" class="block text-sm font-medium text-gray-700">或上传自定义HTML页面</label>
                                <div class="relative">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fa fa-cloud-upload text-gray-400 text-3xl mb-2"></i>
                                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">点击上传HTML文件</span></p>
                                                <p class="text-xs text-gray-500">支持 .html 格式文件</p>
                                            </div>
                                            <input id="file-upload" name="custom_html" type="file" accept=".html" class="hidden" />
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full bg-gradient-blue hover:opacity-90 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                                    <i class="fa fa-magic mr-2"></i> 生成防红链接
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 结果区域 -->
                    <div id="result" class="hidden bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">生成结果</h3>
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <span class="text-gray-600 mb-2 sm:mb-0 sm:mr-4">防红链接:</span>
                                <div class="flex-1 flex">
                                    <input type="text" id="shortUrl" readonly 
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-200 bg-white"
                                        placeholder="生成的链接将显示在这里">
                                    <button id="copyBtn" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-r-lg transition-colors duration-200">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <span class="text-gray-600 mb-2 sm:mb-0 sm:mr-4">访问统计:</span>
                                <a href="#" id="statsLink" target="_blank" class="text-primary hover:underline">查看统计数据</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 特性介绍 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                    <div class="bg-white rounded-xl p-6 shadow-soft transform hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <i class="fa fa-shield text-primary text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">防止屏蔽</h3>
                        <p class="text-gray-600">通过转换为防红链接，有效避免微信、QQ等平台的网址屏蔽</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-soft transform hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <i class="fa fa-code text-primary text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">自定义页面</h3>
                        <p class="text-gray-600">支持上传自定义HTML页面，打造个性化的跳转体验</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-soft transform hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <i class="fa fa-bar-chart text-primary text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">访问统计</h3>
                        <p class="text-gray-600">实时监控链接点击数据，了解用户访问行为和来源</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- 页脚 -->
        <footer class="bg-gray-800 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-6 md:mb-0">
                        <div class="flex items-center">
                            <i class="fa fa-link text-primary text-2xl mr-2"></i>
                            <span class="text-xl font-bold">网址防红系统</span>
                        </div>
                        <p class="text-gray-400 mt-2">保护您的链接不被屏蔽</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fa fa-weibo text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fa fa-wechat text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fa fa-github text-xl"></i>
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; 2025 网址防红系统 版权所有</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlForm = document.getElementById('urlForm');
            const resultDiv = document.getElementById('result');
            const shortUrlInput = document.getElementById('shortUrl');
            const copyBtn = document.getElementById('copyBtn');
            const fileUpload = document.getElementById('file-upload');
            
            // 文件上传预览
            fileUpload.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    document.querySelector('label[for="file-upload"] span.font-semibold').textContent = fileName;
                }
            });
            
            // 表单提交
            urlForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                // 显示加载状态
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> 生成中...';
                
                fetch('generate.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        shortUrlInput.value = data.short_url;
                        document.getElementById('statsLink').href = 'stats.php?id=' + data.id;
                        resultDiv.classList.remove('hidden');
                        resultDiv.scrollIntoView({ behavior: 'smooth' });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('生成链接时发生错误，请重试');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
            
            // 复制链接
            copyBtn.addEventListener('click', function() {
                shortUrlInput.select();
                document.execCommand('copy');
                
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fa fa-check"></i>';
                this.classList.add('bg-secondary');
                this.classList.remove('bg-primary', 'hover:bg-primary/90');
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('bg-secondary');
                    this.classList.add('bg-primary', 'hover:bg-primary/90');
                }, 2000);
            });
            
            // 导航栏滚动效果
            window.addEventListener('scroll', function() {
                const nav = document.querySelector('nav');
                if (window.scrollY > 10) {
                    nav.classList.add('bg-white/95', 'backdrop-blur-sm');
                    nav.classList.remove('bg-white');
                } else {
                    nav.classList.remove('bg-white/95', 'backdrop-blur-sm');
                    nav.classList.add('bg-white');
                }
            });
        });
    </script>
</body>
</html>    