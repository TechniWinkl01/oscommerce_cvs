# MySQL dump 7.1
#
# Host: localhost    Database: catalog
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'address_book'
#
CREATE TABLE address_book (
  address_book_id int(5) DEFAULT '0' NOT NULL auto_increment,
  entry_gender char(1) DEFAULT '' NOT NULL,
  entry_firstname varchar(32) DEFAULT '' NOT NULL,
  entry_lastname varchar(32) DEFAULT '' NOT NULL,
  entry_street_address varchar(64) DEFAULT '' NOT NULL,
  entry_suburb varchar(32),
  entry_postcode varchar(8) DEFAULT '' NOT NULL,
  entry_city varchar(32) DEFAULT '' NOT NULL,
  entry_state varchar(32),
  entry_country varchar(32) DEFAULT '' NOT NULL,
  PRIMARY KEY (address_book_id)
);

#
# Dumping data for table 'address_book'
#


#
# Table structure for table 'address_book_to_customers'
#
CREATE TABLE address_book_to_customers (
  address_book_to_customers_id int(5) DEFAULT '0' NOT NULL auto_increment,
  address_book_id int(5) DEFAULT '0' NOT NULL,
  customers_id int(5) DEFAULT '0' NOT NULL,
  PRIMARY KEY (address_book_to_customers_id)
);

#
# Dumping data for table 'address_book_to_customers'
#


#
# Table structure for table 'category_index'
#
CREATE TABLE category_index (
  category_index_id int(5) DEFAULT '0' NOT NULL auto_increment,
  category_index_name varchar(32) DEFAULT '' NOT NULL,
  sql_select varchar(16) DEFAULT '' NOT NULL,
  PRIMARY KEY (category_index_id)
);

#
# Dumping data for table 'category_index'
#

INSERT INTO category_index VALUES (1,'Products','subcategories');
INSERT INTO category_index VALUES (2,'Manufacturers','manufacturers');
INSERT INTO category_index VALUES (3,'Genre','subcategories');
INSERT INTO category_index VALUES (4,'Developers','manufacturers');
INSERT INTO category_index VALUES (5,'Genre','subcategories');
INSERT INTO category_index VALUES (6,'Distributors','manufacturers');

#
# Table structure for table 'category_index_to_top'
#
CREATE TABLE category_index_to_top (
  category_index_to_top_id int(5) DEFAULT '0' NOT NULL auto_increment,
  category_top_id int(5) DEFAULT '0' NOT NULL,
  category_index_id int(5),
  sort_order int(3),
  PRIMARY KEY (category_index_to_top_id)
);

#
# Dumping data for table 'category_index_to_top'
#

INSERT INTO category_index_to_top VALUES (1,1,1,1);
INSERT INTO category_index_to_top VALUES (2,1,2,2);
INSERT INTO category_index_to_top VALUES (3,2,3,1);
INSERT INTO category_index_to_top VALUES (4,2,4,2);
INSERT INTO category_index_to_top VALUES (5,3,5,1);
INSERT INTO category_index_to_top VALUES (6,3,6,2);

#
# Table structure for table 'category_top'
#
CREATE TABLE category_top (
  category_top_id int(5) DEFAULT '0' NOT NULL auto_increment,
  category_top_name varchar(32) DEFAULT '' NOT NULL,
  sort_order int(3),
  category_image varchar(64),
  PRIMARY KEY (category_top_id)
);

#
# Dumping data for table 'category_top'
#

INSERT INTO category_top VALUES (1,'Hardware',1,'images/category_hardware.gif');
INSERT INTO category_top VALUES (2,'Software',2,'images/category_software.gif');
INSERT INTO category_top VALUES (3,'DVD Movies',3,'images/category_dvd_movies.gif');

#
# Table structure for table 'counter'
#
CREATE TABLE counter (
  startdate char(8),
  counter int(12)
);

#
# Dumping data for table 'counter'
#

INSERT INTO counter VALUES ('20000312',147897);

#
# Table structure for table 'counter_history'
#
CREATE TABLE counter_history (
  month char(8),
  counter int(12)
);

#
# Dumping data for table 'counter_history'
#


