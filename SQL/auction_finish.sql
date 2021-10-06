DELIMITER $$

DROP EVENT IF EXISTS run_event $$

CREATE EVENT `update` 
ON SCHEDULE EVERY 5 MINUTE STARTS '2021-08-20 14:00:00' 
ON COMPLETION PRESERVE ENABLE 
DO 
BEGIN
  call update_auction;
END $$

delimiter ;

delimiter $$
use assignment $$
create procedure update_auction()
begin
	DECLARE n INT DEFAULT 0;
	DECLARE i INT DEFAULT 0;
    DECLARE var_auctionId INT;
    DECLARE var_seller CHAR(255);
    DECLARE var_bidder CHAR(20);
    DECLARE var_bid INT;
	SELECT COUNT(*) FROM unfinished_auction INTO n;
	SET i=0;
	WHILE i<n DO 
		SELECT auctionId INTO var_auctionId FROM unfinished_auction LIMIT i,1;
        SELECT seller INTO var_seller FROM unfinished_auction LIMIT i,1;
        SELECT bidder INTO var_bidder FROM unfinished_auction LIMIT i,1;
        SELECT bid INTO var_bid FROM unfinished_auction LIMIT i,1;
		INSERT INTO test_update_procedure(id,seller,bidder,bid) VALUES (var_auctionId,var_seller,var_bidder,var_bid);
        CALL loser_notified(var_auctionId,var_bidder,var_bid);
		SET i = i + 1;
	END WHILE;
    SET i = 0;
    WHILE i<n DO 
		SELECT id INTO var_auctionId FROM test_update_procedure LIMIT i,1;
        SELECT seller INTO var_seller FROM test_update_procedure LIMIT i,1;
        SELECT bidder INTO var_bidder FROM test_update_procedure LIMIT i,1;
        SELECT bid INTO var_bid FROM test_update_procedure LIMIT i,1;
		CALL auction_transaction(var_auctionId,var_seller,var_bidder,var_bid);
		SET i = i + 1;
	END WHILE;
    DELETE FROM test_update_procedure;
end $$
delimiter ;

delimiter $$
use assignment $$
create procedure auction_transaction (
in auctionId INT,
in seller CHAR(255),
in bidder CHAR(20),
in bid INT
)
begin
	DECLARE exit handler for sqlexception
    BEGIN
		ROLLBACK;
		INSERT INTO notification(auctionId,receiver,message,transaction) values (auctionId,bidder,'You do not enough money to pay for this auction');
        UPDATE users SET `in-dept` = bid WHERE email = bidder;
    END;
    DECLARE exit handler for sqlwarning
    BEGIN
		ROLLBACK;
    END;
    START TRANSACTION;
		UPDATE users SET balence = balence + bid WHERE email = seller;
        UPDATE users SET balence = balence - bid WHERE email = bidder;
        UPDATE auction SET conclude = 'Yes' WHERE id = auctionId;
        CALL create_notification(auctionId,seller,bidder,bid);
    COMMIT;
end $$
delimiter ;

delimiter $$
use assignment $$
create procedure create_notification (
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
    
    SET msg_winner = concat('You have won auction for product: ',product);
    SET msg_seller = concat('Your auction for product: ',product,' has completed');
    
    INSERT INTO notification(auctionId,receiver,message,transaction) values (auction,seller,msg_seller,concat('+',bid));
    INSERT INTO notification(auctionId,receiver,message,transaction) values (auction,winner,msg_winner,concat('-',bid));
end $$
delimiter ;

delimiter $$
use assignment $$
create procedure loser_notified (
in auction int,
in winner char(20),
in bid int
)
begin
	DECLARE CURSOR_BIDDER_NAME CHAR(20);
	DECLARE done INT DEFAULT FALSE;
	DECLARE cursor_bidder CURSOR FOR SELECT bidder FROM bids WHERE auctionId = auction AND bidder != winner;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	OPEN cursor_bidder;
	loop_through_rows: LOOP
		FETCH cursor_bidder INTO CURSOR_BIDDER_NAME;
		IF done THEN
			LEAVE loop_through_rows;
		END IF;
		INSERT INTO notification(auctionId,receiver,message) values (auction,CURSOR_BIDDER_NAME,concat('You have lost this auction, max bid is: ',bid));
	END LOOP;
	CLOSE cursor_bidder;
end $$
delimiter ;

