# Guia de Instalação - CyberTool360

## Instalação Rápida (5 minutos)

### 1. Preparação do Servidor

**Requisitos mínimos:**
- PHP 8.1+
- MySQL 8.0+
- Apache com mod_rewrite

### 2. Fazer Upload dos Arquivos

**Via cPanel File Manager:**
1. Compacte todos os arquivos em um ZIP
2. Faça upload para `public_html`
3. Descompacte no servidor
4. Configure o document root para apontar para `/public`

**Via FTP/SFTP:**
```bash
# Conecte-se ao servidor
sftp usuario@seuservidor.com

# Navegue até o diretório web
cd /public_html/cybertool360

# Upload dos arquivos
put -r *
```

### 3. Criar o Banco de Dados

**No cPanel → MySQL Databases:**

1. **Criar banco de dados:**
   - Nome: `cybertool360`
   - Charset: `utf8mb4`
   - Collation: `utf8mb4_0900_ai_ci`

2. **Criar usuário:**
   - Usuário: `cybertool_user` (ou seu preferido)
   - Senha: Gere uma senha forte
   - Adicione o usuário ao banco com ALL PRIVILEGES

3. **Importar schema:**
   - cPanel → phpMyAdmin
   - Selecione o banco `cybertool360`
   - Importe o arquivo `database/migration.sql`
   - (Opcional) Importe `database/seed.sql` para dados de exemplo

### 4. Configurar a Aplicação

Edite o arquivo `config.php` na raiz do projeto:

```php
// === ALTERE ESTAS LINHAS ===

define('DB_HOST', 'localhost');           // Host do MySQL
define('DB_NAME', 'cybertool360');        // Nome do banco
define('DB_USER', 'cybertool_user');      // Seu usuário MySQL
define('DB_PASS', 'sua_senha_segura');    // Sua senha MySQL

define('APP_URL', 'https://seudominio.com');  // URL do seu site

define('APP_ENV', 'production');          // Mude para production
```

### 5. Configurar o Document Root

**Opção A - Recomendado (se tiver acesso):**

Configure o document root para apontar para `/public`:
- cPanel → Domains → Manage
- Document Root: `/home/usuario/public_html/cybertool360/public`

**Opção B - Alternativa:**

Se não puder mudar o document root, mova os arquivos de `public/` para a raiz:

```bash
cd /public_html/cybertool360
mv public/* ./
mv public/.htaccess ./
rm -rf public/
```

E edite `config.php`:
```php
define('PUBLIC_PATH', ROOT_PATH);
```

### 6. Testar a Instalação

Acesse: `https://seudominio.com/`

Você deve ver a página de login. Use as credenciais padrão:

- **Admin**: admin@cybercode360.com / admin123
- **Manager**: manager@cybercode360.com / admin123
- **User**: user@cybercode360.com / admin123

⚠️ **ALTERE ESSAS SENHAS IMEDIATAMENTE!**

### 7. Verificar Permissões

Certifique-se que as permissões estão corretas:

```bash
# Arquivos
find . -type f -exec chmod 644 {} \;

# Diretórios
find . -type d -exec chmod 755 {} \;

# Específico para logs (se criar)
mkdir -p logs
chmod 777 logs
```

### 8. Habilitar HTTPS (Produção)

1. **Instale certificado SSL** (Let's Encrypt via cPanel é gratuito)

2. **Descomente a linha no `.htaccess`:**
```apache
# Edite public/.htaccess e descomente:
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

3. **Atualize `config.php`:**
```php
define('APP_URL', 'https://seudominio.com');
```

---

## Instalação Local (Desenvolvimento)

### Com XAMPP/WAMP/MAMP:

1. **Copie os arquivos** para:
   - Windows: `C:\xampp\htdocs\cybertool360\`
   - Mac: `/Applications/MAMP/htdocs/cybertool360/`

2. **Crie o banco de dados**:
   - Acesse: http://localhost/phpmyadmin
   - Crie banco `cybertool360`
   - Importe `database/migration.sql`
   - Importe `database/seed.sql`

3. **Configure** o `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cybertool360');
define('DB_USER', 'root');
define('DB_PASS', '');  // Senha vazia no XAMPP

define('APP_URL', 'http://localhost/cybertool360');
define('APP_ENV', 'development');
```

4. **Acesse**: http://localhost/cybertool360/public/

---

## Troubleshooting

### Erro: "Call to undefined function App\Models\..."
**Solução**: Verifique o autoloader no `public/index.php`

### Erro: "Access denied for user"
**Solução**: Verifique as credenciais do banco em `config.php`

### Erro 500 sem detalhes
**Solução**: 
1. Ative `APP_ENV = 'development'` em `config.php`
2. Veja o erro detalhado
3. Verifique os logs do Apache: `/var/log/apache2/error.log`

### Apps não carregam no iframe
**Solução**: 
- Alguns sites bloqueiam iframes (Google, Facebook)
- Mude o `open_mode` para `EXTERNAL`
- Verifique a allowlist de domínios

### CSS não carrega
**Solução**:
- Limpe o cache do navegador (Ctrl+Shift+R)
- Verifique se o Tailwind CDN está acessível
- Veja o console do navegador (F12)

---

## Próximos Passos

1. ✅ Acesse o painel admin: `/admin`
2. ✅ Altere as senhas padrão
3. ✅ Crie suas categorias
4. ✅ Adicione seus apps
5. ✅ Convide usuários
6. ✅ Configure CSP e allowlists
7. ✅ Teste dark mode e responsividade
8. ✅ Configure backups automáticos

---

## Suporte

- 📧 Email: contato@cybercode360.com
- 🌐 Website: https://cybercode360.com
- 📚 Documentação: README.md
- 🐛 Issues: GitHub Issues

**Boa sorte! 🚀**
