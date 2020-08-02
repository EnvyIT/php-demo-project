/* DROP Database and User */
DROP DATABASE IF EXISTS fh_2020_scm4_S1810307008;
DROP USER IF EXISTS 'fh_2020_scm4'@'localhost';
COMMIT;

/* Create Database */
CREATE DATABASE IF NOT EXISTS fh_2020_scm4_S1810307008 CHARACTER SET utf8;
COMMIT;
/* Create User*/
CREATE USER IF NOT EXISTS 'fh_2020_scm4'@'localhost' IDENTIFIED BY 'fh_2020_scm4';
COMMIT;

GRANT ALL PRIVILEGES ON fh_2020_scm4_S1810307008.* TO 'fh_2020_scm4'@'localhost';
FLUSH PRIVILEGES;
COMMIT;

USE fh_2020_scm4_S1810307008;

/*Create Tables*/
CREATE TABLE IF NOT EXISTS Role
(
    id      int auto_increment primary key,
    name    enum ('Admin', 'HelpSeeker', 'Volunteer') not null unique,
    code    bit(7)                                    not null unique,
    deleted bool                                      not null
);

CREATE TABLE IF NOT EXISTS User
(
    id            int auto_increment primary key,
    role_id       int,
    first_name    varchar(250) not null,
    last_name     varchar(250) not null,
    user_name     varchar(250) not null unique,
    password      varchar(250) not null,
    creation_date date         not null,
    deleted       bool         not null,
    foreign key (role_id) references Role (id)
);

CREATE TABLE IF NOT EXISTS ShoppingList
(
    id           int auto_increment primary key,
    owner_id     int,
    volunteer_id int,
    name         varchar(250)                                      null,
    total        decimal(19, 4)                                    null check (total > 0.0),
    due_date     date                                              null,
    state        enum ('unpublished','new', 'in progress', 'done') not null,
    deleted      bool                                              not null,
    foreign key (owner_id) references User (id),
    foreign key (volunteer_id) references User (id)
);

CREATE TABLE IF NOT EXISTS Article
(
    id               int auto_increment primary key,
    shopping_list_id int,
    name             varchar(250)   not null,
    max_price        decimal(19, 4) not null check (max_price > 0.0),
    quantity         int            not null check (quantity > 0),
    checked          bool           not null,
    deleted          bool           not null,
    foreign key (shopping_list_id) references ShoppingList (id)
);
COMMIT;


/*Insert Roles*/
insert into Role (name, code)
values ('Admin', b'11111111');
insert into Role (name, code)
values ('HelpSeeker', b'00000001');
insert into Role (name, code)
values ('Volunteer', b'00000010');
COMMIT;

/*Insert Users*/
/* pw = username|welcome1234! sha512*/
insert into User (role_id, first_name, last_name, user_name, password, creation_date, deleted)
values (3, 'Michael', 'Eder', 'meder',
        'bab1067a333c163bf71e9cd0b3e5d64f82a714a307244a6746b02f21b3c0830c272dec0699a0f075a485fe5250a025c8ea9fa692f49778cabaaa1e17f6506008',
        '2020-04-11', false);
insert into User (role_id, first_name, last_name, user_name, password, creation_date, deleted)
values (2, 'Warren', 'Ferro', 'wferro0',
        '99c77d79948c03e93810bfad9b1c2305a0c62c0c8a2b96809d4c5dd0171885b24d21176884d0b8f6f1bf1ed86d9a3273b6123fdc3040ec6addc208344e768c33',
        '2020-04-11', false);
insert into User (role_id, first_name, last_name, user_name, password, creation_date, deleted)
values (2, 'Arlena', 'McGroarty', 'amcgroarty1',
        '6077cef8d52f25784cce41d53409d857eb00b810da3fd160f319b50ce02316753a0e68366ded1647b3515a6cc194e58788969824945fb52c97933990dbada27b',
        '2020-04-11', false);
insert into User (role_id, first_name, last_name, user_name, password, creation_date, deleted)
values (3, 'Roz', 'McCreagh', 'rmccreagh2',
        'c5f8db3618f9ca10b3ce296f5afe1c9d8b6a0f3768a2baff8739166b3776536008b01e158f58cdf33854e645705d963a175fc44e9dc4401d44b9f731938f6526',
        '2020-04-11', false);
insert into User (role_id, first_name, last_name, user_name, password, creation_date, deleted)
values (3, 'Crissie', 'Pendrey', 'cpendrey3',
        '2b7656e83fe08aa9a026f81f18adaec88b212fcd55a08c0b46a9adbe5e5ccbbad4ab183296931078dc9d2c9d88516053f0843ed05b118fac517a65e2a8eafad3',
        '2020-04-11', false);
insert into User (role_id, first_name, last_name, user_name, password, creation_date, deleted)
values (2, 'Barry', 'Moore', 'bmoore',
        '8dbe6483c9d236bb810c1aa795867707669bd2934a78c10aa0162670c8558b5ba6afc8faee4c38eb8b37123196856cdc5e8c9fab4b5735f9d2c279806c1baf74',
        '2020-04-11', true);
COMMIT;
