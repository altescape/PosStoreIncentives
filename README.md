# POS Store Data

#### NOTE: Development is running on this repository rather than the sfm_production version

##### Data is being collected from POS at stores across Saudi, the data needs to be uploaded, checked, cleaned then imported as members into ExpressionEngine, once imported the members are then emailed with a link that automatically logs them in.

The data is being supplied as CSV and will be supplied through an API in the future - there are no specs for this yet.

The file, once uploaded through this application, goes through a number of processes to cleanse, check and output the data, they are as follows:

### Cleaning
1. The record is removed if email address is not valid according to RFC 822 standards

### Checking
1. The record is removed if a duplicate exists within the supplied data-set
2. The record is removed if an email supplied is being used by an existing member of SFM

### Output
1. All remaining records can be converted to valid XML for importing members to EE
2. All remaining records can be converted to XML for Cron Jobs - the only difference with the above is that it is placed into a cron directory and the name is always the same
3. All remaining records can be converted to valid JSON for importing members to EE
4. All remaining records can be converted to TXT for importing to Campaign Monitor

### Note:

Required directories are not included here, they should be as follows:

- __data_sets__
  - __bad__ to store bad data (not yet implemented
  - __cron_list_data__ to store cron job file, see point 2 of output
  - __formats__ this is where XML, JSON and TXT formats are saved
  - __original__ original file is saved here, though extension of .txt is added for some reason

### Requirements
- PHP 5.4
- PDO
- Composer
- Twig

