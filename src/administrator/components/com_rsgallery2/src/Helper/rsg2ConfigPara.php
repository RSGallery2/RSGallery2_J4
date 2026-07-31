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

    // from joomla component request
    private Registry $configCompParams;

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

        $this->configCompParams = $this->read_ComponentParam();
        $this->configDbParams   = $this->readDbExtensionParaDirect();

        $configParam = $this->mergeXmlWithCompParam ();

        return $configParam;
    }

    /**
     * Merge joomla component parameter into xml parameter
     * May be called instead of extractConfigParam when the merged shall be saved later
     *
     * @since version
     */
    public function mergeXmlWithCompParam () {

        // on first call
        if (empty($this->configXmlParams))
        {
            $this->configXmlParams = $this->read_default_xml_configParam();
        }
        // on first call
        if (empty($this->configCompParams))
        {
            $this->configCompParams = $this->read_ComponentParam();
        }

        $this->mergedConfigParam =  $this->configXmlParams->merge($this->configCompParams);

        return $this->mergedConfigParam;
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

//            $fields = $xml->xpath('descendant-or-self::field');
            $fields = $xml->xpath('.//field');

            if (!empty($fields)) {

                foreach ($fields as $field) {

                    // only default values are needed
                    if (!isset($field['default'])) {
                        continue;
                    }

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
    private function read_ComponentParam ()
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
    public function getConfigCompParameter () {
        return $this->configCompParams;
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
        return $this->getConfigCompParameter ();
    }

    /**
     *
     * @return Registry
     *
     * @since version
     */
    public function getDbParameter () {
        return $this->configCompParams;
    }

    /**
     * Plan B direct access of __extension table item
     * read configuration from DB
     *
     * @return array|mixed
     *
     * @since  5.1.0
     */
    public function readDbExtensionParaDirect(): Registry
    {
        $dbXmlParams = new Registry();

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
                $dbXmlParams = new Registry($params);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'readDbExtensionParaDirect: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
            throw new \Exception($OutTxt);
        }

        return $dbXmlParams;
    }

    /**
     * Direct save to the database
     *
     * @since version
     */
    public function saveDbParams (Registry $params): bool
    {
        $isWritten = false;
        // $test = (string) $params;

        $db = Factory::getContainer()->get(DatabaseInterface::class);

        $query = $db->createQuery()
            ->update($db->quoteName('#__extensions'))
            ->set($db->quoteName('params') . ' = ' . $db->quote((string)$params))
            ->where($db->quoteName('name') . ' = ' . $db->quote((string)$this->componentName));
        $db->setQuery($query);

        if ($db->execute()) {
            $isWritten = true;
        }

        return $isWritten;
    }

}
