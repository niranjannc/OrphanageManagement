<?php

include('../connect.php');

try {
	$conn = new PDO("mysql:host = $server;dbname = $dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	echo "<h3>Connected Sucessfully</h3>";

	$sql = "USE $dbname";
	
	$sql1 = "create table orphanage (
		orgid varchar(7) primary key,
		name varchar(60) not null,
		addlocation varchar(50) not null,
		addcity varchar(25) not null,
		adddistrict varchar(20) not null,
		addstate varchar(25) not null,
		phone real not null,
		email varchar(30) not null,
		estyear int(4) not null,
		trust varchar(40) not null,
		head varchar(7)
		 )";
	
	$sql2 = "create table employee(
		orgid varchar(7) references orphanage(orgid) on delete restrict,
		empid varchar(7) primary key,
		fname varchar(30) not null,
		lname varchar(30),
		gender char(1) not null,
		bdate date,
		addlocation varchar(50) not null,
		addcity varchar(25) not null,
		adddistrict varchar(20) not null,
		addstate varchar(25) not null,
		phone real not null,
		designation varchar(20) not null,
		salary real not null,
		joindate date not null,
		hasleft char(1),
		leavedate date,
		photo longblob
		 )";
		
	$sql3 = "alter table orphanage add constraint orgfk foreign key(head) references employee(empid) on delete restrict";
	
	$sql4 = "create table infant(
		orgid varchar(7) references orphanage(orgid) on delete restrict,
		infid varchar(7) primary key,
		fname varchar(30) not null,
		lname varchar(30),
		gender char(1) not null,
		bdate date not null,
		disability varchar(15),
		caretakerid varchar(7),
		joindate date not null,
		hasleft char(1),
		leavedate date,
		photo longblob not null,
		foreign key (caretakerid) references employee (empid) on delete restrict
		 )";
		
	$sql5 = "create table donor(
		orgid varchar(7) references orphanage(orgid) on delete restrict,
		did varchar(7) primary key,
		fname varchar(30) not null,
		lname varchar(30),
		gender char(1) not null,
		bdate date,
		addlocation varchar(50) not null,
		addcity varchar(25) not null,
		adddistrict varchar(20) not null,
		addstate varchar(25) not null,
		phone real not null,
		occupation varchar(30),
		contribution real,
		role varchar(15),
		photo longblob
		)";
		
	$sql6 = "create table donation(
		did varchar(7) references donor(did) on delete restrict,
		amount real,
		paymode varchar(10),
		service varchar(25),
		purpose varchar(15),
		ddate date not null
		)";
	
	$sql7 = "create table sponser(
		infid varchar(7) references infant(infid) on delete restrict,
		did varchar(7) references donor(did) on delete restrict
		)";
	
	$sql8 = "create table parents(
		orgid varchar(7) references orphanage(orgid) on delete restrict,
		pid varchar(7) primary key,
		fname varchar(50),
		mname varchar(50),
		fbdate date,
		mbdate date,
		addlocation varchar(50) not null,
		addcity varchar(25) not null,
		adddistrict varchar(20) not null,
		addstate varchar(25) not null,
		phone real not null,
		agereq int not null,
		genreq char(1) not null,
		photo longblob
		)";
	
	$sql9 = "create table adoption (
		pid varchar(7) references parents(pid) on delete restrict,
		infid varchar(7) references infant(infid) on delete restrict,
		adoptdate date,
		approvehead varchar(7) references employee(empid) on delete restrict
		)";
	
	$sql10 = "create table login(
		username varchar(15) primary key,
		password varchar(15) not null,
		role varchar(10) not null,
		id varchar(7) not null
		)";
		
	$conn->beginTransaction();
	$conn->exec($sql);
	$conn->exec($sql1);
	$conn->exec($sql2);
	$conn->exec($sql3);
	$conn->exec($sql4);
	$conn->exec($sql5);
	$conn->exec($sql6);
	$conn->exec($sql7);
	$conn->exec($sql8);
	$conn->exec($sql9);
	$conn->exec($sql10);
	
	$conn->commit();
	$conn = null;
	echo "<h2>Tables created Sucessfully</h2>";
}
catch(PDOException $e) {
	$conn->rollback();
	echo "<h2>Error : ".$e->getMessage()."</h2>";
}
	
?>