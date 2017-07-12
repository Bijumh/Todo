DELIMITER //
DROP PROCEDURE IF EXISTS spa_todo_update //
CREATE PROCEDURE spa_todo_update(
	IN id INT,
    IN name VARCHAR(100),
	IN description VARCHAR(1000)
)
BEGIN

	UPDATE `todo`
	SET `todo_name` = name,
		`todo_desc` = description
	WHERE `todo_id` = id;
    
END //
DELIMITER ;