#
# Table structure for table 'customers'
#
CREATE TABLE customers (
  customers_id int(5) DEFAULT '0' NOT NULL auto_increment,
  customers_gender char(1) DEFAULT '' NOT NULL,
  customers_firstname varchar(32) DEFAULT '' NOT NULL,
  customers_lastname varchar(32) DEFAULT '' NOT NULL,
  customers_dob varchar(8) DEFAULT '' NOT NULL,
  customers_email_address varchar(96) DEFAULT '' NOT NULL,
  customers_street_address varchar(64) DEFAULT '' NOT NULL,
  customers_suburb varchar(32),
  customers_postcode varchar(8) DEFAULT '' NOT NULL,
  customers_city varchar(32) DEFAULT '' NOT NULL,
  customers_state varchar(32),
  customers_country varchar(32) DEFAULT '' NOT NULL,
  customers_telephone varchar(32) DEFAULT '' NOT NULL,
  customers_fax varchar(32),
  customers_password varchar(12) DEFAULT '' NOT NULL,
  PRIMARY KEY (customers_id)
);

#
# Dumping data for table 'customers'
#

INSERT INTO customers VALUES (1,'m','Harald','Ponce de Leon','19790903','hpdl@theexchangeproject.org','1 Way Street','','12345','Mycity','','NeverNeverLand','11111','','woooooo');

#
# Table structure for table 'customers_basket'
#
CREATE TABLE customers_basket (
  customers_basket_id int(5) DEFAULT '0' NOT NULL auto_increment,
  customers_id int(5) DEFAULT '0' NOT NULL,
  products_id int(5) DEFAULT '0' NOT NULL,
  customers_basket_quantity int(2) DEFAULT '0' NOT NULL,
  customers_basket_date_added char(8),
  PRIMARY KEY (customers_basket_id)
);

#
# Dumping data for table 'customers_basket'
#


#
# Table structure for table 'customers_info'
#
CREATE TABLE customers_info (
  customers_info_id int(5) DEFAULT '0' NOT NULL,
  customers_info_date_of_last_logon char(8),
  customers_info_number_of_logons int(5),
  customers_info_date_account_created char(8),
  customers_info_date_account_last_modified char(8),
  PRIMARY KEY (customers_info_id)
);

#
# Dumping data for table 'customers_info'
#

INSERT INTO customers_info VALUES (1,'20000514',15,'20000312','20000514');

#
# Table structure for table 'manufacturers'
#
CREATE TABLE manufacturers (
  manufacturers_id int(5) DEFAULT '0' NOT NULL auto_increment,
  manufacturers_name varchar(32) DEFAULT '' NOT NULL,
  manufacturers_location tinyint(1) DEFAULT '0' NOT NULL,
  manufacturers_image varchar(64),
  PRIMARY KEY (manufacturers_id)
);

#
# Dumping data for table 'manufacturers'
#

INSERT INTO manufacturers VALUES (1,'Matrox',0,'images/manufacturer_matrox.gif');
INSERT INTO manufacturers VALUES (2,'Microsoft',0,'images/manufacturer_microsoft.gif');
INSERT INTO manufacturers VALUES (3,'Warner',1,'images/manufacturer_warner.gif');
INSERT INTO manufacturers VALUES (4,'Fox',1,'images/manufacturer_fox.gif');
INSERT INTO manufacturers VALUES (5,'Logitech',0,'images/manufacturer_logitech.gif');
INSERT INTO manufacturers VALUES (6,'Canon',0,'images/manufacturer_canon.gif');
INSERT INTO manufacturers VALUES (7,'Sierra',1,'images/manufacturer_sierra.gif');
INSERT INTO manufacturers VALUES (8,'GT Interactive',1,'images/manufacturer_gt_interactive.gif');
INSERT INTO manufacturers VALUES (9,'Hewlett Packard',0,'images/manufacturer_hewlett_packard.gif');

#
# Table structure for table 'manufacturers_to_category'
#
CREATE TABLE manufacturers_to_category (
  manufacturers_to_category_id int(5) DEFAULT '0' NOT NULL auto_increment,
  manufacturers_id int(5) DEFAULT '0' NOT NULL,
  category_top_id int(5) DEFAULT '0' NOT NULL,
  PRIMARY KEY (manufacturers_to_category_id)
);

#
# Dumping data for table 'manufacturers_to_category'
#

