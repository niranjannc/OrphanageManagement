INSERING INTO ORPHANAGE

INSERT INTO orphanage VALUES('org1','Ramakrishna Ashrama','No.35,2nd cross,Mlayout,Bangalore','9845321655','rkashrama@gmail.com','2000','Vivekananda Trust',null);
INSERT INTO orphanage VALUES('org2','Balashrama','No.62,2nd cross,BSK,Mysore','8265321655','balashrama@gmail.com','2005','Vivekananda Trust',null);
INSERT INTO orphanage VALUES('org3','Balagangadhara Ashrayadhama','No.15,2nd cross,JS,Chitradurga','9845495195','bgashrayadhama@gmail.com','2012','Vivekananda Trust',null);

INSERING INTO EMPLOYEE

INSERT INTO employee VALUES('org1','emp1','Chandana','Srinivas','f','18-oct-1978','No.39,3rd Main,Mlayout,Bangalore','9648624975','head','35000','06-jan-2002','n',null);
UPDATE orphanage SET head='emp1' WHERE orgid='org1';

INSERT INTO employee VALUES('org2','emp2','Shivaraj','A','m','08-sep-1974','No.162,10th Main,BSK,Mysore','9845367812','head','30000','06-oct-2005','n',null);
UPDATE orphanage SET head='emp2' WHERE orgid='org2';

INSERT INTO employee VALUES('org3','emp3','Jeevan','Hiremath','m','03-dec-1978','No.3,2nd Cross,JS,Chitradurga','8296178566','head','35500','16-jul-2014','n',null);
UPDATE orphanage SET head='emp3' WHERE orgid='org3';

INSERT INTO employee VALUES('org1','emp4','Sirish','J','m','19-feb-1982','No.39,16th Main,BHL,Bangalore','9648195962','accountant','15000','06-oct-2004','y','19-jun-2012');

INSERT INTO employee VALUES('org2','emp5','Jayashree','M','f','12-may-1985','No.16,15th Cross,BLRoad,Mysore','8553946218','cook','8000','16-aug-2008','n',null);

INSERING INTO INFANTS

INSERT INTO infant VALUES('org1','inf1','Sundar','P','m','16-nov-2016','none','emp1','18-jan-2017','n',null);
INSERT INTO infant VALUES('org1','inf2','Kavana','B','f','23-jan-2013','ph','emp1','03-oct-2015','n',null);
INSERT INTO infant VALUES('org2','inf3','Thomas','D','m','05-jun-2008','none','emp2','05-jun-2008','y','15-jan-2013');
INSERT INTO infant VALUES('org2','inf4','Chandan','M','m','29-sep-2015','md','emp2','06-feb-2018','n',null);
INSERT INTO infant VALUES('org3','inf5','Tilak','J','m','25-mar-2011','ph','emp3','18-jan-2014','n',null);
INSERT INTO infant VALUES('org3','inf6','Deepak','M','m','16-nov-2012','ph','emp3','10-jun-2013','y','19-oct-2016');
INSERT INTO infant VALUES('org1','inf7','Bhavana','S','f','23-jun-2015','none','emp1','13-jan-2016','y','29-jul-2017');
INSERT INTO infant VALUES('org2','inf8','Umesh','H','m','16-aug-2012','both','emp2','26-jun-2014','n',null);

INSERING INTO DONORS

INSERT INTO donor VALUES('org1','dnr1','Suraj','M','m','25-jun-1976','NO.69,Ganapathi block,Bangalore','9456872678','Business','0','trustee');
INSERT INTO donor VALUES('org1','dnr2','devaraj','R','m','06-feb-1972','NO.49,JS Road,Bangalore','8564366197','Business','0','donor');
INSERT INTO donor VALUES('org2','dnr3','Surya','R','m','16-jul-1980','NO.16,JK Circle,Mysore','8296497616','Real Estate','0','trustee');
INSERT INTO donor VALUES('org2','dnr4','Anirudh','L','m','06-apr-1983','NO.69,CV Raman Road,Mysore','6360466684','Software Engineer','0','trustee');
INSERT INTO donor VALUES('org3','dnr5','Vedh','Rajmani','m','20-jan-1982','NO.6,JS Road,Chitradurga','8073654953','Business','0','trustee');

INSERING INTO DONATION
INSERT INTO donation VALUES('dnr1','25000','cash',null,'construction','16-oct-2018');
UPDATE donor SET contribution = (contribution+25000) WHERE did='dnr1';

INSERT INTO donation VALUES('dnr2',null,null,'Food','Celebration','20-apr-2017');

INSERT INTO donation VALUES('dnr3','15000','cheque',null,'maintainance','16-apr-2016');
UPDATE donor SET contribution = (contribution+15000) WHERE did='dnr3';

INSERT INTO donation VALUES('dnr4','10000','cash',null,'cleaning','12-may-2016');
UPDATE donor SET contribution = (contribution+10000) WHERE did='dnr4';

INSERT INTO donation VALUES('dnr5',null,null,'Clothes','function','18-sep-2014');

INSERING INTO SPONSER

INSERT INTO sponser VALUES('inf1','dnr1');
INSERT INTO sponser VALUES('inf4','dnr4');
INSERT INTO sponser VALUES('inf5','dnr5');
INSERT INTO sponser VALUES('inf8','dnr4');
INSERT INTO sponser VALUES('inf8','dnr3');

INSERTING INTO PARENTS

INSERT INTO parents VALUES('org1','pid1','Shivu','Roopa','19-jun-1986','26-aug-1988','no.34,5th cross,JS Road,Bangalore','9845336448','3','m');
INSERT INTO parents VALUES('org1','pid2','Raaj','Usha','23-jan-1982','02-feb-1985','no.6,15th cross,BL Road,Bangalore','9478532196','5','f');
INSERT INTO parents VALUES('org2','pid3','Parmesh','Shaila','06-oct-1985','26-jul-1985','no.34,20th cross,SJB Road,Myssore','8296336448','4','m');
INSERT INTO parents VALUES('org1','pid5','Jai','Depika','26-aug-1986','06-jul-1988','no.69,20th main,SB Road,Tumkur','8296394510','4','f');
INSERT INTO parents VALUES('org3','pid6','Gaurav','Lakshmi','13-sep-1983','20-jan-1985','no.364,21th cross,SJ Road,Chitradurga','8276336848','5','m');

INSERTING INTO ADOPTION

INSERT INTO adoption VALUES('pid2','inf3','15-jan-13','emp2');
INSERT INTO adoption VALUES('pid5', 'inf7','29-jul-2017','emp1');
INSERT INTO adoption VALUES('pid6', 'inf6','19-oct-2016','emp3');

select * from orphanage;
select * from employee;
select * from infant;
select * from donor;
select * from donation;
select * from sponser;
select * from parents;
select * from adoption;