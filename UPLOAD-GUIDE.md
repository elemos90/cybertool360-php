# 📤 Guia Completo de Upload - Correções

## 🎯 Arquivos que DEVEM ser Atualizados

### ✅ OBRIGATÓRIOS (para corrigir CSP)

1. **app/Helpers/Security.php**
   - ✅ Já atualizado localmente
   - ⚠️ PRECISA fazer upload para o servidor

2. **public/sw.js** (Service Worker)
   - ✅ Correção de erro no header
   - ⚠️ PRECISA fazer upload

### ✅ TEMPORÁRIOS (para testes e desbloqueio)

3. **public/clear-ratelimit.php**
   - 🆕 Script para desbloquear login
   - ⏳ Upload e depois DELETAR após uso

4. **public/test-db.php**
   - 🆕 Script para testar conexão ao banco
   - ⏳ Upload e depois DELETAR após uso

---

## 📋 Método 1: Upload Individual via cPanel

### Passo a Passo

1. **Acesse cPanel → File Manager**

2. **Upload do Security.php:**
   ```
   Navegue até: /public_html/cybertool360/app/Helpers/
   Upload: Security.php (substituir existente)
   ✅ Confirme a substituição
   ```

3. **Upload do sw.js:**
   ```
   Navegue até: /public_html/cybertool360/public/
   Upload: sw.js (substituir existente)
   ✅ Confirme a substituição
   ```

4. **Upload dos scripts temporários:**
   ```
   Navegue até: /public_html/cybertool360/public/
   Upload: clear-ratelimit.php (novo arquivo)
   Upload: test-db.php (novo arquivo)
   ```

---

## 📋 Método 2: Upload via FTP/SFTP

```bash
# Conectar ao servidor
sftp cycodene_webapp@57.128.126.160

# Navegar até o diretório
cd public_html/cybertool360

# Upload Security.php
cd app/Helpers
put Security.php

# Upload sw.js
cd ../../public
put sw.js

# Upload scripts temporários
put clear-ratelimit.php
put test-db.php

# Verificar
ls -la

# Sair
exit
```

---

## 📋 Método 3: Edição Direta (Security.php)

Se preferir editar diretamente no servidor:

1. **cPanel → File Manager**
2. Navegue até: `/public_html/cybertool360/app/Helpers/Security.php`
3. Clique direito → **Edit**
4. Localize as linhas que contêm `script-src`
5. Adicione `'unsafe-eval'`

**Linha ~128 (método sendCspForInternal):**
```php
// ANTES:
"script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://unpkg.com",

// DEPOIS:
"script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com",
```

**Linha ~152 (método sendSecurityHeaders):**
```php
// ANTES:
"script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://unpkg.com",

// DEPOIS:
"script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com",
```

6. **Salve** (Ctrl+S)

---

## 🧪 Testar Após Upload

### 1️⃣ Desregistrar Service Worker

Antes de testar, limpe o Service Worker antigo:

**No navegador:**
1. Abra: https://cybertool360.cycode.net
2. Pressione **F12** (DevTools)
3. Aba **Application** (ou **Aplicação**)
4. Sidebar → **Service Workers**
5. Clique em **Unregister** (Cancelar registro)
6. **Feche o navegador completamente**

### 2️⃣ Limpar Cache

```
Ctrl + Shift + Delete
- Marcar: Cache e cookies
- Período: Últimas 24 horas
- Limpar
```

### 3️⃣ Testar Conexão ao Banco

```
https://cybertool360.cycode.net/test-db.php
```

Deve mostrar:
- ✅ Conexão estabelecida
- ✅ Número de usuários
- ✅ Hash da senha correto

### 4️⃣ Desbloquear Rate Limit

```
https://cybertool360.cycode.net/clear-ratelimit.php
```

Deve mostrar:
- ✅ "Rate Limit Limpo!"
- ✅ Botão "Fazer Login"

### 5️⃣ Testar Login

```
https://cybertool360.cycode.net/signin
```

**Abra Console (F12):**
- ✅ Sem erros de CSP
- ✅ Ícones aparecem
- ✅ Dark mode funciona

**Fazer login:**
- Email: `admin@cybercode360.com`
- Senha: `admin123`

### 6️⃣ Verificar Dashboard

Se o login funcionar:
- ✅ Deve redirecionar para `/`
- ✅ Grid de apps deve aparecer
- ✅ Sidebar e navbar funcionando
- ✅ Sem erros no console

---

## 🗑️ Limpeza Pós-Sucesso

Após login bem-sucedido, **DELETE** via cPanel:

```
/public_html/cybertool360/public/clear-ratelimit.php
/public_html/cybertool360/public/test-db.php
/public_html/cybertool360/FIX-LOGIN.md
/public_html/cybertool360/CSP-FIX.md
/public_html/cybertool360/UPLOAD-GUIDE.md
```

---

## 🆘 Troubleshooting

### Erro: "Ainda aparecem erros de CSP"

**Possíveis causas:**
1. Arquivo não foi atualizado no servidor
2. Cache do navegador ou servidor
3. Service Worker com cache antigo

**Soluções:**
1. Verifique data de modificação do arquivo no servidor
2. Limpe cache do navegador (Ctrl+Shift+R)
3. Desregistre Service Worker (F12 → Application → Service Workers → Unregister)
4. Reset OPcache (cPanel → Select PHP Version → Reset OPcache)

### Erro: "Não consigo fazer upload"

**Verifique:**
- Permissões do usuário FTP
- Espaço em disco disponível
- Tamanho do arquivo (não deve ser problema)

**Alternativa:**
- Use a edição direta no cPanel

### Erro: "Service Worker não carrega"

**Temporariamente:**
1. Renomeie no servidor:
   - `sw.js` → `sw.js.bak`
   - `sw-disabled.js` → `sw.js`
2. Teste sem Service Worker
3. Depois reverta

---

## 📊 Checklist Completo

### Upload
- [ ] ✅ app/Helpers/Security.php
- [ ] ✅ public/sw.js
- [ ] ✅ public/clear-ratelimit.php
- [ ] ✅ public/test-db.php

### Testes
- [ ] ✅ Desregistrar Service Worker
- [ ] ✅ Limpar cache do navegador
- [ ] ✅ Acessar test-db.php (verificar conexão)
- [ ] ✅ Acessar clear-ratelimit.php (desbloquear)
- [ ] ✅ Abrir /signin (F12 - sem erros CSP)
- [ ] ✅ Fazer login (admin@cybercode360.com / admin123)
- [ ] ✅ Verificar dashboard

### Limpeza
- [ ] ✅ Deletar clear-ratelimit.php
- [ ] ✅ Deletar test-db.php
- [ ] ✅ Deletar arquivos .md temporários

---

## 🎯 Status Atual

### Local (Seu Computador)
✅ Security.php - Atualizado  
✅ sw.js - Corrigido  
✅ clear-ratelimit.php - Criado  
✅ test-db.php - Criado  

### Servidor (cybertool360.cycode.net)
⏳ Security.php - **PRECISA SER ATUALIZADO**  
⏳ sw.js - **PRECISA SER ATUALIZADO**  
⏳ Scripts temporários - **PRECISA FAZER UPLOAD**  

---

## ✅ Após Tudo Funcionar

1. **Altere as senhas padrão** (urgente!)
2. **Delete arquivos temporários**
3. **Configure backups**
4. **Adicione seus apps personalizados**
5. **Convide usuários**

---

**🚀 Faça o upload agora e teste em seguida!**
