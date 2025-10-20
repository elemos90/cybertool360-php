# ✅ Checklist de Produção - CyberTool360
## cybertool360.cycode.net

---

## 📋 Pré-Deploy

### Configuração Local
- [ ] Testar localmente com todas as funcionalidades
- [ ] Verificar que não há erros no console (F12)
- [ ] Testar em diferentes navegadores (Chrome, Firefox, Safari)
- [ ] Testar responsividade (mobile, tablet, desktop)
- [ ] Verificar que todos os links funcionam

### Arquivos de Configuração
- [x] ✅ `config.php` atualizado com credenciais de produção
- [x] ✅ `APP_ENV` definido como `production`
- [x] ✅ `APP_URL` definido como `https://cybertool360.cycode.net`
- [x] ✅ Credenciais do banco configuradas
- [x] ✅ `.htaccess` com HTTPS redirect ativado

---

## 🚀 Durante o Deploy

### 1. Banco de Dados
- [ ] Acessar phpMyAdmin no cPanel
- [ ] Selecionar banco: `cycodene_cybertool360`
- [ ] Importar `database/migration.sql`
- [ ] Verificar que todas as 5 tabelas foram criadas:
  - [ ] users
  - [ ] categories
  - [ ] apps
  - [ ] pins
  - [ ] metrics
- [ ] Importar `database/seed.sql` (dados de exemplo)
- [ ] Verificar que foram criados:
  - [ ] 3 usuários (admin, manager, user)
  - [ ] 6 categorias
  - [ ] 13 apps de exemplo

### 2. Upload de Arquivos
- [ ] Compactar projeto em ZIP (excluindo .git, node_modules)
- [ ] Acessar cPanel → File Manager
- [ ] Navegar até `public_html/`
- [ ] Criar pasta `cybertool360` (se necessário)
- [ ] Upload do ZIP
- [ ] Extrair arquivos
- [ ] Verificar estrutura de pastas:
  ```
  cybertool360/
  ├── app/
  ├── database/
  ├── public/
  ├── config.php
  └── ...
  ```

### 3. Document Root
- [ ] Acessar cPanel → Domains → Manage
- [ ] Configurar Document Root para: `/home/cycodene/public_html/cybertool360/public`
- [ ] OU mover conteúdo de `public/` para raiz (se não tiver acesso)

### 4. Permissões
- [ ] Via SSH ou File Manager, definir:
  - [ ] Arquivos: 644 (`find . -type f -exec chmod 644 {} \;`)
  - [ ] Diretórios: 755 (`find . -type d -exec chmod 755 {} \;`)
  - [ ] config.php: 640 (`chmod 640 config.php`)

