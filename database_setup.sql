CREATE DATABASE IF NOT EXISTS college_db;

USE college_db;

DROP TABLE IF EXISTS Marks;
DROP TABLE IF EXISTS Student;

CREATE TABLE Student (
    Roll_Number VARCHAR(20) PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Class VARCHAR(50) NOT NULL,
    DOB DATE NOT NULL,
    Contact_no VARCHAR(15) NOT NULL
);

CREATE TABLE Marks (
    Roll_Number VARCHAR(20) PRIMARY KEY,
    M1 INT NOT NULL CHECK (M1 >= 0 AND M1 <= 100),
    M2 INT NOT NULL CHECK (M2 >= 0 AND M2 <= 100),
    M3 INT NOT NULL CHECK (M3 >= 0 AND M3 <= 100),
    FOREIGN KEY (Roll_Number) REFERENCES Student(Roll_Number) ON DELETE CASCADE
);

INSERT INTO Student (Roll_Number, Name, Class, DOB, Contact_no) VALUES
('119', 'Girish Sapkale', 'BBA-CA', '2003-05-15', '9876543210'),
('120', 'Vaibhav Sakhare', 'BBA-CA', '2003-08-22', '9876543211'),
('121', 'Yashraj Mangnale', 'BBA-CA', '2003-03-10', '9876543212'),
('122', 'Chetan Jagdale', 'BBA-CA', '2003-11-30', '9876543213'),
('123', 'Aditya Jirwankar', 'BBA-CA', '2003-07-18', '9876543214');

INSERT INTO Marks (Roll_Number, M1, M2, M3) VALUES
('119', 85, 90, 88),
('120', 78, 82, 80),
('121', 65, 70, 68),
('122', 92, 95, 93),
('123', 55, 58, 52);
