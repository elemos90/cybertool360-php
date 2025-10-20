# 🔧 Solução Rápida - Erro de Login

## Problema Atual
**Erro:** "Muitas tentativas de login. Tente novamente em 15 minutos."

---

## ✅ Solução em 3 Passos

### Passo 1: Desbloquear Rate Limit

**Acesse no navegador:**
```
https://cybertool360.cycode.net/clear-ratelimit.php
```

Isso vai limpar o bloqueio de tentativas.

---

### Passo 2: Verificar Hash da Senha

**Via phpMyAdmin, execute:**

```sql
-- Verifica o hash atual
SELECT email, password_hash FROM users WHERE email = 'admin@cybercode360.com';

-- Se estiver incorreto, atualize com o hash correto
UPDATE users 
SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email = 'admin@cybercode360.com';
```

**Este hash corresponde à senha:** `admin123`

---

### Passo 3: Fazer Login

1. Acesse: https://cybertool360.cycode.net/signin
2. Email: `admin@cybercode360.com`
3. Senha: `admin123`
4. Clique em "Entrar"

✅ **Deve funcionar!**

---

## 🗑️ IMPORTANTE - Limpar Após Uso

Depois de fazer login com sucesso, **DELETE** estes arquivos:

```bash
# Via cPanel → File Manager, deletar:
/public_html/cybertool360/public/clear-ratelimit.php
/public_html/cybertool360/FIX-LOGIN.md
```

Ou acesse via FTP e delete manualmente.

---

## 🔐 Alternativamente - Via SQL

Se não conseguir acessar o script PHP, você pode limpar diretamente no servidor:

**Via SSH (se tiver acesso):**

```bash
# Limpar arquivos de rate limit
rm /tmp/cybertool360_ratelimit_*.lock

# Ou via PHP CLI
php -r "array_map('unlink', glob(sys_get_temp_dir() . '/cybertool360_ratelimit_*.lock'));"
```

**Via cPanel → Terminal:**

Igual ao comando SSH acima.

---

## 📋 Resumo das Ações

1. ✅ Arquivos SQL atualizados para banco `cycodene_cybertool360`
2. ✅ Hash da senha verificado (correto no seed.sql)
3. ✅ Script de desbloqueio criado (`clear-ratelimit.php`)
4. ⏳ Execute o passo 1 para desbloquear
5. ⏳ Verifique o hash no banco (passo 2)
6. ⏳ Teste login (passo 3)

---

## 🆘 Se Ainda Não Funcionar

### Debug Mode

Temporariamente ative o modo de desenvolvimento:

**Edite `config.php`:**
```php
define('APP_ENV', 'development');
```

Tente fazer login novamente e veja o erro detalhado.

**NÃO ESQUEÇA** de voltar para `production` depois!

### Verificar Conexão ao Banco

**Crie arquivo temporário:** `public/test-db.php`

```php
<?php
require_once __DIR__ . '/../config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    
    echo "✅ Conexão OK! Total de usuários: $count";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}
```

Acesse: `https://cybertool360.cycode.net/test-db.php`

**DELETE após uso!**

---

## 📞 Suporte

Se nada funcionar:
1. Verifique logs: cPanel → Errors
2. Verifique console do navegador (F12)
3. Verifique credenciais em `config.php`
4. Reimporte `migration.sql` e `seed.sql`

---

**Boa sorte! 🚀**