INSERT INTO manufacturers_to_category VALUES (1,1,1);
INSERT INTO manufacturers_to_category VALUES (2,2,1);
INSERT INTO manufacturers_to_category VALUES (3,3,3);
INSERT INTO manufacturers_to_category VALUES (4,4,3);
INSERT INTO manufacturers_to_category VALUES (5,5,1);
INSERT INTO manufacturers_to_category VALUES (6,6,1);
INSERT INTO manufacturers_to_category VALUES (7,7,2);
INSERT INTO manufacturers_to_category VALUES (8,8,2);
INSERT INTO manufacturers_to_category VALUES (9,9,1);

#
# Table structure for table 'orders'
#
CREATE TABLE orders (
  orders_id int(5) DEFAULT '0' NOT NULL auto_increment,
  customers_id int(5) DEFAULT '0' NOT NULL,
  customers_name varchar(64) DEFAULT '' NOT NULL,
  customers_street_address varchar(64) DEFAULT '' NOT NULL,
  customers_suburb varchar(32),
  customers_city varchar(32) DEFAULT '' NOT NULL,
  customers_postcode varchar(8) DEFAULT '' NOT NULL,
  customers_state varchar(32),
  customers_country varchar(32) DEFAULT '' NOT NULL,
  customers_telephone varchar(32) DEFAULT '' NOT NULL,
  customers_email_address varchar(96) DEFAULT '' NOT NULL,
  delivery_name varchar(64) DEFAULT '' NOT NULL,
  delivery_street_address varchar(64) DEFAULT '' NOT NULL,
  delivery_suburb varchar(32),
  delivery_city varchar(32) DEFAULT '' NOT NULL,
  delivery_postcode varchar(8) DEFAULT '' NOT NULL,
  delivery_state varchar(32),
  delivery_country varchar(32) DEFAULT '' NOT NULL,
  payment_method varchar(12) DEFAULT '' NOT NULL,
  cc_type varchar(20),
  cc_owner varchar(64),
  cc_number varchar(32),
  cc_expires varchar(4),
  date_purchased varchar(8),
  products_tax int(2),
  PRIMARY KEY (orders_id)
);

#
# Dumping data for table 'orders'
#

#
# Table structure for table 'orders_products'
#
CREATE TABLE orders_products (
  orders_products_id int(5) DEFAULT '0' NOT NULL auto_increment,
  orders_id int(5) DEFAULT '0' NOT NULL,
  products_id int(5) DEFAULT '0' NOT NULL,
  products_name varchar(64) DEFAULT '' NOT NULL,
  products_price decimal(8,2) DEFAULT '0.00' NOT NULL,
  products_quantity int(2) DEFAULT '0' NOT NULL,
  PRIMARY KEY (orders_products_id)
);

#
# Dumping data for table 'orders_products'
#

#
# Table structure for table 'products'
#
CREATE TABLE products (
  products_id int(5) DEFAULT '0' NOT NULL auto_increment,
  products_name varchar(32) DEFAULT '' NOT NULL,
  products_description text,
  products_quantity int(4) DEFAULT '0' NOT NULL,
  products_model varchar(12),
  products_image varchar(64),
  products_url varchar(255),
  products_price decimal(8,2) DEFAULT '0.00' NOT NULL,
  products_date_added varchar(8),
  products_viewed int(5) DEFAULT '0',
  PRIMARY KEY (products_id),
  KEY products_name (products_name)
);

#
# Dumping data for table 'products'
#

