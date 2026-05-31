-- ========================================================
-- MONTERO STUDIO - DATABASE MIGRATION SCRIPT (POSTGRESQL)
-- ========================================================
-- Copy and paste this script into your Supabase SQL Editor and click "Run".

-- 1. Create Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Create Site Settings Table
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT NOT NULL
);

-- 3. Create Services Table
CREATE TABLE IF NOT EXISTS services (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(50) NOT NULL,
    bullet_items TEXT NOT NULL,
    sort_order INT DEFAULT 0
);

-- 4. Create Portfolio Projects Table
CREATE TABLE IF NOT EXISTS portfolio_projects (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    subtitle VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    tags VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    external_link VARCHAR(255) DEFAULT '#',
    category VARCHAR(50) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Truncate existing data to prevent duplicates
TRUNCATE TABLE site_settings CASCADE;
TRUNCATE TABLE services CASCADE;
TRUNCATE TABLE portfolio_projects CASCADE;
TRUNCATE TABLE admins CASCADE;

-- Insert default admin user (username: admin, password: admin123)
-- Please change this password in your admin panel after logging in!
INSERT INTO admins (username, password_hash) VALUES 
('admin', '$2y$10$2PVKNIL2t8JrRSdMxUETEu138DGImSv0FbChghVW9/oDFBTp0Mfb2');

-- Seed Settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'MONTERO STUDIO - DISEÑO VISUAL & DESARROLLO WEB'),
('site_description', 'MONTERO STUDIO es un portafolio profesional de diseño visual de alto nivel y desarrollo web limpio, rápido y seguro.'),
('hero_title_part1', 'Transformo Ideas en'),
('hero_title_part2', 'Experiencias Digitales'),
('hero_description', 'Hola, soy MONTERO STUDIO. Combino la precisión del desarrollo web con la magia del diseño visual para crear portales rápidos, interactivos y funcionales.'),
('about_subtitle', 'Sobre Mí'),
('about_title_part1', 'El Arte del Diseño Visual'),
('about_title_part2', 'y la Ingeniería de Software'),
('about_intro', 'Fusionando lo estético con lo funcional'),
('about_biography', 'En MONTERO STUDIO no creo en las páginas web aburridas ni en las plantillas saturadas. Diseño con un propósito visual y programo con código limpio, rápido y seguro. Como desarrollador independiente y diseñador visual, entiendo que tu marca necesita destacar en segundos. Por eso creo sistemas Single Page Application (SPA) que ofrecen velocidades de carga asombrosas y una estética que capta la atención.'),
('stat_exp_val', '1+'),
('stat_exp_lbl', 'Años de Exp.'),
('stat_projects_val', '10+'),
('stat_projects_lbl', 'Proyectos'),
('stat_commit_val', '100%'),
('stat_commit_lbl', 'Compromiso'),
('footer_description', 'Diseño visual y desarrollo web a nivel de creador independiente. Creo soluciones digitales limpias, funcionales y adaptadas a tus objetivos.');

-- Seed Services
INSERT INTO services (title, description, icon, bullet_items, sort_order) VALUES
('Diseño Visual & Gráfico', 'Llevo la estética de tu marca al siguiente nivel. Creo logotipos, paletas de color equilibradas (como nuestro azul índigo) y manuales de identidad visual que conectan con tu audiencia.', 'bx-brush', 'Logotipo principal e imagotipos
Manuales de uso de marca completos
Diseño de empaque y papelería', 0),
('Diseño UI/UX Equilibrado', 'Diseño interfaces estéticas y equilibradas antes de programar. Creo prototipos navegables en Figma pensando siempre en la máxima fluidez tecnológica.', 'bx-layer', 'Prototipos de alta fidelidad interactivos
Pruebas de usabilidad y wireframes
Adaptabilidad responsive garantizada', 1),
('Programación Web & SPA', 'Programación web a nivel de aplicaciones SPA. Escribimos código limpio, seguro y estructurado en PHP moderno para garantizar que el motor de tu sitio funcione con una velocidad y fluidez impecables.', 'bx-code-alt', 'Sitios dinámicos sin recargas molestas
Backend rápido estructurado en PHP
API RESTful y seguridad de datos', 2),
('Seguridad & Rendimiento', 'La seguridad y la fluidez son prioridad. Implementamos código seguro contra vulnerabilidades, optimización profunda y buenas prácticas tecnológicas para que tu plataforma sea robusta y confiable.', 'bx-line-chart', 'Puntuaciones altas en Core Web Vitals
Estructura semántica HTML5 pura
Indexación XML y SEO on-page', 3);

-- Seed Portfolio Projects
INSERT INTO portfolio_projects (title, subtitle, description, tags, image_path, external_link, category, sort_order) VALUES
('AeroBranding', 'Identidad Corporativa', 'Branding ecológico premium diseñado para una aerolínea de movilidad sostenible. Cree una paleta de colores limpia inspirada en el aire libre, logotipo vectorial geométrico, papelería institucional y guías de estilos tipográficos modernos.', 'Branding, Logotipos, Manual de Marca, Vector', 'aerobranding.png', '#', 'design', 0),
('NovaCommerce', 'Desarrollo Web SPA', 'E-commerce moderno desarrollado como una Single Page Application (SPA). Cuenta con pasarela de pago simulada por AJAX, administración de stock dinámico en base de datos MySQL de Laragon y un enrutador CSS reactivo ultrarápido.', 'PHP, MySQL, JavaScript ES6, Tailwind CSS', 'novacommerce.png', '#', 'web', 1),
('Valkyria Esports', 'Diseño UI/UX & Figma', 'Prototipo de aplicación móvil y web para una comunidad competitiva de videojuegos. Desarrollé esquemas de flujos (wireframes), prototipos interactivos animados en Figma y logotipos deportivos dinámicos de alto nivel.', 'Figma, UI/UX, Logotipos, Prototipos', 'valkyria.png', '#', 'design', 2),
('Cryptic Wallet', 'Web Application Dashboard', 'Dashboard interactivo para visualizar precios e inventarios de criptoactivos. Integra librerías de gráficos en JS, comunicación segura con APIs públicas mediante AJAX en PHP y arquitectura de componentes responsivos.', 'JavaScript, PHP, API REST, Charts.js', 'cryptic.png', '#', 'web', 3);
