# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

---

## [1.0.0] - 2025-01-20

### 🎉 Lançamento Inicial

#### Adicionado
- **Launcher responsivo** com grid de apps tipo smartphone
- **Sistema de autenticação** completo (login, registro, logout)
- **RBAC** com 3 níveis: ADMIN, MANAGER, USER
- **Janela interna** com iframe sandbox e CSP dinâmica
- **Sistema de favoritos** (pins) por usuário
- **Categorias** para organização de apps
- **Busca e filtros** por nome, tags e categoria
- **Painel administrativo** completo:
  - Dashboard com métricas
  - CRUD de Apps
  - CRUD de Categorias
  - Gestão de usuários (ADMIN)
  - Métricas de uso (7/30/90 dias)
- **Dark/Light mode** com persistência
- **PWA ready** (manifest.json + service worker)
- **Segurança**:
  - CSP dinâmica por app
  - Iframe sandbox
  - CSRF protection
  - Rate limiting no login
  - Password hashing (BCrypt)
  - Headers de segurança
- **UI profissional** com Tailwind CSS
- **Ícones** com Lucide
- **Reatividade** com Alpine.js

#### Segurança
- PDO prepared statements
- Validação de URLs (rejeita javascript:, data:)
- Session security (httponly, secure, samesite)
- X-Frame-Options, CSP, X-Content-Type-Options

#### Performance
- Tailwind via CDN (sem build)
- Cache headers configurados
- GZIP compression
- Service Worker para offline

#### Documentação
- README completo
- INSTALL.md com guia passo-a-passo
- Comentários no código
- SQL migrations e seeds

---

## [Unreleased] - Roadmap

### Planejado
- [ ] Upload de ícones via interface
- [ ] Temas customizáveis
- [ ] API REST
- [ ] Notificações push (PWA)
- [ ] Multi-tenancy
- [ ] SSO/OAuth
- [ ] Export/Import de apps (JSON)
- [ ] Analytics com gráficos (Chart.js)
- [ ] Modo offline completo
- [ ] Suporte a múltiplos idiomas (i18n)

### Em Consideração
- [ ] Docker support
- [ ] Composer para gerenciamento de dependências
- [ ] Testes automatizados (PHPUnit)
- [ ] CI/CD pipeline
- [ ] WebSockets para notificações real-time

---

## Formato do Changelog

### Tipos de Mudanças
- **Adicionado** (Added): Novas funcionalidades
- **Alterado** (Changed): Mudanças em funcionalidades existentes
- **Descontinuado** (Deprecated): Funcionalidades que serão removidas
- **Removido** (Removed): Funcionalidades removidas
- **Corrigido** (Fixed): Correções de bugs
- **Segurança** (Security): Vulnerabilidades corrigidas

---

**Desenvolvido com ❤️ pela CyberCode360**
