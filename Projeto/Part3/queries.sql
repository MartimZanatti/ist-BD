                                   
/*a)*/

SELECT DISTINCT p.morada, p.codigo_espaco
FROM posto p
WHERE (p.morada, p.codigo) NOT IN
(
	SELECT a.morada, a.codigo
	FROM aluga a
);

/*b)*/

select N.morada 
from 
(
	select E.morada, count(A.morada) as Acount 
	from edificio E left join aluga A 
	on E.morada = A.morada 
	group by E.morada
) as N 
group by N.morada, N.Acount 
having N.Acount > (	select avg(N.Acount) 
					from (
						select E.morada, count(A.morada) as Acount 
						from edificio E left join aluga A 
						on E.morada = A.morada 
						group by E.morada
					) as N);

/*c)*/
select N.nif		
from (
	SELECT distinct nif, id
	FROM arrenda NATURAL JOIN fiscaliza
	) as N
group by nif
having count(nif) = 1;


/*d)*/

select morada, codigo, sum(Montante) as Montante from (
select *
from                    
	(select morada, codigo, 0 as Montante
	from espaco) as otherespaco 
	union
	(select morada, codigo, sum(total) as Montante 
	from (select morada, codigo, total 
		  from (select morada, codigo_espaco as codigo, sum(total) as total 
				from (select * 
					  from posto natural join (select tarifa * datediff(data_fim,data_inicio) as total, morada, codigo 
											   from oferta natural join aluga natural join paga
											   where year(data) = 2016
											   ) as T
					  ) as P
				group by morada, codigo_espaco
				) as POSTO 
		  union (select * 
				 from espaco natural join (select tarifa * datediff(data_fim,data_inicio) as total, morada, codigo 
										   from oferta natural join aluga natural join paga
										   where year(data) = 2016
										   ) as E
				)
		  ) as ESPACO 
	group by morada, codigo)) as NOVO
group by morada, codigo;


/*e)*/
Select distinct morada, codigo_espaco
    From posto p NATURAL JOIN aluga a
    Where NOT EXISTS
                (Select codigo, morada 
                    From posto 
					Where codigo_espaco = p.codigo_espaco 
					and codigo NOT IN 
                                        (Select distinct p2.codigo
                                            From posto p2 NATURAL JOIN
												aluga a
                                            Where p.codigo_espaco = p2.codigo_espaco)
                );




 