INSERT INTO products VALUES (1,'G200 MMS','Reinforcing its position as a multi-monitor trailblazer, Matrox Graphics Inc. has once again developed the most flexible and highly advanced solution in the industry. Introducing the new Matrox G200 Multi-Monitor Series; the first graphics card ever to support up to four DVI digital flat panel displays on a single 8&quot; PCI board.<br><br>With continuing demand for digital flat panels in the financial workplace, the Matrox G200 MMS is the ultimate in flexible solutions. The Matrox G200 MMS also supports the new digital video interface (DVI) created by the Digital Display Working Group (DDWG) designed to ease the adoption of digital flat panels. Other configurations include composite video capture ability and onboard TV tuner, making the Matrox G200 MMS the complete solution for business needs.<br><br>Based on the award-winning MGA-G200 graphics chip, the Matrox G200 Multi-Monitor Series provides superior 2D/3D graphics acceleration to meet the demanding needs of business applications such as real-time stock quotes (Versus), live video feeds (Reuters & Bloombergs), multiple windows applications, word processing, spreadsheets and CAD.',32,'MG200MMS','images/matrox/mg200mms.gif','www.matrox.com/mga/feat_story/jun99/mms_g200.htm',299.99,'19991217',214);
INSERT INTO products VALUES (2,'G400 32MB','<b>Dramatically Different High Performance Graphics</b><br><br>Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry\'s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC\'s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br><br><b>Key features:</b><ul><li>New Matrox G400 256-bit DualBus graphics chip</li><li>Explosive 3D, 2D and DVD performance</li><li>DualHead Display</li><li>Superior DVD and TV output</li><li>3D Environment-Mapped Bump Mapping</li><li>Vibrant Color Quality rendering </li><li>UltraSharp DAC of up to 360 MHz</li><li>3D Rendering Array Processor</li><li>Support for 16 or 32 MB of memory</li></ul>',32,'MG400-32MB','images/matrox/mg400-32mb.gif','www.matrox.com/mga/products/mill_g400/home.htm',499.99,'19991217',205);
INSERT INTO products VALUES (4,'The Replacement Killers','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 80 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).',13,'DVD-RPMK','images/dvd/replacement_killers.gif','www.replacement-killers.com',42.00,'20000109',15);
INSERT INTO products VALUES (3,'IntelliMouse Pro','Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft\'s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.',32,'MSIMPRO','images/microsoft/msimpro.gif','www.microsoft.com/products/hardware/mouse/impro/default.htm',49.99,'20000105',346);
INSERT INTO products VALUES (5,'Blade Runner - Director\'s Cut','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 112 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).',17,'DVD-BLDRNDC','images/dvd/blade_runner.gif','www.bladerunner.com',35.99,'20000109',217);
INSERT INTO products VALUES (7,'You\'ve Got Mail','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch, Spanish.\r<br>\nSubtitles: English, Deutsch, Spanish, French, Nordic, Polish.\r<br>\nAudio: Dolby Digital 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 115 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-YGEM','images/dvd/youve_got_mail.gif','www.youvegotemail.com',34.99,'20000115',94);
INSERT INTO products VALUES (6,'The Matrix','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch.\r<br>\nAudio: Dolby Surround.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 131 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Making Of.',10,'DVD-MATR','images/dvd/the_matrix.gif','www.thematrix.com',39.99,'20000115',443);
INSERT INTO products VALUES (8,'A Bug\'s Life','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Digital 5.1 / Dobly Surround Stereo.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 91 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-ABUG','images/dvd/a_bugs_life.gif','www.abugslife.com',35.99,'20000115',120);
INSERT INTO products VALUES (9,'Under Siege','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 98 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-UNSG','images/dvd/under_siege.gif','',29.99,'20000115',11);
INSERT INTO products VALUES (10,'Under Siege 2 - Dark Territory','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 98 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-UNSG2','images/dvd/under_siege2.gif','',29.99,'20000115',15);
INSERT INTO products VALUES (11,'Fire Down Below','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 100 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-FDBL','images/dvd/fire_down_below.gif','',29.99,'20000115',13);
INSERT INTO products VALUES (12,'Die Hard With A Vengeance','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 122 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-DHWV','images/dvd/die_hard_3.gif','',39.99,'20000115',83);
INSERT INTO products VALUES (13,'Lethal Weapon','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 100 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-LTWP','images/dvd/lethal_weapon.gif','',34.99,'20000115',55);
INSERT INTO products VALUES (14,'Red Corner','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 117 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-REDC','images/dvd/red_corner.gif','',32.00,'20000115',32);
INSERT INTO products VALUES (15,'Frantic','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 115 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-FRAN','images/dvd/frantic.gif','',35.00,'20000115',37);
INSERT INTO products VALUES (16,'Courage Under Fire','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 112 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-CUFI','images/dvd/courage_under_fire.gif','',38.99,'20000115',297);
INSERT INTO products VALUES (17,'Speed','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 112 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-SPEED','images/dvd/speed.gif','',39.99,'20000115',48);
INSERT INTO products VALUES (18,'Speed 2: Cruise Control','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 120 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-SPEED2','images/dvd/speed_2.gif','',42.00,'20000115',58);
INSERT INTO products VALUES (19,'There\'s Something About Mary','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 114 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-TSAB','images/dvd/theres_something_about_mary.gif','',49.99,'20000115',180);
INSERT INTO products VALUES (20,'Beloved','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 164 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-BELOVED','images/dvd/beloved.gif','',54.99,'20000115',53);
INSERT INTO products VALUES (21,'SWAT 3: Close Quarters Battle','<b>Windows 95/98</b><br><br>211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and \"When needed\" to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!',16,'PC-SWAT3','images/sierra/swat_3.gif','www.swat3.com',79.99,'20000302',162);
INSERT INTO products VALUES (22,'Unreal Tournament','From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br><br>This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It\'s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of \'bots\' (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.',13,'PC-UNTM','images/gt_interactive/unreal_tournament.gif','www.unrealtournament.net',89.99,'20000302',502);
INSERT INTO products VALUES (23,'The Wheel Of Time','The world in which The Wheel of Time takes place is lifted directly out of Jordan\'s pages; it\'s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you\'re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter\'angreal, Portal Stones, and the Ways. However you move around, though, you\'ll quickly discover that means of locomotion can easily become the least of the your worries...<br><br>During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time\'s main characters. Some of these places are ripped directly from the pages of Jordan\'s books, made flesh with Legend\'s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you\'ll have this year.',16,'PC-TWOF','images/gt_interactive/wheel_of_time.gif','www.wheeloftime.com',99.99,'20000302',462);
INSERT INTO products VALUES (24,'Disciples: Sacred Lands','A new age is dawning...<br><br>Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br><br>The day of reckoning has come... and only the chosen will survive.',17,'PC-DISC','images/gt_interactive/disciples.gif','',90.00,'20000302',457);
INSERT INTO products VALUES (25,'Internet Keyboard PS/2','The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!',16,'MSINTKB','images/microsoft/intkeyboardps2.gif','',69.99,'20000302',769);
INSERT INTO products VALUES (26,'IntelliMouse Explorer PS2/USB','Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!',10,'MSIMEXP','images/microsoft/imexplorer.gif','www.microsoft.com/mouse/explorer.htm',64.95,'20000302',1942);
INSERT INTO products VALUES (27,'LaserJet 1100Xi','HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP\'s Resolution Enhancement technology (REt) makes every document more professional.<br><br>Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<br><br>HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).',8,'HPLJ1100XI','images/hewlett_packard/lj1100xi.gif','www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100',499.99,'20000302',1574);

