-- ============================================================
-- Backstreet Boys Website - Full Database Schema + Seed Data
-- ============================================================

CREATE DATABASE IF NOT EXISTS backstreetboys_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE backstreetboys_db;

-- ============================================================
-- TABLE: members
-- ============================================================
CREATE TABLE IF NOT EXISTS members (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)  NOT NULL,
  nickname    VARCHAR(100)  DEFAULT NULL,
  birthdate   DATE          DEFAULT NULL,
  birthplace  VARCHAR(150)  DEFAULT NULL,
  role        VARCHAR(100)  DEFAULT NULL,
  photo       VARCHAR(255)  DEFAULT NULL,
  bio         TEXT          DEFAULT NULL,
  social_ig   VARCHAR(255)  DEFAULT NULL,
  social_tw   VARCHAR(255)  DEFAULT NULL,
  social_fb   VARCHAR(255)  DEFAULT NULL,
  is_active   TINYINT(1)    DEFAULT 1,
  display_order INT         DEFAULT 0,
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: top_hits
-- ============================================================
CREATE TABLE IF NOT EXISTS top_hits (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  title        VARCHAR(200) NOT NULL,
  album        VARCHAR(200) DEFAULT NULL,
  year_released YEAR        DEFAULT NULL,
  duration     VARCHAR(10)  DEFAULT NULL,
  cover_image  VARCHAR(255) DEFAULT NULL,
  youtube_url  VARCHAR(255) DEFAULT NULL,
  spotify_url  VARCHAR(255) DEFAULT NULL,
  description  TEXT         DEFAULT NULL,
  peak_chart   INT          DEFAULT NULL,
  is_featured  TINYINT(1)   DEFAULT 0,
  is_active    TINYINT(1)   DEFAULT 1,
  display_order INT         DEFAULT 0,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: history (timeline of events)
-- ============================================================
CREATE TABLE IF NOT EXISTS history (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  event_year   YEAR         NOT NULL,
  event_month  TINYINT      DEFAULT NULL,
  title        VARCHAR(255) NOT NULL,
  description  TEXT         DEFAULT NULL,
  image        VARCHAR(255) DEFAULT NULL,
  category     ENUM('Formation','Album','Tour','Award','Milestone','Other') DEFAULT 'Milestone',
  is_active    TINYINT(1)   DEFAULT 1,
  display_order INT         DEFAULT 0,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: albums
-- ============================================================
CREATE TABLE IF NOT EXISTS albums (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  title        VARCHAR(200) NOT NULL,
  year_released YEAR        DEFAULT NULL,
  cover_image  VARCHAR(255) DEFAULT NULL,
  description  TEXT         DEFAULT NULL,
  is_active    TINYINT(1)   DEFAULT 1,
  display_order INT         DEFAULT 0,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: site_settings
-- ============================================================
CREATE TABLE IF NOT EXISTS site_settings (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_val TEXT         DEFAULT NULL,
  updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: admin_users
-- ============================================================
CREATE TABLE IF NOT EXISTS admin_users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(80)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  email      VARCHAR(150) DEFAULT NULL,
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- SEED DATA: members
-- ============================================================
INSERT INTO members (name, nickname, birthdate, birthplace, role, bio, display_order) VALUES
('AJ McLean',      'AJ',        '1978-01-09', 'West Palm Beach, Florida, USA',
 'Baritone / Rapper',
 'Alexander James McLean, known as AJ, is the baritone and rapper of the Backstreet Boys. Known for his distinctive raspy voice and energetic stage presence, AJ has been a cornerstone of the group since its inception in 1993. He is also known for his solo work and philanthropic efforts.',
 1),
('Howie Dorough',  'Howie D',   '1973-08-22', 'Orlando, Florida, USA',
 'Tenor',
 'Howard Dwaine Dorough, known as Howie D, is the tenor of the Backstreet Boys. With his smooth vocals and charismatic personality, Howie has been an essential part of the group\'s harmonies. He is also known for his charitable work and solo music career.',
 2),
('Nick Carter',    'Nick',      '1980-01-28', 'Jamestown, New York, USA',
 'Tenor / Lead Vocalist',
 'Nickolas Gene Carter is the youngest founding member and one of the lead vocalists of the Backstreet Boys. Known for his signature high tenor voice, Nick has been a teen idol since the group\'s formation. He has also pursued a successful solo career and appeared in various television shows.',
 3),
('Kevin Richardson','Kevin',   '1971-10-03', 'Lexington, Kentucky, USA',
 'Bass / Baritone',
 'Kevin Scott Richardson is the bass and baritone of the Backstreet Boys. As the oldest member, Kevin has been described as the group\'s anchor and musical compass. He temporarily left the group in 2006 but rejoined in 2012. He is known for his powerful low notes and piano skills.',
 4),
('Brian Littrell', 'B-Rok',    '1975-02-20', 'Lexington, Kentucky, USA',
 'Tenor / Lead Vocalist',
 'Brian Thomas Littrell is one of the lead vocalists of the Backstreet Boys. Cousin of Kevin Richardson, Brian joined the group in 1993 after being recommended by Kevin. Known for his clear, powerful tenor voice and his Christian faith, Brian has also pursued a solo gospel career.',
 5);

-- ============================================================
-- SEED DATA: albums
-- ============================================================
INSERT INTO albums (title, year_released, description, display_order) VALUES
('Backstreet Boys',        1996, 'International debut album, released first in Germany. Features hits like "We\'ve Got It Goin\' On" and "Quit Playing Games (With My Heart)".', 1),
('Backstreet Boys (US)',   1997, 'US debut album, slightly different track listing from the international version.', 2),
('Backstreet Boys (BSB)',  1997, 'Second international album with massive global hits.', 3),
('Millennium',             1999, 'The group\'s commercial peak. One of the best-selling albums of all time with over 35 million copies sold. Features "I Want It That Way" and "Larger Than Life".', 4),
('Black & Blue',           2000, 'Follow-up to Millennium, debuted at #1 in 26 countries. Sold over 12 million copies worldwide.', 5),
('Never Gone',             2005, 'First studio album after a four-year hiatus, recorded as a quartet after Kevin Richardson took a break.', 6),
('Unbreakable',            2007, 'Second album as a quartet. Features a darker, more mature sound.', 7),
('This Is Us',             2009, 'Seventh studio album, marking the group\'s return to a more pop-oriented sound.', 8),
('In a World Like This',   2013, 'Eighth studio album, first with all five members in years.', 9),
('DNA',                    2019, 'Ninth studio album. Their first #1 album in the US in 20 years.', 10);

-- ============================================================
-- SEED DATA: top_hits
-- ============================================================
INSERT INTO top_hits (title, album, year_released, duration, description, peak_chart, is_featured, display_order) VALUES
('I Want It That Way',        'Millennium',          1999, '3:33', 'One of the most iconic pop songs of the 90s. While it reached #6 on the Billboard Hot 100, it was #1 in many other countries and is often cited as the Backstreet Boys\' signature song.', 6, 1, 1),
('Everybody (Backstreet\'s Back)', 'Backstreet Boys', 1997, '3:54', 'An upbeat dance-pop track with a memorable music video featuring horror movie themes. It became one of their biggest international hits.', 4, 1, 2),
('As Long As You Love Me',    'Backstreet Boys',     1997, '3:31', 'A romantic ballad-pop song that became one of the group\'s signature tracks. It reached #1 in multiple countries and #17 on the Billboard Hot 100.', 17, 1, 3),
('Quit Playing Games (With My Heart)', 'Backstreet Boys', 1996, '3:50', 'Their breakthrough hit in many markets. Known for its emotional music video featuring rain, it became a teen anthem of the late 90s.', 2, 1, 4),
('Larger Than Life',          'Millennium',          1999, '4:00', 'An uptempo dance track dedicated to the group\'s fans. Featured an elaborate space-themed music video.', 25, 1, 5),
('Shape of My Heart',         'Black & Blue',        2000, '4:01', 'A beautiful ballad that showcased the group\'s vocal harmonies. One of their most emotionally resonant songs.', 9, 1, 6),
('Show Me the Meaning of Being Lonely', 'Millennium', 1999, '3:56', 'A heartfelt ballad that became one of their biggest hits in Europe. The music video paid tribute to friends and family members the group had lost.', 6, 1, 7),
('The Call',                  'Black & Blue',        2000, '3:47', 'An upbeat track about a guy lying to his girlfriend while out with another woman. Notable for its driving production.', 39, 0, 8),
('Incomplete',                'Never Gone',          2005, '4:08', 'A solo ballad by Nick Carter on the Backstreet Boys album, showcasing the group\'s emotional depth.', 13, 0, 9),
('In a World Like This',      'In a World Like This',2013, '3:29', 'The lead single from their comeback album featuring all five members. A celebration of their friendship and bond.', NULL, 0, 10);

-- ============================================================
-- SEED DATA: history
-- ============================================================
INSERT INTO history (event_year, event_month, title, description, category, display_order) VALUES
(1993, 4,  'Formation of the Backstreet Boys',
  'The Backstreet Boys were formed in Orlando, Florida, by Lou Pearlman. The original members were AJ McLean, Howie Dorough, Nick Carter, Kevin Richardson, and Brian Littrell. The group got their name from the Backstreet Market, an outdoor flea market in Orlando.',
  'Formation', 1),
(1996, 5,  'International Debut Album Release',
  'The group released their self-titled debut album internationally, starting with Germany. The album was a massive success in Europe and Asia, going platinum in numerous countries.',
  'Album', 2),
(1997, 8,  'US Debut Album Release',
  'The Backstreet Boys released their debut album in the United States, which quickly went multi-platinum. The album produced hit singles including "Quit Playing Games (With My Heart)" and "As Long As You Love Me."',
  'Album', 3),
(1998, NULL, 'Legal Battle with Lou Pearlman',
  'The Backstreet Boys sued their manager Lou Pearlman for fraud and mismanagement of their finances. This marked a turbulent period for the group but ultimately led to greater control over their career.',
  'Milestone', 4),
(1999, 5,  'Millennium Album Release',
  'The group released "Millennium," which became one of the best-selling albums of all time. It sold over 35 million copies worldwide and included hits like "I Want It That Way" and "Larger Than Life."',
  'Album', 5),
(1999, NULL, 'Grammy Nomination',
  '"Millennium" was nominated for Album of the Year at the Grammy Awards, marking the group\'s recognition by the Recording Academy.',
  'Award', 6),
(2000, 10, 'Black & Blue Album Release',
  'Their fourth studio album "Black & Blue" was released and debuted at #1 in 26 countries simultaneously. It featured hit singles "Shape of My Heart" and "The Call."',
  'Album', 7),
(2001, NULL, 'Into the Millennium World Tour',
  'The Backstreet Boys completed one of the highest-grossing concert tours in history, the "Into the Millennium" world tour, breaking attendance records worldwide.',
  'Tour', 8),
(2006, 6,  'Kevin Richardson Departs',
  'Kevin Richardson announced his departure from the Backstreet Boys to pursue other interests. The remaining four members continued as a group.',
  'Milestone', 9),
(2012, 2,  'Kevin Richardson Rejoins',
  'Kevin Richardson officially rejoined the Backstreet Boys, reuniting all five original members. The group celebrated with a concert in Las Vegas.',
  'Milestone', 10),
(2013, 7,  'In a World Like This Album',
  'The Backstreet Boys released "In a World Like This," their first album with all five members since 2000. The album debuted at #1 in several countries.',
  'Album', 11),
(2019, 1,  'DNA Album Release',
  'The group released "DNA," their ninth studio album, which debuted at #1 on the Billboard 200 — their first chart-topping album in the US in 19 years.',
  'Album', 12),
(2022, NULL, 'DNA World Tour',
  'The Backstreet Boys embarked on the DNA World Tour, one of their most extensive concert tours, celebrating 30 years of music and their enduring legacy.',
  'Tour', 13);

-- ============================================================
-- SEED DATA: site_settings
-- ============================================================
INSERT INTO site_settings (setting_key, setting_val) VALUES
('site_title',       'Backstreet Boys - Official Fan Site'),
('site_tagline',     'The World\'s Best-Selling Boy Band'),
('hero_title',       'Backstreet Boys'),
('hero_subtitle',    'The Legacy Lives On'),
('about_text',       'The Backstreet Boys are an American vocal group formed in Orlando, Florida in 1993. The group consists of AJ McLean, Howie Dorough, Nick Carter, Kevin Richardson, and Brian Littrell. They are one of the best-selling music artists of all time, with over 100 million records sold worldwide.'),
('footer_text',      '© 2024 Backstreet Boys Fan Site. All Rights Reserved.');

-- ============================================================
-- SEED DATA: admin_users (password: admin123)
-- ============================================================
INSERT INTO admin_users (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@bsbsite.com');
