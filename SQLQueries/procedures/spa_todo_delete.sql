DELIMITER //
DROP PROCEDURE IF EXISTS spa_todo_delete //
CREATE PROCEDURE spa_todo_delete(
	IN id INT
)
BEGIN

	DELETE FROM `todo` 
	WHERE `todo_id` = id;
    
END //
DELIMITER ;
