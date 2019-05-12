-- SET SQL_SAFE_UPDATES = 0;
 -- SET GLOBAL log_bin_trust_function_creators = 1;
DROP DATABASE tcabs;
CREATE DATABASE tcabs;

USE tcabs;

CREATE TABLE UserRole (
	userType		VARCHAR(50)				NOT NULL,

	PRIMARY KEY (userType)
);

CREATE TABLE Users (
	fName				VARCHAR(255)			, 
	lName				VARCHAR(255)			, 
	gender				VARCHAR(20),
	pNum				VARCHAR(255),
	email				VARCHAR(255)			NOT NULL,
	pwd					VARCHAR(40)				NOT NULL,

	PRIMARY KEY (email)
	-- FOREIGN KEY (userType) REFERENCES UserRole (userType)
);
-- Alter Table Users
-- Alter Users set Default null;

CREATE TABLE UserCat (
	email				VARCHAR(50)				NOT NULL,
	userType		VARCHAR(50)				NOT NULL,

	PRIMARY KEY (email, userType),
	FOREIGN KEY (userType) REFERENCES UserRole (userType),
    FOREIGN KEY (email) REFERENCES Users (email)
);

CREATE TABLE Functions (
	procName		VARCHAR(50)				NOT NULL,

	PRIMARY KEY (procName)
);

CREATE TABLE Permission (
	userType		VARCHAR(50)				NOT NULL,
	procName		VARCHAR(50)				NOT NULL,

	PRIMARY KEY (userType, procName),
	FOREIGN KEY (userType) REFERENCES UserRole (userType),
	FOREIGN KEY (procName) REFERENCES Functions (procName)
);

CREATE TABLE Unit (
	unitCode		VARCHAR(10)				NOT NULL,
	unitName		VARCHAR(100)			NOT NULL,
	faculty			VARCHAR(255)				,

	PRIMARY KEY (unitCode)
);

CREATE TABLE TeachingPeriod (
	term			VARCHAR(10)				NOT NULL,
	year			VARCHAR(10)				NOT NULL,
	StartDate		date					NOT Null,
    EndDate			date					NOT Null,
	PRIMARY KEY (term, year)
);

CREATE TABLE UnitOffering (
	unitOfferingID			INT				AUTO_INCREMENT,
	unitCode		VARCHAR(10)				NOT NULL,
    cUserName		VARCHAR(255),
	term				VARCHAR(10)				NOT NULL,
	year				VARCHAR(10)				NOT NULL,
    
	censusDate	Date				, -- i don't think this needs to be filled

	PRIMARY KEY (unitOfferingID),
	FOREIGN KEY (unitCode) REFERENCES Unit(unitCode),
	FOREIGN KEY (term, year) REFERENCES TeachingPeriod(term, year)
);

CREATE TABLE Enrolment (
	enrolmentID					INT				AUTO_INCREMENT,
	unitOfferingID			INT				NOT NULL,
	sUserName		VARCHAR(255)			NOT NULL,

	PRIMARY KEY (enrolmentID),
	FOREIGN KEY (sUserName) REFERENCES Users(email),
	FOREIGN KEY (unitOfferingID) REFERENCES UnitOffering(unitOfferingID)
);

 CREATE TABLE OfferingStaff (
	UserName			VARCHAR(255)				NOT NULL,
	UnitOfferingID			int				NOT NULL,
	PRIMARY KEY (UserName, UnitOfferingID),
    FOREIGN KEY (UserName) REFERENCES Users(email),
    FOREIGN KEY (unitOfferingID) REFERENCES UnitOffering(unitOfferingID)
);

Create Table Team (
	TeamID							INT				AUTO_INCREMENT,
	TeamName						Varchar(255) 	not null,
	SupervisorUser					Varchar(255)	Not Null,
    UnitOfferingID					int				Not Null,
    ProjectManager					Varchar(255),

	PRIMARY KEY (TeamID),
	FOREIGN KEY (SupervisorUser,UnitOfferingID) REFERENCES OfferingStaff(UserName,UnitOfferingID)
    
);

Create Table TeamMember (
	TeamMemberID					INT				AUTO_INCREMENT,
	EnrolmentID						INT				NOT NULL,
	TeamID							INT				NOT NULL,

	PRIMARY KEY (TeamMemberID),
	FOREIGN KEY (TeamID) REFERENCES Team(TeamID),
	FOREIGN KEY (EnrolmentID) REFERENCES Enrolment(enrolmentID)
);

Create Table ProjectRole (
	Rolename						varchar(255) 			Not Null,
    Salary							double(6,2)			Not Null,
    RoleDescription					text,
    
    Primary Key (Rolename)
);

Create Table Task (
	TaskID							int 			auto_increment,
    TeamMemberID					int				not null,
    Rolename						varchar(255)	Not null,
    TaskDetails						text			not null,
    TimeTaken						int				not null,
    logged							boolean			DEFAULT false,
    Altered							boolean			DEFAULT false,

	Primary Key (TaskID),
    Foreign Key (TeamMemberID) REFERENCES Enrolment(enrolmentID),
    Foreign Key (Rolename) REFERENCES ProjectRole(Rolename)
);







INSERT INTO tcabs.UserRole VALUES ("admin");
INSERT INTO tcabs.UserRole VALUES ("convenor");
INSERT INTO tcabs.UserRole VALUES ("supervisor");
INSERT INTO tcabs.UserRole VALUES ("student");
INSERT INTO tcabs.UserRole VALUES ("nullUser");

