-- CyberTool360 - Database Seed
-- Dados iniciais para demonstração

USE cycodene_cybertool360;

-- Usuário Admin (senha: admin123)
INSERT INTO users (id, email, password_hash, name, role) VALUES
('admin001', 'admin@cybercode360.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'ADMIN'),
('manager001', 'manager@cybercode360.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Gestor', 'MANAGER'),
('user001', 'user@cybercode360.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Utilizador', 'USER');

-- Categorias
INSERT INTO categories (id, name, slug, icon, `order`) VALUES
('cat001', 'Produtividade', 'produtividade', 'briefcase', 10),
('cat002', 'Desenvolvimento', 'desenvolvimento', 'code', 20),
('cat003', 'Design', 'design', 'palette', 30),
('cat004', 'Comunicação', 'comunicacao', 'message-circle', 40),
('cat005', 'Analítica', 'analitica', 'bar-chart', 50),
('cat006', 'Ferramentas', 'ferramentas', 'tool', 60);

-- Apps de Exemplo
INSERT INTO apps (id, name, slug, description, url, open_mode, allowlist_domains, icon, tags, active, `order`, category_id) VALUES
-- Produtividade
('app001', 'Google Docs', 'google-docs', 'Editor de documentos online colaborativo', 'https://docs.google.com', 'INTERNAL', 'docs.google.com,*.google.com', 'https://www.gstatic.com/images/branding/product/1x/docs_2020q4_48dp.png', 'docs,office,editor', 1, 10, 'cat001'),
('app002', 'Notion', 'notion', 'Workspace tudo-em-um para notas e projetos', 'https://notion.so', 'INTERNAL', 'notion.so,*.notion.so', 'https://www.notion.so/images/favicon.ico', 'notes,workspace,productivity', 1, 20, 'cat001'),
('app003', 'Trello', 'trello', 'Gestão de projetos visual com quadros Kanban', 'https://trello.com', 'INTERNAL', 'trello.com,*.trello.com', 'https://trello.com/favicon.ico', 'kanban,project,management', 1, 30, 'cat001'),

-- Desenvolvimento
('app004', 'GitHub', 'github', 'Plataforma de desenvolvimento colaborativo', 'https://github.com', 'INTERNAL', 'github.com,*.github.com,githubusercontent.com', 'https://github.com/favicon.ico', 'git,code,repository', 1, 10, 'cat002'),
('app005', 'Stack Overflow', 'stackoverflow', 'Comunidade de perguntas e respostas para programadores', 'https://stackoverflow.com', 'INTERNAL', 'stackoverflow.com,*.stackoverflow.com,stackexchange.com', 'https://stackoverflow.com/favicon.ico', 'qa,programming,community', 1, 20, 'cat002'),
('app006', 'CodePen', 'codepen', 'Editor online para frontend (HTML, CSS, JS)', 'https://codepen.io', 'INTERNAL', 'codepen.io,*.codepen.io', 'https://codepen.io/favicon.ico', 'html,css,javascript,editor', 1, 30, 'cat002'),

-- Design
('app007', 'Figma', 'figma', 'Ferramenta de design colaborativo', 'https://figma.com', 'INTERNAL', 'figma.com,*.figma.com', 'https://static.figma.com/app/icon/1/favicon.png', 'design,ui,ux', 1, 10, 'cat003'),
('app008', 'Canva', 'canva', 'Design gráfico simplificado', 'https://canva.com', 'INTERNAL', 'canva.com,*.canva.com', 'https://www.canva.com/favicon.ico', 'design,graphics,templates', 1, 20, 'cat003'),

-- Comunicação
('app009', 'Gmail', 'gmail', 'Email da Google', 'https://mail.google.com', 'INTERNAL', 'mail.google.com,*.google.com', 'https://ssl.gstatic.com/ui/v1/icons/mail/rfr/gmail.ico', 'email,communication', 1, 10, 'cat004'),
('app010', 'Slack', 'slack', 'Plataforma de comunicação para equipas', 'https://slack.com', 'EXTERNAL', '', 'https://a.slack-edge.com/cebaa/img/ico/favicon.ico', 'chat,team,communication', 1, 20, 'cat004'),

-- Analítica
('app011', 'Google Analytics', 'google-analytics', 'Análise de tráfego web', 'https://analytics.google.com', 'INTERNAL', 'analytics.google.com,*.google.com', 'https://www.gstatic.com/analytics-suite/header/suite/v2/ic_analytics.svg', 'analytics,metrics,web', 1, 10, 'cat005'),

-- Ferramentas
('app012', 'ChatGPT', 'chatgpt', 'Assistente de IA da OpenAI', 'https://chat.openai.com', 'INTERNAL', 'chat.openai.com,openai.com,*.openai.com', 'https://chat.openai.com/favicon.ico', 'ai,assistant,chatbot', 1, 10, 'cat006'),
('app013', 'Google Drive', 'google-drive', 'Armazenamento em nuvem', 'https://drive.google.com', 'INTERNAL', 'drive.google.com,*.google.com', 'https://ssl.gstatic.com/docs/doclist/images/drive_2022q3_32dp.png', 'storage,cloud,files', 1, 20, 'cat006');

-- Pins de exemplo para o admin
INSERT INTO pins (id, user_id, app_id, `order`) VALUES
('pin001', 'admin001', 'app001', 10),
('pin002', 'admin001', 'app004', 20),
('pin003', 'admin001', 'app007', 30);