#
# Table structure for table 'products_expected'
#
CREATE TABLE products_expected (
  products_expected_id int(5) DEFAULT '0' NOT NULL auto_increment,
  products_name varchar(255) DEFAULT '' NOT NULL,
  date_expected varchar(8),
  PRIMARY KEY (products_expected_id)
);

#
# Dumping data for table 'products_expected'
#

INSERT INTO products_expected VALUES (1,'The Beach','20000320');
INSERT INTO products_expected VALUES (2,'Alien Triology (Warner)','20000317');
INSERT INTO products_expected VALUES (3,'American Pie (Warner)','20000317');

#
# Table structure for table 'products_to_manufacturers'
#
CREATE TABLE products_to_manufacturers (
  products_to_manufacturers_id int(5) DEFAULT '0' NOT NULL auto_increment,
  products_id int(5) DEFAULT '0' NOT NULL,
  manufacturers_id int(5) DEFAULT '0' NOT NULL,
  PRIMARY KEY (products_to_manufacturers_id)
);

#
# Dumping data for table 'products_to_manufacturers'
#

INSERT INTO products_to_manufacturers VALUES (1,1,1);
INSERT INTO products_to_manufacturers VALUES (2,2,1);
INSERT INTO products_to_manufacturers VALUES (3,3,2);
INSERT INTO products_to_manufacturers VALUES (4,4,3);
INSERT INTO products_to_manufacturers VALUES (5,5,3);
INSERT INTO products_to_manufacturers VALUES (6,6,3);
INSERT INTO products_to_manufacturers VALUES (7,7,3);
INSERT INTO products_to_manufacturers VALUES (8,8,3);
INSERT INTO products_to_manufacturers VALUES (9,9,3);
INSERT INTO products_to_manufacturers VALUES (10,10,3);
INSERT INTO products_to_manufacturers VALUES (11,11,3);
INSERT INTO products_to_manufacturers VALUES (12,12,4);
INSERT INTO products_to_manufacturers VALUES (13,13,3);
INSERT INTO products_to_manufacturers VALUES (14,14,3);
INSERT INTO products_to_manufacturers VALUES (15,15,3);
INSERT INTO products_to_manufacturers VALUES (16,16,4);
INSERT INTO products_to_manufacturers VALUES (17,17,4);
INSERT INTO products_to_manufacturers VALUES (18,18,4);
INSERT INTO products_to_manufacturers VALUES (19,19,4);
INSERT INTO products_to_manufacturers VALUES (20,20,3);
INSERT INTO products_to_manufacturers VALUES (21,21,7);
INSERT INTO products_to_manufacturers VALUES (22,22,8);
INSERT INTO products_to_manufacturers VALUES (23,23,8);
INSERT INTO products_to_manufacturers VALUES (24,24,8);
INSERT INTO products_to_manufacturers VALUES (25,25,2);
INSERT INTO products_to_manufacturers VALUES (26,26,2);
INSERT INTO products_to_manufacturers VALUES (27,27,9);

