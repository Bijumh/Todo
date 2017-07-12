DELIMITER //
DROP PROCEDURE IF EXISTS spa_todo_select //
CREATE PROCEDURE spa_todo_select()
BEGIN

	SELECT 	td.todo_id AS ID,
			td.todo_name AS Name,
			td.todo_desc AS Description,
			td.create_ts AS Date
    FROM todo td
    ORDER BY td.create_ts DESC;
    
END //
DELIMITER ;
