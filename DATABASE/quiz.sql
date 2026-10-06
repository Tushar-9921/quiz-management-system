-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2026 at 05:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_demo2`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `leaderboard` ()  NO SQL select q.quizname,s.score,s.totalscore,st.name,s.mail from score s,student st,quiz q where s.mail=st.mail and q.quizid=s.quizid order by score DESC$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dept`
--

CREATE TABLE `dept` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dept`
--

INSERT INTO `dept` (`dept_id`, `dept_name`) VALUES
(1, 'CSE'),
(2, 'ISE'),
(3, 'ECE'),
(4, 'EEE');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `qs` varchar(200) NOT NULL,
  `op1` varchar(30) NOT NULL,
  `op2` varchar(30) NOT NULL,
  `op3` varchar(30) NOT NULL,
  `answer` varchar(30) NOT NULL,
  `quizid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`qs`, `op1`, `op2`, `op3`, `answer`, `quizid`) VALUES
('/ Assume that integers take 4 bytes.<br>  #include<iostream> <br>    using namespace std; <br>       class Test  { <br>   static int i;<br>    int j;<br>  }; <br>    int Test::i; <br>    int main() { ', '1', '2', '3', '4', 5),
('AI: What are neural networks made from?', 'No valid choice', 'Web pages', 'Hard drives', 'Connected layers', 25),
('AI: What does an LLM mainly process?', 'No valid choice', 'Video cables', 'CPU fans', 'Language tokens', 25),
('AI: What does machine learning learn from?', 'No valid choice', 'Paint', 'Cables', 'Data', 25),
('AI: What does supervised learning use?', 'No valid choice', 'No examples', 'Only random noise', 'Labeled examples', 25),
('AI: What is a dataset?', 'No valid choice', 'A computer monitor', 'A programming loop', 'A collection of examples', 25),
('AI: What is a token in language models?', 'No valid choice', 'A power supply', 'A database row only', 'A text unit', 25),
('AI: What is an AI hallucination?', 'No valid choice', 'A correct citation', 'A faster network', 'Plausible but false output', 25),
('AI: What is inference?', 'No valid choice', 'Collecting electricity', 'Formatting a disk', 'Using a trained model', 25),
('AI: What is model training?', 'No valid choice', 'Printing a report', 'Deleting a dataset', 'Fitting model parameters', 25),
('AI: What is overfitting?', 'No valid choice', 'Model has no parameters', 'Model runs offline', 'Overfits training examples', 25),
('Algorithms: Binary search requires what?', 'None of these', 'Images', 'A database', 'Sorted input', 21),
('Algorithms: Bubble sort compares what?', 'None of these', 'Random files', 'Tree roots', 'Adjacent items', 21),
('Algorithms: Dijkstra finds what kind of paths?', 'None of these', 'Longest words', 'Sorted arrays', 'Shortest paths', 21),
('Algorithms: What describes growth as input grows?', 'None of these', 'HTML', 'SQL', 'Big O', 21),
('Algorithms: What is linear search worst-case time?', 'None of these', 'O(1)', 'O(log n)', 'O(n)', 21),
('Algorithms: What stops recursive calls?', 'None of these', 'Loop label', 'Global variable', 'Base case', 21),
('Algorithms: Which sort merges sorted halves?', 'None of these', 'Insertion sort', 'Heap sort', 'Merge sort', 21),
('Algorithms: Which sort uses divide and conquer?', 'None of these', 'Bubble sort', 'Selection sort', 'Merge sort', 21),
('Algorithms: Which traversal commonly uses a queue?', 'None of these', 'DFS', 'Quick sort', 'BFS', 21),
('Algorithms: Which traversal commonly uses a stack?', 'None of these', 'BFS', 'Merge sort', 'DFS', 21),
('Biology: What do red blood cells carry?', 'Not applicable', 'Bile', 'Insulin', 'Oxygen', 35),
('Biology: What includes organisms and their environment?', 'Not applicable', 'Chromosome', 'Tissue', 'Ecosystem', 35),
('Biology: What is the basic unit of life?', 'Not applicable', 'Atom', 'Organ', 'Cell', 35),
('Biology: What molecule carries genetic information?', 'Not applicable', 'ATP only', 'Starch', 'DNA', 35),
('Biology: What process lets plants make food from light?', 'Not applicable', 'Respiration', 'Digestion', 'Photosynthesis', 35),
('Biology: Which group feeds milk to young?', 'Not applicable', 'Reptiles', 'Insects', 'Mammals', 35),
('Biology: Which organ pumps blood?', 'Not applicable', 'Liver', 'Stomach', 'Heart', 35),
('Biology: Which organelle releases cell energy?', 'Not applicable', 'Ribosome', 'Nucleus only', 'Mitochondrion', 35),
('Biology: Which organs exchange gases?', 'Not applicable', 'Kidneys', 'Bones', 'Lungs', 35),
('Biology: Which pigment absorbs light for photosynthesis?', 'Not applicable', 'Hemoglobin', 'Melanin', 'Chlorophyll', 35),
('C primiarily developed as..', 'General purpose language', 'Data processing language D.', 'None of the above.', 'System programming language  ', 4),
('C programs converted into machine language with the help of..', 'An Editor  ', 'An operating system', ' None of these.', 'A compiler  ', 4),
('C++: What defines a class instance setup?', 'No valid choice', 'Destructor', 'Namespace', 'Constructor', 13),
('C++: Which access level hides class members?', 'No valid choice', 'No answer', 'global', 'private', 13),
('C++: Which header supports cout?', 'No valid choice', '<stdio.h>', '<vector.h>', '<iostream>', 13),
('C++: Which keyword allocates dynamic memory?', 'No valid choice', 'create', 'alloc', 'new', 13),
('C++: Which keyword enables runtime dispatch?', 'No valid choice', 'friend', 'inline', 'virtual', 13),
('C++: Which library type is a dynamic array?', 'No valid choice', 'queue', 'pair', 'vector', 13),
('C++: Which operator accesses a namespace member?', 'No valid choice', 'No answer', '.', '::', 13),
('C++: Which stream prints output?', 'No valid choice', 'No answer', 'cerrin', 'cout', 13),
('C++: Which stream reads standard input?', 'No valid choice', 'No answer', 'clog', 'cin', 13),
('C++: Who designed C++?', 'No valid choice', 'No answer', 'Guido van Rossum', 'Bjarne Stroustrup', 13),
('C: What is the first array index?', 'None of these', '1', '-1', '0', 12),
('C: What is the usual program entry point?', 'None of these', 'start()', 'begin()', 'main()', 12),
('C: What translates C source code?', 'None of these', 'Browser', 'Editor', 'Compiler', 12),
('C: Which character ends a C string?', 'None of these', 'space', 'newline', 'zero byte', 12),
('C: Which format specifier is for int?', 'None of these', 'No answer', '%f', '%d', 12),
('C: Which function prints formatted output?', 'None of these', 'No answer', 'input', 'printf', 12),
('C: Which header declares malloc?', 'None of these', 'No answer', '<ctype.h>', '<stdlib.h>', 12),
('C: Which header declares printf?', 'None of these', 'No answer', '<string.h>', '<stdio.h>', 12),
('C: Which operator gets an address?', 'None of these', 'No answer', '%', '&', 12),
('C: Who created the C language?', 'None of these', 'No answer', 'Bjarne Stroustrup', 'Dennis Ritchie', 12),
('Chemistry: What charge does a proton have?', 'No valid choice', 'Negative', 'Neutral', 'Positive', 34),
('Chemistry: What charge does an electron have?', 'No valid choice', 'No answer', 'Neutral', 'Negative', 34),
('Chemistry: What do covalent bonds share?', 'No valid choice', 'Protons', 'Neutrons', 'Electrons', 34),
('Chemistry: What is common table salt?', 'No valid choice', 'HCl', 'KOH', 'NaCl', 34),
('Chemistry: What is the atomic number of oxygen?', 'No valid choice', 'No answer', '10', '8', 34),
('Chemistry: What is the formula for carbon dioxide?', 'No valid choice', 'CO', 'C2O', 'CO2', 34),
('Chemistry: What is the formula for water?', 'No valid choice', 'CO2', 'O2', 'H2O', 34),
('Chemistry: What pH is neutral?', 'No valid choice', 'No answer', '14', '7', 34),
('Chemistry: Where are elements organized?', 'No valid choice', 'Food pyramid', 'Map grid', 'Periodic table', 34),
('Chemistry: Which is a state of matter?', 'No valid choice', 'Metallic', 'Magnetic', 'Solid', 34),
('Cloud: What does IaaS provide?', 'None of these', 'Finished applications', 'Only source code', 'Virtual infrastructure', 30),
('Cloud: What does PaaS provide?', 'None of these', 'A keyboard', 'A database row', 'Application platform', 30),
('Cloud: What does SaaS deliver?', 'None of these', 'Physical cables', 'A local CPU', 'Software over the internet', 30),
('Cloud: What does scaling change?', 'None of these', 'File extension', 'User spelling', 'Available resources', 30),
('Cloud: What does serverless commonly run?', 'None of these', 'Only desktop apps', 'A local printer', 'Functions on demand', 30),
('Cloud: What is a cloud region?', 'None of these', 'A password type', 'A source file', 'A geographic service area', 30),
('Cloud: What is a private cloud?', 'None of these', 'Public social media', 'A web browser', 'Dedicated cloud environment', 30),
('Cloud: What is a virtual machine?', 'None of these', 'A physical switch', 'A web address', 'Software-based computer', 30),
('Cloud: What is object storage suited for?', 'None of these', 'CPU instructions', 'Live keyboard input', 'Unstructured files', 30),
('Cloud: What is public cloud infrastructure?', 'None of these', 'One private laptop', 'Offline storage only', 'Shared provider services', 30),
('Computer: How many bits are in a byte?', 'Not applicable', '4', '16', '8', 29),
('Computer: What connects major components?', 'Not applicable', 'Mouse pad', 'Web page', 'Motherboard', 29),
('Computer: What does USB commonly connect?', 'Not applicable', 'Cloud regions', 'Web domains', 'Peripherals', 29),
('Computer: What executes program instructions?', 'Not applicable', 'SSD', 'Monitor', 'CPU', 29),
('Computer: What manages hardware and software?', 'Not applicable', 'Web browser', 'Text file', 'Operating system', 29),
('Computer: Which component accelerates graphics?', 'Not applicable', 'NIC', 'PSU', 'GPU', 29),
('Computer: Which device is an input device?', 'Not applicable', 'Monitor', 'Speaker', 'Keyboard', 29),
('Computer: Which device is an output device?', 'Not applicable', 'Keyboard', 'Mouse', 'Monitor', 29),
('Computer: Which device stores files persistently?', 'Not applicable', 'RAM', 'CPU cache', 'SSD', 29),
('Computer: Which memory is temporary?', 'Not applicable', 'SSD', 'DVD', 'RAM', 29),
('CSS: What does CSS stand for?', 'None of these', 'Computer Style Syntax', 'Color Sheet System', 'Cascading Style Sheets', 15),
('CSS: What does the box model include?', 'None of these', 'Only text', 'Only border', 'Content box and spacing', 15),
('CSS: Which display mode helps flex layouts?', 'None of these', 'blocker', 'float', 'flex', 15),
('CSS: Which property adds inside spacing?', 'None of these', 'No answer', 'outline', 'padding', 15),
('CSS: Which property adds outside spacing?', 'None of these', 'No answer', 'border', 'margin', 15),
('CSS: Which property changes text color?', 'None of these', 'No answer', 'text-align', 'color', 15),
('CSS: Which property changes text size?', 'None of these', 'text-weight', 'letter-size', 'font-size', 15),
('CSS: Which property sets an element background?', 'None of these', 'font-color', 'fill-text', 'background-color', 15),
('CSS: Which selector targets a class?', 'None of these', 'No answer', ':card', '.card', 15),
('CSS: Which selector targets an element ID?', 'None of these', 'No answer', '*name', '#name', 15),
('Data Structures: Binary search needs what input?', 'Not applicable', 'Random data', 'Text only', 'Sorted data', 20),
('Data Structures: What are graph points called?', 'Not applicable', 'Leaves', 'Buckets', 'Vertices', 20),
('Data Structures: What does a linked list connect?', 'Not applicable', 'Pixels', 'Files', 'Nodes', 20),
('Data Structures: What is a function calling itself?', 'Not applicable', 'Iteration', 'Sorting', 'Recursion', 20),
('Data Structures: Which structure follows FIFO?', 'Not applicable', 'No answer', 'Tree', 'Queue', 20),
('Data Structures: Which structure follows LIFO?', 'Not applicable', 'No answer', 'Graph', 'Stack', 20),
('Data Structures: Which structure has parent-child links?', 'Not applicable', 'Queue', 'Stack', 'Tree', 20),
('Data Structures: Which structure helps pick highest priority?', 'Not applicable', 'Queue only', 'Linked list', 'Heap', 20),
('Data Structures: Which structure maps keys to values?', 'Not applicable', 'Stack', 'Array list', 'Hash map', 20),
('Data Structures: Which structure stores indexed items?', 'Not applicable', 'Tree', 'Graph', 'Array', 20),
('DBMS: What can an index improve?', 'No valid choice', 'Screen brightness', 'Password length', 'Lookup speed', 19),
('DBMS: What does a foreign key reference?', 'No valid choice', 'A file name', 'A screen column', 'Another table key', 19),
('DBMS: What does a primary key ensure?', 'No valid choice', 'Fast display color', 'File compression', 'Unique row identity', 19),
('DBMS: What does a relational database use?', 'No valid choice', 'Slides', 'Audio tracks', 'Tables', 19),
('DBMS: What does atomicity mean?', 'No valid choice', 'Always encrypted', 'Sorted by date', 'All or nothing', 19),
('DBMS: What does normalization reduce?', 'No valid choice', 'User accounts', 'Network speed', 'Data redundancy', 19),
('DBMS: What helps recover lost data?', 'No valid choice', 'Query', 'Index', 'Backup', 19),
('DBMS: What is a table column also called?', 'No valid choice', 'Record', 'Query', 'Field', 19),
('DBMS: What is a table row also called?', 'No valid choice', 'Field', 'Schema', 'Record', 19),
('DBMS: What language commonly queries databases?', 'No valid choice', 'HTML', 'CSS', 'SQL', 19),
('English: What can an adverb describe?', 'Not applicable', 'A noun only', 'An article', 'A verb', 38),
('English: What does an adjective describe?', 'Not applicable', 'A punctuation mark', 'A preposition', 'A noun', 38),
('English: What is the doer in a sentence called?', 'Not applicable', 'Object', 'Clause', 'Subject', 38),
('English: What is the past tense of go?', 'Not applicable', 'Gone', 'Unknown', 'Went', 38),
('English: What is the plural of child?', 'Not applicable', 'No answer', 'Childes', 'Children', 38),
('English: Which mark commonly ends a question?', 'Not applicable', '.', ',', '?', 38),
('English: Which part of speech expresses an action?', 'Not applicable', 'No answer', 'Article', 'Verb', 38),
('English: Which part of speech names a person or thing?', 'Not applicable', 'Verb', 'Adverb', 'Noun', 38),
('English: Which word is a synonym for quick?', 'Not applicable', 'Late', 'Quiet', 'Fast', 38),
('English: Which word shows possession: their or there?', 'Not applicable', 'There', 'Theyre', 'Their', 38),
('Excel: What arranges data by order?', 'No valid choice', 'Filter', 'Freeze', 'Sort', 31),
('Excel: What character starts a formula?', 'No valid choice', '+', '#', '=', 31),
('Excel: What displays only matching rows?', 'No valid choice', 'Merge', 'Wrap', 'Filter', 31),
('Excel: What does cell A1 identify?', 'No valid choice', 'Row A, column 1', 'Sheet 1 only', 'Column A, row 1', 31),
('Excel: What is a spreadsheet intersection called?', 'No valid choice', 'Slide', 'Recordset', 'Cell', 31),
('Excel: What is an Excel file with sheets?', 'No valid choice', 'Worksheet cell', 'Database key', 'Workbook', 31),
('Excel: What visualizes data values?', 'No valid choice', 'Comment', 'Macro name', 'Chart', 31),
('Excel: Which direction do columns run?', 'No valid choice', 'Horizontally', 'Circularly', 'Vertically', 31),
('Excel: Which direction do rows run?', 'No valid choice', 'Vertically', 'Diagonally', 'Horizontally', 31),
('Excel: Which function adds values?', 'No valid choice', 'JOIN', 'COUNTIFONLY', 'SUM', 31),
('General: At what temperature does water freeze?', 'None of these', '10 C', '100 C', '0 C', 39),
('General: How many colors are traditionally in a rainbow?', 'None of these', 'No answer', '9', '7', 39),
('General: How many days are in a common year?', 'None of these', 'No answer', '366', '365', 39),
('General: What do bees make from nectar?', 'None of these', 'Silk', 'Wax paper', 'Honey', 39),
('General: What is the currency of Japan?', 'None of these', 'Won', 'Yuan', 'Yen', 39),
('General: What is the largest mammal?', 'None of these', 'Elephant', 'Giraffe', 'Blue whale', 39),
('General: What is the largest planet in our solar system?', 'None of these', 'No answer', 'Neptune', 'Jupiter', 39),
('General: What is the Sun?', 'None of these', 'A planet', 'A moon', 'A star', 39),
('General: Which chess piece moves in an L shape?', 'None of these', 'No answer', 'Rook', 'Knight', 39),
('General: Which planet is called the Red Planet?', 'None of these', 'No answer', 'Jupiter', 'Mars', 39),
('Geography: How many continents are commonly taught?', 'None of these', 'No answer', '9', '7', 36),
('Geography: On which continent is Japan?', 'None of these', 'Europe', 'Africa', 'Asia', 36),
('Geography: What imaginary line divides north and south?', 'None of these', 'Prime Meridian', 'Tropic of Cancer', 'Equator', 36),
('Geography: What is the capital of France?', 'None of these', 'No answer', 'Madrid', 'Paris', 36),
('Geography: What is the largest ocean?', 'None of these', 'No answer', 'Indian', 'Pacific', 36),
('Geography: What measures position east or west?', 'None of these', 'Latitude', 'Altitude', 'Longitude', 36),
('Geography: Where is Mount Everest?', 'None of these', 'Andes', 'Alps', 'Himalayas', 36),
('Geography: Which continent has the most countries?', 'None of these', 'Antarctica', 'South America', 'Africa', 36),
('Geography: Which is the largest hot desert?', 'None of these', 'Gobi', 'Mojave', 'Sahara', 36),
('Geography: Which is the smallest continent?', 'None of these', 'Europe', 'Antarctica', 'Australia', 36),
('Git: Which command combines branch changes?', 'None of these', 'git init', 'git status', 'git merge', 27),
('Git: Which command copies a remote repository?', 'None of these', 'git commit', 'git diff', 'git clone', 27),
('Git: Which command creates a branch?', 'None of these', 'git clean', 'git grep', 'git branch', 27),
('Git: Which command creates a repository?', 'None of these', 'git push', 'git show', 'git init', 27),
('Git: Which command downloads and integrates changes?', 'None of these', 'git push', 'git branch', 'git pull', 27),
('Git: Which command records a snapshot?', 'None of these', 'git status', 'git clone', 'git commit', 27),
('Git: Which command shows commit history?', 'None of these', 'git add', 'git fetchall', 'git log', 27),
('Git: Which command shows working tree state?', 'None of these', 'git reset', 'git tag', 'git status', 27),
('Git: Which command stages changes?', 'None of these', 'git log', 'git pull', 'git add', 27),
('Git: Which command uploads local commits?', 'None of these', 'git log', 'git init', 'git push', 27),
('History: In which year did humans first land on the Moon?', 'No valid choice', 'No answer', '1979', '1969', 37),
('History: In which year did the Berlin Wall fall?', 'No valid choice', 'No answer', '1999', '1989', 37),
('History: In which year did World War II end?', 'No valid choice', 'No answer', '1950', '1945', 37),
('History: When was the US Declaration of Independence adopted?', 'No valid choice', '1789', '1812', '1776', 37),
('History: Where did the Industrial Revolution begin?', 'No valid choice', 'Brazil', 'Australia', 'Britain', 37),
('History: Where did the Renaissance begin?', 'No valid choice', 'Japan', 'Canada', 'Italy', 37),
('History: Which civilization built the Colosseum?', 'No valid choice', 'Mayan', 'Incan', 'Roman', 37),
('History: Which river was central to ancient Egypt?', 'No valid choice', 'Danube', 'Amazon', 'Nile', 37),
('History: Who developed the movable-type printing press in Europe?', 'No valid choice', 'Newton', 'Galileo', 'Gutenberg', 37),
('History: Who was the first US president?', 'No valid choice', 'Abraham Lincoln', 'John Adams', 'George Washington', 37),
('HTML: What does HTML stand for?', 'Not applicable', 'High Transfer Machine Link', 'Home Tool Markup List', 'HyperText Markup Language', 14),
('HTML: Which attribute holds a link URL?', 'Not applicable', 'No answer', 'alt', 'href', 14),
('HTML: Which attribute holds an image path?', 'Not applicable', 'No answer', 'link', 'src', 14),
('HTML: Which image attribute improves accessibility?', 'Not applicable', 'width', 'target', 'alt', 14),
('HTML: Which tag creates a link?', 'Not applicable', '<linktext>', '<href>', '<a>', 14),
('HTML: Which tag creates an unordered list?', 'Not applicable', 'No answer', '<li>', '<ul>', 14),
('HTML: Which tag is semantic for page top?', 'Not applicable', '<span>', '<b>', '<header>', 14),
('HTML: Which tag makes the largest heading?', 'Not applicable', '<head>', 'Unknown', '<h1>', 14),
('HTML: Which tag represents a paragraph?', 'Not applicable', '<para>', '<text>', '<p>', 14),
('HTML: Which tag sets the browser tab title?', 'Not applicable', '<caption>', '<meta>', '<title>', 14),
('Java: What does JDK provide?', 'Not applicable', 'Web hosting', 'Image editing', 'Java development tools', 11),
('Java: What runs Java bytecode?', 'Not applicable', 'HTML', ' BIOS', 'JVM', 11),
('Java: Which keyword creates an object?', 'Not applicable', 'make', 'class', 'new', 11),
('Java: Which keyword inherits a class?', 'Not applicable', 'No answer', 'inherits', 'extends', 11),
('Java: Which keyword prevents reassignment?', 'Not applicable', 'No answer', 'public', 'final', 11),
('Java: Which method starts a Java app?', 'Not applicable', 'No answer', 'run()', 'main()', 11),
('Java: Which statement prints to console?', 'Not applicable', 'console.write()', 'print.line()', 'System.out.println()', 11),
('Java: Which symbol ends a statement?', 'Not applicable', 'No answer', ',', ';', 11),
('Java: Which type stores text?', 'Not applicable', 'char', 'boolean', 'String', 11),
('Java: Which type stores true or false?', 'Not applicable', 'No answer', 'double', 'boolean', 11),
('JavaScript: What represents a web page document?', 'No valid choice', 'JVM', 'SQL', 'DOM', 16),
('JavaScript: Which brackets create an array?', 'No valid choice', 'No answer', '()', '[]', 16),
('JavaScript: Which brackets create an object?', 'No valid choice', 'No answer', '<>', '{}', 16),
('JavaScript: Which command writes to the console?', 'No valid choice', 'print.console()', 'echo()', 'console.log()', 16),
('JavaScript: Which keyword declares a changeable variable?', 'No valid choice', 'No answer', 'static', 'let', 16),
('JavaScript: Which keyword declares a function?', 'No valid choice', 'define', 'method', 'function', 16),
('JavaScript: Which keyword prevents reassignment?', 'No valid choice', 'let', 'Unknown', 'const', 16),
('JavaScript: Which method parses JSON text?', 'No valid choice', 'JSON.read()', 'JSON.open()', 'JSON.parse()', 16),
('JavaScript: Which object handles future results?', 'No valid choice', 'WindowBox', 'ThreadList', 'Promise', 16),
('JavaScript: Which operator compares value and type?', 'No valid choice', 'No answer', '=', '===', 16),
('Linux: What does ~ usually represent?', 'Not applicable', 'Root file', 'Temporary drive', 'Home directory', 26),
('Linux: Which command changes directory?', 'Not applicable', 'mkdir', 'grep', 'cd', 26),
('Linux: Which command changes permissions?', 'Not applicable', 'chownall', 'grep', 'chmod', 26),
('Linux: Which command creates a directory?', 'Not applicable', 'rmdir', 'touchfile', 'mkdir', 26),
('Linux: Which command lists directory contents?', 'Not applicable', 'mv', 'pwd', 'ls', 26),
('Linux: Which command removes a file?', 'Not applicable', 'cp', 'pwd', 'rm', 26),
('Linux: Which command runs with elevated privilege?', 'Not applicable', 'home', 'list', 'sudo', 26),
('Linux: Which command searches text patterns?', 'Not applicable', 'ls', 'cd', 'grep', 26),
('Linux: Which command shows current directory?', 'Not applicable', 'cd', 'cat', 'pwd', 26),
('Linux: Which tool installs packages on Debian?', 'Not applicable', 'npm', 'pip', 'apt', 26),
('Math: How many degrees are in a triangle?', 'Not applicable', 'No answer', '360', '180', 32),
('Math: Square perimeter with side 4?', 'Not applicable', 'No answer', '20', '16', 32),
('Math: What decimal equals one half?', 'Not applicable', 'No answer', '2.0', '0.5', 32),
('Math: What is 15 percent of 200?', 'Not applicable', 'No answer', '40', '30', 32),
('Math: What is 2 to the power of 5?', 'Not applicable', 'No answer', '64', '32', 32),
('Math: What is 7 multiplied by 8?', 'Not applicable', 'No answer', '64', '56', 32),
('Math: What is pi approximately?', 'Not applicable', '2.14', '4.13', '3.14', 32),
('Math: What is the median of 2, 4, 9?', 'Not applicable', 'No answer', '9', '4', 32),
('Math: What is the square root of 81?', 'Not applicable', 'No answer', '11', '9', 32),
('Math: Which number is prime?', 'Not applicable', 'No answer', '15', '13', 32),
('Networks: What does a port identify?', 'Not applicable', 'A computer screen', 'A file format', 'An application endpoint', 23),
('Networks: What does HTTPS add?', 'Not applicable', 'Compression only', 'Extra pixels', 'Encryption', 23),
('Networks: What forwards traffic between networks?', 'Not applicable', 'Keyboard', 'Scanner', 'Router', 23),
('Networks: What identifies a device on an IP network?', 'Not applicable', 'File path', 'User name', 'IP address', 23),
('Networks: What identifies a network interface in hardware?', 'Not applicable', 'URL', 'DNS name', 'MAC address', 23),
('Networks: What is a network in a small area?', 'Not applicable', 'WAN', 'URL', 'LAN', 23),
('Networks: What maps domain names to IP addresses?', 'Not applicable', 'HTML', 'FTP', 'DNS', 23),
('Networks: Which protocol has lower delivery overhead?', 'Not applicable', 'TCP', 'HTTPS', 'UDP', 23),
('Networks: Which protocol provides reliable delivery?', 'Not applicable', 'UDP', 'ARP', 'TCP', 23),
('Networks: Which protocol serves web pages?', 'Not applicable', 'SMTP', 'SSH', 'HTTP', 23),
('No. of consonant in english language is..', '20', '22', '28', '21', 6),
('No. of vowels in english language is..', '3', '4', '7', '5', 6),
('OS: Is RAM volatile or permanent?', 'No valid choice', 'Permanent', 'Optical', 'Volatile', 22),
('OS: What assigns CPU time to processes?', 'No valid choice', 'Firewall', 'Compiler', 'Scheduler', 22),
('OS: What can virtual memory use as overflow?', 'No valid choice', 'Printer ink', 'CPU cache only', 'Disk storage', 22),
('OS: What is a running program called?', 'No valid choice', 'Folder', 'Packet', 'Process', 22),
('OS: What is a thread?', 'No valid choice', 'Storage drive', 'Network cable', 'Unit of execution', 22),
('OS: What is deadlock?', 'No valid choice', 'A full disk', 'A deleted file', 'Processes wait indefinitely', 22),
('OS: What is the core of an operating system?', 'No valid choice', 'Browser', 'Compiler', 'Kernel', 22),
('OS: What organizes files and folders?', 'No valid choice', 'Web server', 'Text editor', 'File system', 22),
('OS: What saves and restores process state?', 'No valid choice', 'Formatting', 'Compilation', 'Context switch', 22),
('OS: Which is an open-source operating system?', 'No valid choice', 'Photoshop', 'Excel', 'Linux', 22),
('PHP: What is the usual file extension?', 'Not applicable', '.ph', '.phtmlx', '.php', 17),
('PHP: What symbol prefixes a variable?', 'Not applicable', 'No answer', '@', '$', 17),
('PHP: Which array contains POST form data?', 'Not applicable', 'No answer', '$_FILES', '$_POST', 17),
('PHP: Which function runs a MySQL query?', 'Not applicable', 'mysql_run()', 'sql_exec()', 'mysqli_query()', 17),
('PHP: Which keyword begins a line comment?', 'Not applicable', '**', '##', '//', 17),
('PHP: Which language construct outputs text?', 'Not applicable', 'printline', 'console.log', 'echo', 17),
('PHP: Which opening tag starts PHP code?', 'Not applicable', '<php>', '<?', '<?php', 17),
('PHP: Which operator compares value and type?', 'Not applicable', 'No answer', '=', '===', 17),
('PHP: Which operator joins strings?', 'Not applicable', 'No answer', '&', '.', 17),
('PHP: Which superglobal stores uploaded files?', 'Not applicable', 'No answer', '$_SESSION', '$_FILES', 17),
('Physics: At what temperature does water boil at sea level?', 'None of these', '50 C', '0 C', '100 C', 33),
('Physics: How fast does light travel in vacuum?', 'None of these', 'About 300 km/s', 'About 30 km/s', 'About 300000 km/s', 33),
('Physics: How is speed calculated?', 'None of these', 'Time divided by mass', 'Force times area', 'Distance divided by time', 33),
('Physics: What does sound need to travel?', 'None of these', 'A vacuum only', 'A magnet', 'A medium', 33),
('Physics: What force attracts objects to Earth?', 'None of these', 'Friction', 'Magnetism only', 'Gravity', 33),
('Physics: What is the SI unit of acceleration?', 'None of these', 'kg/m', 'J/s', 'm/s2', 33),
('Physics: What is the SI unit of current?', 'None of these', 'Volt', 'Ohm', 'Ampere', 33),
('Physics: What is the SI unit of energy?', 'None of these', 'Newton', 'Pascal', 'Joule', 33),
('Physics: What is the SI unit of force?', 'None of these', 'Joule', 'Watt', 'Newton', 33),
('Physics: What is the SI unit of mass?', 'None of these', 'Liter', 'Meter', 'Kilogram', 33),
('Python: What does len() return?', 'No valid choice', 'Memory size', 'Data type', 'Item count', 10),
('Python: What file extension is common?', 'No valid choice', 'No answer', '.cpp', '.py', 10),
('Python: What is the Boolean true value?', 'No valid choice', 'No answer', 'Unknown', 'True', 10),
('Python: Which collection is immutable?', 'No valid choice', 'No answer', 'dictionary', 'tuple', 10),
('Python: Which collection is mutable?', 'No valid choice', 'string', 'Unknown', 'list', 10),
('Python: Which keyword defines a function?', 'No valid choice', 'No answer', 'method', 'def', 10),
('Python: Which structure stores key-value pairs?', 'No valid choice', 'No answer', 'tuple', 'dict', 10),
('Python: Which symbol starts a comment?', 'No valid choice', 'No answer', '<!--', '#', 10),
('Python: Which tool installs packages?', 'No valid choice', 'npm', 'gem', 'pip', 10),
('Python: Who created Python?', 'No valid choice', 'Dennis Ritchie', 'Unknown', 'Guido van Rossum', 10),
('Security: HTTPS commonly uses which security protocol?', 'None of these', 'FTP', 'DNS', 'TLS', 24),
('Security: Is a cryptographic hash reversible?', 'None of these', 'Always yes', 'Only at night', 'Usually no', 24),
('Security: What does encryption do?', 'None of these', 'Deletes all data', 'Sorts files', 'Unreadable without its key', 24),
('Security: What does least privilege mean?', 'None of these', 'Admin access for all', 'No passwords', 'Only needed access', 24),
('Security: What does MFA require?', 'None of these', 'One short password', 'A larger monitor', 'Multiple proof factors', 24),
('Security: What filters network traffic?', 'None of these', 'Spreadsheet', 'Compiler', 'Firewall', 24),
('Security: What helps restore files after loss?', 'None of these', 'Cache', 'Cookie', 'Backup', 24),
('Security: What is phishing?', 'None of these', 'A data backup', 'A software update', 'A deceptive message', 24),
('Security: What securely stores passwords?', 'None of these', 'Public document', 'Clipboard history', 'Password manager', 24),
('Security: Why install security patches?', 'None of these', 'Add new fonts', 'Increase screen size', 'Fix known flaws', 24),
('SQL: Which clause filters rows?', 'None of these', 'ORDER BY', 'GROUP', 'WHERE', 18),
('SQL: Which clause sorts results?', 'None of these', 'SORT', 'ARRANGE', 'ORDER BY', 18),
('SQL: Which command adds a row?', 'None of these', 'UPDATE', 'ALTER', 'INSERT', 18),
('SQL: Which command changes existing rows?', 'None of these', 'INSERT', 'CREATE', 'UPDATE', 18),
('SQL: Which command reads rows?', 'None of these', 'FETCHDB', 'READ', 'SELECT', 18),
('SQL: Which command removes selected rows?', 'None of these', 'No answer', 'REMOVE', 'DELETE', 18),
('SQL: Which function counts rows?', 'None of these', 'TOTAL()', 'NUMBER()', 'COUNT()', 18),
('SQL: Which key uniquely identifies a row?', 'None of these', 'Foreign key', 'Index label', 'Primary key', 18),
('SQL: Which keyword checks for missing values?', 'None of these', '= EMPTY', 'IS ZERO', 'IS NULL', 18),
('SQL: Which operation combines related rows?', 'None of these', 'MERGEFILE', 'APPEND', 'JOIN', 18),
('Total no of letters in english language is..', '23', '24', '25', '26', 6),
('Web: What application displays web pages?', 'No valid choice', 'Compiler', 'Router', 'Browser', 28),
('Web: What does an API expose?', 'No valid choice', 'Screen pixels', 'Power outlets', 'Software endpoints', 28),
('Web: What is a web address called?', 'No valid choice', 'RAM', 'CPU', 'URL', 28),
('Web: What sends pages to a browser?', 'No valid choice', 'Spreadsheet', 'Keyboard', 'Web server', 28),
('Web: Which format represents structured data?', 'No valid choice', 'JPEG', 'MP3', 'JSON', 28),
('Web: Which language adds browser interactivity?', 'No valid choice', 'SQL', 'Bash', 'JavaScript', 28),
('Web: Which language structures page content?', 'No valid choice', 'CSS', 'SQL', 'HTML', 28),
('Web: Which language styles page content?', 'No valid choice', 'HTML', 'PHP', 'CSS', 28),
('Web: Which protocol transfers web requests?', 'No valid choice', 'SQL', 'SMTP', 'HTTP', 28),
('Web: Which side runs in the browser?', 'No valid choice', 'Server rack', 'Database', 'Client', 28),
('When a copy constructor may be called?', 'When an object of the class is', 'When an object of the class is', 'When an object is constructed ', 'All of the above', 5),
('Which of the following functions must use reference.', 'Assignment operator function', 'Destructor', 'Parameterized constructor', 'Copy Constructor', 5),
('Which of the following is FALSE about references in C++', 'References cannot be NULL', 'A reference must be initialize', 'Once a reference is created, i', 'References cannot refer to con', 5),
('Which of the following operators cannot be overloaded', '. (Member Access or Dot operat', '?: (Ternary or Conditional Ope', ':: (Scope Resolution Operator)', 'All of the above', 5),
('Which of the followings is/are automatically added to every class, if we do not write our own.', 'Copy Constructor', 'Assignment Operator', 'A constructor without any para', 'All of the above', 5),
('Who is the father of C language?', 'Bjarne Stroustrup', 'James A. Gosling  ', 'Dr. E.F. Codd', 'Dennis Ritchie  ', 4);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quizid` int(11) NOT NULL,
  `quizname` varchar(20) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `mail` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quizid`, `quizname`, `date_created`, `mail`) VALUES
