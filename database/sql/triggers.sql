DELIMITER //

CREATE TRIGGER after_insert_client
AFTER INSERT ON client
FOR EACH ROW
BEGIN
    INSERT INTO panier (id_client)
    VALUES (NEW.id_client); 
END //

DELIMITER ;