INSERT INTO tcabs.Users VALUES ("Daenerys", "Targaryen", "F", "0412323443", "dtargaryen@gmail.com", "motherofdragons");
INSERT INTO tcabs.Users VALUES ("Tyrion", "Lannister", "M", "0412332543", "tlannister@gmail.com", "lannisteralwayspaysitsdebt");
INSERT INTO tcabs.Users VALUES ("John", "Snow", "M", "0412332243", "jsnow@gmail.com", "kingingthenorth");
INSERT INTO tcabs.Users VALUES ("Robert", "Baratheon", "M", "0412332263", "rbaratheon@gmail.com", "rulerofsevenkingdoms");
INSERT INTO tcabs.Users VALUES ("Arya", "Stark", "F", "0412332263", "astark@gmail.com", "thereisonlyonegod");

INSERT INTO tcabs.Unit VALUES ("ICT30001", "Information Technology Project", "FSET");
INSERT INTO tcabs.Unit VALUES ("INF30011", "Database Implementation", "FSET");
INSERT INTO tcabs.Unit VALUES ("STA10003", "Foundation of Statistics", "FSET");
INSERT INTO tcabs.Unit VALUES ("STA20010", "Statisical Computing", "FSET");

INSERT INTO tcabs.TeachingPeriod VALUES ("Semester 1", "2019", STR_TO_DATE("2019-5-3", '%Y-%m-%d') , STR_TO_DATE("2019-8-1", '%Y-%m-%d'));
INSERT INTO tcabs.TeachingPeriod VALUES ("Semester 2", "2019", STR_TO_DATE("2019-5-3", '%Y-%m-%d') , STR_TO_DATE("2019-8-1", '%Y-%m-%d'));
INSERT INTO tcabs.TeachingPeriod VALUES ("Semester 2", "2018", STR_TO_DATE("2019-5-3", '%Y-%m-%d') , STR_TO_DATE("2019-8-1", '%Y-%m-%d'));
INSERT INTO tcabs.TeachingPeriod VALUES ("Winter", "2018", STR_TO_DATE("2019-5-3", '%Y-%m-%d') , STR_TO_DATE("2019-8-1", '%Y-%m-%d'));
INSERT INTO tcabs.TeachingPeriod VALUES ("Summer", "2018", STR_TO_DATE("2019-5-3", '%Y-%m-%d') , STR_TO_DATE("2019-8-1", '%Y-%m-%d'));


