# 🚀 Guia de Deploy - CyberTool360
## Produção: cybertool360.cycode.net

---

## 📋 Informações do Servidor

| Configuração | Valor |
|--------------|-------|
| **Domínio** | cybertool360.cycode.net |
| **IP** | 57.128.126.160 |
| **Banco de Dados** | cycodene_cybertool360 |
| **Usuário DB** | cycodene_webapp |
| **Senha DB** | Passw0rd2025 |
| **Host DB** | localhost |

---

## ⚙️ Configurações Aplicadas

### ✅ config.php
- ✅ Database: `cycodene_cybertool360`
- ✅ User: `cycodene_webapp`
- ✅ URL: `https://cybertool360.cycode.net`
- ✅ Environment: `production`
- ✅ Error display: OFF (automático em production)
- ✅ Error logging: ON

### ✅ .htaccess
- ✅ HTTPS redirect: ATIVADO
- ✅ mod_rewrite: Configurado
- ✅ Cache headers: Configurados
- ✅ GZIP compression: Ativado
- ✅ Proteção de arquivos sensíveis: Ativada

---

## 📦 Passo a Passo do Deploy

### 1. Preparar o Banco de Dados

**Via cPanel → MySQL Databases:**

```sql
-- O banco e usuário já devem existir no cPanel
-- Banco: cycodene_cybertool360
-- User: cycodene_webapp

-- 1. Acesse phpMyAdmin
-- 2. Selecione o banco: cycodene_cybertool360
-- 3. Execute o arquivo: database/migration.sql
-- 4. Execute o arquivo: database/seed.sql
```

**⚠️ IMPORTANTE**: Após importar o seed.sql, ALTERE AS SENHAS PADRÃO imediatamente!

### 2. Upload dos Arquivos

**Opção A - Via cPanel File Manager:**

1. Acesse cPanel → File Manager
2. Navegue até `public_html`
3. Crie uma pasta `cybertool360` (ou use o domínio diretamente)
4. Compacte todo o projeto localmente em ZIP
5. Faça upload do ZIP
6. Extraia os arquivos
7. **Configure o Document Root** para apontar para `public/`

**Opção B - Via FTP/SFTP:**

```bash
# Conecte-se ao servidor
sftp cycodene_webapp@57.128.126.160

# Navegue até o diretório
cd public_html/cybertool360

# Upload de todos os arquivos
put -r *

# Verifique as permissões
chmod 644 config.php
```

### 3. Configurar Document Root

**No cPanel:**

1. Domains → Manage Domains
2. Encontre `cybertool360.cycode.net`
3. Document Root: `/home/cycodene/public_html/cybertool360/public`
4. Salve

**Se não tiver acesso ao Document Root:**

Mova os arquivos de `public/` para a raiz:
```bash
cd /home/cycodene/public_html/cybertool360
mv public/* ./
mv public/.htaccess ./
rm -rf public/
```

E edite `config.php`:
```php
define('PUBLIC_PATH', ROOT_PATH);
```

### 4. Verificar Permissões

```bash
# Arquivos: 644
find . -type f -exec chmod 644 {} \;

# Diretórios: 755
find . -type d -exec chmod 755 {} \;

# config.php (mais restritivo)
chmod 640 config.php

# .htaccess
chmod 644 public/.htaccess
```

### 5. Instalar Certificado SSL

**Via cPanel - Let's Encrypt:**

1. cPanel → SSL/TLS Status
2. Selecione `cybertool360.cycode.net`
3. Clique em "Run AutoSSL"
4. Aguarde a instalação (1-2 minutos)

**Verificar:**
- Acesse: https://cybertool360.cycode.net
- Deve carregar com HTTPS automaticamente

### 6. Alterar Senhas Padrão

**Via phpMyAdmin:**

```sql
-- Gere hash de senha forte em PHP:
-- php -r "echo password_hash('SuaSenhaForte123!', PASSWORD_BCRYPT);"

-- Atualize as senhas
UPDATE users 
SET password_hash = '$2y$10$NOVO_HASH_AQUI' 
WHERE email = 'admin@cybercode360.com';

UPDATE users 
SET password_hash = '$2y$10$NOVO_HASH_AQUI' 
WHERE email = 'manager@cybercode360.com';

UPDATE users 
SET password_hash = '$2y$10$NOVO_HASH_AQUI' 
WHERE email = 'user@cybercode360.com';
```

**Ou via interface:**
1. Login como admin
2. Acesse /admin/users
3. Use a interface para alterar roles (futuro: adicionar change password)

### 7. Testar Tudo

**Checklist de Testes:**

- [ ] ✅ Acesso ao site: https://cybertool360.cycode.net
- [ ] ✅ HTTP redireciona para HTTPS
- [ ] ✅ Login funciona (admin@cybercode360.com)
- [ ] ✅ Launcher carrega apps
- [ ] ✅ Abrir app internamente funciona
- [ ] ✅ Abrir app externamente funciona
- [ ] ✅ Favoritar/Desfavoritar funciona
- [ ] ✅ Busca funciona
- [ ] ✅ Filtros por categoria funcionam
- [ ] ✅ Dark mode persiste
- [ ] ✅ Admin panel acessível (/admin)
- [ ] ✅ CRUD de apps funciona
- [ ] ✅ CRUD de categorias funciona
- [ ] ✅ Gestão de usuários funciona (ADMIN)
- [ ] ✅ Métricas são registradas
- [ ] ✅ Responsivo (mobile, tablet, desktop)

