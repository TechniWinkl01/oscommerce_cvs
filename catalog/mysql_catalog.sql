# The Exchange Project
# Database Model for Preview Release 2.0
#
# NOTE: * Please make any modifications to this file by hand!
#       * DO NOT use a mysqldump created file for new changes!
#       * Please take note of the table structure, and use this
#         structure as a standard for future modifications!
#       * To see the 'diff'erence between MySQL databases, use
#         the mysqldiff perl script located in the extras
#         directory of the 'catalog' module.

#
# Table structure for table 'address_book'
#

CREATE TABLE address_book (
  address_book_id int(5) NOT NULL auto_increment,
  entry_gender char(1) NOT NULL,
  entry_firstname varchar(32) NOT NULL,
  entry_lastname varchar(32) NOT NULL,
  entry_street_address varchar(64) NOT NULL,
  entry_suburb varchar(32),
  entry_postcode varchar(8) NOT NULL,
  entry_city varchar(32) NOT NULL,
  entry_state varchar(32),
  entry_country_id int(5) NOT NULL,
  PRIMARY KEY (address_book_id)
);

#
# Dumping data for table 'address_book'
#

#
# Table structure for table 'address_book_to_customers'
#

CREATE TABLE address_book_to_customers (
  address_book_to_customers_id int(5) NOT NULL auto_increment,
  address_book_id int(5) NOT NULL,
  customers_id int(5) NOT NULL,
  PRIMARY KEY (address_book_to_customers_id)
);

#
# Dumping data for table 'address_book_to_customers'
#

#
# Table structure for table 'categories'
#

CREATE TABLE categories (
  categories_id int(5) NOT NULL auto_increment,
  categories_name varchar(32) NOT NULL,
  categories_image varchar(64),
  parent_id int(5),
  sort_order int(3),
  PRIMARY KEY (categories_id),
  KEY IDX_CATEGORIES_NAME (categories_name)
);

#
# Dumping data for table 'categories'
#

INSERT INTO categories VALUES (1,'Hardware','images/category_hardware.gif',0,1);
INSERT INTO categories VALUES (2,'Software','images/category_software.gif',0,2);
INSERT INTO categories VALUES (3,'DVD Movies','images/category_dvd_movies.gif',0,3);
INSERT INTO categories VALUES (4,'Graphics Cards','images/subcategory_graphic_cards.gif',1,0);
INSERT INTO categories VALUES (5,'Printers','images/subcategory_printers.gif',1,0);
INSERT INTO categories VALUES (6,'Monitors','images/subcategory_monitors.gif',1,0);
INSERT INTO categories VALUES (7,'Speakers','images/subcategory_speakers.gif',1,0);
INSERT INTO categories VALUES (8,'Keyboards','images/subcategory_keyboards.gif',1,0);
INSERT INTO categories VALUES (9,'Mice','images/subcategory_mice.gif',1,0);
INSERT INTO categories VALUES (10,'Action','images/subcategory_action.gif',3,0);
INSERT INTO categories VALUES (11,'Science Fiction','images/subcategory_science_fiction.gif',3,0);
INSERT INTO categories VALUES (12,'Comedy','images/subcategory_comedy.gif',3,0);
INSERT INTO categories VALUES (13,'Cartoons','images/subcategory_cartoons.gif',3,0);
INSERT INTO categories VALUES (14,'Thriller','images/subcategory_thriller.gif',3,0);
INSERT INTO categories VALUES (15,'Drama','images/subcategory_drama.gif',3,0);
INSERT INTO categories VALUES (16,'Memory','images/subcategory_memory.gif',1,0);
INSERT INTO categories VALUES (17,'CDROM Drives','images/subcategory_cdrom_drives.gif',1,0);
INSERT INTO categories VALUES (18,'Simulation','images/subcategory_simulation.gif',2,0);
INSERT INTO categories VALUES (19,'Action','images/subcategory_action_games.gif',2,0);
INSERT INTO categories VALUES (20,'Strategy','images/subcategory_strategy.gif',2,0);

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

INSERT INTO counter VALUES ('20000312',148052);

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
# Table structure for table 'countries'
#

CREATE TABLE countries (
  countries_id int(5) NOT NULL auto_increment,
  countries_name varchar(64) NOT NULL,
  countries_iso_code_2 char(2) NOT NULL,
  countries_iso_code_3 char(3) NOT NULL,
  PRIMARY KEY (countries_id),
  KEY IDX_COUNTRIES_NAME (countries_name)
);

#
# Dumping data for table 'countries'
#

