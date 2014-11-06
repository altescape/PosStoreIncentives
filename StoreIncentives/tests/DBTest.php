<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 10:18
 */

namespace StoreIncentives;


#####################################################################################
#
# AUTOLOADER
# Autoload classes

require_once '../../loader.php';


#####################################################################################
#
# PHP SETTINGS
# Set time settings as using data() function

ini_set('auto_detect_line_endings', true);
ini_set('date.timezone', 'UTC');
ini_set('memory_limit', '256M');



class Run {

    function __construct()
    {

        #####################################################################################
        #
        # SET CONFIG
        # Set global configs

        $config = new Config();


        #####################################################################################
        #
        # SET DATA CONNECTIONS
        # For connection to SFM  database

        $conn = new Conn($config->getDbConn(), $config->getDbUser(), $config->getDbPswd());


        #####################################################################################
        #
        # CHECK FOR EXISTING SFM MEMBERS

        $ee_members = new ExistingMembers($conn);


        #####################################################################################
        #
        # CHECK DIRECTORIES EXIST
        # And if they don't exist, make them

        new Directories($config);


        #####################################################################################
        #
        # LOG A MESSAGE
        # Log message test

        new Logger("PROG_START", "Started process...");


        #####################################################################################
        #
        # GET LATEST FILE

        $latest_file = new LatestFile($config);


        #####################################################################################
        #
        # READ DATA

        $data = new ReadData($config, $latest_file);


        #####################################################################################
        #
        # VALIDATE DATA

        $valid_data = new ValidateData($data, $ee_members);


        #####################################################################################
        #
        # FORMAT DATA

        $formatted_data = new FormatData($valid_data);


        #####################################################################################
        #
        # EXPORT DATA
        # Exports data to variables

        $exported_data = new ExportData($valid_data, $formatted_data);


        #####################################################################################
        #
        # LOG GOOD DATA MESSAGE
        # Log message db write

        new Logger("GOOD_DATAS", count($valid_data->getData()) . " valid emails found.");


        #####################################################################################
        #
        # CREATE FILES

        $create_files = new CreateFile($config, $latest_file, $exported_data);

        # Create bad log first, then check for data or die
        $create_files->createBadLog();

        # Die here if no data
        if (empty($exported_data->data)) {
            new Logger("DATA_EMPTY", "No data to export");
            die;
        }

        # Data exists, export to files
        $create_files->createCampaignMonitorFile();
        $create_files->createXmlFile();
        $create_files->createJsonFile();
        $create_files->createXmlFileForCron();


        #####################################################################################
        #
        # SEND EMAIL FOR PROCESS START
        # That's it
        if ($config->getDebug() === false) {
            $email = new EmailRun(
              $valid_data->getBogusEmails(),
              $latest_file->getLatestFileName(),
              $valid_data->getExistingEmails(),
              $valid_data->getData()
            );
            $email->sendEmail();
        }


        #####################################################################################
        #
        # CAMPAIGN MONITOR
        # Create list, subscribers, campaign and send

        if ($config->getDebug() === false) {
            $cp_mon = new CampaignMonitor();

            $cp_mon->setSubscribers($exported_data->campaignMonitorForImport());
            $cp_mon->createList();
            $cp_mon->createCustomTextField("encrypted_email");
            $cp_mon->createCustomTextField("gender");
            $cp_mon->addSubscribers();
            $cp_mon->createCampaign();
            $cp_mon->sendCampaign();
        }


        #####################################################################################
        #
        # WRITE TO DB
        # db write

        $to_db = new DB(
          $conn,
          $valid_data->getBogusEmails(),        // Bogus emails
          $valid_data->getExistingEmails(),     // Existing members
          $valid_data->getData(),               // Valid data
          $latest_file->getLatestFileName()     // File name
        );

        // Insert to db
        $to_db->query();


        #####################################################################################
        #
        # LOG DB MESSAGE
        # Log message db write

        new Logger("WRIT_TO-DB", "Writing to database");


        #####################################################################################
        #
        # LOG END MESSAGE
        # Log message end

        new Logger("PROG_FINIS", "Finished process;", $config);

    }

}

new Run();
