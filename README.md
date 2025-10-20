# CyberTool360 🚀

Hub/Launcher de aplicações web da CyberCode360. Interface moderna tipo "smartphone" com grid de ícones, suporte para aberturas internas (iframe com CSP) e externas, sistema RBAC completo, dark mode e PWA-ready.

![CyberTool360](https://img.shields.io/badge/PHP-8.1+-blue) ![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange) ![Tailwind](https://img.shields.io/badge/Tailwind-CSS-38bdf8) ![License](https://img.shields.io/badge/license-MIT-green)

---

## 📋 Características

### ✨ Funcionalidades Principais
- **Launcher Responsivo**: Grid de apps tipo smartphone, mobile-first
- **Janela Interna**: Abertura de apps em iframe com sandbox e CSP dinâmica por domínio
- **Modo Externo**: Redirecionamento para nova aba
- **Modo Smart**: Detecta automaticamente baseado na allowlist
- **Sistema RBAC**: 3 níveis (ADMIN, MANAGER, USER)
- **Favoritos**: Sistema de pins por usuário
- **Categorias**: Organização visual dos apps
- **Busca & Filtros**: Pesquisa por nome/tags e filtro por categoria
- **Métricas**: Dashboard com estatísticas de uso (7/30/90 dias)
- **Dark/Light Mode**: Toggle com persistência no localStorage
- **PWA Ready**: Service Worker e manifest.json inclusos

### 🛡️ Segurança
- **CSP Dinâmica**: `frame-src` limitada aos domínios da allowlist
- **Sandbox Iframe**: `allow-scripts allow-same-origin allow-forms`
- **Password Hashing**: BCrypt via `password_hash()`
- **CSRF Protection**: Tokens em formulários
- **Rate Limiting**: Proteção contra brute force no login
- **Headers de Segurança**: X-Frame-Options, CSP, X-Content-Type-Options, etc.
- **PDO Prepared Statements**: Proteção contra SQL injection
- **URL Validation**: Rejeição de `javascript:`, `data:`, etc.

### 🎨 UI/UX
- **Tailwind CSS via CDN**: Sem build steps
- **Alpine.js**: Reatividade leve
- **Lucide Icons**: Biblioteca de ícones moderna
- **Design Profissional**: Cards com rounded-2xl, shadows suaves
- **Animações**: Hover states, transitions
- **Responsivo**: Grid adaptativo (2-6 colunas)

---

## 🚀 Stack Tecnológica

- **Backend**: PHP 8.1+ (puro, sem framework pesado)
- **Database**: MySQL 8.0+
- **Frontend**: Tailwind CSS (CDN), Alpine.js, Lucide Icons
- **Arquitetura**: MVC micro, roteador simples
- **Deployment**: cPanel, hospedagem shared (SFTP/ZIP)
- **PWA**: manifest.json + Service Worker

### Sem Build Steps!
Tudo funciona via CDN - basta fazer upload dos arquivos.

---

## 📦 Instalação

### Pré-requisitos
- PHP 8.1 ou superior
- MySQL 8.0 ou superior
- Apache com mod_rewrite ativado
- cPanel ou servidor com suporte a `.htaccess`

### Passo 1: Download/Clone
```bash
git clone https://github.com/cybercode360/cybertool360-php.git
cd cybertool360-php
```

### Passo 2: Configuração do Banco de Dados

1. **Criar o banco de dados**:
   - Acesse o phpMyAdmin do seu cPanel
   - Execute o script `database/migration.sql`
   - Isso criará o banco `cybertool360` e todas as tabelas

2. **Popular com dados de teste** (opcional):
   - Execute o script `database/seed.sql`
   - Isso criará usuários demo, categorias e apps de exemplo

### Passo 3: Configuração da Aplicação

Edite o arquivo `config.php` e ajuste as credenciais:

```php
// Configurações do Banco de Dados
define('DB_HOST', 'localhost');          // Host do MySQL
define('DB_NAME', 'cybertool360');       // Nome do banco
define('DB_USER', 'seu_usuario');        // Usuário do MySQL
define('DB_PASS', 'sua_senha');          // Senha do MySQL

// URL da aplicação
define('APP_URL', 'https://seudominio.com');

// Ambiente
define('APP_ENV', 'production');  // production | development
```

### Passo 4: Upload para o Servidor

#### Via cPanel File Manager:
1. Acesse File Manager → `public_html`
2. Faça upload de **todos os arquivos** exceto `.git`
3. Certifique-se que o `public/` está acessível pela web

#### Via FTP/SFTP:
```bash
# Upload de todos os arquivos
sftp usuario@seuservidor.com
put -r * /public_html/cybertool360/
```

### Passo 5: Configurar Document Root

Se possível, configure o document root para apontar para a pasta `/public`:

**No cPanel:**
1. Domains → Manage → Document Root
2. Altere para: `/home/usuario/public_html/cybertool360/public`

**Caso não seja possível**, mova o conteúdo de `public/` para a raiz:
```bash
mv public/* ./
mv public/.htaccess ./
rm -rf public/
```

E ajuste os paths em `config.php`:
```php
define('PUBLIC_PATH', ROOT_PATH);
```

### Passo 6: Permissões

Garanta que o servidor pode escrever no diretório temporário (para rate limiting):
```bash
chmod 755 /tmp
```

---

## 🔐 Credenciais Padrão

Após executar o `seed.sql`, você terá:

| Nível    | Email                      | Senha    |
|----------|----------------------------|----------|
| ADMIN    | admin@cybercode360.com     | admin123 |
| MANAGER  | manager@cybercode360.com   | admin123 |
| USER     | user@cybercode360.com      | admin123 |

**⚠️ IMPORTANTE**: Altere essas senhas imediatamente em produção!

---

## 📖 Uso

### Acessar a Aplicação
- **Home (Launcher)**: `https://seudominio.com/`
- **Login**: `https://seudominio.com/signin`
- **Admin Panel**: `https://seudominio.com/admin`

### Fluxo Básico

1. **Login** com uma das credenciais acima
2. **Navegue** pelos apps no launcher
3. **Clique em um app** para abrir internamente
4. **Use o menu ⋯** para:
   - Abrir em nova aba
   - Favoritar/Desfavoritar
   - Copiar link

### Painel Administrativo

#### MANAGER pode:
- ✅ Criar/editar/deletar Apps
- ✅ Criar/editar/deletar Categorias
- ✅ Ver dashboard e métricas básicas

#### ADMIN pode (além do MANAGER):
- ✅ Gerenciar usuários (alterar roles)
- ✅ Ver métricas detalhadas (7/30/90 dias)
- ✅ Deletar usuários

---

## 🏗️ Estrutura do Projeto

```
cybertool360-php/
├── app/
│   ├── Controllers/          # Lógica de negócio
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── AppController.php
│   │   └── AdminController.php
│   ├── Models/               # Acesso aos dados
│   │   ├── Db.php           # Wrapper PDO
│   │   ├── User.php
│   │   ├── App.php
│   │   ├── Category.php
│   │   ├── Pin.php
│   │   └── Metric.php
│   ├── Helpers/              # Funções auxiliares
│   │   ├── Auth.php         # Autenticação/RBAC
│   │   ├── Response.php     # Respostas HTTP
│   │   └── Security.php     # Segurança/CSP
│   └── Views/                # Templates PHP
│       ├── layouts/
│       ├── home.php
│       ├── internal.php
│       ├── auth/
│       └── admin/
├── database/
│   ├── migration.sql         # Schema do banco
│   └── seed.sql              # Dados de exemplo
├── public/                   # Raiz pública (document root)
│   ├── index.php             # Roteador
│   ├── .htaccess             # Configuração Apache
│   ├── manifest.json         # PWA manifest
│   ├── sw.js                 # Service Worker
│   └── assets/               # Imagens, etc (criar)
├── config.php                # Configurações
└── README.md
```

---

## 🔧 Configuração Avançada

### CSP e Allowlist de Domínios

Ao criar um app, você pode definir:

**Open Mode:**
- `INTERNAL`: Sempre abre no iframe (requer allowlist)
- `EXTERNAL`: Sempre redireciona para nova aba
- `SMART`: Usa iframe se houver allowlist, senão externa

**Allowlist Domains** (separados por vírgula):
```
example.com, *.example.com, subdomain.example.com
```

Isso gera uma CSP dinâmica:
```
Content-Security-Policy: frame-src 'self' https://example.com https://*.example.com;
```

### Rate Limiting

Por padrão, o login permite 5 tentativas em 15 minutos. Ajuste em `config.php`:
```php
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // segundos
```

### Sessão

Configurável em `config.php`:
```php
define('SESSION_LIFETIME', 7200); // 2 horas
```

### PWA

Para ativar o PWA, adicione no `<head>` das views:
```html
<link rel="manifest" href="/manifest.json">
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js');
  }
</script>
```

---

## 🎨 Personalização

### Trocar Cores/Tema

Edite `tailwind.config` inline nas views (ex: `home.php`):
```javascript
tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: {
                    500: '#0ea5e9',  // Sua cor primária
                    600: '#0284c7',
                }
            }
        }
    }
}
```

### Adicionar Ícones Personalizados

Use qualquer URL de imagem no campo "Ícone" ao criar um app:
```
https://exemplo.com/icone.png
```

Ou ícones inline SVG/Base64.

---

## 🐛 Troubleshooting

### Erro 500 - Internal Server Error
- Verifique as permissões dos arquivos (644 para arquivos, 755 para pastas)
- Ative o modo de desenvolvimento em `config.php` para ver o erro real
- Verifique se o mod_rewrite está ativo: `apache2ctl -M | grep rewrite`

### Erro de Conexão ao Banco
- Confirme as credenciais em `config.php`
- Teste a conexão via phpMyAdmin
- Certifique-se que o banco `cybertool360` existe

### Apps não abrem no iframe
- Verifique se a allowlist está correta
- Alguns sites bloqueiam iframes via `X-Frame-Options` (ex: Google, Facebook)
- Use modo EXTERNAL para esses casos

### CSS/JS não carregam
- Verifique a CSP no console do navegador
- Ajuste os headers em `Helpers/Security.php` se necessário
- Limpe o cache do navegador

### Dark mode não persiste
- Verifique se localStorage está habilitado
- Teste em modo anônimo/privado

---

## 📊 Banco de Dados

### Schema Resumido

**users**
- id, email, password_hash, name, role, avatar_url
- role: ENUM('ADMIN','MANAGER','USER')

**categories**
- id, name, slug, icon, order

**apps**
- id, name, slug, description, url, open_mode, allowlist_domains, icon, tags, active, order, category_id
- open_mode: ENUM('INTERNAL','EXTERNAL','SMART')

**pins**
- id, user_id, app_id, order
- Relação usuário ↔ apps favoritados

**metrics**
- id, app_id, opened_at, user_id, user_agent, referrer
- Log de aberturas para analytics

---

## 🚀 Deploy em Produção

### Checklist

- [ ] Alterar `APP_ENV` para `production` em `config.php`
- [ ] Trocar senhas padrão dos usuários demo
- [ ] Desabilitar display_errors (já configurado em production mode)
- [ ] Habilitar HTTPS (descomente o redirect em `.htaccess`)
- [ ] Configurar backups automáticos do banco de dados
- [ ] Testar todos os fluxos (login, apps, admin)
- [ ] Verificar permissões de arquivos (644/755)
- [ ] Adicionar ícones PWA personalizados em `public/assets/`
- [ ] Configurar monitoramento de erros (Sentry, etc)

### Performance

- ✅ Tailwind via CDN (sem build)
- ✅ Headers de cache configurados (.htaccess)
- ✅ GZIP compression ativada
- ✅ Service Worker para cache offline
- ✅ Lazy loading de iframes

### Backup

Recomendado fazer backup regular de:
1. **Banco de dados**: Via phpMyAdmin ou `mysqldump`
2. **Uploads** (se houver): Pasta `public/assets/`
3. **Configuração**: Arquivo `config.php`

```bash
# Backup MySQL via CLI
mysqldump -u usuario -p cybertool360 > backup_$(date +%Y%m%d).sql
```

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Para contribuir:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

---

## 📝 Roadmap

- [ ] Upload de ícones via interface
- [ ] Suporte a temas customizáveis
- [ ] API REST para integração externa
- [ ] Notificações push (PWA)
- [ ] Multi-tenancy (múltiplas organizações)
- [ ] Integração com SSO/OAuth
- [ ] Export/Import de apps (JSON)
- [ ] Analytics avançados (gráficos com Chart.js)

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 👨‍💻 Autor

**CyberCode360**
- Website: [cybercode360.com](https://cybercode360.com)
- Email: contato@cybercode360.com

---

## 🙏 Agradecimentos

- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- [Alpine.js](https://alpinejs.dev) - Framework JS reativo
- [Lucide](https://lucide.dev) - Biblioteca de ícones
- Comunidade PHP e open source

---

**Desenvolvido com ❤️ pela CyberCode360**