(10, 'Python Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(11, 'Java Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(12, 'C Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(13, 'C++ Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(14, 'HTML Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(15, 'CSS Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(16, 'JavaScript', '2026-10-01 09:44:30', 'trp@gmail.com'),
(17, 'PHP Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(18, 'SQL Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(19, 'DBMS Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(20, 'Data Structures', '2026-10-01 09:44:30', 'trp@gmail.com'),
(21, 'Algorithms', '2026-10-01 09:44:30', 'trp@gmail.com'),
(22, 'Operating Systems', '2026-10-01 09:44:30', 'trp@gmail.com'),
(23, 'Computer Networks', '2026-10-01 09:44:30', 'trp@gmail.com'),
(24, 'Cybersecurity', '2026-10-01 09:44:30', 'trp@gmail.com'),
(25, 'Artificial Intel', '2026-10-01 09:44:30', 'trp@gmail.com'),
(26, 'Linux Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(27, 'Git Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(28, 'Web Development', '2026-10-01 09:44:30', 'trp@gmail.com'),
(29, 'Computer Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(30, 'Cloud Computing', '2026-10-01 09:44:30', 'trp@gmail.com'),
(31, 'Excel Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(32, 'Mathematics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(33, 'Physics Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(34, 'Chemistry Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(35, 'Biology Basics', '2026-10-01 09:44:30', 'trp@gmail.com'),
(36, 'Geography', '2026-10-01 09:44:30', 'trp@gmail.com'),
(37, 'World History', '2026-10-01 09:44:30', 'trp@gmail.com'),
(38, 'English Grammar', '2026-10-01 09:44:30', 'trp@gmail.com'),
(39, 'General Knowledge', '2026-10-01 09:44:30', 'trp@gmail.com');

--
-- Triggers `quiz`
--
DELIMITER $$
CREATE TRIGGER `ondeleteqs` AFTER DELETE ON `quiz` FOR EACH ROW delete from questions where questions.quizid=old.quizid
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `slno` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `quizid` int(11) NOT NULL,
  `mail` varchar(30) DEFAULT NULL,
  `totalscore` int(11) DEFAULT NULL,
  `remark` varchar(20) DEFAULT NULL,
  `custom_rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`slno`, `score`, `quizid`, `mail`, `totalscore`, `remark`, `custom_rank`) VALUES
(24, 2, 38, 'trp@gmail.com', 10, 'good', NULL);

--
-- Triggers `score`
--
DELIMITER $$
CREATE TRIGGER `remarks` BEFORE INSERT ON `score` FOR EACH ROW set NEW.remark = if(NEW.score = 0, 'bad', 'good')
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffid` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `phno` varchar(10) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `DOB` varchar(10) NOT NULL,
  `pw` varchar(200) NOT NULL,
  `dept` varchar(3) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffid`, `name`, `mail`, `phno`, `gender`, `DOB`, `pw`, `dept`, `avatar`) VALUES
('104', 'Prof. Tushar Parmar', 'trp@gmail.com', '7874989921', 'M', '1006-05-23', 'raw33v05StilU', 'CSE', 'images/avatars/staff_104_1790847267.png');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `usn` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `phno` varchar(10) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `DOB` varchar(10) NOT NULL,
  `pw` varchar(200) NOT NULL,
  `dept` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`usn`, `name`, `mail`, `phno`, `gender`, `DOB`, `pw`, `dept`) VALUES
('24CS002UG0', 'Chauhan Hardik', 'chauhan.hardik.ca.012@gmail.co', '7600772569', 'M', '2006-10-15', 'raxDiPAQh9LpU', 'ISE'),
('1KS20CS054', 'Manjunath R', 'rmanjunath7692@gmail.com', '6363358647', 'M', '2002-07-18', 'rayD.ut57ldEo', 'CSE'),
('105', 'Tushar Parmar', 'trp@gmail.com', '7874989921', 'M', '2005-01-23', 'raCVFMdZ4nGGY', 'CSE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD UNIQUE KEY `qs` (`qs`),
  ADD KEY `quizid` (`quizid`),
  ADD KEY `quizid_2` (`quizid`),
  ADD KEY `quizid_3` (`quizid`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quizid`),
  ADD KEY `mail` (`mail`);

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`slno`),
  ADD KEY `quizid` (`quizid`),
  ADD KEY `mail` (`mail`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`mail`),
  ADD UNIQUE KEY `mail` (`mail`,`phno`),
  ADD UNIQUE KEY `staffid` (`staffid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`mail`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `phno` (`phno`),
  ADD UNIQUE KEY `usn` (`usn`),
  ADD KEY `dept` (`dept`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quizid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `slno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`mail`) REFERENCES `staff` (`mail`) ON DELETE CASCADE;

--
-- Constraints for table `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`quizid`) REFERENCES `quiz` (`quizid`) ON DELETE CASCADE,
  ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`mail`) REFERENCES `student` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