### 8. Configurações de Segurança Adicionais

**Proteger config.php no .htaccess:**

Já está configurado, mas verifique:
```apache
<FilesMatch "^(config\.php)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

**Verificar PHP.ini (via cPanel → Select PHP Version):**
```ini
display_errors = Off
log_errors = On
error_log = /home/cycodene/logs/php_errors.log
max_execution_time = 30
memory_limit = 256M
upload_max_filesize = 10M
```

### 9. Configurar Backups

**Backup Automático (cPanel):**

1. cPanel → Backup Wizard
2. Configure backup diário do banco de dados
3. Configure backup semanal dos arquivos
4. Email de notificação

**Backup Manual:**

```bash
# Banco de dados
mysqldump -u cycodene_webapp -p cycodene_cybertool360 > backup_$(date +%Y%m%d).sql

# Arquivos
tar -czf cybertool360_$(date +%Y%m%d).tar.gz /home/cycodene/public_html/cybertool360/
```

### 10. Monitoramento

**Verificar Logs:**

- PHP errors: `/home/cycodene/logs/php_errors.log`
- Apache errors: Via cPanel → Errors
- Access logs: Via cPanel → Raw Access

**Configurar Uptime Monitor:**
- Use serviços como UptimeRobot, Pingdom
- URL para monitorar: https://cybertool360.cycode.net
- Alertas via email

---

## 🔒 Checklist de Segurança Pós-Deploy

- [ ] ✅ HTTPS funcionando
- [ ] ✅ Senhas padrão alteradas
- [ ] ✅ `APP_ENV` = `production`
- [ ] ✅ `display_errors` = Off
- [ ] ✅ Permissões de arquivos corretas (644/755)
- [ ] ✅ config.php protegido
- [ ] ✅ Backups configurados
- [ ] ✅ SSL certificado válido
- [ ] ✅ Rate limiting testado
- [ ] ✅ CSP headers verificados
- [ ] ✅ Logs funcionando

---

## 📊 Credenciais Demo (ALTERAR URGENTEMENTE)

**Após deploy, use estas credenciais temporariamente:**

| Nível | Email | Senha Padrão |
|-------|-------|--------------|
| ADMIN | admin@cybercode360.com | admin123 |
| MANAGER | manager@cybercode360.com | admin123 |
| USER | user@cybercode360.com | admin123 |

**⚠️ CRÍTICO**: Altere TODAS as senhas antes de divulgar o site!

---

## 🐛 Troubleshooting

### Erro 500 - Internal Server Error

**Solução:**
1. Verifique logs: cPanel → Errors
2. Verifique permissões: `chmod 644 config.php`
3. Verifique sintaxe PHP: `php -l public/index.php`
4. Temporariamente ative modo desenvolvimento em `config.php`

### Erro de Conexão ao Banco

**Solução:**
1. Teste conexão via phpMyAdmin
2. Verifique credenciais em `config.php`
3. Verifique se o usuário tem permissões no banco
4. Teste: `mysql -u cycodene_webapp -p cycodene_cybertool360`

### CSS/JS não carregam

**Solução:**
1. Limpe cache do navegador (Ctrl+Shift+R)
2. Verifique CSP headers no console
3. Verifique se CDNs estão acessíveis
4. Teste em modo anônimo

### HTTPS não funciona

**Solução:**
1. Force instalação SSL: cPanel → SSL/TLS Status
2. Aguarde propagação DNS (até 48h)
3. Verifique redirect no .htaccess
4. Teste: `curl -I https://cybertool360.cycode.net`

### Apps não abrem no iframe

**Solução:**
1. Verifique allowlist de domínios
2. Alguns sites bloqueiam iframes (Google, Facebook)
3. Mude para `open_mode = EXTERNAL`
4. Veja erros de CSP no console (F12)

---

## 📈 Otimizações Pós-Deploy

### Performance

1. **Habilitar OPcache** (cPanel → Select PHP Version):
   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.max_accelerated_files=10000
   ```

2. **Configurar CDN** (opcional):
   - Cloudflare (gratuito)
   - Configure DNS para apontar para Cloudflare

3. **Comprimir imagens**:
   - Use TinyPNG para ícones
   - WebP format quando possível

### SEO

1. **Adicionar robots.txt**:
   ```
   User-agent: *
   Allow: /
   Disallow: /admin/
   
   Sitemap: https://cybertool360.cycode.net/sitemap.xml
   ```

2. **Meta tags** (já incluídas nas views)

---

## 🆘 Suporte

**Em caso de problemas:**

1. Verifique este guia primeiro
2. Consulte logs de erro
3. Teste em modo desenvolvimento
4. Contato: contato@cybercode360.com

---

## ✅ Deploy Completo!

Após seguir todos os passos:

1. ✅ Site acessível via HTTPS
2. ✅ Banco de dados funcionando
3. ✅ Usuários podem fazer login
4. ✅ Apps funcionando (interno/externo)
5. ✅ Admin panel operacional
6. ✅ Backups configurados
7. ✅ Segurança implementada

**🎉 Parabéns! CyberTool360 está no ar!**

Acesse: **https://cybertool360.cycode.net**

---

**Desenvolvido com ❤️ pela CyberCode360**
