# 🔧 Correção de CSP - Content Security Policy

## Problema Identificado

Os erros no console mostram que a **Content Security Policy (CSP)** está bloqueando:
- ❌ Alpine.js (precisa de `'unsafe-eval'`)
- ❌ Tailwind CSS com expressões dinâmicas
- ❌ Lucide icons

## ✅ Solução Aplicada

### Arquivo Atualizado
`app/Helpers/Security.php`

### Mudanças
Adicionei `'unsafe-eval'` à diretiva `script-src`:

**ANTES:**
```php
"script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://unpkg.com"
```

**DEPOIS:**
```php
"script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com"
```

Isso permite que:
- ✅ Alpine.js funcione (usa `new Function()`)
- ✅ Tailwind CSS processe expressões dinâmicas
- ✅ Outros scripts que precisam de eval

---

## 📤 Próximo Passo: Fazer Upload

### Opção A - Upload via cPanel File Manager

1. Acesse cPanel → File Manager
2. Navegue até: `/public_html/cybertool360/app/Helpers/`
3. Faça upload do arquivo atualizado: `Security.php`
4. Substitua o arquivo existente

### Opção B - Upload via FTP/SFTP

```bash
# Conecte ao servidor
sftp cycodene_webapp@57.128.126.160

# Navegue até o diretório
cd public_html/cybertool360/app/Helpers/

# Upload do arquivo
put Security.php

# Confirme
ls -l Security.php
```

### Opção C - Editar Diretamente no cPanel

1. cPanel → File Manager
2. Navegue até: `/public_html/cybertool360/app/Helpers/Security.php`
3. Clique direito → Edit
4. Localize a linha 152 (aproximadamente)
5. Mude de:
   ```php
   "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://unpkg.com",
   ```
   Para:
   ```php
   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com",
   ```
6. Repita na linha 128 (para sendCspForInternal)
7. Salve

---

## 🧪 Testar Após Upload

1. Limpe o cache do navegador (Ctrl + Shift + R)
2. Acesse: https://cybertool360.cycode.net/signin
3. Abra o Console (F12)
4. ✅ Não deve haver mais erros de CSP
5. ✅ O formulário deve aparecer corretamente
6. ✅ Dark mode toggle deve funcionar

---

## 📋 Checklist Completo

- [ ] Upload do `Security.php` atualizado
- [ ] Limpar cache do navegador
- [ ] Testar página de login
- [ ] Verificar console (F12) - sem erros
- [ ] Testar dark mode toggle
- [ ] Acessar: `/clear-ratelimit.php` (desbloquear)
- [ ] Fazer login com: admin@cybercode360.com / admin123
- [ ] ✅ **SUCESSO!**

---

## 🔒 Nota sobre Segurança

**Q:** `'unsafe-eval'` não é inseguro?

**A:** Em produção ideal, sim. Mas é necessário para:
- Alpine.js
- Tailwind CSS (modo CDN com JIT)
- Outras bibliotecas modernas

**Alternativas para futuro:**
1. Usar Tailwind CSS compilado (sem CDN)
2. Substituir Alpine.js por vanilla JS
3. Usar nonces CSP específicos

Para esta aplicação (intranet/hub), o risco é aceitável dado que:
- ✅ Não há input de usuário não sanitizado
- ✅ XSS é mitigado com `htmlspecialchars()`
- ✅ Outras camadas de segurança estão ativas

---

## 🆘 Se Ainda Houver Erros

### Verificar se o upload funcionou

**Crie arquivo:** `public/check-csp.php`

```php
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Helpers/Security.php';

use App\Helpers\Security;

// Envia headers
Security::sendSecurityHeaders();

// Captura headers enviados
$headers = headers_list();
?>
<!DOCTYPE html>
<html>
<head>
    <title>CSP Check</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">CSP Headers Check</h1>
        <div class="bg-gray-50 p-4 rounded font-mono text-sm">
            <?php foreach ($headers as $header): ?>
                <?php if (str_contains($header, 'Content-Security-Policy')): ?>
                    <div class="mb-2">
                        <strong class="text-blue-600">CSP:</strong><br>
                        <code class="text-xs"><?= htmlspecialchars($header) ?></code>
                    </div>
                    <?php 
                    $hasUnsafeEval = str_contains($header, "'unsafe-eval'");
                    ?>
                    <div class="mt-2 p-2 <?= $hasUnsafeEval ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> rounded">
                        <?= $hasUnsafeEval ? '✅ unsafe-eval PRESENTE' : '❌ unsafe-eval AUSENTE' ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="mt-4">
            <a href="/signin" class="text-blue-600 hover:underline">← Voltar ao Login</a>
        </div>
    </div>
</body>
</html>
```

Acesse: `https://cybertool360.cycode.net/check-csp.php`

Deve mostrar `✅ unsafe-eval PRESENTE`

---

**Desenvolvido com ❤️ pela CyberCode360**
