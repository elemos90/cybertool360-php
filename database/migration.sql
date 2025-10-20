-- CyberTool360 - Database Migration
-- Execute este script para criar o banco de dados e as tabelas necessárias

CREATE DATABASE IF NOT EXISTS cybertool360
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_0900_ai_ci;

USE cybertool360;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS users (
  id            VARCHAR(24) PRIMARY KEY,
  email         VARCHAR(191) NOT NULL UNIQUE,
  password_hash VARCHAR(191) NOT NULL,
  name          VARCHAR(120),
  role          ENUM('ADMIN','MANAGER','USER') NOT NULL DEFAULT 'USER',
  avatar_url    VARCHAR(512),
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_email (email),
  INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabela de Categorias
CREATE TABLE IF NOT EXISTS categories (
  id         VARCHAR(24) PRIMARY KEY,
  name       VARCHAR(120) NOT NULL,
  slug       VARCHAR(191) NOT NULL UNIQUE,
  icon       VARCHAR(120),
  `order`    INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_categories_slug (slug),
  INDEX idx_categories_order (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabela de Apps
CREATE TABLE IF NOT EXISTS apps (
  id                VARCHAR(24) PRIMARY KEY,
  name              VARCHAR(160) NOT NULL,
  slug              VARCHAR(191) NOT NULL UNIQUE,
  description       TEXT,
  url               VARCHAR(1024) NOT NULL,
  open_mode         ENUM('INTERNAL','EXTERNAL','SMART') NOT NULL DEFAULT 'INTERNAL',
  allowlist_domains TEXT,
  icon              VARCHAR(512),
  tags              VARCHAR(512),
  active            TINYINT(1) NOT NULL DEFAULT 1,
  `order`           INT NOT NULL DEFAULT 0,
  category_id       VARCHAR(24) NOT NULL,
  created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_apps_slug (slug),
  INDEX idx_apps_category (category_id),
  INDEX idx_apps_active (active),
  INDEX idx_apps_order (`order`),
  CONSTRAINT fk_apps_category FOREIGN KEY (category_id)
    REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabela de Pins (Favoritos)
CREATE TABLE IF NOT EXISTS pins (
  id         VARCHAR(24) PRIMARY KEY,
  user_id    VARCHAR(24) NOT NULL,
  app_id     VARCHAR(24) NOT NULL,
  `order`    INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_pin_user_app (user_id, app_id),
  INDEX idx_pins_user (user_id),
  INDEX idx_pins_app (app_id),
  CONSTRAINT fk_pins_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_pins_app  FOREIGN KEY (app_id)  REFERENCES apps(id)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabela de Métricas
CREATE TABLE IF NOT EXISTS metrics (
  id         VARCHAR(24) PRIMARY KEY,
  app_id     VARCHAR(24) NOT NULL,
  opened_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_id    VARCHAR(24),
  user_agent VARCHAR(512),
  referrer   VARCHAR(512),
  INDEX idx_metrics_app (app_id, opened_at),
  INDEX idx_metrics_user (user_id, opened_at),
  INDEX idx_metrics_opened (opened_at),
  CONSTRAINT fk_metrics_app FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_metrics_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