/*
INSERT INTO tcabs.UnitOffering VALUES (1, "ICT30001", "rbaratheon@gmail.com", "Semester 2", "2018", "31 March 2018");

INSERT INTO tcabs.Enrolment VALUES (1, 1, "dtargaryen@gmail.com");

INSERT INTO tcabs.Functions VALUES (1, "TCABSUSERCreateNewUser");

INSERT INTO tcabs.Permission VALUES ("admin", "TCABSUSERCreateNewUser");
*/
delimiter $$
create PROCEDURE TCABSAuthenticateEmail(in Email varchar (255))
BEGIN
	if(char_length(Email) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the New Email feild has not been filled";
	end if;
	if (Email not Like '%.%' or Email not Like '%@%') then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Email must contain a '@' and a '.' character";
	end if;
	if (position("@" in Email) < 2) then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "'@' character in email can only occur after two characters";
	end if;
	if ((position("." in Email) -2) <= position("@" in Email) ) then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "'.' must occur at least two characters after '@'";
	end if;
	if (position("." in Email) >= (char_length(Email)-2)) then 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "'.' must occur at least two characters before the emailaddresses end";
	end if;
END$$
 delimiter;

 DELIMITER //
create PROCEDURE TCABSUSERCreateNewUser(in Newemail varchar(225), in newpassword varchar(225))
	BEGIN
		if (select count(*) from tcabs.Users where email = Newemail) = 0 then 
	    call tcabs.TCABSAuthenticateEmail(Newemail);
			if (char_length(newpassword) <=3) then
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Password is too short';
			end if;
            insert into tcabs.Users(email,pwd) values (newemail,newpassword);
		else
-- call raise_application_error(1234, 'msg');
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This email already exists';
	End if;
	END //
 DELIMITER ;

 DELIMITER //
create PROCEDURE TCABSUSERSetUserFirstName(in UserEmail Varchar(255), in NewuserFistname Varchar(255) )
	BEGIN
	Declare Errormsg varchar(255) default "no email entered";
    if(char_length(UserEmail) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the First name Email feild has not been filled";
	end if;
	call TCABSValidateNameString(NewuserFistname);
	if (select count(*) from tcabs.Users where email = UserEmail) = 1 then 
		update tcabs.Users set Fname = NewuserFistname where email = UserEmail;
	else
		set Errormsg = concat("There is no user with the email ", UserEmail);
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = Errormsg;
	end if;
	END //
 DELIMITER ;

 DELIMITER //
create PROCEDURE TCABSValidateNameString(in Username varchar(255))
begin
	if (char_length(Username) <= 1) then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "a name must have at least two characters";
    end if;
	 if (Username REGEXP '[0-9]') then 
	 	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "a name can't contain numbers";
	 end if;
   if (Username like '%"%' or Username like "%'%" or Username like '%!%' or Username like '%@%' or Username like '%&%' or Username like '%*%' or Username like '%(%' or Username like '%)%' or Username like '%+%' or Username like '%?%' or Username like '%=%')
   then 
   SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "a name can only contain letters of the alphabet";
   end if;
	END //
 DELIMITER ;

 DELIMITER //
create PROCEDURE TCABSUSERSetUserLastName(in UserEmail Varchar(255), in NewuserLastname Varchar(255) )
	BEGIN
	Declare Errormsg varchar(255) default "no email entered";
    if(char_length(UserEmail) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the Last name Email feild has not been filled";
	end if;
	call TCABSValidateNameString(NewuserLastname);
	if (select count(*) from tcabs.Users where email = UserEmail) = 1 then 
		update tcabs.Users set lName = NewuserLastname where email = UserEmail;
	else
		set Errormsg = concat("There is no user with the email ", UserEmail);
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = Errormsg;
	end if;
	END //
 DELIMITER ;
 
  DELIMITER //
create PROCEDURE TCABSUSERSetUserGender(in UserEmail Varchar(255), in NewuserGender Varchar(20) )
	BEGIN
	Declare Errormsg varchar(255) default "no email entered";
    if(char_length(UserEmail) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the Gender Email feild has not been filled";
	end if;
	if (select count(*) from tcabs.Users where email = UserEmail) = 1 then 
		update tcabs.Users set gender = NewuserGender where email = UserEmail;
	else
		set Errormsg = concat("There is no user with the email ", UserEmail);
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = Errormsg;
	end if;
	END //
 DELIMITER ;
 
   DELIMITER //
create PROCEDURE TCABSUSERSetUserPhone(in UserEmail Varchar(255), in NewPhone Varchar(255) )
	BEGIN
	Declare Errormsg varchar(255) default "no email entered";
    if(char_length(UserEmail) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the Phone Email feild has not been filled";
	end if;
    call TCABSValidUserPhonenumber(NewPhone);
	if (select count(*) from tcabs.Users where email = UserEmail) = 1 then 
		update tcabs.Users set pNum = NewPhone where email = UserEmail;
	else
		set Errormsg = concat("There is no user with the email ", UserEmail);
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = Errormsg;
	end if;
	END //
 DELIMITER ;
 
    DELIMITER //
create PROCEDURE TCABSValidUserPhonenumber( in NewPhone Varchar(255) )
	BEGIN
		if (NewPhone not REGEXP '[0-9]{3}-[0-9]{3}-[0-9]{4}') then
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "phone number must be numbers in ###-###-#### format";
        end if;
	END //
 DELIMITER ;
 

-- adding user actions. You should initalize with TCABSCreatNewUser if you are creating a new user
-- Create new user sets user Email (identification) and user Password
call tcabs.TCABSUSERCreateNewUser("Example@hotmail.com" , 12345);
-- Set User firstname updates users first name from what it was before to new name via the Email identifier
call tcabs.TCABSUSERSetUserFirstName("Example@hotmail.com" , "Billy");
-- Set User Lastname updates users Last name from what it was before to new name via the Email identifier
call tcabs.TCABSUSERSetUserLastName("Example@hotmail.com" , "Bobington");
-- Set User Gender updates users Gender from what it was before to new name via the Email identifier currently there is minimal checks for feild
call tcabs.TCABSUSERSetUserGender("Example@hotmail.com" , "Male");
-- set User Phone updates their phone number from what it was before to new name via the Email identifier. The phone number must follow ###-###-#### pattern
call tcabs.TCABSUSERSetUserPhone("Example@hotmail.com" , "041-144-7897");

 
 
    DELIMITER //
create PROCEDURE TCABSUNITAddnewunit( in NewUnitcode varchar(10), in NewUnitname varchar(100))
	BEGIN
		Declare Errormsg varchar(255) default "no Unit code entered";
		if (select count(*) from tcabs.Unit where unitCode = NewUnitcode) = 0 then 
		call TCABSUNITValidateNewUnitCode(NewUnitcode);
        call TCABSUNITValidateNewUnitName(NewUnitname);
		insert into tcabs.Unit (unitCode, unitName) values (NewUnitcode,NewUnitname);
        else
        set Errormsg = concat( NewUnitcode, " already exist");
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = Errormsg;
        end if;
	END //
 DELIMITER ;

     DELIMITER //
create PROCEDURE TCABSUNITValidateNewUnitCode( in NewUnitcode varchar(10))
	BEGIN
		if (Char_length(NewUnitcode) <> 8) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Unit code is not the appropriate length of 8";
        end if;
        
        if(Substring(NewUnitcode,1,3) REGEXP '[0-9]') then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Unit code must start with a three letter prefix";
        end if;
        
        if(Substring(NewUnitcode,4) REGEXP '[A-Z]') then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Unit code must be 3 letters followed by 5 numbers";
        end if;
        
	END //
 DELIMITER ;

     DELIMITER //
create PROCEDURE TCABSUNITValidateNewUnitName( in NewUnitname varchar(100))
	BEGIN
		Declare checkname varchar(255) default "";
        set checkname = Replace(NewUnitname, ' ', '');
        if(char_length(NewUnitname) <= 1) then
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Unit name must be more than one character";
        end if;
        if(Substring(checkname,1) REGEXP '[0-9]') then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Unit name must not contain numbers";
        end if;
	END //
 DELIMITER ;
 
      DELIMITER //
create PROCEDURE TCABSUNITValidateNewFacultyName( in NewFacultyname varchar(100))
	BEGIN
		Declare checkname varchar(255) default "";
        set checkname = Replace(NewFacultyname, ' ', '');
        if(char_length(NewFacultyname) <= 1) then
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Faculty name must be more than one character";
        end if; 
        if(Substring(checkname,1) REGEXP '[0-9]') then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Faculty Name must not contain numbers";
        end if;
	END //
 DELIMITER ;
 
       DELIMITER //
create PROCEDURE TCABSUNITSetNewFacultyName( in EnteredUnitcode Varchar(255), in NewFacultyname varchar(100))
	BEGIN
    Declare Errormsg varchar(255) default "no Faculty entered";
    if(char_length(EnteredUnitcode) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the Unit code for new Faculty has not been filled";
	end if;
        call TCABSUNITValidateNewFacultyName(NewFacultyname);
	if (select count(*) from tcabs.Unit where unitCode = EnteredUnitcode) = 1 then 
		update tcabs.Unit set faculty = NewFacultyname where unitCode = EnteredUnitcode;
	else
		set Errormsg = concat("There is no Unit with the code ", EnteredUnitcode);
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = Errormsg;
	end if;
	END //
 DELIMITER ;
 
 -- adding new unit to available subjects
 -- you should initalise with Addnewunit by providing the new units code which is a 3 letter prefix followed by 5 numbers. Then you provide the units name which doesn't contain numbers
call TCABSUNITAddnewunit("ICT30002", "Information Technology Project");
-- this sets the original Faculty name to the new faculty name which doesn't contain numbers, utalising the unit code as its id
call TCABSUNITSetNewFacultyName("ICT30002", "Buisnesses and Law");
 
             DELIMITER //
create Procedure TCABSUserCatAssignUserARole(in UserEmail varchar(255), in RoleName varchar(255))
	BEGIN
		if (char_length(UserEmail) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no User Email entered";
        end if;
        
		if (char_length(RoleName) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Role name entered";
        end if;
        
        if ((select count(*) from Users where email = UserEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User Email does not exist";
        end if;
        
        if ((select count(*) from UserRole where usertype = RoleName) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered Role name does not exist";
        end if;
        
        if ((select count(*) from UserCat where userType = RoleName and email = UserEmail) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User already has the assigned Role";
		end if;
        insert into UserCat values (UserEmail,RoleName);
	END //
 DELIMITER ;
 -- User Cat
 -- assigns the User with the email Example@hotmail.com to the Role of testerRole
 call TCABSUserCatAssignUserARole("Example@hotmail.com", "student");
 call TCABSUserCatAssignUserARole("jsnow@gmail.com", "convenor");
  call TCABSUserCatAssignUserARole("dtargaryen@gmail.com", "supervisor");

          DELIMITER //
create Procedure TCABSValidateDate(in checkdate varchar(255))
	BEGIN
    Declare ErrormsgTeachingperiod varchar(255) default "no Values entered";
        if ((STR_TO_DATE(checkdate, "%Y-%m-%d")) IS NULL) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "invalid date entry";
        end if;
	END //
 DELIMITER ;
 
       DELIMITER //
create PROCEDURE TCABSUNITOFFERINGAddNewOffering( in OfferedUnitID Varchar(255), in Offeredterm varchar(255), in Offeredyear varchar(255))
	BEGIN
		if(char_length(OfferedUnitID) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the Unit feild has not been filled";
		end if;
		if((select count(*) from tcabs.Unit where unitCode = OfferedUnitID) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the Unit you entered does not exist";
		end if;
        call TCABSUNITOFFERINGValidateOfferingPeriod(Offeredterm,Offeredyear);
        if ((select count(*) from UnitOffering where unitCode = OfferedUnitID and term = Offeredterm and year = Offeredyear) = 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "the Unit offering you entered already exists";
        end if;
		insert into UnitOffering(unitCode, term, year) values (OfferedUnitID,Offeredterm,Offeredyear);
	END //
 DELIMITER ;

       DELIMITER //
create PROCEDURE TCABSUNITOFFERINGValidateOfferingPeriod(in Offeredterm varchar(255), in Offeredyear varchar(255))
	BEGIN
    Declare ErrormsgTeachingperiod varchar(255) default "no Values entered";
		if (char_length(Offeredterm) <= 1 or char_length(Offeredyear) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "values have not been entered into the feilds";
        end if;
        -- need to add time validation so that users can't create offerings in the past
		if((select count(*) from tcabs.teachingperiod where term = Offeredterm and teachingperiod.year = Offeredyear) <> 1) then
			set ErrormsgTeachingperiod = concat("There is no teaching period for ", Offeredterm, " ", Offeredyear);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = ErrormsgTeachingperiod;
		end if;
	END //
 DELIMITER ;
 
        DELIMITER //
create Procedure TCABSUNITOFFERINGGetKey(in OfferedUnitID Varchar(255), in Offeredterm varchar(255), in Offeredyear varchar(255), out ValuesunitOfferingID int)
	BEGIN
    Declare ErrormsgTeachingperiod varchar(255) default "no Values entered";
		if (char_length(Offeredterm) <= 1 or char_length(Offeredyear) <= 1 or char_length(OfferedUnitID) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "values have not been entered into the feilds";
        end if;
		select unitOfferingID into ValuesunitOfferingID from UnitOffering where unitCode = OfferedUnitID and term = Offeredterm and year = Offeredyear;
        
        if (ValuesunitOfferingID is null) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Unknown Unit code, term and year combination ";
		end if;
	END //
 DELIMITER ;
 
         DELIMITER //
create Procedure TCABSUNITOFFERINGSetCensusDate(in OfferedUnitID Varchar(255), in Offeredterm varchar(255), in Offeredyear varchar(255), in OfferedCencusdate varchar(255))
	BEGIN
    Declare ErrormsgTeachingperiod varchar(255) default "no Values entered";
		if (char_length(OfferedCencusdate) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "values have not been entered for the Cencus date";
        end if;
        call TCABSUNITOFFERINGGetKey(OfferedUnitID,Offeredterm,Offeredyear,@ValuesunitOfferingID);
        call TCABSUNITOFFERINGValidateCenDate(@ValuesunitOfferingID, OfferedCencusdate);
        update tcabs.Unitoffering set censusDate = STR_TO_DATE(OfferedCencusdate, '%Y-%m-%d') where unitOfferingID = @ValuesunitOfferingID; 
	END //
 DELIMITER ;


         DELIMITER //
create Procedure TCABSUNITOFFERINGValidateCenDate(in OfferingKey int, in OfferedCencusdate varchar(255))
	BEGIN
    Declare StoredYear varchar(255) default "";
    Declare StoredPeriod varchar(255) default "";
    Declare CrosscheckDateStart Date;
    Declare CrosscheckDateEnd Date;
		call TCABSValidateDate(OfferedCencusdate);
        select term, year into StoredPeriod ,StoredYear from UnitOffering where unitOfferingID = OfferingKey;
        select StartDate, EndDate into CrosscheckDateStart,CrosscheckDateEnd from TeachingPeriod where term = StoredPeriod and year = StoredYear;
        if (STR_TO_DATE(OfferedCencusdate, '%Y-%m-%d') <= CrosscheckDateStart) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Cencus date can not occur on or before start date";
        end if;
        if (STR_TO_DATE(OfferedCencusdate, '%Y-%m-%d') >= CrosscheckDateEnd) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Cencus date can not occur after or after the end date";
        end if;
	END //
 DELIMITER ;
 
          DELIMITER //
create Procedure TCABSUNITOFFERINGSetConvenor(in ConvnorEmail varchar(255), in OfferedUnitID Varchar(255), in Offeredterm varchar(255), in Offeredyear varchar(255))
	BEGIN
    Declare ErrormsgTeachingperiod varchar(255) default "no Values entered";
		if (char_length(ConvnorEmail) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "values have not been entered into the email feild";
        end if;
		 call TCABSUNITOFFERINGGetKey(OfferedUnitID, Offeredterm, Offeredyear, @ValuesunitOfferingID);
        if ((select count(*) from Users where email = ConvnorEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered Email is not found";
        end if;
        if ((select usertype from Usercat where email = ConvnorEmail) <> "convenor") then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered User Does not have the role of convenor";
        end if;
        if ((select count(*) from OfferingStaff where Username = ConvnorEmail and unitOfferingID = @ValuesunitOfferingID) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered user isn't set as a staff member for this offering";
        end if;
        update tcabs.Unitoffering set cUserName = ConvnorEmail where unitOfferingID = @ValuesunitOfferingID; 
	END //
 DELIMITER ;

 -- add unit offering 
 -- must initalise new unit offering with Add new offering. Pass in a matching Subject code and a matching Offering term and Offering year combo
 call TCABSUNITOFFERINGAddNewOffering("ICT30002", "Semester 1", "2019");
 -- this procedures accepts a subject code, offering term and offering year (@ValuesunitOfferingID feild empty). If they are a registored combonation then @ValuesunitOfferingID returns the key for that combination
 -- this shouldn't be called outside of a procedure as it doesn't update tables and returns a key which holds no relation to any information the user would know
 call TCABSUNITOFFERINGGetKey("ICT30002", "Semester 1", "2019", @ValuesunitOfferingID);
 -- the cencus date can only occur during the time period the unit is offered
 call TCABSUNITOFFERINGSetCensusDate("ICT30002", "Semester 1", "2019","2019-6-03");
 -- additional functionality called TCABSUNITOFFERINGSetConvenor after Add new TCABSOFFERINGSTAFFAddOfferingStaff

          DELIMITER //
create Procedure TCABSENROLMENTAddNewEnrolment(in NewEnrolUser varchar(255),in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255))
	BEGIN
		
		 if ((select count(*) from Users where email = NewEnrolUser) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered Email is not in users";
		end if;
		 call TCABSUNITOFFERINGGetKey(SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesunitOfferingID);
         call TCABSENROLMENTPossibleEnrolmentTime(@ValuesunitOfferingID);
         if ((select count(*) from Enrolment where sUserName = NewEnrolUser and unitOfferingID = @ValuesunitOfferingID) = 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "user has already been assigned into that unit";
         end if;
         if ((select count(*) from UserCat where email = NewEnrolUser and userType = "student") <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "user is not a student";
         end if;
         insert into Enrolment(sUserName, unitOfferingID) values (NewEnrolUser, @ValuesunitOfferingID);
	END //
 DELIMITER ;
 
           DELIMITER //
create Procedure TCABSENROLMENTPossibleEnrolmentTime(in OfferingKey int)
	BEGIN
		declare storedcensus date;
		 select censusDate into storedcensus from UnitOffering where unitOfferingID = OfferingKey;
         if (storedcensus < sysdate() and storedcensus is not null) then 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Selected offering is past its census date";
		end if;
	END //
 DELIMITER ;
 
           DELIMITER //
create Procedure TCABSENROLMENTGetEnrolKey(in EnroledUser varchar(255),in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255), out ValueEnrolID int)
	BEGIN
		if (char_length(EnroledUser) <= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "values have not been entered into the email feild";
        end if;
		 if ((select count(*) from Users where email = EnroledUser) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered Email is not in users";
		end if;
		 call TCABSUNITOFFERINGGetKey(SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesunitOfferingID);
         
         select enrolmentid into ValueEnrolID from Enrolment where sUserName = EnroledUser and unitOfferingID = @ValuesunitOfferingID;
         
         if (ValueEnrolID is null) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered user has not enroled in selected unit";
         end if;
	END //
 DELIMITER ;
 -- Create Enrolment 
 -- creates a new Enrolment record using User Email, Unit code, offering term, offering year. returns an error is a filled offering census date is passed by system clock
 call TCABSENROLMENTAddNewEnrolment("Example@hotmail.com","ICT30002", "Semester 1", "2019");
 -- sub procedure functionally that causes no changes but used in other procedures
 call TCABSENROLMENTGetEnrolKey("Example@hotmail.com","ICT30002", "Semester 1", "2019", @ValueEnrolID);

            DELIMITER //
create Procedure TCABSTEACHINGPERIODCreateNewPeriod(in NewTerm varchar(255), in NewStartDate varchar(255), in NewEndDate varchar(255))
	BEGIN
		if (char_length(NewTerm) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no new term name entered";
        end if;
        call TCABSValidateDate(NewStartDate);
        call TCABSValidateDate(NewEndDate);
        if ((select count(*) from teachingperiod where term = NewTerm and year = year(STR_TO_DATE(NewStartDate, '%Y-%m-%d'))) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "That term name and startdate year already exists";
        end if;
        if (STR_TO_DATE(NewStartDate, '%Y-%m-%d') >= STR_TO_DATE(NewEndDate, '%Y-%m-%d')) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Enddate cannot occur on or before Start date";
        end if;
        
        insert into teachingperiod values (NewTerm, year(STR_TO_DATE(NewStartDate, '%Y-%m-%d')), STR_TO_DATE(NewStartDate, '%Y-%m-%d'), STR_TO_DATE(NewEndDate, '%Y-%m-%d'));
        
	END //
 DELIMITER ;
 -- creating a teaching period
 -- this is to enact the creation of a new period by entering the name of the new period entering the start date and end date (the year column stored in the table is the year of the start date)
 call TCABSTEACHINGPERIODCreateNewPeriod("Semester 3","2020-3-1","2021-2-2");
 
            DELIMITER //
create Procedure TCABSUSERROLEEnterNewRole(in RoleName varchar(255))
	BEGIN
		Declare checkname varchar(255) default "";
        set checkname = Replace(RoleName, ' ', '');
		if (char_length(checkname) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no new Role name entered";
        end if;
        if(Substring(checkname,1) REGEXP '[0-9]') then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Role Name must not contain numbers";
        end if;
        if ((select count(*) from UserRole where usertype = RoleName) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "The entered Role name already exists";
        end if;
        insert into UserRole values (RoleName);
	END //
 DELIMITER ;

-- User role
-- this accepts a new user Role which can then be assigned 
call TCABSUSERROLEEnterNewRole("testerRole");

            DELIMITER //
create Procedure TCABSPERMISSIONAddPermission(in RoleName varchar(255), in Functionality varchar(255))
	BEGIN
		
		if (char_length(RoleName) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Role name entered";
        end if;
        if (char_length(Functionality) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Functionality entered";
        end if;
        if ((select count(*) from UserRole where usertype = RoleName) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered Role name does not exist";
        end if;
        if ((select count(*) from Functions where procName = Functionality) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered Functionality does not exist";
        end if;
        if ((select count(*) from Permission where userType = RoleName and procName = Functionality) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered permission already exists";
		end if;
        insert into Permission values (RoleName,Functionality);
	END //
 DELIMITER ;
 
 -- TCABS Permissions 
 -- look at bottom of database code to see call method
 
 -- TCABS Check system date
             DELIMITER //
create function TCABSDateValiadationCheckUnitOfferingVsSystem(SelectedOfferingterm varchar(255), SelectedOfferingyear varchar(255)) returns bool
	BEGIN
		if((select EndDate from teachingperiod where term = SelectedOfferingterm and year = SelectedOfferingyear) < sysdate()) then
			return true;
		else
			return false;
        end if;
	END //
 DELIMITER ;

 -- above is a subfunction within procedures
 
             DELIMITER //
create Procedure TCABSOFFERINGSTAFFAddOfferingStaff(in UserEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255))
	BEGIN
		
		if (char_length(UserEmail) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no User Email entered";
        end if;
        
        if ((select count(*) from Users where email = UserEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User Email does not exist";
        end if;
        
        if ((select count(*) from UserCat where userType = "student" and email = UserEmail) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "User is a student and can't be a staff member";
        end if;
        call TCABSUNITOFFERINGGetKey(SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesunitOfferingID);
        
        if (TCABSDateValiadationCheckUnitOfferingVsSystem(SelectedOfferingterm,SelectedOfferingyear)) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "you can not add a staff member to a unit which has already concluded";
        end if;
        
        if ((select count(*) from Offeringstaff where Username = UserEmail and UnitOfferingID = @ValuesunitOfferingID) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User has already has been assigned as a staff member to this unit";
		end if;
        
        insert into Offeringstaff(Username,UnitOfferingID) values (UserEmail,@ValuesunitOfferingID);
	END //
 DELIMITER ;
 -- TCABS OfferingStaff
 -- registors user into specified offering
-- select * from Teachingperiod;
 call TCABSOFFERINGSTAFFAddOfferingStaff("dtargaryen@gmail.com","ICT30002", "Semester 1", "2019");
 call TCABSOFFERINGSTAFFAddOfferingStaff("jsnow@gmail.com","ICT30002", "Semester 1", "2019");
 
 -- Unit offering Continued..
 -- sets user with entered email to the unit offering convenor if they possess the role of convenor and resgistored as an offering staff member
call TCABSUNITOFFERINGSetConvenor("jsnow@gmail.com","ICT30002", "Semester 1", "2019");
 
             DELIMITER //
create Procedure TCABSTeamAddTeam(in NewTeamName varchar(255), in UserEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255))
	BEGIN
		
		if (char_length(UserEmail) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no User Email entered";
        end if;
        if (char_length(NewTeamName) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no team name entered";
        end if;
        if ((select count(*) from Users where email = UserEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User Email does not exist";
        end if;
        
        if ((select count(*) from UserCat where userType = "supervisor" and email = UserEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "User Is not a supervisor and can't be assigned to a team";
        end if;
        call TCABSUNITOFFERINGGetKey(SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesunitOfferingID);
        if ((select count(*) from team where SupervisorUser = UserEmail and UnitOfferingID = @ValuesunitOfferingID and TeamName = NewTeamName) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User has already has been assigned as the supervisor of the team with the entered name";
		end if;
		if ((select count(*) from Offeringstaff where Username = UserEmail and UnitOfferingID = @ValuesunitOfferingID) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User is not registored as a staff member for this unit";
        end if;
        if (TCABSDateValiadationCheckUnitOfferingVsSystem(SelectedOfferingterm,SelectedOfferingyear)) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "you can not add a team to a unit which has already concluded";
        end if;
        insert into Team(TeamName,SupervisorUser,UnitOfferingID) values (NewTeamName,UserEmail,@ValuesunitOfferingID);
	END //
 DELIMITER ;
 
 
 
              DELIMITER //
create Procedure TCABSTeamSetTeamName(in oldteamname varchar(255), in UserEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255), in newteamname varchar(255))
	BEGIN
		
		if (char_length(UserEmail) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no User Email entered";
        end if;
        if (char_length(newTeamname) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no new Team name entered";
        end if;
        if (char_length(oldTeamname) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no old Team name entered";
        end if;
        if ((select count(*) from Users where email = UserEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered User Email does not exist";
        end if;
        call TCABSUNITOFFERINGGetKey(SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesunitOfferingID);
        if ((select count(*) from team where SupervisorUser = UserEmail and UnitOfferingID = @ValuesunitOfferingID and Teamname = teamname) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "There is no team with that combination teamname and Staff member for the entered unit offering";
		end if;
        update Team set TeamName = newTeamname where UnitOfferingID = @ValuesunitOfferingID and SupervisorUser = UserEmail and teamname = oldteamname;
	END //
 DELIMITER ;
 
               DELIMITER //
create Procedure TCABSTeamGetTeamKey(in Teamname varchar(255), in UserEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255), OUT ValuesTeamID int)
	BEGIN
		if (char_length(UserEmail) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no User Email entered";
        end if;
        call TCABSUNITOFFERINGGetKey(SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesunitOfferingID);
        
        select TeamID into ValuesTeamID from team where SupervisorUser = UserEmail and UnitOfferingID = @ValuesunitOfferingID and TeamName = Teamname;
        
        if (ValuesTeamID is null) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Unknown team name, Supervisor and unit offering combination ";
		end if;
	END //
 DELIMITER ;
 -- select * from Enrolment;
 
                DELIMITER //
create Procedure TCABSTeamSetTeamProjectManager(in Teamname varchar(255), in SuperviserUserEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255), in ProjectManagerUser varchar(255))
	BEGIN
    -- ProjectManagerUser
    
		if (char_length(ProjectManagerUser) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no student Email entered";
        end if;
        
		call TCABSENROLMENTGetEnrolKey(ProjectManagerUser,SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValueEnrolID);
		
        call TCABSTeamGetTeamKey(Teamname, SuperviserUserEmail,SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesTeamID);
        if ((select count(*) from teammember where Enrolmentid = @ValueEnrolID and TeamID = @ValuesTeamID) <> 1) then 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "student is not assigned to team which they are becoming a project manager of";
        end if;
        
        update Team set ProjectManager = ProjectManagerUser where Teamid = @ValuesTeamID;
	END //
 DELIMITER ;
 
 -- Addteam
 -- adds a supervisor user to a particular team if they are in the offering staff members list
call TCABSTeamAddTeam("testTeam","dtargaryen@gmail.com","ICT30002", "Semester 1", "2019");
-- allows for allocation of team name
call TCABSTeamSetTeamName("testTeam","dtargaryen@gmail.com","ICT30002", "Semester 1", "2019","testTeam2");
-- this procedure is mainly used in other procedures and performs no actions that change tables
call TCABSTeamGetTeamKey("testTeam2", "dtargaryen@gmail.com","ICT30002", "Semester 1", "2019", @ValuesTeamID);

-- need to add Project Manager allocation occurs after adding team members

               DELIMITER //
create Procedure TCABSTEAMMEMBERAddTeamMember(in StudentEmail varchar(255),in Teamname varchar(255), in SupervisorEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255))
	BEGIN
		declare StoredEnrolmentID int;
		if (char_length(StudentEmail) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Student Email entered";
        end if;
        if (char_length(SupervisorEmail) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Supervisor Email entered";
        end if;
        if (char_length(Teamname) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no team name entered";
        end if;
        if ((select count(*) from Users where email = StudentEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered Student Email does not exist";
        end if;
        if ((select count(*) from Users where email = SupervisorEmail) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered Supervisor Email does not exist";
        end if;
        
        call TCABSUNITOFFERINGGetKey(SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesunitOfferingID);
        
        select enrolmentID into StoredEnrolmentID from Enrolment where unitOfferingID = @ValuesunitOfferingID and sUserName = StudentEmail;
		if( StoredEnrolmentID is null) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered Student Enrolment not exist";
        end if;
        
        if (TCABSDateValiadationCheckUnitOfferingVsSystem(SelectedOfferingterm,SelectedOfferingyear)) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "you can not add a team member to a unit which has already concluded";
        end if;
        
        call TCABSTeamGetTeamKey(Teamname,SupervisorEmail,SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesTeamID);
        if ((select count(*) from TeamMember where TeamID = @ValuesTeamID) >= 6) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "entered team to assign to a student already has the max of 6 team members";
        end if;
        insert into TeamMember(EnrolmentID,TeamID) values (StoredEnrolmentID,@ValuesTeamID);
        
	END //
 DELIMITER ;
 
                DELIMITER //
create Procedure TCABSTEAMMEMBERGetTeamMember(in StudentEmail varchar(255),in Teamname varchar(255), in SupervisorEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255), out ValueTeamMemberID int)
	BEGIN
		declare StoredEnrolmentID int;
        
        
        call TCABSENROLMENTGetEnrolKey(StudentEmail,SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValueEnrolID);
        call TCABSTeamGetTeamKey(Teamname,SupervisorEmail,SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValuesTeamID);
        
        if ((select count(*) from TeamMember where TeamID = @ValuesTeamID and TeamID = @ValueEnrolID) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "The entered team doesn't have the entered Enroled member";
        end if;
        select TeamMemberID into ValueTeamMemberID from TeamMember where TeamID = @ValuesTeamID and TeamID = @ValueEnrolID;
        
	END //
 DELIMITER ;
 -- add student user X to the team called Y with the supervisor user Z which is enroled in the subject A in the period of B for the year of C 
call TCABSTEAMMEMBERAddTeamMember("Example@hotmail.com","testTeam2","dtargaryen@gmail.com","ICT30002", "Semester 1", "2019");
-- calling method for other procedure this shouldn't be used outside of procedures
call TCABSTEAMMEMBERGetTeamMember("Example@hotmail.com","testTeam2","dtargaryen@gmail.com","ICT30002", "Semester 1", "2019", @ValueTeamMemberID);
-- Team continued 
-- adding the user Example@hotmail.com as the project manager of testTeam2 after he was assigned to that team
call TCABSTeamSetTeamProjectManager("testTeam2","dtargaryen@gmail.com","ICT30002", "Semester 1", "2019","Example@hotmail.com");

               DELIMITER //
create Procedure TCABSPROJECTROLEAddProjectRole(in EnteredRoleName varchar(255),in EnteredSalary double)
	BEGIN
		call TCABSValidateNameString(EnteredRoleName);
        if (EnteredSalary < 0) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A Project roles salary can not be negative";
        end if;
        
        if ((select count(*) from ProjectRole where Rolename = EnteredRoleName) >= 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered Role already exists";
        end if;
        insert into ProjectRole (Rolename,Salary) values (EnteredRoleName,EnteredSalary);
	END //
 DELIMITER ;
 
                DELIMITER //
create Procedure TCABSPROJECTROLESetRoleDescription(in EnteredRoleName varchar(255),in EnteredRoleDesciption text)
	BEGIN
		
        if (char_length(EnteredRoleName) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Role name entered";
        end if;
        
        if (char_length(EnteredRoleDesciption) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Role description entered";
        end if;
        
        if ((select count(*) from ProjectRole where Rolename = EnteredRoleName) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered Role Does not exist";
        end if;
        update ProjectRole set RoleDescription = EnteredRoleDesciption where Rolename = EnteredRoleName;
	END //
 DELIMITER ;
 -- Project Roles
 -- adds a project role with an associated salary per hour
 call TCABSProjectRoleAddProjectRole("Project Manager", 56.60);
 -- sets Project role description
 call TCABSPROJECTROLESetRoleDescription("Project Manager", "Charged with managing high level group tasks and organisation");
 
 /*
 Under Construction
                DELIMITER //
create Procedure TCABSTASKSAddNewTask(in StudentEmail varchar(255),in Teamname varchar(255), in SupervisorEmail varchar(255), in SelectedUnitCode varchar(255), in SelectedOfferingterm varchar(255), in SelectedOfferingyear varchar(255), in MinuteTime int, in Taskdescription text, in EnteredRoleName varchar(255))
	BEGIN
		
        if (char_length(EnteredRoleName) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Role entered";
        end if;
         if (char_length(Taskdescription) < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "no Task description entered";
        end if;
        if (MinuteTime < 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Task must be one or more minutes long ";
        end if;
        
        if ((select count(*) from ProjectRole where Rolename = EnteredRoleName) <> 1) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Entered project role doesn't exist";
        end if;
        
		call TCABSTEAMMEMBERGetTeamMember(StudentEmail,Teamname,SupervisorEmail,SelectedUnitCode, SelectedOfferingterm, SelectedOfferingyear, @ValueTeamMemberID);
        
        
        insert into Task (TeamMemberID,Rolename,TaskDetails,TimeTaken) values (@ValueTeamMemberID,EnteredRoleName,Taskdescription,MinuteTime);
	END //
 DELIMITER ;
 -- Tasks
 -- for the student user X that is a team member of the team called Y that has the supervisor of Z which watched over this team for the subject A that is offered in period B within the Year C. The user A has submitted a task for D minutes of work that has the description of F and the Role type of G
 call TCABSTASKSAddNewTask("Example@hotmail.com","testTeam2","dtargaryen@gmail.com","ICT30002", "Semester 1", "2019",7,"Assigning team tasks","Project Manager");
select * from task;
 /*
 
 Create Table Task (
	TaskID							int 			auto_increment,
    TeamMemberID					int				not null,
    Rolename						varchar(255)	Not null,
    TaskDetails						text			not null,
    TimeTaken						int				not null,
    logged							boolean			DEFAULT false,
    Altered							boolean			DEFAULT false,

	Primary Key (TaskID),
    Foreign Key (TeamMemberID) REFERENCES Enrolment(enrolmentID),
    Foreign Key (Rolename) REFERENCES ProjectRole(Rolename)
);

Create Table TeamMember (
	TeamMemberID					INT				AUTO_INCREMENT,
	EnrolmentID						INT				NOT NULL,
	TeamID							INT				NOT NULL,

	PRIMARY KEY (TeamMemberID),
	FOREIGN KEY (TeamID) REFERENCES Team(TeamID),
	FOREIGN KEY (EnrolmentID) REFERENCES Enrolment(enrolmentID)
);

Create Table ProjectRole (
	Rolename						varchar(255) not null,
    Salary							decimal not null,
    RoleDescription					text,
    
    Primary Key (Rolename)
);

*/

-- ---------------------------------------No Procedures or Functions after this point -----------------------------------------------
-- Functions
-- this must run after all procedures and functions as it loads all available user functionality for security to manage
insert into Functions(procName) SELECT Routine_Name FROM INFORMATION_SCHEMA.ROUTINES where Routine_Schema like '%TCABS%';


-- TCABS Permissions
-- assigns a Role to a Permission 
call TCABSPERMISSIONAddPermission("testerRole", "TCABSauthenticateEmail");