INSERT INTO countries VALUES (1,'Afghanistan','AF','AFG');
INSERT INTO countries VALUES (2,'Albania','AL','ALB');
INSERT INTO countries VALUES (3,'Algeria','DZ','DZA');
INSERT INTO countries VALUES (4,'American Samoa','AS','ASM');
INSERT INTO countries VALUES (5,'Andorra','AD','AND');
INSERT INTO countries VALUES (6,'Angola','AO','AGO');
INSERT INTO countries VALUES (7,'Anguilla','AI','AIA');
INSERT INTO countries VALUES (8,'Antarctica','AQ','ATA');
INSERT INTO countries VALUES (9,'Antigua and Barbuda','AG','ATG');
INSERT INTO countries VALUES (10,'Argentina','AR','ARG');
INSERT INTO countries VALUES (11,'Armenia','AM','ARM');
INSERT INTO countries VALUES (12,'Aruba','AW','ABW');
INSERT INTO countries VALUES (13,'Australia','AU','AUS');
INSERT INTO countries VALUES (14,'Austria','AT','AUT');
INSERT INTO countries VALUES (15,'Azerbaijan','AZ','AZE');
INSERT INTO countries VALUES (16,'Bahamas','BS','BHS');
INSERT INTO countries VALUES (17,'Bahrain','BH','BHR');
INSERT INTO countries VALUES (18,'Bangladesh','BD','BGD');
INSERT INTO countries VALUES (19,'Barbados','BB','BRB');
INSERT INTO countries VALUES (20,'Belarus','BY','BLR');
INSERT INTO countries VALUES (21,'Belgium','BE','BEL');
INSERT INTO countries VALUES (22,'Belize','BZ','BLZ');
INSERT INTO countries VALUES (23,'Benin','BJ','BEN');
INSERT INTO countries VALUES (24,'Bermuda','BM','BMU');
INSERT INTO countries VALUES (25,'Bhutan','BT','BTN');
INSERT INTO countries VALUES (26,'Bolivia','BO','BOL');
INSERT INTO countries VALUES (27,'Bosnia and Herzegowina','BA','BIH');
INSERT INTO countries VALUES (28,'Botswana','BW','BWA');
INSERT INTO countries VALUES (29,'Bouvet Island','BV','BVT');
INSERT INTO countries VALUES (30,'Brazil','BR','BRA');
INSERT INTO countries VALUES (31,'British Indian Ocean Territory','IO','IOT');
INSERT INTO countries VALUES (32,'Brunei Darussalam','BN','BRN');
INSERT INTO countries VALUES (33,'Bulgaria','BG','BGR');
INSERT INTO countries VALUES (34,'Burkina Faso','BF','BFA');
INSERT INTO countries VALUES (35,'Burundi','BI','BDI');
INSERT INTO countries VALUES (36,'Cambodia','KH','KHM');
INSERT INTO countries VALUES (37,'Cameroon','CM','CMR');
INSERT INTO countries VALUES (38,'Canada','CA','CAN');
INSERT INTO countries VALUES (39,'Cape Verde','CV','CPV');
INSERT INTO countries VALUES (40,'Cayman Islands','KY','CYM');
INSERT INTO countries VALUES (41,'Central African Republic','CF','CAF');
INSERT INTO countries VALUES (42,'Chad','TD','TCD');
INSERT INTO countries VALUES (43,'Chile','CL','CHL');
INSERT INTO countries VALUES (44,'China','CN','CHN');
INSERT INTO countries VALUES (45,'Christmas Island','CX','CXR');
INSERT INTO countries VALUES (46,'Cocos (Keeling) Islands','CC','CCK');
INSERT INTO countries VALUES (47,'Colombia','CO','COL');
INSERT INTO countries VALUES (48,'Comoros','KM','COM');
INSERT INTO countries VALUES (49,'Congo','CG','COG');
INSERT INTO countries VALUES (50,'Cook Islands','CK','COK');
INSERT INTO countries VALUES (51,'Costa Rica','CR','CRI');
INSERT INTO countries VALUES (52,'Cote D\'Ivoire','CI','CIV');
INSERT INTO countries VALUES (53,'Croatia','HR','HRV');
INSERT INTO countries VALUES (54,'Cuba','CU','CUB');
INSERT INTO countries VALUES (55,'Cyprus','CY','CYP');
INSERT INTO countries VALUES (56,'Czech Republic','CZ','CZE');
INSERT INTO countries VALUES (57,'Denmark','DK','DNK');
INSERT INTO countries VALUES (58,'Djibouti','DJ','DJI');
INSERT INTO countries VALUES (59,'Dominica','DM','DMA');
INSERT INTO countries VALUES (60,'Dominican Republic','DO','DOM');
INSERT INTO countries VALUES (61,'East Timor','TP','TMP');
INSERT INTO countries VALUES (62,'Ecuador','EC','ECU');
INSERT INTO countries VALUES (63,'Egypt','EG','EGY');
INSERT INTO countries VALUES (64,'El Salvador','SV','SLV');
INSERT INTO countries VALUES (65,'Equatorial Guinea','GQ','GNQ');
INSERT INTO countries VALUES (66,'Eritrea','ER','ERI');
INSERT INTO countries VALUES (67,'Estonia','EE','EST');
INSERT INTO countries VALUES (68,'Ethiopia','ET','ETH');
INSERT INTO countries VALUES (69,'Falkland Islands (Malvinas)','FK','FLK');
INSERT INTO countries VALUES (70,'Faroe Islands','FO','FRO');
INSERT INTO countries VALUES (71,'Fiji','FJ','FJI');
INSERT INTO countries VALUES (72,'Finland','FI','FIN');
INSERT INTO countries VALUES (73,'France','FR','FRA');
INSERT INTO countries VALUES (74,'France, MEtropolitan','FX','FXX');
INSERT INTO countries VALUES (75,'French Guiana','GF','GUF');
INSERT INTO countries VALUES (76,'French Polynesia','PF','PYF');
INSERT INTO countries VALUES (77,'French Southern Territories','TF','ATF');
INSERT INTO countries VALUES (78,'Gabon','GA','GAB');
INSERT INTO countries VALUES (79,'Gambia','GM','GMB');
INSERT INTO countries VALUES (80,'Georgia','GE','GEO');
INSERT INTO countries VALUES (81,'Germany','DE','DEU');
INSERT INTO countries VALUES (82,'Ghana','GH','GHA');
INSERT INTO countries VALUES (83,'Gibraltar','GI','GIB');
INSERT INTO countries VALUES (84,'Greece','GR','GRC');
INSERT INTO countries VALUES (85,'Greenland','GL','GRL');
INSERT INTO countries VALUES (86,'Grenada','GD','GRD');
INSERT INTO countries VALUES (87,'Guadeloupe','GP','GLP');
INSERT INTO countries VALUES (88,'Guam','GU','GUM');
INSERT INTO countries VALUES (89,'Guatemala','GT','GTM');
INSERT INTO countries VALUES (90,'Guinea','GN','GIN');
INSERT INTO countries VALUES (91,'Guinea-bissau','GW','GNB');
INSERT INTO countries VALUES (92,'Guyana','GY','GUY');
INSERT INTO countries VALUES (93,'Haiti','HT','HTI');
INSERT INTO countries VALUES (94,'Heard and Mc Donald Islands','HM','HMD');
INSERT INTO countries VALUES (95,'Honduras','HN','HND');
INSERT INTO countries VALUES (96,'Hong Kong','HK','HKG');
INSERT INTO countries VALUES (97,'Hungary','HU','HUN');
INSERT INTO countries VALUES (98,'Iceland','IS','ISL');
INSERT INTO countries VALUES (99,'India','IN','IND');
INSERT INTO countries VALUES (100,'Indonesia','ID','IDN');
INSERT INTO countries VALUES (101,'Iran (Islamic Republic of)','IR','IRN');
INSERT INTO countries VALUES (102,'Iraq','IQ','IRQ');
INSERT INTO countries VALUES (103,'Ireland','IE','IRL');
INSERT INTO countries VALUES (104,'Israel','IL','ISR');
INSERT INTO countries VALUES (105,'Italy','IT','ITA');
INSERT INTO countries VALUES (106,'Jamaica','JM','JAM');
INSERT INTO countries VALUES (107,'Japan','JP','JPN');
INSERT INTO countries VALUES (108,'Jordan','JO','JOR');
INSERT INTO countries VALUES (109,'Kazakhstan','KZ','KAZ');
INSERT INTO countries VALUES (110,'Kenya','KE','KEN');
INSERT INTO countries VALUES (111,'Kiribati','KI','KIR');
INSERT INTO countries VALUES (112,'Korea, Democratic People\'s Republic of','KP','PRK');
INSERT INTO countries VALUES (113,'Korea, Republic of','KR','KOR');
INSERT INTO countries VALUES (114,'Kuwait','KW','KWT');
INSERT INTO countries VALUES (115,'Kyrgyzstan','KG','KGZ');
INSERT INTO countries VALUES (116,'Lao People\'s Democratic Republic','LA','LAO');
INSERT INTO countries VALUES (117,'Latvia','LV','LVA');
INSERT INTO countries VALUES (118,'Lebanon','LB','LBN');
INSERT INTO countries VALUES (119,'Lesotho','LS','LSO');
INSERT INTO countries VALUES (120,'Liberia','LR','LBR');
INSERT INTO countries VALUES (121,'Libyan Arab Jamahiriya','LY','LBY');
INSERT INTO countries VALUES (122,'Liechtenstein','LI','LIE');
INSERT INTO countries VALUES (123,'Lithuania','LT','LTU');
INSERT INTO countries VALUES (124,'Luxembourg','LU','LUX');
INSERT INTO countries VALUES (125,'Macau','MO','MAC');
INSERT INTO countries VALUES (126,'Macedonia, The Former Yugoslav Republic of','MK','MKD');
INSERT INTO countries VALUES (127,'Madagascar','MG','MDG');
INSERT INTO countries VALUES (128,'Malawi','MW','MWI');
INSERT INTO countries VALUES (129,'Malaysia','MY','MYS');
INSERT INTO countries VALUES (130,'Maldives','MV','MDV');
INSERT INTO countries VALUES (131,'Mali','ML','MLI');
INSERT INTO countries VALUES (132,'Malta','MT','MLT');
INSERT INTO countries VALUES (133,'Marshall Islands','MH','MHL');
INSERT INTO countries VALUES (134,'Martinique','MQ','MTQ');
INSERT INTO countries VALUES (135,'Mauritania','MR','MRT');
INSERT INTO countries VALUES (136,'Mauritius','MU','MUS');
INSERT INTO countries VALUES (137,'Mayotte','YT','MYT');
INSERT INTO countries VALUES (138,'Mexico','MX','MEX');
INSERT INTO countries VALUES (139,'Micronesia, Federated States of','FM','FSM');
INSERT INTO countries VALUES (140,'Moldova, Republic of','MD','MDA');
INSERT INTO countries VALUES (141,'Monaco','MC','MCO');
INSERT INTO countries VALUES (142,'Mongolia','MN','MNG');
INSERT INTO countries VALUES (143,'Montserrat','MS','MSR');
INSERT INTO countries VALUES (144,'Morocco','MA','MAR');
INSERT INTO countries VALUES (145,'Mozambique','MZ','MOZ');
INSERT INTO countries VALUES (146,'Myanmar','MM','MMR');
INSERT INTO countries VALUES (147,'Namibia','NA','NAM');
INSERT INTO countries VALUES (148,'Nauru','NR','NRU');
INSERT INTO countries VALUES (149,'Nepal','NP','NPL');
INSERT INTO countries VALUES (150,'Netherlands','NL','NLD');
INSERT INTO countries VALUES (151,'Netherlands Antilles','AN','ANT');
INSERT INTO countries VALUES (152,'New Caledonia','NC','NCL');
INSERT INTO countries VALUES (153,'New Zealand','NZ','NZL');
INSERT INTO countries VALUES (154,'Nicaragua','NI','NIC');
INSERT INTO countries VALUES (155,'Niger','NE','NER');
INSERT INTO countries VALUES (156,'Nigeria','NG','NGA');
INSERT INTO countries VALUES (157,'Niue','NU','NIU');
INSERT INTO countries VALUES (158,'Norfolk Island','NF','NFK');
INSERT INTO countries VALUES (159,'Northern Mariana Islands','MP','MNP');
INSERT INTO countries VALUES (160,'Norway','NO','NOR');
INSERT INTO countries VALUES (161,'Oman','OM','OMN');
INSERT INTO countries VALUES (162,'Pakistan','PK','PAK');
INSERT INTO countries VALUES (163,'Palau','PW','PLW');
INSERT INTO countries VALUES (164,'Panama','PA','PAN');
INSERT INTO countries VALUES (165,'Papua New Guinea','PG','PNG');
INSERT INTO countries VALUES (166,'Paraguay','PY','PRY');
INSERT INTO countries VALUES (167,'Peru','PE','PER');
INSERT INTO countries VALUES (168,'Philippines','PH','PHL');
INSERT INTO countries VALUES (169,'Pitcairn','PN','PCN');
INSERT INTO countries VALUES (170,'Poland','PL','POL');
INSERT INTO countries VALUES (171,'Portugal','PT','PRT');
INSERT INTO countries VALUES (172,'Puerto Rico','PR','PRI');
INSERT INTO countries VALUES (173,'Qatar','QA','QAT');
INSERT INTO countries VALUES (174,'Reunion','RE','REU');
INSERT INTO countries VALUES (175,'Romania','RO','ROM');
INSERT INTO countries VALUES (176,'Russian Federation','RU','RUS');
INSERT INTO countries VALUES (177,'Rwanda','RW','RWA');
INSERT INTO countries VALUES (178,'Saint Kitts and Nevis','KN','KNA');
INSERT INTO countries VALUES (179,'Saint Lucia','LC','LCA');
INSERT INTO countries VALUES (180,'Saint Vincent and the Grenadines','VC','VCT');
INSERT INTO countries VALUES (181,'Samoa','WS','WSM');
INSERT INTO countries VALUES (182,'San Marino','SM','SMR');
INSERT INTO countries VALUES (183,'Sao Tome and Principe','ST','STP');
INSERT INTO countries VALUES (184,'Saudi Arabia','SA','SAU');
INSERT INTO countries VALUES (185,'Senegal','SN','SEN');
INSERT INTO countries VALUES (186,'Seychelles','SC','SYC');
INSERT INTO countries VALUES (187,'Sierra Leone','SL','SLE');
INSERT INTO countries VALUES (188,'Singapore','SG','SGP');
INSERT INTO countries VALUES (189,'Slovakia (Slovak Republic)','SK','SVK');
INSERT INTO countries VALUES (190,'Slovenia','SI','SVN');
INSERT INTO countries VALUES (191,'Solomon Islands','SB','SLB');
INSERT INTO countries VALUES (192,'Somalia','SO','SOM');
INSERT INTO countries VALUES (193,'south Africa','ZA','ZAF');
INSERT INTO countries VALUES (194,'South Georgia and the South Sandwich Islands','GS','SGS');
INSERT INTO countries VALUES (195,'Spain','ES','ESP');
INSERT INTO countries VALUES (196,'Sri Lanka','LK','LKA');
INSERT INTO countries VALUES (197,'St. Helena','SH','SHN');
INSERT INTO countries VALUES (198,'St. Pierre and Miquelon','PM','SPM');
INSERT INTO countries VALUES (199,'Sudan','SD','SDN');
INSERT INTO countries VALUES (200,'Suriname','SR','SUR');
INSERT INTO countries VALUES (201,'Svalbard and Jan Mayen Islands','SJ','SJM');
INSERT INTO countries VALUES (202,'Swaziland','SZ','SWZ');
INSERT INTO countries VALUES (203,'Sweden','SE','SWE');
INSERT INTO countries VALUES (204,'Switzerland','CH','CHE');
INSERT INTO countries VALUES (205,'Syrian Arab Republic','SY','SYR');
INSERT INTO countries VALUES (206,'Taiwan, Province of China','TW','TWN');
INSERT INTO countries VALUES (207,'Tajikistan','TJ','TJK');
INSERT INTO countries VALUES (208,'Tanzania, United Republic of','TZ','TZA');
INSERT INTO countries VALUES (209,'Thailand','TH','THA');
INSERT INTO countries VALUES (210,'Togo','TG','TGO');
INSERT INTO countries VALUES (211,'Tokelau','TK','TKL');
INSERT INTO countries VALUES (212,'Tonga','TO','TON');
INSERT INTO countries VALUES (213,'Trinidad and Tobago','TT','TTO');
INSERT INTO countries VALUES (214,'Tunisia','TN','TUN');
INSERT INTO countries VALUES (215,'Turkey','TR','TUR');
INSERT INTO countries VALUES (216,'Turkmenistan','TM','TKM');
INSERT INTO countries VALUES (217,'Turks and Caicos Islands','TC','TCA');
INSERT INTO countries VALUES (218,'Tuvalu','TV','TUV');
INSERT INTO countries VALUES (219,'Uganda','UG','UGA');
INSERT INTO countries VALUES (220,'Ukraine','UA','UKR');
INSERT INTO countries VALUES (221,'United Arab Emirates','AE','ARE');
INSERT INTO countries VALUES (222,'United Kingdom','GB','GBR');
INSERT INTO countries VALUES (223,'United States','US','USA');
INSERT INTO countries VALUES (224,'United States Minor Outlying Islands','UM','UMI');
INSERT INTO countries VALUES (225,'Uruguay','UY','URY');
INSERT INTO countries VALUES (226,'Uzbekistan','UZ','UZB');
INSERT INTO countries VALUES (227,'Vanuatu','VU','VUT');
INSERT INTO countries VALUES (228,'Vatican City State (Holy See)','VA','VAT');
INSERT INTO countries VALUES (229,'Venezuela','VE','VEN');
INSERT INTO countries VALUES (230,'Viet Nam','VN','VNM');
INSERT INTO countries VALUES (231,'Virgin Islands (British)','VG','VGB');
INSERT INTO countries VALUES (232,'Virgin Islands (U.S.)','VI','VIR');
INSERT INTO countries VALUES (233,'Wallis and Futuna Islands','WF','WLF');
INSERT INTO countries VALUES (234,'Western Sahara','EH','ESH');
INSERT INTO countries VALUES (235,'Yemen','YE','YEM');
INSERT INTO countries VALUES (236,'Yugoslavia','YU','YUG');
INSERT INTO countries VALUES (237,'Zaire','ZR','ZAR');
INSERT INTO countries VALUES (238,'Zambia','ZM','ZMB');
INSERT INTO countries VALUES (239,'Zimbabwe','ZW','ZWE');

