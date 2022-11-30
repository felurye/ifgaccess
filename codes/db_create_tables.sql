CREATE TABLE users (
	id integer primary key auto_increment,
	tag varchar(8) not null,
	enrollment varchar(14) not null,
	name varchar(80) not null,
	email varchar(45),
	phone varchar(11)
);

CREATE TABLE access (
	id integer primary key auto_increment,
	user_id integer not null,
	foreign key (user_id) References users(id),
	room varchar(10) not null,
	checkin timestamp,
	checkout timestamp
);