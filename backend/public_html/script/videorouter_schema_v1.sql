-- Name: 	Video Router Schema
-- Date: 	27/06/2014
-- Version: 1.0
-- Author: 	Anthony Ferrillo
-- Email: 	anthonyf@themill.com

CREATE TABLE vr_group (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`group_uid` INT NOT NULL,
	`name` TEXT NOT NULL,
	`position` INT DEFAULT NULL,
	`active` INT NOT NULL DEFAULT 1,
	PRIMARY KEY (`uid`)
);

-- CREATE TABLE vr_label (
-- 	`uid` INT NOT NULL AUTO_INCREMENT,
-- 	`name` TEXT NOT NULL,
-- 	`friendly_label` TEXT,
-- 	`short_label` TEXT,
-- 	`name` TEXT NOT NULL,
-- 	`type` TEXT NOT NULL,
-- 	`group_uid` INT NOT NULL,
-- 	`port_uid` INT NOT NULL,
-- 	`active` INT NOT NULL,
-- 	PRIMARY KEY (`uid`)
-- );

CREATE TABLE vr_label (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`short_label` TEXT,
	`type` TEXT NOT NULL,
	`group_uid` INT NOT NULL,
	`port_uid` INT NOT NULL,
	`active` INT NOT NULL,
	PRIMARY KEY (`uid`)
);

CREATE TABLE vr_input (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`port_uid` TEXT NOT NULL,
	`name` TEXT NOT NULL,
	PRIMARY KEY (`uid`)
);

CREATE TABLE vr_output (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`port_uid` TEXT NOT NULL,
	`name` TEXT NOT NULL,
	`floor_uid` INT NOT NULL,
	PRIMARY KEY (`uid`)
);

CREATE TABLE vr_floor (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`position` INT NOT NULL,
	`active` INT NOT NULL DEFAULT 1,
	PRIMARY KEY (`uid`)
);

CREATE TABLE vr_log (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`user_uid` INT NOT NULL,
	`input_port_uid` INT NOT NULL,
	`output_port_uid` INT NOT NULL,
	`status` TEXT NOT NULL,
	`created` INT NOT NULL,
	PRIMARY KEY (`uid`)
);

-- Test Tables
CREATE TABLE vr_input_test (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`port_uid` TEXT NOT NULL,
	`label` TEXT NOT NULL,
	`hardware` TEXT NOT NULL,
	PRIMARY KEY (`uid`)
);

CREATE TABLE vr_output_test (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`port_uid` TEXT NOT NULL,
	`label` TEXT NOT NULL,
	`hardware` TEXT NOT NULL,
	`source` TEXT NOT NULL,
	PRIMARY KEY (`uid`)
);

CREATE TABLE vr_input_lock (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`port_uid` TEXT NOT NULL,
	`username` TEXT NOT NULL,
	`created` INT NOT NULL,
	PRIMARY KEY (`uid`)
);

-- Test Rows

-- vr_group
INSERT INTO vr_group (name, position) VALUES ('Flame', 0);
INSERT INTO vr_group (name, position) VALUES ('Smoke', 1);
INSERT INTO vr_group (name, position) VALUES ('Colour', 2);
INSERT INTO vr_group (name, position) VALUES ('Misc', 3);

-- vr_floor
INSERT INTO vr_floor (name, position, active) VALUES ('Basement', 1, 1);
INSERT INTO vr_floor (name, position, active) VALUES ('Ground', 2, 1);
INSERT INTO vr_floor (name, position, active) VALUES ('1', 3, 1);
INSERT INTO vr_floor (name, position, active) VALUES ('2', 4, 1);
INSERT INTO vr_floor (name, position, active) VALUES ('3', 5, 1);
INSERT INTO vr_floor (name, position, active) VALUES ('4', 6, 1);
INSERT INTO vr_floor (name, position, active) VALUES ('5', 7, 1);

