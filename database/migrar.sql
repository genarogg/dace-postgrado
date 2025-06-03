INSERT INTO sedes (nombre, activo, created_at, updated_at)
(SELECT DISTINCT aula, 1, NOW(), NOW() FROM apunella_postgrado.aulas ORDER BY aula);



INSERT into carreras (id, nombre, codigo, modalidad, activo, created_at, updated_at)
(SELECT idProgramas, programa, codigo, 'Trimestral', 1, NOW(), NOW() FROM apunella_postgrado.programas where !ISNULL(programa))



INSERT INTO carrera_sede (carrera_id, sede_id, activo, created_at, updated_at)
(
	SELECT
		idProgramas,
		(
			SELECT
				id
			FROM
				sedes
			WHERE
				APA.aula = nombre
		),
		1,
		NOW(),
		NOW()
	FROM
		apunella_postgrado.aulas AS APA
	ORDER BY
		APA.idProgramas
);



INSERT INTO pensums (id, carrera_id, codigo, activo, created_at, updated_at)
(SELECT id, id, codigo, 1, NOW(), NOW() from carreras WHERE id > 1);



INSERT INTO materias (id, carrera_id, nombre, codigo, creditos, periodo, activo, created_at, updated_at)
(SELECT idUnidadesCurriculares, idProgramas, unidadCurricular, codUC, creditos, nivel, apunella_postgrado.unidadescurriculares.activo, NOW(), NOW() FROM apunella_postgrado.unidadescurriculares INNER JOIN carreras ON carreras.id = apunella_postgrado.unidadescurriculares.idProgramas ORDER BY idProgramas, nivel, apunella_postgrado.unidadescurriculares.activo);



INSERT INTO pensum_detalles (pensum_id, materia_id, periodo, activo, created_at, updated_at)
(SELECT carrera_id, id, periodo, activo, NOW(), NOW() FROM materias);



INSERT INTO linea_investigaciones (id, pensum_id, nombre, activo, created_at, updated_at)
(SELECT idLinea, idProgramas, linea, activo, NOW(), NOW() FROM apunella_postgrado.lineas_investigacion);



INSERT INTO estudiantes (
	id,
	cedula,
	nombre,
	genero,
	telefono,
	activo,
	created_at,
	updated_at
)(
	SELECT
		idParticipantes,
		cedula,
		nombres,

	IF (
		genero = 'F',
		'Femenino',
		'Masculino'
	) AS genero,
	telefono,
	1,
	NOW(),
	NOW()
FROM
	apunella_postgrado.participantes AS app
WHERE
	app.idParticipantes != '59771'
	AND app.idParticipantes != '19822'
);





INSERT INTO periodos (nombre, modalidad, activo, created_at, updated_at)
(SELECT 
    CONCAT(
        IF(LENGTH(periodo)>4,SUBSTRING(periodo, 1, LENGTH(periodo)-1),periodo),
        IF(LENGTH(periodo)>4 AND periodo LIKE '%-%','','-'),
        IF(LENGTH(periodo)>4,RIGHT(periodo, 1),'')
    ) AS periodo_formato,
    (SELECT modalidad FROM carreras WHERE id = a.idProgramas) AS modalidad,
    0,
    NOW(),
    NOW()
FROM 
    apunella_postgrado.notas AS n
    INNER JOIN apunella_postgrado.aulas AS a ON a.idAulas = n.idAulas
WHERE 
    LENGTH(periodo) > 0
GROUP BY 
    periodo_formato, modalidad
ORDER BY 
    periodo_formato); 



SELECT
	idMatriculas,
	(SELECT id FROM sedes WHERE nombre = (SELECT aula FROM apunella_postgrado.aulas as aulas WHERE aulas.idAulas = matriculas.idAulas)) AS sede_id,
	(SELECT idProgramas FROM apunella_postgrado.aulas as aulas WHERE aulas.idAulas = matriculas.idAulas) AS carrera_id,
	idParticipantes as estudiante_id,
	matriculas.*
FROM
	apunella_postgrado.matriculas AS matriculas