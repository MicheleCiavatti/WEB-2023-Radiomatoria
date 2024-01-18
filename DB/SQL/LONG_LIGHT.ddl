-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Fri Dec 29 13:26:30 2023 
-- * LUN file: C:\Users\miche\OneDrive - Alma Mater Studiorum Università di Bologna\Tecnologie Web\Progetto - DB Main\LONG_LIGHT.lun 
-- * Schema: Relational/1 
-- ********************************************* 


-- Database Section
-- ________________ 

create database longlight;
use longlight;


-- Tables Section
-- _____________ 

create table AMICIZIA (
     Amico2 varchar(50) not null,
     Amico1 varchar(50) not null,
     constraint IDAMICIZIA primary key (Amico2, Amico1));

create table BANDE (
     NomeUtente varchar(50) not null,
     MHz float(10) not null,
     constraint IDBANDE primary key (NomeUtente, MHz));

create table COMMENTI (
     Creatore varchar(50) not null,
     NrPost int not null,
     NrCommento int not null,
     DataCommento date not null,
     TestoCommento varchar(500) not null,
     ImmagineCommento varchar(1024),
     constraint IDCOMMENTO primary key (Creatore, NrPost, Scrittore, NrCommento));

create table DISPONIBILITA (
     OraInizio time not null,
     OraFine time not null,
     Utente varchar(50) not null,
     constraint IDDISPONIBILITA primary key (Utente, OraInizio, MinutiInizio));

create table FOLLOW (
     Followed varchar(50) not null,
     Follower varchar(50) not null,
     constraint IDFOLLOW primary key (Follower, Followed));

create table BLOCCO (
     Bloccato varchar(50) not null,
     Bloccante varchar(50) not null,
     constraint IDFOLLOW primary key (Bloccante, Bloccato));

create table INTERAZIONI (
     Creatore varchar(50) not null,
     ElementId int not null,
     Tipo char not null,
     constraint IDINTERAZIONI primary key (Interagente, Creatore, NrPost));

create table NOTIFICHE (
     Ricevente varchar(50) not null,
     Mandante varchar(50) not null,
     IdNotifica int not null,
     TestoNotifica varchar(300) not null,
     Richiesta char not null,
     Lettura char not null,
     constraint IDNOTIFICA primary key (Mandante, Ricevente, IdNotifica));

create table POST (
     Creatore varchar(50) not null,
     NrPost int not null,
     DataPost date not null,
     TestoPost varchar(500) not null,
     ImmaginePost varchar(1024),
     constraint IDPOST primary key (Creatore, NrPost));

create table UTENTI (
     NomeUtente varchar(50) not null,
     FotoProfilo varchar(1024) not null,
     Indirizzo varchar(100) not null,
     Città varchar(30) not null,
     Password varchar(255) not null,
     DataNascita date not null,
     IndirizzoMail varchar(100) not null,
     Indizio varchar(500) not null,
     constraint IDUTENTE primary key (NomeUtente));


-- Constraints Section
-- ___________________ 

alter table AMICIZIA add constraint FKAmico1
     foreign key (Amico1)
     references UTENTI (NomeUtente);

alter table AMICIZIA add constraint FKAmico2
     foreign key (Amico2)
     references UTENTI (NomeUtente);

alter table BLOCCO add constraint FKBloccante
     foreign key (Bloccante)
     references UTENTI (NomeUtente);

alter table BLOCCO add constraint FKBloccato
     foreign key (Bloccato)
     references UTENTI (NomeUtente);

alter table BANDE add constraint FKBAN_UTE
     foreign key (NomeUtente)
     references UTENTI (NomeUtente);

alter table COMMENTI add constraint FKCONTENUTO
     foreign key (Creatore, NrPost)
     references POST (Creatore, NrPost);

alter table DISPONIBILITA add constraint FKDIS_UTE
     foreign key (Utente)
     references UTENTI (NomeUtente);

alter table FOLLOW add constraint FKFollower
     foreign key (Follower)
     references UTENTI (NomeUtente);

alter table FOLLOW add constraint FKFollowed
     foreign key (Followed)
     references UTENTI (NomeUtente);

alter table INTERAZIONI add constraint FKINT_UTE
     foreign key (Interagente)
     references UTENTI (NomeUtente);

alter table INTERAZIONI add constraint FKINT_POS
     foreign key (Creatore, NrPost)
     references POST (Creatore, NrPost);

alter table NOTIFICHE add constraint FKCAUSA
     foreign key (Mandante)
     references UTENTI (NomeUtente);

alter table NOTIFICHE add constraint FKRICEZIONE
     foreign key (Ricevente)
     references UTENTI (NomeUtente);

alter table POST add constraint FKCREAZIONE
     foreign key (Creatore)
     references UTENTI (NomeUtente);


-- Index Section
-- _____________ 

