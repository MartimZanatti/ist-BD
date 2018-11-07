SELECT codigo_espaco, codigo, dia, mes, AVG(montante) AS Media
	FROM dimensao_reserva NATURAL JOIN
		 dimensao_localizacao NATURAL JOIN
		 dimensao_data
GROUP BY codigo_espaco, codigo, dia, mes WITH ROLLUP
UNION
SELECT codigo_espaco, codigo, dia, mes, AVG(montante) AS Media
	FROM dimensao_reserva NATURAL JOIN
		 dimensao_localizacao NATURAL JOIN
		 dimensao_data
GROUP BY codigo, dia, mes, codigo_espaco WITH ROLLUP
UNION
SELECT codigo_espaco, codigo, dia, mes, AVG(montante) AS Media
	FROM dimensao_reserva NATURAL JOIN
		 dimensao_localizacao NATURAL JOIN
		 dimensao_data
GROUP BY dia, mes, codigo_espaco, codigo WITH ROLLUP
UNION
SELECT codigo_espaco, codigo, dia, mes, AVG(montante) AS Media
	FROM dimensao_reserva NATURAL JOIN
		 dimensao_localizacao NATURAL JOIN
		 dimensao_data
GROUP BY mes, codigo_espaco, codigo, dia WITH ROLLUP
UNION
SELECT codigo_espaco, codigo, dia, mes, AVG(montante) AS Media
	FROM dimensao_reserva NATURAL JOIN
		 dimensao_localizacao NATURAL JOIN
		 dimensao_data
GROUP BY codigo_espaco, dia, codigo, mes WITH ROLLUP
UNION
SELECT codigo_espaco, codigo, dia, mes, AVG(montante) AS Media
	FROM dimensao_reserva NATURAL JOIN
		 dimensao_localizacao NATURAL JOIN
		 dimensao_data
GROUP BY codigo, mes, codigo_espaco, dia WITH ROLLUP