#
# Table structure for table 'customers'
#

CREATE TABLE customers (
  customers_id int(5) NOT NULL auto_increment,
  customers_gender char(1) NOT NULL,
  customers_firstname varchar(32) NOT NULL,
  customers_lastname varchar(32) NOT NULL,
  customers_dob varchar(8) NOT NULL,
  customers_email_address varchar(96) NOT NULL,
  customers_street_address varchar(64) NOT NULL,
  customers_suburb varchar(32),
  customers_postcode varchar(8) NOT NULL,
  customers_city varchar(32) NOT NULL,
  customers_state varchar(32),
  customers_telephone varchar(32) NOT NULL,
  customers_fax varchar(32),
  customers_password varchar(40) NOT NULL,
  customers_country_id int(5) NOT NULL,
  PRIMARY KEY (customers_id)
);

#
# Dumping data for table 'customers'
#

INSERT INTO customers VALUES (1,'m','Harald','Ponce de Leon','19790903','hpdl@theexchangeproject.org','1 Way Street','','12345','Mycity','','11111','','2fb312614a2dfcafa3cd71d13e1948f0:ca',81);

#
# Table structure for table 'customers_basket'
#

CREATE TABLE customers_basket (
  customers_basket_id int(5) NOT NULL auto_increment,
  customers_id int(5) NOT NULL,
  products_id int(5) NOT NULL,
  customers_basket_quantity int(2) NOT NULL,
  final_price decimal(6,2) NOT NULL,
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
  customers_info_id int(5) NOT NULL,
  customers_info_date_of_last_logon char(8),
  customers_info_number_of_logons int(5),
  customers_info_date_account_created char(8),
  customers_info_date_account_last_modified char(8),
  PRIMARY KEY (customers_info_id)
);

