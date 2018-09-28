 CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `firstname` varchar(30) NOT NULL DEFAULT '',
  `lastname` varchar(255) NOT NULL DEFAULT '',
  `gender` char(1) NOT NULL DEFAULT '',
  `birthdate` date,
  `email` varchar(255) NOT NULL DEFAULT '',
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  `phone_number` varchar(255) NOT NULL DEFAULT '',
  `phone_number_verified` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`));


#  name
#  family_name
#  given_name
#  middle_name
#  nickname
#  preferred_username
#  profile
#  picture
#  website
#  gender
#  birthdate
#  zoneinfo
#  locale
#  updated_at.
#
#  email
#  email_verified
#  address
#  phone_number
#  phone_number_verified
#
#
