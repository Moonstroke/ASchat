drop table if exists MESSAGE;
drop table if exists POSTEUR;

create table POSTEUR (
	pseudo varchar(16) primary key,
	mdp varchar(16) not null,
	avatar varchar(64) not null,
	posts int(5) default 0
)engine=InnoDB default charset=utf8;

create table MESSAGE (
	idMsg int primary key auto_increment,
	dateMsg datetime,
	pseudo varchar(16),
	contenu varchar(255) not null,
	points int default 0,
	foreign key (pseudo) references POSTEUR(pseudo)
)engine=InnoDB default charset=utf8;
