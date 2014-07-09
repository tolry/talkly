# Doctrine Migration File Generated on 2014-07-09 22:07:07
# Migrating from 20140705163723 to 20140709220056

# Version 20140709220056
ALTER TABLE Comment ADD feedback TINYINT(1) NOT NULL;
UPDATE Comment SET feedback = 0;
