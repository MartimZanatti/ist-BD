DROP TRIGGER IF EXISTS insert_offer;
DROP TRIGGER IF EXISTS verify_timestamp;
DROP TRIGGER IF EXISTS insert_estado_paga;


DELIMITER //

CREATE TRIGGER insert_offer
BEFORE INSERT ON oferta
FOR EACH ROW
BEGIN 
	DECLARE quantidade INT;
	select count(1) into quantidade from (
		select morada, codigo, data_inicio, data_fim 
		from oferta 
		where morada = NEW.morada AND codigo = NEW.codigo AND 
		(data_fim > NEW.data_inicio AND NEW.data_inicio > data_inicio OR data_fim > NEW.data_fim AND NEW.data_fim > data_inicio OR
		(data_inicio > NEW.data_inicio AND NEW.data_fim > data_fim))) as C;
		

	IF quantidade > 0 THEN
		CALL ERROR;
	END IF;	

END; //


CREATE TRIGGER verify_timestamp
BEFORE INSERT ON paga
FOR EACH ROW
BEGIN
	DECLARE quantidade INT;
	select count(1) into quantidade from (
		select numero, time_stamp
		from estado
		where numero = NEW.numero AND (time_stamp >= NEW.data)) as C;


	IF quantidade > 0 THEN
		CALL ERROR;
	END IF;
	
END; //



CREATE TRIGGER insert_estado_paga
AFTER INSERT ON paga
FOR EACH ROW
BEGIN 
 INSERT INTO estado VALUES(NEW.numero, NEW.data, 'Paga');
END; //




DELIMITER ;


