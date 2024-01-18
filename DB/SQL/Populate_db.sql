/*-----------------------------CREAZIONE UTENTI-----------------------------*/
INSERT INTO UTENTI
VALUES('MicheleC', './img/default.png', 'Piazza Rossi 7', 'Cesena', 'MicheleCPW', 
	STR_TO_DATE('14-12-1996', '%d-%m-%Y'), 
	'michelec@hotmail.it', 
	'Your username + PW');

INSERT INTO UTENTI
VALUES('DanieleC', './img/default.png', 'Via Bianchi 7', 'Rimini', 'DanieleCPW', 
	STR_TO_DATE('02-01-1963', '%d-%m-%Y'), 
	'danielec@hotmail.it', 
	'Your username + PW');	

INSERT INTO UTENTI
VALUES('AlessandroC', './img/default.png', 'Via Verdi 8', 'Forlì', 'AlessandroCPW', 
	STR_TO_DATE('10-10-2000', '%d-%m-%Y'), 
	'alessandroc@hotmail.it', 
	'Your username + PW');	

/*-----------------------------CREAZIONE FOLLOWS----------------------------*/
INSERT INTO FOLLOW
VALUES ('MicheleC', 'DanieleC');

INSERT INTO FOLLOW
VALUES ('AlessandroC', 'MicheleC');

/*-----------------------------CREAZIONE AMICIZIE---------------------------*/
INSERT INTO AMICIZIA
VALUES ('MicheleC', 'AlessandroC');

INSERT INTO AMICIZIA
VALUES ('AlessandroC', 'DanieleC');

/*-----------------------------CREAZIONE BANDE-----------------------------*/
INSERT INTO BANDE
VALUES ('MicheleC', 99.4);

INSERT INTO BANDE
VALUES ('AlessandroC', 87.5);

INSERT INTO BANDE
VALUES ('DanieleC', 88.6);

/*-------------------------CREAZIONE DISPONIBILITA'-------------------------*/
INSERT INTO DISPONIBILITA
VALUES (8:30:0, 21:40:0, 'MicheleC');

INSERT INTO DISPONIBILITA
VALUES (7:45:0, 22:10:0, 'AlessandroC');

INSERT INTO DISPONIBILITA
VALUES (8:0:0, 10:0:0, 'DanieleC');