-- vr_input
INSERT INTO vr_input (port_uid, name) VALUES (1, 'Flame 1 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (2, 'Flame 1 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (3, 'Flame 2 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (4, 'Flame 2 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (5, 'Flame 3 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (6, 'Flame 3 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (7, 'Flame 4 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (8, 'Flame 4 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (9, 'Flame 5 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (10, 'Flame 5 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (11, 'Flame 6 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (12, 'Flame 6 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (13, 'Flame 7 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (14, 'Flame 7 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (15, 'Flame 8 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (16, 'Flame 8 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (17, 'Flame 9 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (18, 'Flame 9 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (19, 'Flame 10 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (20, 'Flame 10 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (21, 'Flame 11 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (22, 'Flame 11 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (23, 'Flame 12 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (24, 'Flame 12 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (25, 'Flame Hire 1 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (26, 'Flame Hire 1 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (27, 'Flame Hire 2 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (28, 'Flame Hire 2 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (29, 'Flame Hire 3 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (30, 'Flame Hire 3 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (31, 'Flint 1 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (32, 'Flint 1 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (33, 'Flint 2 Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (34, 'Flint 2 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (35, 'Backdraft Kona out A');
INSERT INTO vr_input (port_uid, name) VALUES (36, 'Backdraft GFX');
INSERT INTO vr_input (port_uid, name) VALUES (37, 'Smoke 1 out A');
INSERT INTO vr_input (port_uid, name) VALUES (38, 'Smoke 1 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (39, 'Smoke 2 out A');
INSERT INTO vr_input (port_uid, name) VALUES (40, 'Smoke 2 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (41, 'Smoke 3 out A');
INSERT INTO vr_input (port_uid, name) VALUES (42, 'Smoke 3 GFX');
INSERT INTO vr_input (port_uid, name) VALUES (43, 'Smoke 4');
INSERT INTO vr_input (port_uid, name) VALUES (44, 'Smoke 5');
INSERT INTO vr_input (port_uid, name) VALUES (45, 'Smoke 6');
INSERT INTO vr_input (port_uid, name) VALUES (46, 'Smoke 7');
INSERT INTO vr_input (port_uid, name) VALUES (47, 'Smoke 8');
INSERT INTO vr_input (port_uid, name) VALUES (48, 'Colour 1 out A');
INSERT INTO vr_input (port_uid, name) VALUES (49, 'Colour 1 out B');
INSERT INTO vr_input (port_uid, name) VALUES (50, 'Colour 2 out A');
INSERT INTO vr_input (port_uid, name) VALUES (51, 'Colour 2 out B');
INSERT INTO vr_input (port_uid, name) VALUES (52, 'Colour 3 out A');
INSERT INTO vr_input (port_uid, name) VALUES (53, 'Colour 3 out B');
INSERT INTO vr_input (port_uid, name) VALUES (54, 'Colour 3 VT out A');
INSERT INTO vr_input (port_uid, name) VALUES (55, 'Colour 3 VT out B');
INSERT INTO vr_input (port_uid, name) VALUES (56, 'Colour Assist 1 out A');
INSERT INTO vr_input (port_uid, name) VALUES (57, 'Colour Assist 1 out B');
INSERT INTO vr_input (port_uid, name) VALUES (58, 'Colour Assist 2 out A');
INSERT INTO vr_input (port_uid, name) VALUES (59, 'Colour Assist 2 out B');
INSERT INTO vr_input (port_uid, name) VALUES (60, 'HDVT 1 out A');
INSERT INTO vr_input (port_uid, name) VALUES (61, 'HDVT 1 out B');
INSERT INTO vr_input (port_uid, name) VALUES (62, 'HDVT 1 FC out A');
INSERT INTO vr_input (port_uid, name) VALUES (63, 'HDVT 1 FC out B');
INSERT INTO vr_input (port_uid, name) VALUES (64, 'HDVT 1 DownC out B');
INSERT INTO vr_input (port_uid, name) VALUES (65, 'HDVT 2 out A');
INSERT INTO vr_input (port_uid, name) VALUES (66, 'HDVT 2 out B');
INSERT INTO vr_input (port_uid, name) VALUES (67, 'HDVT 2 FC out A');
INSERT INTO vr_input (port_uid, name) VALUES (68, 'HDVT 2 FC out B');
INSERT INTO vr_input (port_uid, name) VALUES (69, 'HDVT 2 DownC out B');
INSERT INTO vr_input (port_uid, name) VALUES (70, 'Digi 1');
INSERT INTO vr_input (port_uid, name) VALUES (71, 'Digi 2');
INSERT INTO vr_input (port_uid, name) VALUES (72, 'Digi 3');
INSERT INTO vr_input (port_uid, name) VALUES (73, 'FCP 1');
INSERT INTO vr_input (port_uid, name) VALUES (74, 'FCP 2');
INSERT INTO vr_input (port_uid, name) VALUES (75, 'FCP 3');
INSERT INTO vr_input (port_uid, name) VALUES (76, 'ARC 1');
INSERT INTO vr_input (port_uid, name) VALUES (77, 'Standards Convertor');
INSERT INTO vr_input (port_uid, name) VALUES (78, 'Media Port 1');
INSERT INTO vr_input (port_uid, name) VALUES (79, 'Media Port 2');
INSERT INTO vr_input (port_uid, name) VALUES (80, 'Media Port 3');
INSERT INTO vr_input (port_uid, name) VALUES (81, 'Media Port 4');
INSERT INTO vr_input (port_uid, name) VALUES (82, 'Media Port 5');
INSERT INTO vr_input (port_uid, name) VALUES (83, 'Media Port 6');
INSERT INTO vr_input (port_uid, name) VALUES (84, 'BBH RX');
INSERT INTO vr_input (port_uid, name) VALUES (85, 'Publicis RX');
INSERT INTO vr_input (port_uid, name) VALUES (86, 'YoYo 1');
INSERT INTO vr_input (port_uid, name) VALUES (87, 'Sky Box');
INSERT INTO vr_input (port_uid, name) VALUES (88, 'TVIPS 1');
INSERT INTO vr_input (port_uid, name) VALUES (89, 'TVIPS 2');
INSERT INTO vr_input (port_uid, name) VALUES (90, 'Legaliser SD');
INSERT INTO vr_input (port_uid, name) VALUES (91, 'Legaliser HD');
INSERT INTO vr_input (port_uid, name) VALUES (92, '50i Bars');
INSERT INTO vr_input (port_uid, name) VALUES (93, '50i Black');
INSERT INTO vr_input (port_uid, name) VALUES (94, '23.97 Bars');
INSERT INTO vr_input (port_uid, name) VALUES (95, '23.97 Black');
INSERT INTO vr_input (port_uid, name) VALUES (96, '59.97i Bars');
INSERT INTO vr_input (port_uid, name) VALUES (97, '59.97i Black');
INSERT INTO vr_input (port_uid, name) VALUES (98, 'TSG Selectable');
INSERT INTO vr_input (port_uid, name) VALUES (99, '625 Bars');
INSERT INTO vr_input (port_uid, name) VALUES (100, '625 Black');
INSERT INTO vr_input (port_uid, name) VALUES (101, '525 Bars');
INSERT INTO vr_input (port_uid, name) VALUES (102, '525 Black');
INSERT INTO vr_input (port_uid, name) VALUES (103, 'Nuke 1');
INSERT INTO vr_input (port_uid, name) VALUES (104, 'Nuke 2');
INSERT INTO vr_input (port_uid, name) VALUES (105, 'Nuke 3');
INSERT INTO vr_input (port_uid, name) VALUES (106, 'Nuke 4');
INSERT INTO vr_input (port_uid, name) VALUES (107, 'Nuke 5');
INSERT INTO vr_input (port_uid, name) VALUES (108, 'Nuke 6');
INSERT INTO vr_input (port_uid, name) VALUES (109, 'Encoder 3 out (dig rapids stream)');
INSERT INTO vr_input (port_uid, name) VALUES (110, 'Encoder 5 out (dig rapids stream)');
INSERT INTO vr_input (port_uid, name) VALUES (111, 'Encoder 6 out (FCP)');
INSERT INTO vr_input (port_uid, name) VALUES (112, 'Encoder 7 out (FCP)');
INSERT INTO vr_input (port_uid, name) VALUES (113, 'Encoder 8 out (FCP)');
INSERT INTO vr_input (port_uid, name) VALUES (114, 'Encoder 9 (Clipster 1) out A');
INSERT INTO vr_input (port_uid, name) VALUES (115, 'Encoder 9 (Clipster 1) out B');
INSERT INTO vr_input (port_uid, name) VALUES (116, 'Encoder 13 out (FCP cs-mac)');
INSERT INTO vr_input (port_uid, name) VALUES (117, 'Encoder 10 (amberfin)');
INSERT INTO vr_input (port_uid, name) VALUES (118, 'Amberfin 2');
INSERT INTO vr_input (port_uid, name) VALUES (119, 'DFR 1 (Adstrem qc)');
INSERT INTO vr_input (port_uid, name) VALUES (120, 'DFR 1 (Adstrem qc)');
INSERT INTO vr_input (port_uid, name) VALUES (121, 'Content Agent');
INSERT INTO vr_input (port_uid, name) VALUES (122, 'Content Agent');
INSERT INTO vr_input (port_uid, name) VALUES (123, 'Venice 1');
INSERT INTO vr_input (port_uid, name) VALUES (124, 'Venice 2');
INSERT INTO vr_input (port_uid, name) VALUES (125, 'Venice 3');
INSERT INTO vr_input (port_uid, name) VALUES (126, 'Venice 4');
INSERT INTO vr_input (port_uid, name) VALUES (127, 'NR A');
INSERT INTO vr_input (port_uid, name) VALUES (128, 'NR B');
INSERT INTO vr_input (port_uid, name) VALUES (129, '1st Floor Floorbox DE4');
INSERT INTO vr_input (port_uid, name) VALUES (130, '2nd Floor Floorbox DE4');
INSERT INTO vr_input (port_uid, name) VALUES (131, '3rd Floor Floorbox DE4');
INSERT INTO vr_input (port_uid, name) VALUES (132, 'AU9 Monoizer');
INSERT INTO vr_input (port_uid, name) VALUES (133, 'Teletext Decoder');

-- vr_output
INSERT INTO vr_output (port_uid, name) VALUES (1, 'Flame 1 Kona in A', 1);
INSERT INTO vr_output (port_uid, name) VALUES (2, 'Flame 2 Kona in A', 1);
INSERT INTO vr_output (port_uid, name) VALUES (3, 'Flame 3 Kona in A', 2);
INSERT INTO vr_output (port_uid, name) VALUES (4, 'Flame 4 Kona in A', 2);
INSERT INTO vr_output (port_uid, name) VALUES (5, 'Flame 5 Kona in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (6, 'Flame 6 Kona in A', 1);
INSERT INTO vr_output (port_uid, name) VALUES (7, 'Flame 7 Kona in A', 2);
INSERT INTO vr_output (port_uid, name) VALUES (8, 'Flame 8 Kona in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (9, 'Flame 9 Kona in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (10, 'Flame 10 Kona in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (11, 'Flame 11 Kona in A', 2);
INSERT INTO vr_output (port_uid, name) VALUES (12, 'Flame 12 Kona in A', 2);
INSERT INTO vr_output (port_uid, name) VALUES (13, 'Flame Hire 1 Kona in A', 1);
INSERT INTO vr_output (port_uid, name) VALUES (14, 'Flame Hire 2 Kona in A', 2);
INSERT INTO vr_output (port_uid, name) VALUES (15, 'Flame Hire 3 Kona in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (15, 'Flame Hire 3 Kona in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (16, 'Flint 1', 2);
INSERT INTO vr_output (port_uid, name) VALUES (17, 'Flint 2', 2);
INSERT INTO vr_output (port_uid, name) VALUES (18, 'Backdraft 1', 2);
INSERT INTO vr_output (port_uid, name) VALUES (19, 'Smoke 1', 3);
INSERT INTO vr_output (port_uid, name) VALUES (20, 'Smoke 2', 3);
INSERT INTO vr_output (port_uid, name) VALUES (21, 'Smoke 3', 3);
INSERT INTO vr_output (port_uid, name) VALUES (22, 'Smoke 4', 3);
INSERT INTO vr_output (port_uid, name) VALUES (23, 'Smoke 5', 2);
INSERT INTO vr_output (port_uid, name) VALUES (24, 'Smoke 6', 2);
INSERT INTO vr_output (port_uid, name) VALUES (25, 'Smoke 7', 1);
INSERT INTO vr_output (port_uid, name) VALUES (26, 'Smoke 8', 1);
INSERT INTO vr_output (port_uid, name) VALUES (27, 'Colour 3 VT in A', 1);
INSERT INTO vr_output (port_uid, name) VALUES (28, 'Colour 3 VT in B', 1);
INSERT INTO vr_output (port_uid, name) VALUES (29, 'HDVT 1 in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (30, 'HDVT 1 in B', 3);
INSERT INTO vr_output (port_uid, name) VALUES (31, 'HDVT 2 in A', 3);
INSERT INTO vr_output (port_uid, name) VALUES (32, 'HDVT 2 in B', 3);
INSERT INTO vr_output (port_uid, name) VALUES (33, 'Digi 1', 3);
INSERT INTO vr_output (port_uid, name) VALUES (34, 'Digi 2', 2);
INSERT INTO vr_output (port_uid, name) VALUES (35, 'Digi 3', 1);
INSERT INTO vr_output (port_uid, name) VALUES (36, 'FCP 1', 1);
INSERT INTO vr_output (port_uid, name) VALUES (37, 'FCP 2', 2);
INSERT INTO vr_output (port_uid, name) VALUES (38, 'FCP 3', 2);
INSERT INTO vr_output (port_uid, name) VALUES (39, 'Encoder 10 Amber fin', 3);
INSERT INTO vr_output (port_uid, name) VALUES (40, 'Amberfin 2', 3);
INSERT INTO vr_output (port_uid, name) VALUES (41, 'Content Agent', 2);
INSERT INTO vr_output (port_uid, name) VALUES (42, 'ARC 1', 1);
INSERT INTO vr_output (port_uid, name) VALUES (43, 'Standards Convertor', 1);
INSERT INTO vr_output (port_uid, name) VALUES (44, 'BBH TX', 1);
INSERT INTO vr_output (port_uid, name) VALUES (45, 'Publicis TX', 1);
INSERT INTO vr_output (port_uid, name) VALUES (46, 'Encoder 3 in (gid rapids stream)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (47, 'Encoder 5 in (gid rapids stream)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (48, 'Encoder 6 in (FCP)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (49, 'Encoder 7 in (FCP)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (50, 'Encoder 8 in (FCP)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (51, 'Encoder 9 in (Clipster 1) in A', 1);
INSERT INTO vr_output (port_uid, name) VALUES (52, 'Encoder 9 in (Clipster 1) in B', 1);
INSERT INTO vr_output (port_uid, name) VALUES (53, 'Encoder 13 in (FCP) cs-mac', 3);
INSERT INTO vr_output (port_uid, name) VALUES (54, 'Venice 1', 1);
INSERT INTO vr_output (port_uid, name) VALUES (55, 'Venice 2', 1);
INSERT INTO vr_output (port_uid, name) VALUES (56, 'Venice 3', 1);
INSERT INTO vr_output (port_uid, name) VALUES (57, 'Venice 4', 1);
INSERT INTO vr_output (port_uid, name) VALUES (58, 'Media Port 1', 2);
INSERT INTO vr_output (port_uid, name) VALUES (59, 'Media Port 2', 2);
INSERT INTO vr_output (port_uid, name) VALUES (60, 'Media Port 3', 2);
INSERT INTO vr_output (port_uid, name) VALUES (61, 'Media Port 4', 2);
INSERT INTO vr_output (port_uid, name) VALUES (62, 'Media Port 5', 3);
INSERT INTO vr_output (port_uid, name) VALUES (63, 'Media Port 6', 3);
INSERT INTO vr_output (port_uid, name) VALUES (64, 'TVIPS 1', 3);
INSERT INTO vr_output (port_uid, name) VALUES (65, 'TVIPS 2', 3);
INSERT INTO vr_output (port_uid, name) VALUES (66, 'NR A', 1);
INSERT INTO vr_output (port_uid, name) VALUES (67, 'NR B', 1);
INSERT INTO vr_output (port_uid, name) VALUES (68, 'Streaming PC', 1);
INSERT INTO vr_output (port_uid, name) VALUES (69, 'Legaliser SD', 2);
INSERT INTO vr_output (port_uid, name) VALUES (70, 'Legaliser HD', 3);
INSERT INTO vr_output (port_uid, name) VALUES (71, 'YoYo', 3);
INSERT INTO vr_output (port_uid, name) VALUES (72, 'Harding PC', 3);
INSERT INTO vr_output (port_uid, name) VALUES (73, 'Mon 1A (GF Boardroom A)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (74, 'Mon 2A (cafe wall-GF Production)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (75, 'Mon 3A (Suite 1.1)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (76, 'Mon 4A (Suite 1.2)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (77, 'Mon 5A (Suite 1.3)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (78, 'Mon 6A (Suite 1.4)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (79, 'Mon 7A (Suite 1.5)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (80, 'Mon 8A (Suite 1.6)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (81, 'Mon 9A (1st Floor Colour 1)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (82, 'Mon 9B (1st Floor Colour 1)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (83, 'Mon 10A (1st Floor open area 1A)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (84, 'Mon 11A (1st Floor open area 2A)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (85, 'Mon 12A (1st Floor open area 3A)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (86, 'Mon 13A (1st Floor projector A)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (87, 'Mon 14 (1st Floor-picnic)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (88, 'Mon 15A (Suite 2.1)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (89, 'Mon 16A (Suite 2.2)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (90, 'Mon 17A (Suite 2.3)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (91, 'Mon 18A (Suite 2.3)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (92, 'Mon 19A (Suite 2.4)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (93, 'Mon 20A (Suite 2.5)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (94, 'Mon 21A (2nd Floor Colour 2)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (95, 'Mon 21B (2nd Floor Colour 2)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (96, 'Mon 22A (2nd Floor Colour Assist)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (97, 'Mon 22B (2nd Floor Colour Assist)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (98, 'Mon 23A (2nd Floor open area 1)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (99, 'Mon 24A (2nd Floor open area 2)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (100, 'Mon 25A (2nd Floor projector)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (101, 'Mon 26A (Suite 3.1)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (102, 'Mon 27A (Suite 3.2)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (103, 'Mon 28A (Suite 3.3)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (104, 'Mon 29A (Suite 3.3)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (105, 'Mon 30A (Suite 3.4)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (106, 'Mon 31A (Suite 3.5)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (107, 'Mon 32A (3rd Floor Colour 3A)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (108, 'Mon 32A (3rd Floor Colour 3B)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (109, 'Mon 33A (3rd Floor open area 1)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (110, 'Mon 34A (3rd Floor open area 2)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (111, 'Mon 35A (3rd Floor Projector)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (112, 'Mon 36 (3rd Floor picnic table)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (113, 'Mon 37 (4th Floor Ad text Mon 1)', 4);
INSERT INTO vr_output (port_uid, name) VALUES (114, 'Mon 38 (4th Floor Ad text Mon 2)', 4);
INSERT INTO vr_output (port_uid, name) VALUES (115, 'Mon 39 (4th Floor Ad text Mon 3)', 4);
INSERT INTO vr_output (port_uid, name) VALUES (116, 'Mon 40 (4th Floor Beam Dev mon)', 4);
INSERT INTO vr_output (port_uid, name) VALUES (117, 'Mon 41 (4th Floor 3D Area mon)', 4);
INSERT INTO vr_output (port_uid, name) VALUES (118, 'Mon 42A (Suite 5.1)', 5);
INSERT INTO vr_output (port_uid, name) VALUES (119, 'Mon 43A (Suite 5.2)', 5);
INSERT INTO vr_output (port_uid, name) VALUES (120, 'Mon 44A (Suite 5.3)', 5);
INSERT INTO vr_output (port_uid, name) VALUES (121, 'Mon 45 (5th Floor client mon)', 5);
INSERT INTO vr_output (port_uid, name) VALUES (122, 'Mon 46A (5th Floor Board Room)', 5);
INSERT INTO vr_output (port_uid, name) VALUES (123, 'Mon 47 (basement IO rack)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (124, 'Mon 48A (basement Datalab Mon)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (125, 'Mon 48B (basement Datalab Mon)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (126, 'Mon 49 (basement IO Rack Mon)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (127, 'Mon 50 (basement Support Plasma)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (128, 'Mon 51 (basement Engineering Mon)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (129, 'Mon 52 (basement QC1)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (130, 'Mon 53 (basement QC2)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (131, 'Mon 54 (basement QC3)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (132, 'Mon 55 (basement QC4)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (133, 'Mon 56 (basement QC5)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (134, 'Mon 57 (basement QC wall mon)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (135, 'Mon 58 (CAR rack 26)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (136, 'Mon 59 (CAR rack 23)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (137, 'Tie 1 (Basement Engineering Mullering)', 3);
INSERT INTO vr_output (port_uid, name) VALUES (138, 'Tie 2 (Basement Engineering Mullering)', 1);
INSERT INTO vr_output (port_uid, name) VALUES (139, 'Dolby LM100 (Basement IO rack)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (140, 'AU9 Monoizer (Basement IO rack)', 2);
INSERT INTO vr_output (port_uid, name) VALUES (141, 'Teletext Decoder (Basement IO rack)', 3);

-- vr_label
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Flame 1', 'input', 1, 2);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Flame 2', 'input', 1, 4);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Flame 3', 'input', 1, 6);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Flame 4', 'input', 1, 8);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Smoke 1', 'input', 2, 38);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Smoke 2', 'input', 2, 40);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Smoke 3', 'input', 2, 42);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Colour 1', 'input', 3, 48);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Colour 2', 'input', 3, 49); 

-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 1 - GF Boardroom', 'output', 1, 73);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 2 - Cafe Wall', 'output', 1, 74);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 3 - Picnic Table', 'output', 1, 75);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 4 - Column 1', 'output', 1, 76);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 5 - 1.1', 'output', 1, 77);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 6 - 1.2', 'output', 2, 78);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 7 - 1.3', 'output', 2, 79);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 8 - 1.4', 'output', 2, 80);
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 9 - 1.5', 'output', 3, 81); 
-- INSERT INTO vr_label (name, type, group_uid, port_uid) VALUES ('Suite 10 - 1.6', 'output', 3, 82); 


INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (2, 'Flame 1 GFX', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (4, 'Flame 2 GFX', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (6, 'Flame 3 GFX', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (8, 'Flame 4 GFX', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (38, 'Smoke 1 GFX', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (40, 'Smoke 2 GFX', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (42, 'Smoke 3 GFX', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (48, 'Colour 1 out A', 'Unknown');
INSERT INTO vr_input_test (port_uid, label, hardware) VALUES (49, 'Colour 1 out B', 'Unknown');

INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (73, 'Mon 1A (GF Boardroom A)', 'Unknown', 2);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (74, 'Mon 2A (cafe wall-GF Production)', 'Unknown', 2);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (75, 'Mon 3 (1st Floor-picnic table)' ,'Unknown', 2);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (76, 'Mon 4A (1st Floor-Column 1)' ,'Unknown', 42);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (77, 'Mon 5A (Suite 1.1)' ,'Unknown', 38);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (78, 'Mon 6A (Suite 1.2)' ,'Unknown', 38);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (79, 'Mon 7A (Suite 1.3)' ,'Unknown', 48);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (80, 'Mon 8A (Suite 1.4)' ,'Unknown', 49);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (81, 'Mon 9A (Suite 1.5)' ,'Unknown', 0);
INSERT INTO vr_output_test (port_uid, label, hardware, source) VALUES (82, 'Mon 10A (Suite 1.6)' ,'Unknown', 0);