#
# Dumping data for table 'customers_info'
#

INSERT INTO customers_info VALUES (1,'20001028',19,'20000312','20000514');

#
# Table structure for table 'manufacturers'
#

CREATE TABLE manufacturers (
  manufacturers_id int(5) NOT NULL auto_increment,
  manufacturers_name varchar(32) NOT NULL,
  manufacturers_location tinyint(1) NOT NULL,
  manufacturers_image varchar(64),
  PRIMARY KEY (manufacturers_id),
  KEY IDX_MANUFACTURERS_NAME (manufacturers_name)
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
# Table structure for table 'orders'
#

CREATE TABLE orders (
  orders_id int(5) NOT NULL auto_increment,
  customers_id int(5) NOT NULL,
  customers_name varchar(64) NOT NULL,
  customers_street_address varchar(64) NOT NULL,
  customers_suburb varchar(32),
  customers_city varchar(32) NOT NULL,
  customers_postcode varchar(8) NOT NULL,
  customers_state varchar(32),
  customers_country varchar(32) NOT NULL,
  customers_telephone varchar(32) NOT NULL,
  customers_email_address varchar(96) NOT NULL,
  delivery_name varchar(64) NOT NULL,
  delivery_street_address varchar(64) NOT NULL,
  delivery_suburb varchar(32),
  delivery_city varchar(32) NOT NULL,
  delivery_postcode varchar(8) NOT NULL,
  delivery_state varchar(32),
  delivery_country varchar(32) NOT NULL,
  payment_method varchar(12) NOT NULL,
  cc_type varchar(20),
  cc_owner varchar(64),
  cc_number varchar(32),
  cc_expires varchar(4),
  date_purchased varchar(8),
  products_tax decimal(6,4) NOT NULL,
  shipping_cost decimal(8,2) NOT NULL,
  shipping_method varchar(32),
  orders_status varchar(10) NOT NULL,
  orders_date_finished varchar(14),
  PRIMARY KEY (orders_id)
);

#
# Dumping data for table 'orders'
#

#
# Table structure for table 'orders_products'
#

CREATE TABLE orders_products (
  orders_products_id int(5) NOT NULL auto_increment,
  orders_id int(5) NOT NULL,
  products_id int(5) NOT NULL,
  products_name varchar(64) NOT NULL,
  products_price decimal(8,2) NOT NULL,
  final_price decimal(8,2) NOT NULL,
  products_quantity int(2) NOT NULL,
  PRIMARY KEY (orders_products_id)
);

#
# Dumping data for table 'orders_products'
#

#
# Table structure for table 'orders_products_attributes'
#

CREATE TABLE orders_products_attributes (
  orders_products_attributes_id int(5) NOT NULL auto_increment,
  orders_products_id int(5) NOT NULL,
  products_options varchar(32) NOT NULL,
  products_options_values varchar(32) NOT NULL,
  options_values_price decimal(8,2) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (orders_products_attributes_id)
);

#
# Dumping data for table 'orders_products_attributes'
#

#
# Table structure for table 'products'
#

CREATE TABLE products (
  products_id int(5) NOT NULL auto_increment,
  products_name varchar(32) NOT NULL,
  products_description text,
  products_quantity int(4) NOT NULL,
  products_model varchar(12),
  products_image varchar(64),
  products_url varchar(255),
  products_price decimal(8,2) NOT NULL,
  products_date_added varchar(8),
  products_viewed int(5),
  products_weight decimal(4,2) NOT NULL,
  products_status tinyint(1) NOT NULL,
  PRIMARY KEY (products_id),
  KEY products_name (products_name)
);

#
# Dumping data for table 'products'
#

INSERT INTO products VALUES (1,'G200 MMS','Reinforcing its position as a multi-monitor trailblazer, Matrox Graphics Inc. has once again developed the most flexible and highly advanced solution in the industry. Introducing the new Matrox G200 Multi-Monitor Series; the first graphics card ever to support up to four DVI digital flat panel displays on a single 8&quot; PCI board.<br><br>With continuing demand for digital flat panels in the financial workplace, the Matrox G200 MMS is the ultimate in flexible solutions. The Matrox G200 MMS also supports the new digital video interface (DVI) created by the Digital Display Working Group (DDWG) designed to ease the adoption of digital flat panels. Other configurations include composite video capture ability and onboard TV tuner, making the Matrox G200 MMS the complete solution for business needs.<br><br>Based on the award-winning MGA-G200 graphics chip, the Matrox G200 Multi-Monitor Series provides superior 2D/3D graphics acceleration to meet the demanding needs of business applications such as real-time stock quotes (Versus), live video feeds (Reuters & Bloombergs), multiple windows applications, word processing, spreadsheets and CAD.',32,'MG200MMS','images/matrox/mg200mms.gif','www.matrox.com/mga/feat_story/jun99/mms_g200.htm',299.99,'19991217',219,23.00,1);
INSERT INTO products VALUES (2,'G400 32MB','<b>Dramatically Different High Performance Graphics</b><br><br>Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry\'s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC\'s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br><br><b>Key features:</b><ul><li>New Matrox G400 256-bit DualBus graphics chip</li><li>Explosive 3D, 2D and DVD performance</li><li>DualHead Display</li><li>Superior DVD and TV output</li><li>3D Environment-Mapped Bump Mapping</li><li>Vibrant Color Quality rendering </li><li>UltraSharp DAC of up to 360 MHz</li><li>3D Rendering Array Processor</li><li>Support for 16 or 32 MB of memory</li></ul>',32,'MG400-32MB','images/matrox/mg400-32mb.gif','www.matrox.com/mga/products/mill_g400/home.htm',499.99,'19991217',228,23.00,1);
INSERT INTO products VALUES (4,'The Replacement Killers','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 80 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).',13,'DVD-RPMK','images/dvd/replacement_killers.gif','www.replacement-killers.com',42.00,'20000109',15,23.00,1);
INSERT INTO products VALUES (3,'IntelliMouse Pro','Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft\'s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.',32,'MSIMPRO','images/microsoft/msimpro.gif','www.microsoft.com/products/hardware/mouse/impro/default.htm',49.99,'20000105',346,7.00,1);
INSERT INTO products VALUES (5,'Blade Runner - Director\'s Cut','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 112 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).',17,'DVD-BLDRNDC','images/dvd/blade_runner.gif','www.bladerunner.com',35.99,'20000109',217,7.00,1);
INSERT INTO products VALUES (7,'You\'ve Got Mail','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch, Spanish.\r<br>\nSubtitles: English, Deutsch, Spanish, French, Nordic, Polish.\r<br>\nAudio: Dolby Digital 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 115 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-YGEM','images/dvd/youve_got_mail.gif','www.youvegotemail.com',34.99,'20000115',94,7.00,1);
INSERT INTO products VALUES (6,'The Matrix','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch.\r<br>\nAudio: Dolby Surround.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 131 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Making Of.',10,'DVD-MATR','images/dvd/the_matrix.gif','www.thematrix.com',39.99,'20000115',443,7.00,1);
INSERT INTO products VALUES (8,'A Bug\'s Life','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Digital 5.1 / Dobly Surround Stereo.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 91 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-ABUG','images/dvd/a_bugs_life.gif','www.abugslife.com',35.99,'20000115',120,7.00,1);
INSERT INTO products VALUES (9,'Under Siege','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 98 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-UNSG','images/dvd/under_siege.gif','',29.99,'20000115',11,7.00,1);
INSERT INTO products VALUES (10,'Under Siege 2 - Dark Territory','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 98 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-UNSG2','images/dvd/under_siege2.gif','',29.99,'20000115',15,7.00,1);
INSERT INTO products VALUES (11,'Fire Down Below','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 100 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-FDBL','images/dvd/fire_down_below.gif','',29.99,'20000115',13,7.00,1);
INSERT INTO products VALUES (12,'Die Hard With A Vengeance','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 122 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-DHWV','images/dvd/die_hard_3.gif','',39.99,'20000115',83,7.00,1);
INSERT INTO products VALUES (13,'Lethal Weapon','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 100 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-LTWP','images/dvd/lethal_weapon.gif','',34.99,'20000115',55,7.00,1);
INSERT INTO products VALUES (14,'Red Corner','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 117 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-REDC','images/dvd/red_corner.gif','',32.00,'20000115',32,7.00,1);
INSERT INTO products VALUES (15,'Frantic','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 115 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-FRAN','images/dvd/frantic.gif','',35.00,'20000115',37,7.00,1);
INSERT INTO products VALUES (16,'Courage Under Fire','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 112 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-CUFI','images/dvd/courage_under_fire.gif','',38.99,'20000115',297,7.00,1);
INSERT INTO products VALUES (17,'Speed','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 112 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-SPEED','images/dvd/speed.gif','',39.99,'20000115',48,7.00,1);
INSERT INTO products VALUES (18,'Speed 2: Cruise Control','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 120 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-SPEED2','images/dvd/speed_2.gif','',42.00,'20000115',58,7.00,1);
INSERT INTO products VALUES (19,'There\'s Something About Mary','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 114 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-TSAB','images/dvd/theres_something_about_mary.gif','',49.99,'20000115',180,7.00,1);
INSERT INTO products VALUES (20,'Beloved','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 164 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).',10,'DVD-BELOVED','images/dvd/beloved.gif','',54.99,'20000115',53,7.00,1);
INSERT INTO products VALUES (21,'SWAT 3: Close Quarters Battle','<b>Windows 95/98</b><br><br>211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and \"When needed\" to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!',16,'PC-SWAT3','images/sierra/swat_3.gif','www.swat3.com',79.99,'20000302',162,7.00,1);
INSERT INTO products VALUES (22,'Unreal Tournament','From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br><br>This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It\'s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of \'bots\' (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.',13,'PC-UNTM','images/gt_interactive/unreal_tournament.gif','www.unrealtournament.net',89.99,'20000302',502,7.00,1);
INSERT INTO products VALUES (23,'The Wheel Of Time','The world in which The Wheel of Time takes place is lifted directly out of Jordan\'s pages; it\'s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you\'re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter\'angreal, Portal Stones, and the Ways. However you move around, though, you\'ll quickly discover that means of locomotion can easily become the least of the your worries...<br><br>During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time\'s main characters. Some of these places are ripped directly from the pages of Jordan\'s books, made flesh with Legend\'s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you\'ll have this year.',16,'PC-TWOF','images/gt_interactive/wheel_of_time.gif','www.wheeloftime.com',99.99,'20000302',462,10.00,1);
INSERT INTO products VALUES (24,'Disciples: Sacred Lands','A new age is dawning...<br><br>Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br><br>The day of reckoning has come... and only the chosen will survive.',17,'PC-DISC','images/gt_interactive/disciples.gif','',90.00,'20000302',457,8.00,1);
INSERT INTO products VALUES (25,'Internet Keyboard PS/2','The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!',16,'MSINTKB','images/microsoft/intkeyboardps2.gif','',69.99,'20000302',769,8.00,1);
INSERT INTO products VALUES (26,'IntelliMouse Explorer PS2/USB','Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!',10,'MSIMEXP','images/microsoft/imexplorer.gif','www.microsoft.com/mouse/explorer.htm',64.95,'20000302',1946,8.00,1);
INSERT INTO products VALUES (27,'LaserJet 1100Xi','HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP\'s Resolution Enhancement technology (REt) makes every document more professional.<br><br>Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<br><br>HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).',8,'HPLJ1100XI','images/hewlett_packard/lj1100xi.gif','www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100',499.99,'20000302',1574,45.00,1);

#
# Table structure for table 'products_attributes'
#

CREATE TABLE products_attributes (
  products_attributes_id int(5) NOT NULL auto_increment,
  products_id int(5) NOT NULL,
  options_id int(5) NOT NULL,
  options_values_id int(5) NOT NULL,
  options_values_price decimal(8,2) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (products_attributes_id)
);

#
# Dumping data for table 'products_attributes'
#

INSERT INTO products_attributes VALUES (1,1,4,1,0.00,'+');
INSERT INTO products_attributes VALUES (2,1,4,2,50.00,'+');
INSERT INTO products_attributes VALUES (3,1,4,3,70.00,'+');
INSERT INTO products_attributes VALUES (4,1,3,5,0.00,'+');
INSERT INTO products_attributes VALUES (5,1,3,6,100.00,'+');
INSERT INTO products_attributes VALUES (6,2,4,3,10.00,'-');
INSERT INTO products_attributes VALUES (7,2,4,4,0.00,'+');
INSERT INTO products_attributes VALUES (8,2,3,6,0.00,'+');
INSERT INTO products_attributes VALUES (9,2,3,7,120.00,'+');
INSERT INTO products_attributes VALUES (10,26,3,8,0.00,'+');
INSERT INTO products_attributes VALUES (11,26,3,9,6.00,'+');

#
# Table structure for table 'products_attributes_to_basket'
#

CREATE TABLE products_attributes_to_basket (
  products_attributes_to_basket_id int(5) NOT NULL auto_increment,
  customers_basket_id int(5) NOT NULL,
  products_attributes_id int(5) NOT NULL,
  PRIMARY KEY (products_attributes_to_basket_id)
);

#
# Dumping data for table 'products_attributes_to_basket'
#

#
# Table structure for table 'products_expected'
#

CREATE TABLE products_expected (
  products_expected_id int(5) NOT NULL auto_increment,
  products_name varchar(255) NOT NULL,
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
# Table structure for table 'products_options'
#

CREATE TABLE products_options (
  products_options_id int(5) NOT NULL auto_increment,
  products_options_name varchar(32) NOT NULL,
  PRIMARY KEY (products_options_id)
);

#
# Dumping data for table 'products_options'
#

INSERT INTO products_options VALUES (1,'Color');
INSERT INTO products_options VALUES (2,'Size');
INSERT INTO products_options VALUES (3,'Model');
INSERT INTO products_options VALUES (4,'Memory');

#
# Table structure for table 'products_options_values'
#

CREATE TABLE products_options_values (
  products_options_values_id int(5) NOT NULL auto_increment,
  products_options_values_name varchar(64) NOT NULL,
  PRIMARY KEY (products_options_values_id)
);

#
# Dumping data for table 'products_options_values'
#

INSERT INTO products_options_values VALUES (1,'4 mb');
INSERT INTO products_options_values VALUES (2,'8 mb');
INSERT INTO products_options_values VALUES (3,'16 mb');
INSERT INTO products_options_values VALUES (4,'32 mb');
INSERT INTO products_options_values VALUES (5,'Value');
INSERT INTO products_options_values VALUES (6,'Premium');
INSERT INTO products_options_values VALUES (7,'Deluxe');
INSERT INTO products_options_values VALUES (8,'PS/2');
INSERT INTO products_options_values VALUES (9,'USB');

#
# Table structure for table 'products_options_values_to_products_options'
#

CREATE TABLE products_options_values_to_products_options (
  products_options_values_to_products_options_id int(5) NOT NULL auto_increment,
  products_options_id int(5) NOT NULL,
  products_options_values_id int(5) NOT NULL,
  PRIMARY KEY (products_options_values_to_products_options_id)
);

#
# Dumping data for table 'products_options_values_to_products_options'
#

INSERT INTO products_options_values_to_products_options VALUES (1,4,1);
INSERT INTO products_options_values_to_products_options VALUES (2,4,2);
INSERT INTO products_options_values_to_products_options VALUES (3,4,3);
INSERT INTO products_options_values_to_products_options VALUES (4,4,4);
INSERT INTO products_options_values_to_products_options VALUES (5,3,5);
INSERT INTO products_options_values_to_products_options VALUES (6,3,6);
INSERT INTO products_options_values_to_products_options VALUES (7,3,7);
INSERT INTO products_options_values_to_products_options VALUES (8,3,8);
INSERT INTO products_options_values_to_products_options VALUES (9,3,9);

#
# Table structure for table 'products_to_categories'
#

CREATE TABLE products_to_categories (
  products_id int(5) NOT NULL auto_increment,
  categories_id int(5) NOT NULL,
  PRIMARY KEY (products_id,categories_id)
);

#
# Dumping data for table 'products_to_categories'
#

INSERT INTO products_to_categories VALUES (1,4);
INSERT INTO products_to_categories VALUES (2,4);
INSERT INTO products_to_categories VALUES (3,9);
INSERT INTO products_to_categories VALUES (4,10);
INSERT INTO products_to_categories VALUES (5,11);
INSERT INTO products_to_categories VALUES (6,10);
INSERT INTO products_to_categories VALUES (7,12);
INSERT INTO products_to_categories VALUES (8,13);
INSERT INTO products_to_categories VALUES (9,10);
INSERT INTO products_to_categories VALUES (10,10);
INSERT INTO products_to_categories VALUES (11,10);
INSERT INTO products_to_categories VALUES (12,10);
INSERT INTO products_to_categories VALUES (13,10);
INSERT INTO products_to_categories VALUES (14,15);
INSERT INTO products_to_categories VALUES (15,14);
INSERT INTO products_to_categories VALUES (16,15);
INSERT INTO products_to_categories VALUES (17,10);
INSERT INTO products_to_categories VALUES (18,10);
INSERT INTO products_to_categories VALUES (19,12);
INSERT INTO products_to_categories VALUES (20,15);
INSERT INTO products_to_categories VALUES (21,18);
INSERT INTO products_to_categories VALUES (22,19);
INSERT INTO products_to_categories VALUES (23,20);
INSERT INTO products_to_categories VALUES (24,20);
INSERT INTO products_to_categories VALUES (25,8);
INSERT INTO products_to_categories VALUES (26,9);
INSERT INTO products_to_categories VALUES (27,5);

#
# Table structure for table 'products_to_manufacturers'
#

CREATE TABLE products_to_manufacturers (
  products_to_manufacturers_id int(5) NOT NULL auto_increment,
  products_id int(5) NOT NULL,
  manufacturers_id int(5) NOT NULL,
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
# Table structure for table 'reviews'
#

CREATE TABLE reviews (
  reviews_id int(5) NOT NULL auto_increment,
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
  reviews_id int(5) NOT NULL,
  products_id int(5) NOT NULL,
  customers_id int(5) NOT NULL,
  date_added char(8) NOT NULL,
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
  specials_id int(5) NOT NULL auto_increment,
  products_id int(5) NOT NULL,
  specials_new_products_price decimal(8,2) NOT NULL,
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

