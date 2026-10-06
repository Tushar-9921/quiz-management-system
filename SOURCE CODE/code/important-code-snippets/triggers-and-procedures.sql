-- Stored Procedure: leaderboard
DELIMITER $$
DROP PROCEDURE IF EXISTS `leaderboard`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `leaderboard` () NO SQL 
SELECT q.quizname, s.score, s.totalscore, st.name, s.mail 
FROM score s, student st, quiz q 
WHERE s.mail=st.mail AND q.quizid=s.quizid 
ORDER BY score DESC$$
DELIMITER ;

-- Trigger: ondeleteqs (Cascade question deletion upon quiz removal)
DROP TRIGGER IF EXISTS `ondeleteqs`;
DELIMITER $$
CREATE TRIGGER `ondeleteqs` AFTER DELETE ON `quiz` FOR EACH ROW 
DELETE FROM questions WHERE questions.quizid=old.quizid$$
DELIMITER ;

-- Trigger: remarks (Automatic performance remark classification)
DROP TRIGGER IF EXISTS `remarks`;
DELIMITER $$
CREATE TRIGGER `remarks` BEFORE INSERT ON `score` FOR EACH ROW 
SET NEW.remark = IF(NEW.score = 0, 'bad', 'good')$$
DELIMITER ;