### 5. SSL/HTTPS
- [ ] cPanel → SSL/TLS Status
- [ ] Instalar certificado SSL (Let's Encrypt - AutoSSL)
- [ ] Aguardar 1-2 minutos
- [ ] Verificar que https:// funciona
- [ ] Testar redirect HTTP → HTTPS

---

## 🔒 Pós-Deploy - Segurança

### Senhas
- [ ] **CRÍTICO**: Alterar senha do usuário ADMIN
- [ ] Alterar senha do usuário MANAGER
- [ ] Alterar senha do usuário USER
- [ ] Ou deletar usuários demo e criar novos

**Como alterar:**
```sql
-- Via phpMyAdmin, execute:
-- Gere hash: php -r "echo password_hash('NovaSenhaForte123!', PASSWORD_BCRYPT);"

UPDATE users 
SET password_hash = '$2y$10$NOVO_HASH_GERADO' 
WHERE email = 'admin@cybercode360.com';
```

### Verificações de Segurança
- [ ] Testar rate limiting (tentar login errado 5x)
- [ ] Verificar que erros PHP não são exibidos (modo production)
- [ ] Testar CSRF protection nos formulários
- [ ] Verificar headers de segurança (F12 → Network → Headers):
  - [ ] Content-Security-Policy
  - [ ] X-Frame-Options
  - [ ] X-Content-Type-Options
  - [ ] Referrer-Policy
- [ ] Testar que config.php não é acessível via browser
- [ ] Verificar que database/ não é acessível via browser

### Backups
- [ ] Configurar backup automático do banco (cPanel → Backup)
- [ ] Configurar backup semanal dos arquivos
- [ ] Testar restauração de backup
- [ ] Configurar notificações por email

---

## ✅ Testes Funcionais

### Autenticação
- [ ] Login com ADMIN funciona
- [ ] Login com MANAGER funciona
- [ ] Login com USER funciona
- [ ] Logout funciona
- [ ] Registro de novo usuário funciona
- [ ] Senha incorreta bloqueia após 5 tentativas

### Launcher (Home)
- [ ] Grid de apps carrega corretamente
- [ ] Apps aparecem com ícones
- [ ] Pesquisa funciona
- [ ] Filtro por categoria funciona
- [ ] Botão "Todos" mostra todos os apps
- [ ] Apps favoritados aparecem primeiro (estrela amarela)

### Apps
- [ ] Clicar em app abre janela interna
- [ ] Iframe carrega o conteúdo
- [ ] Botão "Voltar" funciona
- [ ] Botão "Home" funciona
- [ ] Botão "Recarregar" funciona
- [ ] Botão "Nova aba" abre externamente
- [ ] Fullscreen funciona
- [ ] Seletor de viewport funciona (414/768/1024/full)

### Favoritos (Pins)
- [ ] Clicar em estrela adiciona aos favoritos
- [ ] Clicar novamente remove dos favoritos
- [ ] Favoritos persistem após refresh
- [ ] Favoritos aparecem primeiro no grid

### Menu Contextual (⋯)
- [ ] Menu abre ao clicar nos 3 pontos
- [ ] "Abrir Interno" funciona
- [ ] "Nova Aba" abre externamente
- [ ] "Favoritar/Desfavoritar" funciona
- [ ] "Copiar Link" copia para clipboard

### Dark Mode
- [ ] Toggle dark/light funciona
- [ ] Preferência persiste após refresh
- [ ] Todas as páginas respeitam o modo

---

## 👨‍💼 Painel Admin

### Dashboard
- [ ] Acesso: https://cybertool360.cycode.net/admin
- [ ] Estatísticas aparecem corretamente
- [ ] Top apps são exibidos
- [ ] Cards com contadores funcionam

### Gestão de Apps
- [ ] Listar apps funciona
- [ ] Criar novo app funciona
- [ ] Editar app funciona
- [ ] Deletar app funciona (com confirmação)
- [ ] Apps inativos não aparecem no launcher

### Gestão de Categorias
- [ ] Listar categorias funciona
- [ ] Criar categoria funciona
- [ ] Editar categoria funciona
- [ ] Deletar categoria funciona
- [ ] Não permite deletar categoria com apps

### Gestão de Usuários (ADMIN only)
- [ ] Apenas ADMIN vê o menu "Usuários"
- [ ] Listar usuários funciona
- [ ] Alterar role funciona
- [ ] Deletar usuário funciona
- [ ] Não permite deletar a si mesmo
- [ ] Não permite alterar próprio role

### Métricas (ADMIN only)
- [ ] Apenas ADMIN vê o menu "Métricas"
- [ ] Sumário de 7 dias funciona
- [ ] Sumário de 30 dias funciona
- [ ] Sumário de 90 dias funciona
- [ ] Top apps são exibidos
- [ ] Gráfico diário funciona

---

## 🎨 Testes de UI/UX

### Responsividade
- [ ] Mobile (< 640px): 2 colunas no grid
- [ ] Tablet (640-1024px): 3-4 colunas
- [ ] Desktop (> 1024px): 5-6 colunas
- [ ] Navbar adapta em mobile
- [ ] Sidebar admin adapta em mobile
- [ ] Formulários responsivos

### Performance
- [ ] Página carrega em < 3 segundos
- [ ] Imagens otimizadas
- [ ] Sem erros no console
- [ ] Lighthouse score > 80 (Performance)
- [ ] Lighthouse score > 90 (A11y, Best Practices, SEO)

### Navegadores
- [ ] Chrome/Edge (Windows)
- [ ] Firefox (Windows)
- [ ] Safari (Mac/iOS)
- [ ] Chrome Mobile (Android)
- [ ] Safari Mobile (iOS)

---

## 🔧 Configurações Opcionais

### PWA (Progressive Web App)
- [ ] Service Worker registrado
- [ ] Manifest.json acessível
- [ ] Ícones PWA adicionados em `public/assets/`
- [ ] "Adicionar à tela inicial" funciona no mobile

### CDN (Cloudflare - Opcional)
- [ ] Conta Cloudflare criada
- [ ] DNS apontando para Cloudflare
- [ ] SSL Full (Strict) configurado
- [ ] Cache rules configuradas
- [ ] Firewall rules (opcional)

### Monitoring
- [ ] UptimeRobot configurado (ou similar)
- [ ] Email alerts configurados
- [ ] Google Analytics instalado (opcional)
- [ ] Error tracking (Sentry - opcional)

---

## 📊 Monitoramento Contínuo

### Semanal
- [ ] Verificar logs de erro: cPanel → Errors
- [ ] Verificar uso de disco: cPanel → Disk Usage
- [ ] Verificar tráfego: cPanel → Visitors
- [ ] Testar funcionalidades críticas
- [ ] Verificar uptime report

### Mensal
- [ ] Backup manual dos arquivos
- [ ] Backup manual do banco de dados
- [ ] Atualizar apps de exemplo (se necessário)
- [ ] Revisar métricas de uso
- [ ] Limpar métricas antigas (> 90 dias)

### Trimestral
- [ ] Revisar e atualizar senhas
- [ ] Verificar certificado SSL (renovação automática)
- [ ] Audit de segurança
- [ ] Review de usuários ativos
- [ ] Atualizar documentação

---

## 🆘 Em Caso de Problemas

### Erro 500
1. [ ] Ativar modo development em `config.php`
2. [ ] Ver erro específico
3. [ ] Verificar logs: cPanel → Errors
4. [ ] Verificar permissões de arquivos
5. [ ] Testar conexão com banco

### Site Fora do Ar
1. [ ] Verificar status do servidor
2. [ ] Verificar DNS: `nslookup cybertool360.cycode.net`
3. [ ] Verificar SSL: `curl -I https://cybertool360.cycode.net`
4. [ ] Contatar suporte do hosting

### Banco de Dados Inacessível
1. [ ] Testar phpMyAdmin
2. [ ] Verificar credenciais em `config.php`
3. [ ] Verificar quotas do banco
4. [ ] Restaurar backup se necessário

---

## ✅ Deploy Concluído!

Quando TODOS os itens estiverem marcados:

🎉 **CyberTool360 está oficialmente em produção!**

**URL:** https://cybertool360.cycode.net

**Próximos passos:**
1. Divulgar aos usuários
2. Monitorar primeiros dias de uso
3. Coletar feedback
4. Iterar e melhorar

---

**Assinatura:**

- [ ] Deploy realizado por: _________________
- [ ] Data: _________________
- [ ] Todos os testes passaram: SIM [ ] NÃO [ ]
- [ ] Senhas alteradas: SIM [ ] NÃO [ ]
- [ ] Backups configurados: SIM [ ] NÃO [ ]

---

**Desenvolvido com ❤️ pela CyberCode360**
