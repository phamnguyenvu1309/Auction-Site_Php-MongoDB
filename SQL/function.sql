delimiter $$
use assignment $$
create procedure check_bid(
in bid int,
in username char(255),
in auctionId int,
out err char(255)
)
begin
	declare bl int;
    declare max int;
    declare min int;
    declare total int;
    declare total_current int;
    
	select balence into bl from users where email like username or phone like username ;
    select `current bid` into max from open_auction where id = auctionId ;
    select `minimum price` into min from open_auction where id = auctionId ;
    SELECT sum(b.bid) into total
	FROM bids b, auction a
	where b.auctionId = a.id
	and b.bidder like username 
	and a.`closing time` > current_timestamp;
    set total_current = bid + total;
    
    if bid > bl then
		set err = 'Bid can not be higher than your account balence';
	elseif bid <= max then
		set err = 'Bid can not be lower than current max bid';
	elseif bid < min then
		set err= ' Bid is smaller then minimum value';
	elseif total_current > bl then
		set err= 'Your total bid is higher than your account balence ';
	else 
		set err = '';
	end if;
end $$
delimiter ;

delimiter $$
use assignment $$
create procedure check_update (
in bid int,
in username char(255),
in auctionId int,
out msg char(255)
)
begin
	declare bl int;
    declare currentMax int;
    
    select balence into bl from users where email like username or phone like username ;
    select `current bid` into currentMax from open_auction where id = auctionId ;
    
    if bid > bl then
		set msg = 'bid can not higher then account balence';
	elseif bid <= currentMax then
		set msg = 'New bid must be higher then current max bid';
	else 
		set msg = '';
	end if; 
end $$
delimiter ;

delimiter $$
use assignment $$
create trigger before_bids_insert
before insert
on bids for each row
begin
	declare closing_time datetime;
    declare bidCount int;
    
    select `closing time` into closing_time from auction where id = new.auctionId;
    select `number of bid placed` into bidCount from open_auction where id = new.auctionId;
	
    if closing_time < current_timestamp() then
		signal sqlstate'45000'
        set message_text= "you can not insert record";
	end if;
    if (bid_constraint(new.bid,new.bidder,new.auctionId) like 'false') then
		signal sqlstate'45000'
        set message_text= "you can not insert record";
	end if;
    if bidCount is null then
		update auction set `number of bid placed` = 1 where id = new.auctionId;
        update auction set `current bid` = new.bid where id = new.auctionId;
	else 
		update auction set `number of bid placed` = `number of bid placed` + 1 where id = new.auctionId;
        update auction set `current bid` = new.bid where id = new.auctionId;
	end if;
end $$
delimiter ;

delimiter $$
use assignment $$
create trigger after_update_bids
after update 
on bids for each row
begin
	update auction set `current bid` = new.bid where id = new.auctionId;
end $$
delimiter ;

delimiter $$
use assignment$$
create function bid_constraint(bid int,username varchar(255),auctionId int) returns varchar(10) DETERMINISTIC  
begin
	declare bl int;
    declare max int;
    declare min int;
    
	select balence into bl from users where email like username or phone like username ;
    select `current bid` into max from open_auction where id = auctionId ;
    select `minimum price` into min from open_auction where id = auctionId ;
    
    if bid > bl then
		return 'false';
	elseif bid < max then
		return 'false';
	elseif bid < min then
		return 'false';
	else 
		return 'true';
	end if;
end$$
delimiter ;


		
     
