/* This SQL file will DROP, CREATE, and LOAD sample data into the tables
*  we need for the new La Casa section of the TRP database.
*
*  This table references the "Participants" table, and all 
*  La Casa students and residents will be entered into that table as 
*  their primary key.
*  
*  This file creates a La_Casa_Basics table to hold references to the Colleges
*  table with number of credits earned per term and specific loan
*  information that was requested by the LC director. 
*
*/

USE ttm-trp;



DROP TABLE IF EXISTS `Colleges`;

CREATE TABLE `Colleges`
(
`College_ID` int(11) NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`College_ID`),
`College_Name` varchar(100),
`College_Type` varchar(100)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS `La_Casa_Basics`;

CREATE TABLE `La_Casa_Basics`
(
`Student_ID` int(11) NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`Student_ID`),
`Participant_ID_Students` int(11),
INDEX `par_ind_stu` (`Participant_ID_Students`),
FOREIGN KEY (`Participant_ID_Students`) REFERENCES `Participants`
    (`Participant_ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
`College_ID` int(11), 
`Term_Type` varchar(45),
`Term` varchar(45),
`School_Year` int(4),
`Credits` varchar(45),
`Loan_Applications` int(3),
`Loan_Volume` varchar(45),
`Loans_Received` varchar(45),
`Household_Size` int(3),
`Parent1_AGI` int(11),
`Parent2_AGI` int(11),
`Student_AGI` int(11),
`Major` varchar(100),
`College_GPA` varchar(30),
`ACT_Score` int(5),
`High_School_GPA` int(10),
`Dependency_Status` int(1),
`Father_Highest_Level_Education` varchar(30),
`Mother_Highest_Level_Education` varchar(30),
`Student_Aspiration` varchar(30),
`First_Generation_College_Student` int(1),
`College_Match` varchar(30),
`Persistence_Graduation` varchar(30),
`Student_Hometown` varchar(100),
`Student_High_School` varchar(100)

) ENGINE=InnoDB;


 ALTER TABLE `Participants` ADD
   `Email_2` varchar(60); 

ALTER TABLE `Participants` ADD
`Mobile_Phone` varchar(45);


DROP TABLE IF EXISTS `Educational_Levels`;

CREATE TABLE `Educational_Levels`
(
`Education_ID` int(11) NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`Education_ID`),
`Education_Level_Name` varchar(45)
) ENGINE = InnoDB;

INSERT INTO `Educational_Levels` (Education_Level_Name) VALUES ('Elementary'), ('Middle School'), ('Some HS'), ('GED'), ('High School Diploma'), ('Some College'), ('Trade School'), ('Associates Degree'), ('Bachelors Degree'), ('Masters Degree'), ('MD'), ('PhD'), ('Unknown');
