DELIMITER //
DROP PROCEDURE IF EXISTS spa_todo_insert //
CREATE PROCEDURE spa_todo_insert(
	IN name VARCHAR(100),
    IN desccription VARCHAR(1000)
)
BEGIN

	INSERT INTO `todo` (`todo_name`, `todo_desc`)
	VALUES
	(name, desccription);
    
END //
DELIMITER ;
