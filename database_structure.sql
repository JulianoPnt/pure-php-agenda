create table users
(
    id          int auto_increment
        primary key,
    first_name  varchar(40)                           not null,
    last_name   varchar(40)                           not null,
    email       varchar(60)                           not null,
    password    varchar(255)                          not null,
    created_at  timestamp default current_timestamp() null
    constraint users_email_uindex
        unique (email)
);



create table contacts
(
    id               int auto_increment
        primary key,
    user_id          int                                   not null,
    first_name       varchar(40)                           not null,
    last_name        varchar(40)                           null,
    email            varchar(60)                           null,
    address_city     varchar(40)                           null,
    address_state    varchar(40)                           null,
    address          varchar(80)                           null,
    address_number   int                                   null,
    address_cep      varchar(20)                           null,
    address_district varchar(40)                           null,
    created_at       timestamp default current_timestamp() null
);

create index contacts_users_id_fk
    on contacts (user_id);




create table contacts_phones
(
    id         int auto_increment
        primary key,
    contact_id int                                   not null,
    number     varchar(20)                           not null,
    created_at timestamp default current_timestamp() null
);

create index contacts_phones_contacts_id_fk
    on contacts_phones (contact_id);

INSERT INTO teste_cohros.users (id, first_name, last_name, email, password, created_at, modified_at) VALUES (5, 'Juliano', 'Pantoni Filho', 'contato@julianopantoni.dev', '$2y$10$Y0UY8Ubv.5UXN9uj3ilKaOKZ/apM1q49F7S4zr7jhIqx28Grbpj/C', '2020-07-12 21:59:43', null);