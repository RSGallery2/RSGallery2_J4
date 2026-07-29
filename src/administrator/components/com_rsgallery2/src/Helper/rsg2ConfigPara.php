<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2023-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;


/** following situations are supported
 *
 * - get config list reads parameter from Db extension table
 * - params after installation: DB extension table is empty ?
 * - write parameter should check config.xml for valid item names
 * - Write of single should add not used items in config.xml -> merge
 * - Reset DB extension table to XML
 *
 * On construction the different sources are read into separate registry variables
 */

class rsg2ConfigPara
{
    private string $componentName = 'com_rsgallery2';

    private string $configXmlFilePath;
    private Registry $configXmlParams;

    // From Db extension table
    private Registry $configDbParams;

    // Merged XML with DB parameter needed for writing only valid names
    private Registry $mergedConfigParam;

//    private array $configXmlNames = [];

    public function __construct(string $configXmlFilePath) {
        $this->configXmlFilePath = $configXmlFilePath;

    }

    /**
     * Extract parameter from config.xml and from extension DB table
     * Merge db config parameter into xml file parameter
     * This is part of cli
     *
     * @since version
     */
    public function extractConfigParam ()
    {
        $this->configXmlParams = $this->read_default_xml_configParam ();
//        $this->configXmlNames = $this->configXmlNames($this->configXmlParams);

        $this->configDbParams = $this->read_db_configParam();

        $configParam =
        $this->mergedConfigParam =  $this->configXmlParams->merge($this->configDbParams);

        return $configParam;
    }

    /**
     * config.xml
     *
     * @since version
     */
    private function read_default_xml_configParam (): Registry
    {
        $configXmlParams = new Registry();

        if (file_exists($this->configXmlFilePath)) {
            $xml = simplexml_load_file($this->configXmlFilePath);

            $fieldElements = $xml->xpath('.//field');

            if (!empty($fieldElements)) {

                foreach ($fieldElements as $field) {

                    $attributes = $field->attributes();

                    $name = (string) $attributes->name;
                    $value = (string) $attributes->default;

                    $configXmlParams->set($name, $value);
                }
            }
        }

        return $configXmlParams;
    }

    /**
     * db extension parameter table
     *
     * @since version
     */
    private function read_db_configParam ()
    {
        $component = ComponentHelper::getComponent($this->componentName);
        if (empty($component)) {
            throw new \Exception($this->componentName . "  component not found. Is it installed ?");
        }

        $config = $component->getParams();
        if (empty($config)) {
            throw new \Exception($this->componentName . "  parameter not found. ");
        }

        $testCount = $config->count();
        if ($config->count() == 0) {
            $testCount = $testCount * -1;
        }

        return $config;
    }

    /**
     * Extract names/values as separate arrays from given registry
     *
     * @param   Registry  $configParams
     *
     * @return array
     */
    public static function namesValuesArrays(Registry $configParams)
    {
        $names = [];
        $values = [];

        foreach ($configParams->toArray() as $configName => $configValue) {
            $names[] = $configName;
            $values[] = $configValue;
        }

        return [$names, $values];
    }

//    /**
//     * Extract names from given registry
//     *
//     * @param   Registry  $configParams
//     *
//     * @return array
//     */
//    private function configNames(Registry $configParams)
//    {
//        $configNames = [];
//
//        foreach ($configParams->toArray() as $configName => $configValue) {
//            $configNames[] = $configName;
//        }
//
//        return $configNames;
//    }
//
//    /**
//     * Extract values from given registry
//     *
//     * @param   Registry  $configParams
//     *
//     * @return array
//     *
//     * @since version
//     */
//    public function getConfigValues (Registry $configParams) {
//        $configValues = [];
//
//        foreach ($configParams->toArray() as $configName => $configValue) {
//            $configValues[] = $configValue;
//        }
//
//        return $configValues;
//    }

    /**
     * Complete list of configuration parameters from config.xml file
     * @return Registry
     *
     * @since version
     */
    public function getConfigXmlParameter () {
        return $this->configXmlParams;
    }

    /**
     * Complete list of configuration parameters from config.xml file
     * @return Registry
     *
     * @since version
     */
    public function getConfigDbParameter () {
        return $this->configDbParams;
    }

    /**
     * Merged XML with DB parameter needed for writing only valid names
     * @return Registry
     *
     * @since version
     */
    public function getConfigMergedParameter () {
        return $this->mergedConfigParam;
    }

    /**
     * Actual parameter k nown to the extension
     * @return Registry
     *
     * @since version
     */
    public function getConfigParameter () {
        return $this->getConfigDbParameter ();
    }

    /**
     *
     * @return Registry
     *
     * @since version
     */
    public function getDbParameter () {
        return $this->configDbParams;
    }

    /**
     * Plan B direct access of __extension table item
     * read configuration from DB
     *
     * @return array|mixed
     *
     * @since  5.1.0
     */
    public function readDbExtensionParaDirect()
    {
        $params = [];

        try {
            // read the existing component value(s)
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db
                ->createQuery()
                ->select('params')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote($this->componentName));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();
            if (!empty($jsonStr)) {
                $params = json_decode((string)$jsonStr, true);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'readDbExtensionParaDirect: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
            throw new \Exception($OutTxt);
        }

        return $params;
    }

    /**
     * Direct save to the database
     *
     * @since version
     */
    public function saveDbParams (Registry $params)
    {
        $test = (string) $params;

        $db = Factory::getContainer()->get(DatabaseInterface::class);

        return $db->setQuery(
            'UPDATE #__extensions'
            . ' SET params = ' . $db->quote((string)$params)
            . ' WHERE element = ' . $db->quote($this->componentName),
        )->execute();


    }

}
