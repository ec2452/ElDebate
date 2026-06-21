-- ============================================================
--  debate_portal – SQL export
--  Programiranje web aplikacija, TVZ 2025/26
--  Uvoz: phpMyAdmin > Import > odaberi ovu datoteku
-- ============================================================

SET NAMES utf8;

CREATE DATABASE IF NOT EXISTS `debate_portal`
    CHARACTER SET utf8
    COLLATE utf8_croatian_ci;

USE `debate_portal`;

-- -----------------------------------------------------------
--  Tablica vijesti
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `vijesti`;
CREATE TABLE `vijesti` (
    `id`         INT(11)      NOT NULL AUTO_INCREMENT,
    `datum`      VARCHAR(32)  NOT NULL,
    `naslov`     VARCHAR(255) NOT NULL,
    `sazetak`    TEXT,
    `tekst`      TEXT,
    `slika`      VARCHAR(255),
    `kategorija` VARCHAR(64)  NOT NULL DEFAULT 'mundo',
    `arhiva`     TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- Demo vijesti (slike su priložene u /img kao stilizirane ilustracije)
INSERT INTO `vijesti` (`datum`, `naslov`, `sazetak`, `tekst`, `slika`, `kategorija`, `arhiva`) VALUES
('18.05.2019.', 'Tigres vs Monterrey, minuto a minuto semifinales Liga MX',
 'Medio tiempo, Tigres vence 1-0 a Monterrey con un gol de Pizarro',
 'Tigres recibe al Monterrey con una espina clavada y una desventaja de un gol que al momento lo tiene fuera de su sexta final en los últimos 10 torneos. Después de perder 1-0 ante Rayados en su visita al Estadio BBVA Bancomer, Tigres llega herido y con la esperanza y motivación de darle la vuelta a las semifinales en su estadio ante su gente.',
 'tigres-monterrey.jpg', 'deporte', 0),

('18.05.2019.', 'Boeing reconoce defectos en software del simulador del 737 MAX',
 'La compañía dice que ha identificado un problema con el software del simulador de vuelo.',
 'Boeing ha reconocido que tiene defectos en el software del simulador de vuelo del 737 MAX, el mismo modelo de avión que se ha visto involucrado en dos accidentes mortales en los últimos meses.',
 'boeing-737.jpg', 'mundo', 0),

('18.05.2019.', 'Tornados dejan daños en casas del sur de Estados Unidos',
 'Varios tornados tocaron tierra en el sur de Estados Unidos causando daños materiales.',
 'Una serie de tornados azotaron el sur de Estados Unidos causando daños significativos en viviendas y propiedades. Los equipos de emergencia trabajaron toda la noche para ayudar a los afectados.',
 'tornados-eeuu.jpg', 'mundo', 0),

('17.05.2019.', 'María del Rosario Espinoza comparte amargo adiós a los mundiales',
 'La campeona olímpica mexicana se despide de los mundiales de taekwondo.',
 'María del Rosario Espinoza, una de las deportistas más reconocidas de México, anunció su retiro de las competencias mundiales de taekwondo con un emotivo mensaje en redes sociales.',
 'taekwondo-medalla.jpg', 'deporte', 0),

('17.05.2019.', 'Mujer logra increíble transformación al bajar más de 200 kilos',
 'Una mujer comparte su historia de transformación y motivación.',
 'Una mujer de Estados Unidos ha logrado perder más de 200 kilos gracias a cambios radicales en su estilo de vida y con el apoyo de médicos especialistas. Su historia se ha vuelto viral en redes sociales.',
 'transformacion-salud.jpg', 'mundo', 0),

('16.05.2019.', 'Yo decido en mi equipo, si no me marcharía, dice Zidane',
 'El técnico del Real Madrid habla sobre su poder de decisión en el club.',
 'Zinedine Zidane declaró en rueda de prensa que tiene plena autonomía para tomar decisiones deportivas en el Real Madrid y que de no ser así no dudaría en marcharse del club merengue.',
 'zidane-rueda-prensa.jpg', 'deporte', 0);

-- -----------------------------------------------------------
--  Tablica korisnik
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `korisnik`;
CREATE TABLE `korisnik` (
    `id`             INT(11)      NOT NULL AUTO_INCREMENT,
    `ime`            VARCHAR(32)  NOT NULL,
    `prezime`        VARCHAR(32)  NOT NULL,
    `korisnicko_ime` VARCHAR(32)  NOT NULL,
    `lozinka`        VARCHAR(255) NOT NULL,
    `razina`         TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `korisnicko_ime` (`korisnicko_ime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;


INSERT INTO `korisnik` (`ime`, `prezime`, `korisnicko_ime`, `lozinka`, `razina`) VALUES
('Admin', 'Debate', 'admin',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
