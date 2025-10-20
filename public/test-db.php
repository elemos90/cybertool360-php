<?php
/**
 * Teste de Conexão ao Banco de Dados
 * DELETAR APÓS USO!
 */

require_once __DIR__ . '/../config.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-2xl w-full">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">🔍 Teste de Conexão ao Banco</h1>
        
        <div class="space-y-4">
            <?php
            try {
                echo '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">';
                echo '<h2 class="font-bold text-blue-900 mb-2">📋 Configurações:</h2>';
                echo '<ul class="text-sm text-blue-800 space-y-1">';
                echo '<li><strong>Host:</strong> ' . htmlspecialchars(DB_HOST) . '</li>';
                echo '<li><strong>Database:</strong> ' . htmlspecialchars(DB_NAME) . '</li>';
                echo '<li><strong>User:</strong> ' . htmlspecialchars(DB_USER) . '</li>';
                echo '<li><strong>Charset:</strong> ' . htmlspecialchars(DB_CHARSET) . '</li>';
                echo '</ul>';
                echo '</div>';
                
                // Tenta conectar
                $pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                
                echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
                echo '<h2 class="font-bold text-green-900 mb-2">✅ Conexão Estabelecida!</h2>';
                echo '</div>';
                
                // Conta usuários
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
                $users = $stmt->fetch()['total'];
                
                echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4">';
                echo '<h2 class="font-bold text-green-900 mb-2">👥 Usuários no Banco:</h2>';
                echo '<p class="text-2xl font-bold text-green-700">' . $users . '</p>';
                echo '</div>';
                
                // Lista usuários
                $stmt = $pdo->query("SELECT id, email, name, role FROM users ORDER BY role");
                $usersList = $stmt->fetchAll();
                
                if ($usersList) {
                    echo '<div class="bg-white border border-gray-200 rounded-lg p-4">';
                    echo '<h2 class="font-bold text-gray-900 mb-3">📋 Lista de Usuários:</h2>';
                    echo '<div class="overflow-x-auto">';
                    echo '<table class="min-w-full divide-y divide-gray-200">';
                    echo '<thead class="bg-gray-50">';
                    echo '<tr>';
                    echo '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>';
                    echo '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>';
                    echo '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody class="bg-white divide-y divide-gray-200">';
                    
                    foreach ($usersList as $user) {
                        $roleColor = [
                            'ADMIN' => 'bg-red-100 text-red-800',
                            'MANAGER' => 'bg-blue-100 text-blue-800',
                            'USER' => 'bg-gray-100 text-gray-800'
                        ];
                        
                        echo '<tr>';
                        echo '<td class="px-3 py-2 text-sm text-gray-900">' . htmlspecialchars($user['email']) . '</td>';
                        echo '<td class="px-3 py-2 text-sm text-gray-700">' . htmlspecialchars($user['name']) . '</td>';
                        echo '<td class="px-3 py-2">';
                        echo '<span class="px-2 py-1 text-xs font-medium rounded ' . $roleColor[$user['role']] . '">' . $user['role'] . '</span>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    echo '</div>';
                }
                
                // Verifica hash do admin
                $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE email = ?");
                $stmt->execute(['admin@cybercode360.com']);
                $admin = $stmt->fetch();
                
                if ($admin) {
                    $expectedHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
                    $isCorrect = ($admin['password_hash'] === $expectedHash);
                    
                    echo '<div class="bg-' . ($isCorrect ? 'green' : 'yellow') . '-50 border border-' . ($isCorrect ? 'green' : 'yellow') . '-200 rounded-lg p-4">';
                    echo '<h2 class="font-bold text-' . ($isCorrect ? 'green' : 'yellow') . '-900 mb-2">';
                    echo $isCorrect ? '✅ Hash da Senha Admin: CORRETO' : '⚠️ Hash da Senha Admin: DIFERENTE';
                    echo '</h2>';
                    
                    if (!$isCorrect) {
                        echo '<p class="text-sm text-yellow-800 mb-2">O hash atual não corresponde a "admin123".</p>';
                        echo '<p class="text-xs font-mono bg-yellow-100 p-2 rounded break-all">' . htmlspecialchars($admin['password_hash']) . '</p>';
                        echo '<div class="mt-3 p-3 bg-yellow-100 rounded">';
                        echo '<p class="text-sm font-bold text-yellow-900 mb-1">Execute no phpMyAdmin:</p>';
                        echo '<code class="text-xs text-yellow-800 block">UPDATE users SET password_hash = \'' . $expectedHash . '\' WHERE email = \'admin@cybercode360.com\';</code>';
                        echo '</div>';
                    } else {
                        echo '<p class="text-sm text-green-800">A senha "admin123" deve funcionar! ✓</p>';
                    }
                    echo '</div>';
                }
                
            } catch (PDOException $e) {
                echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4">';
                echo '<h2 class="font-bold text-red-900 mb-2">❌ Erro de Conexão</h2>';
                echo '<p class="text-sm text-red-800 font-mono">' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<div class="mt-3 p-3 bg-red-100 rounded">';
                echo '<p class="text-sm font-bold text-red-900 mb-1">Verifique:</p>';
                echo '<ul class="text-sm text-red-800 list-disc list-inside space-y-1">';
                echo '<li>Credenciais em config.php</li>';
                echo '<li>Banco de dados existe no cPanel</li>';
                echo '<li>Usuário tem permissões no banco</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
            }
            ?>
            
            <div class="bg-red-50 border-2 border-red-300 rounded-lg p-4 mt-6">
                <p class="text-red-900 font-bold text-center">
                    ⚠️ DELETAR este arquivo após uso!
                </p>
                <p class="text-red-700 text-sm text-center mt-1">
                    <code>public/test-db.php</code>
                </p>
            </div>
            
            <div class="flex gap-3">
                <a href="/" class="flex-1 text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Voltar ao Site
                </a>
                <a href="/signin" class="flex-1 text-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                    Fazer Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
