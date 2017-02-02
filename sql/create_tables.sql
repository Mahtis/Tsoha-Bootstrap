-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE LabUser (
  id SERIAL PRIMARY KEY,
  name varchar(50) NOT NULL,
  username varchar(20) NOT NULL UNIQUE,
  password varchar(50) NOT NULL,
  email varchar(50) NOT NULL
);

CREATE TABLE Experiment (
  id SERIAL PRIMARY KEY,
  name varchar(100) NOT NULL,
  description varchar(3000),
  maxSubjects int NOT NULL
);

CREATE TABLE Laboratory (
  id SERIAL PRIMARY KEY,
  name varchar(100) NOT NULL,
  location varchar(255) NOT NULL,
  navigation varchar(3000),
  equipment varchar(3000),
  contactPerson varchar(50)
);

CREATE TABLE UserExperiment (
  labUser_id int NOT NULL,
  experiment_id int NOT NULL,
  PRIMARY KEY (labUser_id, experiment_id),
  FOREIGN KEY (labUser_id) REFERENCES LabUser(id),
  FOREIGN KEY (experiment_id) REFERENCES Experiment(id)
);

CREATE TABLE TimeSlot (
  id SERIAL PRIMARY KEY,
  startTime timestamp NOT NULL,
  endTime timestamp NOT NULL,
  maxReservations int NOT NULL,
  freeSlots int NOT NULL,
  labUser_id int,
  experiment_id int NOT NULL,
  laboratory_id int NOT NULL,
  FOREIGN KEY (labUser_id) REFERENCES LabUser(id),
  FOREIGN KEY (experiment_id) REFERENCES Experiment(id),
  FOREIGN KEY (laboratory_id) REFERENCES Laboratory(id)
);

CREATE TABLE Reservation (
  id SERIAL PRIMARY KEY,
  email varchar NOT NULL,
  timeSlot_id int,
  FOREIGN KEY (timeSlot_id) REFERENCES TimeSlot(id)
);

CREATE TABLE RequiredInfo (
  id SERIAL PRIMARY KEY,
  question varchar(2000) NOT NULL,
  experiment_id int NOT NULL,
  FOREIGN KEY (experiment_id) REFERENCES Experiment(id)
);

CREATE TABLE SubjectInfo (
  id SERIAL PRIMARY KEY,
  response varchar(255),
  reservation_id int NOT NULL,
  requiredInfo_id int NOT NULL,
  FOREIGN KEY (reservation_id) REFERENCES Reservation(id),
  FOREIGN KEY (requiredInfo_id) REFERENCES RequiredInfo(id)
);
