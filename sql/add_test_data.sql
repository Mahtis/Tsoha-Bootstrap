-- Lisätään käyttäjiä LabUser-tauluun
INSERT INTO LabUser (name, username, password, email) VALUES ('Mikko', 'mikko', 'mikko', 'mikko@mikko.com');
INSERT INTO LabUser (name, username, password, email) VALUES ('Apina Napina', 'apina', 'apina', 'apina@apina.com');
INSERT INTO LabUser (name, username, password, email) VALUES ('Bunkey', 'user', 'user', 'user@user.gg');

-- Experiment testidataa
INSERT INTO Experiment (name, description, maxSubjects) VALUES ('pieni koe', 'Tällainen pieni koe jossa tehdään asioita', 20);
INSERT INTO Experiment (name, description, maxSubjects) VALUES ('motoriikkakoe', 'Kokeessa tutkitaan käden motoriikkaa ja siihen liittyviä asioita', 30);
INSERT INTO Experiment (name, description, maxSubjects) VALUES ('koe', 'koe jossa tehdään asioita', 15);

-- UserExperiment-yhdistelmiä kokeiden välillä
INSERT INTO UserExperiment (labUser_id, experiment_id) VALUES (1,2);
INSERT INTO UserExperiment (labUser_id, experiment_id) VALUES (1,3);
INSERT INTO UserExperiment (labUser_id, experiment_id) VALUES (2,1);
INSERT INTO UserExperiment (labUser_id, experiment_id) VALUES (3,1);
INSERT INTO UserExperiment (labUser_id, experiment_id) VALUES (3,2);
INSERT INTO UserExperiment (labUser_id, experiment_id) VALUES (3,3);

-- Laboratory-testidataa
INSERT INTO Laboratory (name, location, navigation, equipment, contactPerson) VALUES ('K140', '60.175949, 24.953988', 'Kävele ovesta sisään rappuset alas ja oikealle, odota lasiovien luona', 'tietokone, puristusotelaitteita, joystick', 'Mikko');
INSERT INTO Laboratory (name, location, navigation, equipment, contactPerson) VALUES ('K137', '60.175949, 24.953988', '', 'tietokone', 'Pasi');
INSERT INTO Laboratory (name, location, navigation, equipment, contactPerson) VALUES ('K139', '60.175949, 24.953988', '', 'tietokone', 'Pasi');
INSERT INTO Laboratory (name, location, navigation, equipment, contactPerson) VALUES ('Näkölabra 227', '60.175949, 24.953988', 'Toisessa kerroksessa', 'tietokone', 'Pasi');

-- TimeSlot-testidataa
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-12 10:00', '2017-01-12 11:00', 1, 1, 2, 1);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-12 11:00', '2017-01-12 10:00', 1, 1, 2, 1);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-12 12:00', '2017-01-12 13:00', 1, 1, 2, 1);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-13 10:00', '2017-01-13 11:00', 1, 1, 2, 1);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-13 11:00', '2017-01-13 12:00', 1, 1, 2, 1);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-14 10:00', '2017-01-14 11:00', 1, 1, 2, 1);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-12 10:00', '2017-01-12 11:00', 2, 3, 1, 2);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-12 10:00', '2017-01-12 11:00', 3, 3, 1, 3);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-13 10:00', '2017-01-13 11:00', 1, 2, 3, 3);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-13 11:00', '2017-01-13 12:00', 1, 2, 3, 3);
INSERT INTO TimeSlot (startTime, endTime, maxReservations, labUser_id, experiment_id, laboratory_id) VALUES ('2017-01-13 12:00', '2017-01-13 13:00', 1, 2, 3, 3);

-- Reservation-testidataa
INSERT INTO Reservation (email, timeSlot_id) VALUES ('joku@gg.com', 1);
INSERT INTO Reservation (email, timeSlot_id) VALUES ('joku@gg.com', 7);
INSERT INTO Reservation (email, timeSlot_id) VALUES ('pasi@gg.com', 7);
INSERT INTO Reservation (email, timeSlot_id) VALUES ('sami@gg.com', 2);
INSERT INTO Reservation (email, timeSlot_id) VALUES ('pp@gg.com', 4);
INSERT INTO Reservation (email, timeSlot_id) VALUES ('papu@gg.com', 9);

-- RequiredInfo-testidataa
INSERT INTO RequiredInfo (question, experiment_id) VALUES ('Kumpi kätinen olet?', 2);
INSERT INTO RequiredInfo (question, experiment_id) VALUES ('Minkä ikäinen olet?', 2);
INSERT INTO RequiredInfo (question, experiment_id) VALUES ('Minkä ikäinen olet?', 1);
INSERT INTO RequiredInfo (question, experiment_id) VALUES ('Kuka olet?', 1);

-- SubjectInfo-testidataa
INSERT INTO SubjectInfo (response, reservation_id, requiredInfo_id) VALUES ('oikea?', 1, 1);
INSERT INTO SubjectInfo (response, reservation_id, requiredInfo_id) VALUES ('22', 2, 3);
INSERT INTO SubjectInfo (response, reservation_id, requiredInfo_id) VALUES ('35', 3, 3);
INSERT INTO SubjectInfo (response, reservation_id, requiredInfo_id) VALUES ('pasi', 3, 4);

-- AcceptableResponse-testidataa
INSERT INTO AcceptableResponse (response, requiredInfo_id) VALUES ('oikea', 1);
INSERT INTO AcceptableResponse (response, requiredInfo_id) VALUES ('vasen', 1);
INSERT INTO AcceptableResponse (response, requiredInfo_id) VALUES ('molempi', 1);
INSERT INTO AcceptableResponse (response, requiredInfo_id) VALUES ('pasi', 4);