#
# Table structure for table 'products_to_subcategories'
#
CREATE TABLE products_to_subcategories (
  products_to_subcategories_id int(5) DEFAULT '0' NOT NULL auto_increment,
  products_id int(5) DEFAULT '0' NOT NULL,
  subcategories_id int(5) DEFAULT '0' NOT NULL,
  PRIMARY KEY (products_to_subcategories_id)
);

#
# Dumping data for table 'products_to_subcategories'
#

INSERT INTO products_to_subcategories VALUES (1,1,1);
INSERT INTO products_to_subcategories VALUES (2,2,1);
INSERT INTO products_to_subcategories VALUES (3,3,6);
INSERT INTO products_to_subcategories VALUES (4,4,7);
INSERT INTO products_to_subcategories VALUES (5,5,8);
INSERT INTO products_to_subcategories VALUES (6,6,7);
INSERT INTO products_to_subcategories VALUES (7,7,9);
INSERT INTO products_to_subcategories VALUES (8,8,10);
INSERT INTO products_to_subcategories VALUES (9,9,7);
INSERT INTO products_to_subcategories VALUES (10,10,7);
INSERT INTO products_to_subcategories VALUES (11,11,7);
INSERT INTO products_to_subcategories VALUES (12,12,7);
INSERT INTO products_to_subcategories VALUES (13,13,7);
INSERT INTO products_to_subcategories VALUES (14,14,12);
INSERT INTO products_to_subcategories VALUES (15,15,11);
INSERT INTO products_to_subcategories VALUES (16,16,12);
INSERT INTO products_to_subcategories VALUES (17,17,7);
INSERT INTO products_to_subcategories VALUES (18,18,7);
INSERT INTO products_to_subcategories VALUES (19,19,9);
INSERT INTO products_to_subcategories VALUES (20,20,12);
INSERT INTO products_to_subcategories VALUES (21,21,15);
INSERT INTO products_to_subcategories VALUES (22,22,16);
INSERT INTO products_to_subcategories VALUES (23,23,17);
INSERT INTO products_to_subcategories VALUES (24,24,17);
INSERT INTO products_to_subcategories VALUES (25,25,5);
INSERT INTO products_to_subcategories VALUES (26,26,6);
INSERT INTO products_to_subcategories VALUES (27,27,2);

#
# Table structure for table 'reviews'
#
CREATE TABLE reviews (
  reviews_id int(5) DEFAULT '0' NOT NULL auto_increment,
  reviews_text text NOT NULL,
  reviews_rating int(1),
  PRIMARY KEY (reviews_id)
);

#
# Dumping data for table 'reviews'
#

INSERT INTO reviews VALUES (1,'this has to be one of the funniest movies released for 1999!',5);

#
# Table structure for table 'reviews_extra'
#
CREATE TABLE reviews_extra (
  reviews_id int(5) DEFAULT '0' NOT NULL,
  products_id int(5) DEFAULT '0' NOT NULL,
  customers_id int(5) DEFAULT '0' NOT NULL,
  date_added char(8) DEFAULT '' NOT NULL,
  reviews_read int(5)
);

#
# Dumping data for table 'reviews_extra'
#

INSERT INTO reviews_extra VALUES (1,19,1,'20000312',56);

