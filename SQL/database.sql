-- create database assignment;
-- use assignment;
-- drop table branch;
-- drop table bids;

create table branch (
	branch_code int(11) auto_increment,
    branch_name char(20) unique,
    address char(255),
    hotline char(11),
    primary key (branch_code,branch_name)
);

create table users (
	branch_name char(20),
    email char (255) UNIQUE,
    phone char(255) unique,
    psw char(16) not null,
    Fname char(9) not null,
    Lname char(9) not null,
    identification_number char(12),
    address char(255),
    city char (9),
    balence int unsigned default 0,
    image varchar(250) not null,
    `in-dept` int default 0,
    primary key (email,phone)
);

create table auction (
	id int auto_increment,
    `product_name` char(20) not null,
    `minimum price` int not null,
    `closing time` datetime not null,
    seller char(255),
    `current bid` int ,
    `number of bid placed` int,
    conclude varchar(5) default 'No',
    created_time varchar(255),
    primary key (id,`closing time`)
) engine = InnoDB
partition by hash (to_days(`closing time`))
partitions 31;

create table bids (
	`id` int auto_increment,
    `auctionId` int,
    `bid` int,
    `bidder` char(20),
    `date` datetime not null default now(),
    primary key (`id`,`date`)
) engine = InnoDB
partition by hash (to_days(`date`))
partitions 31;

create index idx_maximum_bid on  auction(`minimum price`);

create index idx_number_bid on auction(`number of bid placed`);

create index bid_auctionId on bids(`auctionId`);

create view open_auction as 
select id,product_name as Product,`minimum price`,`closing time`, seller, `current bid`,`number of bid placed`,`created_time`
from auction
where `closing time` > current_timestamp();

create view close_auction as 
select id,product_name as Product,`minimum price`,`closing time`, seller, `current bid`,`number of bid placed`
from auction
where `closing time` <= current_timestamp()
and conclude = 'Yes';

create view unfinished_auction as 
select b.auctionId, a.seller, b.bidder , b.bid
from bids b, auction a, (select auctionId, max(bid) as max_bid from bids group by auctionId) c
where b.auctionId = a.id
and b.auctionId = c.auctionId
and b.bid = c.max_bid
and a.`closing time` <= current_timestamp
and a.conclude = 'No';

create view transaction_history as 
select b.auctionId, a.seller, b.bidder , b.bid, a.`closing time`
from bids b, auction a, (select auctionId, max(bid) as max_bid from bids group by auctionId) c
where b.auctionId = a.id
and b.auctionId = c.auctionId
and b.bid = c.max_bid
and a.`closing time` <= current_timestamp
and a.conclude = 'Yes';

create table test_update_procedure (
	id int,
    seller CHAR(255),
	bidder CHAR(20),
	bid INT
);

create table notification (
	`auctionId` int,
    `receiver` varchar(255),
    `message` varchar(255),
    `transaction` varchar(20),
    `id` int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
);

create index receiver on notification(`receiver`);




-- Notfication statement
-- select count(seller) , count(bidder) from test_auction where seller = 'email2' or bidder = 'email2';






