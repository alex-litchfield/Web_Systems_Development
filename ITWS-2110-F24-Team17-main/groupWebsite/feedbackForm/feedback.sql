CREATE TABLE `feedback` (
   `id` int AUTO_INCREMENT PRIMARY KEY,
   `fullName` varchar(255) NOT NULL,
   `email` varchar(255) NOT NULL,
   `firstVisit` varchar(10) NOT NULL,
   `whyVisit` varchar(255),
   `rating` int,
   `comments` varchar(255),
   submittedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);