CREATE TABLE IF NOT EXISTS user_roles (
    id int auto_increment primary key,
    name varchar(255),
    is_active bool
);

CREATE TABLE IF NOT EXISTS users (
    id int auto_increment primary key,
    login varchar(100),
    password varchar(100),
    info_id int
);

CREATE TABLE IF NOT EXISTS users_salt(
    id int auto_increment primary key,
    user_id int not null
);

CREATE TABLE IF NOT EXISTS transactions(
    id int auto_increment primary key,
    user_id int not null,
    balance_before decimal(10,2) default 0,
    balance_after decimal(10,2) default 0,
    amount decimal(10,2) default 0,
    percent decimal(10,2) default 0
);

CREATE TABLE IF NOT EXISTS info(
    id int auto_increment primary key,
    name varchar(200)
);

TRUNCATE info;
INSERT INTO info (name) VALUES ('a'), ('b'), ('c'), ('d'), ('e'), ('f');
