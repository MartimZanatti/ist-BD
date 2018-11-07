drop table if exists dimensao_reserva;
drop table if exists dimensao_user;
drop table if exists dimensao_localizacao;
drop table if exists dimensao_tempo;
drop table if exists dimensao_data;
DROP PROCEDURE IF EXISTS load_time_dim;
DROP PROCEDURE IF EXISTS load_date_dim;


    
create table dimensao_user(
    nif varchar(9) not null unique,
    nome varchar(80) not null,
    telefone varchar(26) not null,
    primary key(nif));


create table dimensao_localizacao(
    local_id varchar(255) not null unique, 
    morada varchar(255) not null,
    codigo_espaco varchar(255) not null,
    codigo varchar(255),
    primary key(local_id));

create table dimensao_tempo(
    tempo_id varchar(5) not null unique,
    hora varchar(3) not null,
    minutos varchar(3) not null,
    primary key(tempo_id));


create table dimensao_data(
    data_id varchar(20) not null unique,
    dia varchar(3) not null,
    semana varchar(3) not null,
    mes varchar(3) not null,
    semestre varchar(10) not null,
    ano varchar(5) not null,
    primary key(data_id));

create table dimensao_reserva(
    numero varchar(255) not null unique,
    montante numeric(19,4) not null,
    duracao numeric(19,0) not null,
    tarifa numeric(19,4) not null,
    nif varchar(9) not null,
    local_id varchar(255) not null ,
    tempo_id varchar(5) not null,
    data_id varchar(20) not null ,
    primary key(numero,nif, tempo_id, local_id, data_id),
    foreign key(nif) references dimensao_user(nif),
    foreign key(tempo_id) references dimensao_tempo(tempo_id),
    foreign key(local_id) references dimensao_localizacao(local_id),
    foreign key(data_id) references dimensao_data(data_id));



insert into dimensao_user(nif, nome, telefone) SELECT nif, nome, telefone FROM user;

insert into dimensao_localizacao(local_id, morada, codigo_espaco, codigo)
(
    SELECT concat(morada, "-", codigo_espaco) as local_id, morada, codigo_espaco, codigo
    from (SELECT morada, codigo as codigo_espaco, NULL as codigo from espaco) as E

);

insert into dimensao_localizacao(local_id, morada, codigo_espaco, codigo)
    (
    SELECT concat(morada, "-", codigo_espaco, "-", codigo) as local_id, morada, codigo_espaco, codigo
    from (SELECT morada, codigo_espaco, codigo from posto) as P

);

delimiter //
CREATE PROCEDURE load_date_dim()
BEGIN
   DECLARE v_full_date DATE;
   DECLARE semester NUMERIC(1,0);
   SET v_full_date = '2016-01-01';
   SET semester = 1;
   WHILE v_full_date < '2018-01-01' DO
       IF month(v_full_date) > 6 THEN
            SET semester = 2;
        ELSE SET semester = 1;
       END IF;
       INSERT INTO dimensao_data(
          data_id,
          dia,
          semana,
          mes,
          semestre,
          ano
       ) VALUES (
          /* YEAR(v_full_date) * 10000 + MONTH(v_full_date)*100 + DAY(v_full_date),*/
           DATE_FORMAT(v_full_date, '%Y-%m-%d'),
           DAY(v_full_date),
           WEEK(v_full_date),
           MONTH(v_full_date),
           semester,
           YEAR(v_full_date)
       );
       SET v_full_date = DATE_ADD(v_full_date, INTERVAL 1 DAY);
   END WHILE;
END;
//

CREATE PROCEDURE load_time_dim()
BEGIN
   
   SET @v_full_time = '2015-01-01 00:00:00';
   WHILE (DAY(@v_full_time)< 2) DO
       INSERT INTO dimensao_tempo(
          tempo_id,
          hora,
          minutos
       ) VALUES (
            DATE_FORMAT(@v_full_time, '%H:%i'),
           hour(@v_full_time),
           minute(@v_full_time)
       );
       SET @v_full_time = DATE_ADD(@v_full_time, INTERVAL 1 MINUTE);
   END WHILE;
END; //

delimiter ;

call load_time_dim;

call load_date_dim;


insert into dimensao_reserva(numero, montante, duracao, tarifa, nif, local_id, tempo_id, data_id)
    select  numero, tarifa * datediff(data_fim,data_inicio) as montante, 
            datediff(data_fim,data_inicio) as duracao, 
            tarifa,
            nif, 
            concat(morada, '-', codigo) as local_id,
            DATE_FORMAT(data, '%H:%i') as tempo_id,
            DATE_FORMAT(data, '%Y-%m-%d') as data_id
    from    reserva natural join 
            paga natural join 
            oferta natural join 
            aluga natural join 
            espaco
            ;



insert into dimensao_reserva(numero, montante, duracao, tarifa, nif, local_id, tempo_id, data_id)
    select  numero, tarifa * datediff(data_fim,data_inicio) as montante, 
            datediff(data_fim,data_inicio) as duracao, 
            tarifa,
            nif, 
            concat(morada, '-', codigo_espaco, '-', codigo) as local_id,
            DATE_FORMAT(data, '%H:%i') as tempo_id,
            DATE_FORMAT(data, '%Y-%m-%d') as data_id
    from    reserva natural join 
            paga natural join 
            oferta natural join 
            aluga natural join 
            posto
            ;




