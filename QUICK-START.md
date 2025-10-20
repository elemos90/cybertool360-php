# 🚀 Quick Start - Deploy em 10 Minutos
## CyberTool360 → cybertool360.cycode.net

---

## ⚡ Deploy Rápido

### 1️⃣ Banco de Dados (2 min)

**cPanel → phpMyAdmin**

```sql
-- Selecione: cycodene_cybertool360
-- Import → Escolha: database/migration.sql → Go
-- Import → Escolha: database/seed.sql → Go
-- ✅ Done!
```

### 2️⃣ Upload (3 min)

**cPanel → File Manager**

1. Compacte o projeto em ZIP
2. Upload para: `public_html/cybertool360/`
3. Extrair ZIP
4. ✅ Done!

### 3️⃣ Document Root (1 min)

**cPanel → Domains → Manage**

```
Document Root: /home/cycodene/public_html/cybertool360/public
```

✅ Save

### 4️⃣ SSL (2 min)

**cPanel → SSL/TLS Status**

1. Selecione: cybertool360.cycode.net
2. Run AutoSSL
3. Aguarde instalação
4. ✅ Done!

### 5️⃣ Testar (2 min)

Acesse: **https://cybertool360.cycode.net**

Login:
- **Email:** admin@cybercode360.com
- **Senha:** admin123

✅ **Funcionou? Deploy completo!**

---

## ⚠️ CRÍTICO - Fazer IMEDIATAMENTE

### Alterar Senhas

**Via phpMyAdmin:**

```sql
-- Gere hash em PHP:
-- php -r "echo password_hash('SuaSenhaSegura123!', PASSWORD_BCRYPT);"

UPDATE users 
SET password_hash = '$2y$10$SEU_HASH_AQUI' 
WHERE email = 'admin@cybercode360.com';
```

---

## 📋 Verificações Rápidas

- [ ] https:// funciona (SSL)
- [ ] Login funciona
- [ ] Apps carregam
- [ ] Admin acessível (/admin)
- [ ] Senhas alteradas

---

## 📚 Documentação Completa

Para setup detalhado, consulte:
- **DEPLOYMENT.md** - Guia completo de deploy
- **PRODUCTION-CHECKLIST.md** - Checklist de 100+ itens
- **README.md** - Documentação geral

---

## 🆘 Problemas?

**Erro 500:**
```php
// Em config.php, temporariamente:
define('APP_ENV', 'development');
// Ver erro detalhado, depois voltar para 'production'
```

**Não conecta ao banco:**
```php
// Verifique em config.php:
define('DB_HOST', 'localhost');
define('DB_NAME', 'cycodene_cybertool360');
define('DB_USER', 'cycodene_webapp');
define('DB_PASS', 'Passw0rd2025');
```

**CSS não carrega:**
- Limpe cache (Ctrl+Shift+R)
- Verifique console do navegador (F12)

---

## ✅ Pronto!

**Site:** https://cybertool360.cycode.net

**Próximos passos:**
1. Alterar senhas ⚠️
2. Adicionar seus apps
3. Convidar usuários
4. Aproveitar! 🎉

---

**Desenvolvido com ❤️ pela CyberCode360**
