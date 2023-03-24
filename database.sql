
create table users (
    `id` int(11) not null auto_increment,
    `name` varchar(250) not null,
    `email` varchar(250) not null,
    `password` varchar(100) not null,
    `profile` varchar(100) not null,
    `status` enum('Disabled', 'Enable'),
    `created_on` datetime not null,
    `verification_code` varchar(100) not null,
    `login_status` enum('Logout', 'Login') not null,  
    primary key (id)
);

create table chatrooms (
    `id` int(11) not null auto_increment,
    `user_id` int(11) not null,
    `msg` varchar(200) not null,
    `created_on` datetime not null,
    primary key (id)
);
