# Contribuindo para CyberTool360

Obrigado por considerar contribuir com o CyberTool360! 🎉

## Como Contribuir

### Reportar Bugs

Se você encontrou um bug, por favor abra uma issue com:

- **Título claro e descritivo**
- **Passos para reproduzir** o problema
- **Comportamento esperado** vs **comportamento atual**
- **Screenshots** (se aplicável)
- **Ambiente**: PHP version, MySQL version, navegador, OS

### Sugerir Funcionalidades

Adoramos receber sugestões! Abra uma issue com:

- **Descrição clara** da funcionalidade
- **Justificativa**: Por que é útil?
- **Exemplos** de uso
- **Mockups** ou wireframes (opcional)

### Pull Requests

1. **Fork** o repositório
2. **Crie uma branch** para sua feature:
   ```bash
   git checkout -b feature/minha-feature
   ```
3. **Desenvolva** sua feature
4. **Teste** localmente
5. **Commit** com mensagens claras:
   ```bash
   git commit -m "feat: adiciona suporte a upload de ícones"
   ```
6. **Push** para seu fork:
   ```bash
   git push origin feature/minha-feature
   ```
7. **Abra um Pull Request** para o branch `main`

### Padrões de Código

#### PHP
- **PSR-12** code style
- **Type hints** sempre que possível
- **Comentários** para lógica complexa
- **Prepared statements** para queries
- **Namespaces** seguindo PSR-4

#### HTML/CSS
- **Indentação**: 4 espaços
- **Tailwind classes**: ordem consistente
- **Alpine.js**: x-data no elemento raiz

#### JavaScript
- **ES6+** syntax
- **Async/await** ao invés de callbacks
- **Const/let** ao invés de var

#### Commits
Seguimos [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` Nova funcionalidade
- `fix:` Correção de bug
- `docs:` Documentação
- `style:` Formatação (sem mudança de código)
- `refactor:` Refatoração
- `test:` Testes
- `chore:` Manutenção

Exemplos:
```bash
feat: adiciona modo de edição inline para apps
fix: corrige CSP para subdomínios wildcard
docs: atualiza README com instruções de Docker
refactor: separa lógica de autenticação em trait
```

### Testes

Antes de submeter um PR, teste:

- ✅ Login/Logout funciona
- ✅ RBAC está correto (testar USER, MANAGER, ADMIN)
- ✅ Apps abrem corretamente (interno e externo)
- ✅ CRUD de apps/categorias funciona
- ✅ Favoritos funcionam
- ✅ Dark mode persiste
- ✅ Busca e filtros funcionam
- ✅ Métricas são registradas
- ✅ Responsividade (mobile, tablet, desktop)
- ✅ Cross-browser (Chrome, Firefox, Safari)

### Estrutura do Projeto

```
app/
├── Controllers/    # Lógica de negócio
├── Models/         # Acesso a dados
├── Helpers/        # Funções auxiliares
└── Views/          # Templates PHP
```

### Adicionando um Novo Controller

1. Crie em `app/Controllers/MeuController.php`
2. Namespace: `namespace App\Controllers;`
3. Métodos públicos e estáticos
4. Use helpers: `Auth`, `Response`, `Security`
5. Adicione rotas em `public/index.php`

### Adicionando um Novo Model

1. Crie em `app/Models/MeuModel.php`
2. Namespace: `namespace App\Models;`
3. Use `Db::query()`, `Db::queryOne()`, `Db::execute()`
4. Retorne arrays ou null
5. Métodos estáticos

### Adicionando uma Nova View

1. Crie em `app/Views/minha-view.php`
2. Use `Response::view('minha-view', $data)`
3. Acesse variáveis diretamente: `<?= $variavel ?>`
4. Siga o padrão das views existentes

## Código de Conduta

### Nossos Compromissos

- 🤝 Ser respeitoso e inclusivo
- 💬 Comunicação construtiva
- 🎯 Foco no que é melhor para a comunidade
- 🤗 Empatia com outros membros

### Comportamentos Inaceitáveis

- ❌ Linguagem ofensiva ou discriminatória
- ❌ Trolling ou comentários insultuosos
- ❌ Assédio público ou privado
- ❌ Divulgação de informações privadas

## Dúvidas?

- 📧 Email: contato@cybercode360.com
- 💬 Abra uma issue de discussão
- 📚 Leia o README.md e INSTALL.md

---

**Obrigado por contribuir! 🚀**
