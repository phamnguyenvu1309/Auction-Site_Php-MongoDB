delimiter $$
use assignment $$
create procedure undo_transaction (
in aid INT,
in seller CHAR(255),
in bidder CHAR(20),
in bid INT
)
begin
	DECLARE exit handler for sqlexception
    BEGIN
		ROLLBACK;
		INSERT INTO notification(auctionId,receiver,message,transaction) values (aid,seller,'This transaction has been undo by the admin, money has been added to your in-dept',bid);
        UPDATE users SET `in-dept` = bid WHERE email = seller;
    END;
    DECLARE exit handler for sqlwarning
    BEGIN
		ROLLBACK;
    END;
    START TRANSACTION;
		UPDATE users SET balence = balence - bid WHERE email = seller;
        UPDATE users SET balence = balence + bid WHERE email = bidder;
        CALL create_undo_notification(aid,seller,bidder,bid);
        DELETE FROM auction WHERE id = aid;
        DELETE FROM bids WHERE auctionId = aid;
    COMMIT;
end $$
delimiter ;

delimiter $$
use assignment $$
create procedure create_undo_notification (
in auction INT,
in seller CHAR(255),
in winner CHAR(20),
in bid INT
)
begin
	DECLARE msg_winner VARCHAR(255);
    DECLARE msg_seller VARCHAR(255);
    DECLARE product VARCHAR(20);
    
    SELECT product_name INTO product FROM auction WHERE id = auction;
    
    SET msg_seller = concat('You have lost money for auction product: ',product);
    SET msg_winner = concat('Your have take back the money for product: ',product);
    
    INSERT INTO notification(auctionId,receiver,message,transaction) values (auction,seller,msg_seller,concat('-',bid));
    INSERT INTO notification(auctionId,receiver,message,transaction) values (auction,winner,msg_winner,concat('+',bid));
end $$
delimiter ;