-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `bertiknet2`;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `gallery_images`;
CREATE TABLE `gallery_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `item_id` int unsigned NOT NULL,
  `priority` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `gallery_images` (`id`, `category`, `item_id`, `priority`) VALUES
(2,	'piece',	8,	0),
(3,	'piece',	8,	1),
(4,	'piece',	8,	2),
(5,	'piece',	8,	3);

DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Language code',
  `name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Language name',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Table of available languages';

INSERT INTO `language` (`id`, `name`) VALUES
('cs',	'česky'),
('en',	'English');

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int NOT NULL,
  `language` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `translation` text CHARACTER SET utf8 COLLATE utf8_czech_ci,
  PRIMARY KEY (`id`,`language`),
  CONSTRAINT `fk_message_source_message` FOREIGN KEY (`id`) REFERENCES `source_message` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8mb4_czech_ci NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base',	1595866475),
('m200727_161300_gallery',	1595866479);

DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `handle` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'URL handle',
  `created_at` int DEFAULT NULL COMMENT 'Datum vzniku',
  `updated_at` int DEFAULT NULL COMMENT 'Datum poslední úpravy',
  PRIMARY KEY (`id`),
  UNIQUE KEY `handle` (`handle`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Table of statical pages';

INSERT INTO `page` (`id`, `handle`, `created_at`, `updated_at`) VALUES
(10,	'bio',	1594737844,	1594737844);

DROP TABLE IF EXISTS `page_lang`;
CREATE TABLE `page_lang` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `page` int NOT NULL COMMENT 'Stránka',
  `language` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Jazyk',
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Název',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Obsah',
  PRIMARY KEY (`id`),
  KEY `page` (`page`),
  KEY `language` (`language`),
  CONSTRAINT `page_lang_ibfk_1` FOREIGN KEY (`page`) REFERENCES `page` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page_lang_ibfk_2` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Localizations of the static pages';

INSERT INTO `page_lang` (`id`, `page`, `language`, `title`, `content`) VALUES
(9,	10,	'cs',	'BIO',	'<p>Barv&aacute;ch dva nehybn&yacute; ob&aacute;lky tentokr&aacute;t kateřina. A m&aacute;&scaron; couv&aacute; dny ať v&iacute;dně nich m&aacute;mě obličej. Sklapl přesně, sestřenice barynu tě sadů studen&yacute;m u psychick&yacute;m je-li. M&eacute; ten kouta b&aacute;t tě hup to diť&aacute;tko pojďme &ndash; za zas ně ba. Motivu mili&oacute;ny v&iacute; z aut d&iacute;v&aacute; citu nepozn&aacute;me. Poplach ty, si se n&iacute; <strong>tomto</strong>, hodině ve&scaron;kerou zhluboka, jim si chlapče a bližn&iacute;m bar&aacute;ku. Hotelech ho l&iacute;p trhl od steh tahle. Ah tě&scaron;&iacute; mně zač&iacute;n&aacute;&scaron;, hora fl&aacute;mech, vhod j&eacute; dět&iacute; ponět&iacute; prov&eacute;st minul. Křesťansk&eacute;mu, dyť netrp&iacute; gramů lid t&aacute;tovi ledvin! Čest či zaj&iacute;kal oko s baryna brouků. Lež nakoukl n&oacute;bl ty aura sb&iacute;rku ji politice a on proč kdyžs v sepp zvolala referent!</p>\r\n<div class=\"\" style=\"height: 20rem;\" data-parallax=\"scroll\" data-z-index=\"10\" data-image-src=\"/svobodova/web/images/bio.png\">&nbsp;</div>\r\n<p>&nbsp;</p>\r\n<p><strong>Zmate</strong> se hospody, občan&eacute; to stop č&iacute;mž, napsal &oacute; přečtu, &ndash; b&yacute;ti, lov hm ženě &oacute; namoudu&scaron;i &oacute; zdi srdcem. Cos t&eacute; si čern&aacute; položila či osoby. Z&aacute;hy mi z motal sladce siln&iacute; prak nap&iacute;&scaron;u tu d&iacute;v&aacute; a pitom&eacute; cvaklo rozhalenou: tahle pepř st&iacute;n už ah st&aacute;r sporn&aacute;? Uf lev&eacute; jo &scaron;karedil d&aacute; přecev&iacute;te. Pr&aacute;vnicky zv&yacute;&scaron;il mužů ho žalostn&yacute; kl&aacute;st utrhl citů herce syn ni krapet dřevěn&eacute; &scaron;la vinu v&iacute;c těm může&scaron; pak bl&aacute;zen hr&aacute;la &ndash; dozorci gandarův vy začat <strong>pozn&aacute;mky věrou:</strong> drnčel care sahal liber lesů. Oč no akta m&aacute;ry poctiv&eacute; chybět. Ke jet zvenč&iacute; če&scaron;i vůl cennou pojede lu&scaron;tit patě sž&iacute;ran&aacute; k hedv&aacute;bn&eacute; odtud domků bezhlase. Kam mu u&scaron;lechtilost duchem umiňoval natřen&aacute;. U&scaron;lechtil&aacute;, svat&iacute; do služka, prach ať i anilinu t&yacute;k&aacute;. Au pe&scaron;ť vyčuhovala urazit tehdy zkoumavě buďte he n&aacute;&scaron;, div ex kůň přeje host, si on po měl hrnu ptali sahaje, stačil. Skryt&aacute; he ho veramon helence.</p>\r\n<p>Ti &scaron;uplata zap&iacute;&scaron;u k&yacute;m př&iacute;buzn&eacute; k milencem &oacute; advok&aacute;ta spusťte j&eacute; konvenčně i čil&yacute;m. Haf postele osaměl&aacute; kam otv&iacute;r&aacute; b&iacute;l&eacute;. On zn&aacute; &uacute;vahy &ndash; s vola okny! P&aacute;s boku křiv&iacute; wankl řku vrahovi prchaj&iacute;c&iacute;m ti př&iacute;zem&iacute; nu patř&iacute;, ten nehnul h&aacute;zet. Bys ho ti někomu jeř&aacute;by od j&eacute; si t&eacute; ukrutně vezmou. M&iacute;rn&aacute; eh. Pan holčičko zrakov&eacute; žen lidi spasen partii. Au v&aacute;žit i přiznala &oacute; ji ubil. M&iacute;ti potvoro s cel&yacute; my&scaron;i ruku. Pov&iacute;dačku ř&aacute;dkou cti ochrannou neru&scaron;en&eacute; j&iacute;l předmětem pacient belu. Vkl&aacute;dala &scaron;katule: j&aacute;my možn&eacute; &uacute;svitu nahej ale vztyčenou dobr&eacute;.</p>'),
(10,	10,	'en',	'BIO ',	'<p>🐌🤯Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Dignissim suspendisse in est ante in nibh mauris cursus mattis. Volutpat blandit aliquam etiam erat velit scelerisque. In nulla posuere sollicitudin aliquam ultrices sagittis orci a scelerisque. Amet consectetur adipiscing elit duis tristique sollicitudin nibh sit. Adipiscing at in tellus integer feugiat scelerisque varius. In iaculis nunc sed augue lacus viverra vitae congue. Cursus sit amet dictum sit amet justo donec enim. Mollis nunc sed id semper risus. Euismod lacinia at quis risus sed vulputate odio ut enim. Integer vitae justo eget magna fermentum iaculis. Vitae tortor condimentum lacinia quis vel eros donec ac. Ipsum nunc aliquet bibendum enim facilisis gravida neque. In tellus integer feugiat scelerisque varius morbi enim nunc. Aliquet nibh praesent tristique magna sit.</p>\r\n<p>Mauris augue neque gravida in fermentum. Adipiscing vitae proin sagittis nisl rhoncus mattis rhoncus. Velit laoreet id donec ultrices tincidunt arcu. Morbi enim nunc faucibus a pellentesque. Ornare arcu dui vivamus arcu. Amet commodo nulla facilisi nullam. Nunc vel risus commodo viverra maecenas accumsan. Lorem ipsum dolor sit amet consectetur adipiscing elit. Accumsan tortor posuere ac ut. Purus sit amet luctus venenatis lectus magna fringilla urna. Aenean sed adipiscing diam donec adipiscing tristique risus nec feugiat. Et malesuada fames ac turpis egestas integer. Amet consectetur adipiscing elit pellentesque habitant morbi tristique senectus et. Vel facilisis volutpat est velit egestas dui id. Massa ultricies mi quis hendrerit dolor magna eget.</p>\r\n<div class=\"\" style=\"height: 20rem;\" data-parallax=\"scroll\" data-z-index=\"10\" data-image-src=\"/svobodova/web/images/bio.png\">&nbsp;</div>\r\n<p>&nbsp;</p>\r\n<p>Ullamcorper dignissim cras tincidunt lobortis feugiat vivamus at. Sed blandit libero volutpat sed cras. Eu feugiat pretium nibh ipsum consequat nisl vel pretium lectus. Euismod quis viverra nibh cras pulvinar. Tempor nec feugiat nisl pretium fusce id velit. Commodo viverra maecenas accumsan lacus vel facilisis volutpat est velit. Scelerisque varius morbi enim nunc faucibus a pellentesque sit amet. Blandit libero volutpat sed cras ornare arcu dui vivamus. Ac ut consequat semper viverra nam libero. Bibendum ut tristique et egestas quis ipsum. Scelerisque fermentum dui faucibus in ornare quam viverra. Aliquet eget sit amet tellus cras. Tincidunt dui ut ornare lectus sit amet est. Leo urna molestie at elementum eu facilisis sed odio morbi. Consectetur a erat nam at lectus.</p>\r\n<p>Platea dictumst quisque sagittis purus sit. Fermentum iaculis eu non diam phasellus vestibulum lorem. Nisl suscipit adipiscing bibendum est ultricies integer. Tellus elementum sagittis vitae et. Morbi tristique senectus et netus et malesuada fames. Pulvinar etiam non quam lacus suspendisse faucibus interdum. Volutpat commodo sed egestas egestas fringilla phasellus. Dui faucibus in ornare quam viverra. Vitae tortor condimentum lacinia quis vel eros donec ac. Enim eu turpis egestas pretium aenean. Egestas fringilla phasellus faucibus scelerisque eleifend donec pretium. Scelerisque in dictum non consectetur a erat nam at lectus. Malesuada proin libero nunc consequat interdum.</p>\r\n<p>Pellentesque pulvinar pellentesque habitant morbi tristique senectus et. Non arcu risus quis varius quam. Vel fringilla est ullamcorper eget. Blandit turpis cursus in hac. Purus viverra accumsan in nisl nisi scelerisque eu ultrices vitae. Montes nascetur ridiculus mus mauris vitae. Integer malesuada nunc vel risus commodo viverra. Eu consequat ac felis donec. Pharetra diam sit amet nisl suscipit adipiscing bibendum. Leo duis ut diam quam nulla porttitor. Libero nunc consequat interdum varius sit amet mattis vulputate enim. Lobortis elementum nibh tellus molestie. Tristique senectus et netus et malesuada fames ac <strong>turpis egestas.</strong></p>');

DROP TABLE IF EXISTS `piece`;
CREATE TABLE `piece` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `handle` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'URL Handle',
  `thumbnail` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Miniatura',
  `vimeo` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci DEFAULT NULL COMMENT 'Vimeo ID',
  `photo` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci DEFAULT NULL COMMENT 'Úvodní obrázek',
  `cover` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Obrázek v průhledu',
  `date` date NOT NULL COMMENT 'Datum vzniku',
  `created_at` int NOT NULL COMMENT 'Datum vytvoření záznamu',
  `updated_at` int NOT NULL COMMENT 'Datum poslední změny',
  PRIMARY KEY (`id`),
  UNIQUE KEY `handle` (`handle`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Tabulka výtvorů';

INSERT INTO `piece` (`id`, `handle`, `thumbnail`, `vimeo`, `photo`, `cover`, `date`, `created_at`, `updated_at`) VALUES
(4,	'desorientation',	'uploads/thumbnail/desorientation.png',	'435282266',	'uploads/cover/desorientation.png',	'uploads/cover/desorientation.png',	'2013-07-20',	1595073889,	1595874171),
(5,	'zkouska',	'uploads/thumbnail/zkouska.png',	NULL,	NULL,	'uploads/cover/zkouska.png',	'1994-07-14',	1595430175,	1595430175),
(7,	'asfafs',	'uploads/thumbnail/asfafs.jpg',	NULL,	NULL,	'uploads/cover/asfafs.jpg',	'2020-07-09',	1595866860,	1595866860),
(8,	'ff',	'uploads/thumbnail/ff.png',	NULL,	NULL,	'uploads/cover/ff.png',	'2010-11-04',	1595866898,	1595866898),
(9,	'gasga',	'uploads/thumbnail/gasga.jpg',	NULL,	NULL,	'uploads/cover/gasga.jpg',	'2017-06-02',	1595868447,	1595868447);

DROP TABLE IF EXISTS `piece_lang`;
CREATE TABLE `piece_lang` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `piece` int NOT NULL COMMENT 'Dílo',
  `language` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Jazyk',
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Název',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Obsah',
  PRIMARY KEY (`id`),
  KEY `piece` (`piece`),
  KEY `language` (`language`),
  CONSTRAINT `piece_lang_ibfk_1` FOREIGN KEY (`piece`) REFERENCES `piece` (`id`) ON DELETE CASCADE,
  CONSTRAINT `piece_lang_ibfk_2` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `piece_lang` (`id`, `piece`, `language`, `title`, `content`) VALUES
(5,	4,	'cs',	'(Des)orientation',	'<p>3 min</p>\r\n<p>Dva nesourod&eacute; světy. D&aacute;vno minul&eacute; osoby, my&scaron;lenky a situace jsou v kontrastu<br />se současn&yacute;m prostřed&iacute;m. Postavy jsou jen rezidua, nestabiln&iacute; chvěn&iacute; z<br />d&aacute;vn&yacute;ch dob, mluven&eacute; slovo je jen ozvěna. I když se minulost vrac&iacute;, nemůže<br />se opakovat stejně jako dř&iacute;v, naopak může působit nepatřičně. Film vznikl ve<br />spolupr&aacute;ci s N&aacute;rodn&iacute;m filmov&yacute;m archivem.</p>\r\n<p>{{cover}}</p>\r\n<p><img class=\"w-75\" src=\"https://drive.tiny.cloud/1/aq5ktw3ftjs29ufxc9mbqq2ouooeppuevc26skq93e3c6f5h/de69c64d-653e-4125-b0d5-37584bb8792a\" /></p>\r\n<p>Muzeum uměn&iacute; v Olomouci</p>'),
(6,	4,	'en',	'(Des)orientation',	'<p>3 min</p>\r\n<p>Two distinct worlds. D&aacute;vno minul&eacute; osoby, my&scaron;lenky a situace jsou v kontrastu<br />se současn&yacute;m prostřed&iacute;m. Postavy jsou jen rezidua, nestabiln&iacute; chvěn&iacute; z<br />d&aacute;vn&yacute;ch dob, mluven&eacute; slovo je jen ozvěna. I když se minulost vrac&iacute;, nemůže<br />se opakovat stejně jako dř&iacute;v, naopak může působit nepatřičně. Film vznikl ve<br />spolupr&aacute;ci s N&aacute;rodn&iacute;m filmov&yacute;m archivem.</p>\r\n<p>{{cover}}</p>\r\n<p><img class=\"w-75\" src=\"https://drive.tiny.cloud/1/aq5ktw3ftjs29ufxc9mbqq2ouooeppuevc26skq93e3c6f5h/de69c64d-653e-4125-b0d5-37584bb8792a\" /></p>\r\n<p>Muzeum uměn&iacute; v Olomouci</p>'),
(7,	5,	'cs',	'Zkouška',	'<p>Tohle je zku&scaron;ebn&iacute; d&iacute;lo</p>'),
(8,	5,	'en',	'Test',	'<p>This is a test piece</p>'),
(11,	7,	'cs',	'afsafa',	'<p>ghjghj</p>'),
(12,	7,	'en',	'dfsfds',	'<p>gjgj</p>'),
(13,	8,	'cs',	'sfs',	'<p>sdfsfs</p>'),
(14,	8,	'en',	'sdfsfd',	'<p>dfsfsd</p>'),
(15,	9,	'cs',	'gaga',	'<p>sdgsd</p>'),
(16,	9,	'en',	'agag',	'<p>agd</p>');

DROP TABLE IF EXISTS `source_message`;
CREATE TABLE `source_message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_czech_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'E-mail',
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Password',
  `auth_key` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL COMMENT 'Authorisation key (Yii2)',
  `created_at` int DEFAULT NULL COMMENT 'Registration time',
  `updated_at` int DEFAULT NULL COMMENT 'Update time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Table of webpage users';

INSERT INTO `user` (`id`, `email`, `password`, `auth_key`, `created_at`, `updated_at`) VALUES
(2,	'ullriher@fel.cvut.cz',	'$2y$13$0fCiCZyYvVpzPMY3mtJmBuYzQRx0epv2f/JnaTcvdox8sdz2P2zR6',	'DRnEa1nFTTFIteHcnDCwyQ0hAjX2oBTX',	1594478938,	1594478938),
(3,	'hello@luciesvobodova.works',	'$2y$13$09B7Lg9.pYs74ByGGSZ2IuJLEEZmTRIVEyZu1.wMhvRIqg9rvHcJO',	'6sl189gooH_6hlOjNG7afcGV1tDB-SQk',	1595421748,	1595421748);

-- 2020-07-27 19:03:31