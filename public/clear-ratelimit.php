<?php
/**
 * Script Temporário para Limpar Rate Limit
 * DELETAR APÓS USO!
 */

// Limpa todos os arquivos de rate limit
$tempDir = sys_get_temp_dir();
$files = glob($tempDir . '/cybertool360_ratelimit_*.lock');

$cleared = 0;
foreach ($files as $file) {
    if (unlink($file)) {
        $cleared++;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Limit Cleared</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md">
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Rate Limit Limpo!</h1>
            <p class="text-gray-600 mb-6">
                <?= $cleared ?> arquivo(s) de bloqueio removido(s).
            </p>
            
            <div class="space-y-3">
                <a href="/signin" class="block w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Fazer Login
                </a>
                
                <p class="text-sm text-red-600 font-medium">
                    ⚠️ DELETAR este arquivo após uso!<br>
                    <code class="bg-red-50 px-2 py-1 rounded">public/clear-ratelimit.php</code>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
