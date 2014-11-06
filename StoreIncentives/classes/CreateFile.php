<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 16:47
 */

namespace StoreIncentives;


class CreateFile {

    protected $export_data;
    protected $route_dir;
    protected $file_name;

    function __construct(Config $config, LatestFile $latestFile, ExportData $exportData)
    {
        $this->export_data = $exportData;
        $this->route_dir = $config->getRouteDir();
        $this->file_name = $latestFile->getLatestFileName();
    }

    public function buildFile($function, $directory, $file_extension, $file_name)
    {
        $file_name_part = pathinfo($file_name);
        $the_file = $function;
        $dir = $this->route_dir . $directory;
        $name = $file_name_part['filename'];
        $ext = "." . $file_extension;

        new Logger("FILE_CREAT", $dir . "/" . $name.$ext);

        file_put_contents($dir . '/' . $name.$ext, $the_file);
    }

    public function createCampaignMonitorFile()
    {
        $this->buildFile($this->export_data->campaignMonitor(), "formats", "txt", $this->file_name);
    }

    public function createXmlFile()
    {
        $this->buildFile($this->export_data->xml(), "formats", "xml", $this->file_name);
    }


    public function createJsonFile()
    {
        $this->buildFile($this->export_data->json(), "formats", "json", $this->file_name);
    }

    public function createXmlFileForCron()
    {
        $this->buildFile($this->export_data->xml(), "cron_list_data", "xml", "cron_data");
    }

    public function createBadLog()
    {
        $bad_data = $this->export_data->bad();

        if ($bad_data !== "null") {
            $this->buildFile($this->export_data->bad(), "bad", "json", $this->file_name);
        }
    }

    public function getBadFilePath()
    {
        return $this->route_dir . "bad/" . $this->file_name . ".json";
    }
}