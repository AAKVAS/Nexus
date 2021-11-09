create table users
(
    user_id   serial
        constraint users_pkey
            primary key,
    firstname text not null,
    lastname  text not null,
    password  text not null,
    email     text not null
);

alter table users
    owner to postgres;

create table posts
(
    post_id   serial
        constraint posts_pkey
            primary key,
    user_id   integer
        constraint posts_users_user_id_fk
            references users
            on update cascade on delete cascade,
    likes     integer,
    content   text,
    send_time integer
);

alter table posts
    owner to postgres;

create table comments
(
    comment_id serial
        constraint comments_pkey
            primary key,
    post_id    integer
        constraint comments_posts_post_id_fk
            references posts
            on update cascade on delete cascade,
    user_id    integer
        constraint comments_users_user_id_fk
            references users
            on update cascade on delete cascade,
    content    text not null
);

alter table comments
    owner to postgres;

create table post_likes
(
    post_id    integer not null
        constraint post_likes_posts_post_id_fk
            references posts
            on update cascade on delete cascade,
    user_id    integer not null
        constraint post_likes_users_user_id_fk
            references users
            on update cascade on delete cascade,
    owner_user integer not null
        constraint post_likes_users_user_id_fk_2
            references users
            on update cascade on delete cascade
);

alter table post_likes
    owner to postgres;

create table replies_to_comments
(
    comment_id       integer not null
        constraint replies_to_comments_comments_comment_id_fk
            references comments
            on update cascade on delete cascade,
    answered_comment integer not null
        constraint replies_to_comments_comments_comment_id_fk_2
            references comments
            on update cascade on delete cascade
);

alter table replies_to_comments
    owner to postgres;

create table repost
(
    post_id integer not null
        constraint repost_posts_post_id_fk
            references posts
            on update cascade on delete cascade,
    repost  integer not null
        constraint repost_posts_post_id_fk_2
            references posts
            on update cascade on delete cascade
);

alter table repost
    owner to postgres;

