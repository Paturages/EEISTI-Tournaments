DROP TABLE IF EXISTS FieldEntries;
DROP TABLE IF EXISTS Fields;
DROP TABLE IF EXISTS Entries;
DROP TABLE IF EXISTS Games;

CREATE TABLE Games (
	name TEXT,
	num_players INTEGER,
	nickname_field TEXT,
	multicampus INTEGER
);

CREATE TABLE Entries (
	name TEXT,
	real_name TEXT,
	email TEXT,
	id_game INTEGER,
	campus TEXT,
	time INTEGER,
	id_team INTEGER,
	password TEXT,
	is_approved INTEGER
);

CREATE TABLE Fields (
	id_game INTEGER,
	field TEXT,
	description TEXT
);

CREATE TABLE FieldEntries (
	id_field INTEGER,
	id_entry INTEGER,
	field TEXT
);