#
# Table structure for table 'specials'
#
CREATE TABLE specials (
  specials_id int(5) DEFAULT '0' NOT NULL auto_increment,
  products_id int(5) DEFAULT '0' NOT NULL,
  specials_new_products_price decimal(8,2) DEFAULT '0.00' NOT NULL,
  specials_date_added char(8),
  PRIMARY KEY (specials_id)
);

#
# Dumping data for table 'specials'
#

INSERT INTO specials VALUES (1,3,39.99,'20000114');
INSERT INTO specials VALUES (2,5,30.00,'20000114');
INSERT INTO specials VALUES (3,6,30.00,'20000115');
INSERT INTO specials VALUES (4,16,29.99,'20000217');

#
# Table structure for table 'subcategories'
#
CREATE TABLE subcategories (
  subcategories_id int(5) DEFAULT '0' NOT NULL auto_increment,
  subcategories_name varchar(32) DEFAULT '' NOT NULL,
  subcategories_image varchar(64),
  PRIMARY KEY (subcategories_id),
  KEY subcategories_name (subcategories_name)
);

#
# Dumping data for table 'subcategories'
#

INSERT INTO subcategories VALUES (1,'Graphic Cards','images/subcategory_graphic_cards.gif');
INSERT INTO subcategories VALUES (2,'Printers','images/subcategory_printers.gif');
INSERT INTO subcategories VALUES (3,'Monitors','images/subcategory_monitors.gif');
INSERT INTO subcategories VALUES (4,'Speakers','images/subcategory_speakers.gif');
INSERT INTO subcategories VALUES (5,'Keyboards','images/subcategory_keyboards.gif');
INSERT INTO subcategories VALUES (6,'Mice','images/subcategory_mice.gif');
INSERT INTO subcategories VALUES (7,'Action','images/subcategory_action.gif');
INSERT INTO subcategories VALUES (8,'Science Fiction','images/subcategory_science_fiction.gif');
INSERT INTO subcategories VALUES (9,'Comedy','images/subcategory_comedy.gif');
INSERT INTO subcategories VALUES (10,'Cartoons','images/subcategory_cartoons.gif');
INSERT INTO subcategories VALUES (11,'Thriller','images/subcategory_thriller.gif');
INSERT INTO subcategories VALUES (12,'Drama','images/subcategory_drama.gif');
INSERT INTO subcategories VALUES (13,'Memory','images/subcategory_memory.gif');
INSERT INTO subcategories VALUES (14,'CDROM Drives','images/subcategory_cdrom_drives.gif');
INSERT INTO subcategories VALUES (15,'Simulation','images/subcategory_simulation.gif');
INSERT INTO subcategories VALUES (16,'Action','images/subcategory_action_games.gif');
INSERT INTO subcategories VALUES (17,'Strategy','images/subcategory_strategy.gif');

#
# Table structure for table 'subcategories_to_category'
#
CREATE TABLE subcategories_to_category (
  subcategories_to_category_id int(5) DEFAULT '0' NOT NULL auto_increment,
  subcategories_id int(5) DEFAULT '0' NOT NULL,
  category_top_id int(5) DEFAULT '0' NOT NULL,
  PRIMARY KEY (subcategories_to_category_id)
);

#
# Dumping data for table 'subcategories_to_category'
#

INSERT INTO subcategories_to_category VALUES (1,1,1);
INSERT INTO subcategories_to_category VALUES (2,2,1);
INSERT INTO subcategories_to_category VALUES (3,3,1);
INSERT INTO subcategories_to_category VALUES (4,4,1);
INSERT INTO subcategories_to_category VALUES (5,5,1);
INSERT INTO subcategories_to_category VALUES (6,6,1);
INSERT INTO subcategories_to_category VALUES (7,7,3);
INSERT INTO subcategories_to_category VALUES (8,8,3);
INSERT INTO subcategories_to_category VALUES (9,9,3);
INSERT INTO subcategories_to_category VALUES (10,10,3);
INSERT INTO subcategories_to_category VALUES (11,11,3);
INSERT INTO subcategories_to_category VALUES (12,12,3);
INSERT INTO subcategories_to_category VALUES (13,13,1);
INSERT INTO subcategories_to_category VALUES (14,14,1);
INSERT INTO subcategories_to_category VALUES (15,15,2);
INSERT INTO subcategories_to_category VALUES (16,16,2);
INSERT INTO subcategories_to_category VALUES (17,17,2);

