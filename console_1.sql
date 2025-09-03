create table if not exists articles
(
    id          int auto_increment
        primary key,
    title       varchar(100)                         not null,
    slug        varchar(100)                         null,
    description text                                 null,
    content     text                                 not null,
    created_at  timestamp  default CURRENT_TIMESTAMP not null,
    updated_at  timestamp  default CURRENT_TIMESTAMP not null,
    active      tinyint(1) default 0                 not null,
    sort        int        default 1                 not null,
    image       json                                 null
);


create table if not exists contact_us
(
    id              int auto_increment
        primary key,
    module_name     varchar(255)                         null,
    contact_name    varchar(255)                         null,
    contact_email   varchar(255)                         null,
    contact_mobile  varchar(25)                          not null,
    send_to         varchar(255)                         null,
    contact_subject varchar(255)                         null,
    contact_message mediumtext                           null,
    created_at      timestamp  default CURRENT_TIMESTAMP not null,
    updated_at      timestamp  default CURRENT_TIMESTAMP not null,
    is_read         tinyint(1) default 0                 not null,
    study_year      tinyint(1)                           null,
    selected_course varchar(50)                          null
);

create table if not exists migrations
(
    id        bigint unsigned not null,
    version   varchar(255)    not null,
    class     varchar(255)    not null,
    `group`   varchar(255)    not null,
    namespace varchar(255)    not null,
    time      int             not null,
    batch     int unsigned    not null
);

create table if not exists pages
(
    id         int auto_increment
        primary key,
    page_link  varchar(255)                         not null,
    title      varchar(255)                         not null,
    `desc`     mediumtext                           null,
    content    mediumtext                           null,
    created_at timestamp  default CURRENT_TIMESTAMP not null,
    deleted_at timestamp  default CURRENT_TIMESTAMP not null,
    updated_at timestamp  default CURRENT_TIMESTAMP not null,
    active     tinyint(1) default 0                 not null,
    show_home  tinyint(1) default 0                 not null,
    images     longtext                             null,
    sort       int        default 0                 not null,
    parent_id  int        default 0                 not null
);



create table if not exists tb_categories
(
    id           int auto_increment
        primary key,
    name_ar      varchar(255)                         not null,
    course_count int unsigned                         not null,
    icon_class   varchar(255)                         not null,
    slug         varchar(255)                         null,
    active       tinyint(1) default 1                 null,
    created_at   timestamp  default CURRENT_TIMESTAMP null,
    updated_at   timestamp  default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint slug
        unique (slug)
);

create table if not exists tb_courses
(
    id               int auto_increment
        primary key,
    course_name      varchar(255)                         not null,
    slug             varchar(255)                         null,
    skill_level      varchar(100)                         null,
    intro_video_id   varchar(100)                         null,
    price            decimal(10, 2)                       not null,
    image            json                                 null,
    course_desc      text                                 null,
    course_structure json                                 null comment 'JSON structure for sections and videos with sort, active (0 or 1), and video_desc',
    waiting_list     tinyint(1) default 0                 not null,
    is_free          tinyint(1) default 0                 null,
    short_desc       varchar(100)                         null,
    created_at       timestamp  default CURRENT_TIMESTAMP null,
    updated_at       timestamp  default CURRENT_TIMESTAMP null,
    sort             int                                  null,
    active           tinyint(1)                           null
);

create table if not exists tb_enrollments
(
    id           int auto_increment
        primary key,
    user_id      int                                                                 not null,
    course_id    int                                                                 not null,
    enrolled_at  datetime                                  default CURRENT_TIMESTAMP null,
    status       enum ('active', 'completed', 'cancelled') default 'active'          null,
    completed_at datetime                                                            null,
    updated_at   timestamp                                 default CURRENT_TIMESTAMP null,
    proof_image  varchar(100)                                                        null,
    constraint unique_enrollment
        unique (user_id, course_id)
);

create index idx_enrollment_course
    on tb_enrollments (course_id);

create index idx_enrollment_user
    on tb_enrollments (user_id);

create table if not exists tb_payments
(
    id             int auto_increment
        primary key,
    user_id        int                                                               not null,
    course_id      int                                                               not null,
    amount         decimal(10, 2)                                                    not null,
    payment_method varchar(50)                             default 'instapay'        not null,
    payment_status enum ('pending', 'completed', 'failed') default 'pending'         null,
    proof_image    varchar(255)                                                      null,
    created_at     timestamp                               default CURRENT_TIMESTAMP null,
    updated_at     timestamp                               default CURRENT_TIMESTAMP null
);

create table if not exists tb_questions
(
    id            int unsigned auto_increment
        primary key,
    course_id     int unsigned                                                                                  not null,
    question_text text                                                                                          not null,
    question_type enum ('single', 'multiple', 'true_false', 'fill_in_blank', 'essay') default 'single'          not null,
    created_at    timestamp                                                           default CURRENT_TIMESTAMP null,
    updated_at    timestamp                                                           default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create table if not exists tb_questions_options
(
    id          int unsigned auto_increment
        primary key,
    question_id int unsigned         not null,
    option_text text                 not null,
    is_correct  tinyint(1) default 0 not null
);

create table if not exists tb_student_answers
(
    id          int unsigned auto_increment
        primary key,
    question_id int unsigned         not null,
    option_id   int unsigned         null,
    answer_text text                 null,
    is_correct  tinyint(1) default 0 null
);

create table if not exists tb_video_completions
(
    id            int auto_increment
        primary key,
    enrollment_id int                                not null,
    video_id      int                                not null,
    completed_at  datetime default CURRENT_TIMESTAMP null
);

create index idx_lesson_enrollment
    on tb_video_completions (enrollment_id);

create table if not exists tbnotifications
(
    id               int auto_increment
        primary key,
    notify_name      varchar(50)                          not null,
    title_ar         varchar(100)                         not null,
    title_en         varchar(100)                         not null,
    desc_ar          varchar(255)                         not null,
    desc_en          varchar(255)                         not null,
    font_color       varchar(25)                          null,
    background_color varchar(25)                          null,
    has_push         tinyint(1) default 0                 not null,
    is_public        tinyint(1) default 0                 not null,
    active           tinyint(1) default 0                 not null,
    created_at       timestamp  default CURRENT_TIMESTAMP not null
);

create table if not exists tborders_status
(
    id                 int auto_increment
        primary key,
    name_ar            varchar(50)          not null,
    name_en            varchar(50)          not null,
    client_status_name varchar(20)          null,
    action_name_client varchar(25)          not null,
    action_name_ar     varchar(50)          not null,
    action_name_en     varchar(50)          not null,
    notify_name        varchar(50)          not null,
    color              varchar(20)          null,
    activated          tinyint(1) default 1 null,
    sort               tinyint(1) default 1 not null
);

create table if not exists users
(
    id          int unsigned auto_increment
        primary key,
    username    varchar(50)          null,
    full_name   varchar(50)          null,
    mobile      varchar(15)          null,
    user_type   tinyint(1) default 1 not null,
    status      tinyint(1) default 1 null,
    active      tinyint(1) default 0 not null,
    last_active datetime             null,
    created_at  datetime             null,
    updated_at  datetime             null,
    group_id    tinyint(1) default 2 null,
    deleted_at  timestamp            null,
    email       varchar(50)          not null,
    constraint users_mobile_uindex
        unique (mobile)
);

create index group_id
    on users (group_id);

create index idx_id_user_type
    on users (id, user_type);

create index user_type
    on users (user_type);

