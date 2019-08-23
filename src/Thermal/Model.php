<?php

namespace Thermal;

use Thermal\Profile\Daruma;
use Thermal\Profile\Diebold;
use Thermal\Profile\Elgin;
use Thermal\Profile\Bematech;
use Thermal\Profile\Epson;
use Thermal\Profile\Generic;
use Thermal\Profile\ControliD;
use Thermal\Profile\Perto;
use Thermal\Profile\Profile;
use Thermal\Profile\Dataregis;
use Thermal\Profile\Sweda;

class Model
{
    /**
     * Model profile
     *
     * @var Profile
     */
    private $profile;

    public function __construct($name)
    {
        if ($name instanceof Profile) {
            $this->profile = $name;
        } else {
            $data = self::loadCapabilities();
            if (is_array($name)) {
                $capabilities = $name;
                $name = isset($capabilities['model']) ? $capabilities['model'] : 'Unknow';
            } else {
                if (!isset($data['models'][$name])) {
                    throw new \Exception(sprintf('Printer model "%s" not supported', $name), 404);
                }
                $capabilities = $data['models'][$name];
            }
            list($profile, $capabilities) = self::expandCapabilities($name, $capabilities, $data);
            $this->profile = self::newProfile($profile, $capabilities);
        }
    }

    private static function expandCapabilities($model, $capabilities, $data)
    {
        $capabilities = \is_array($capabilities) ? $capabilities : ['profile' => $capabilities];
        $capabilities['model'] = $model;
        // fill inherited fields
        $profile = $capabilities['profile'];
        while (isset($capabilities['profile'])
            && isset($data['profiles'][$capabilities['profile']])
        ) {
            $inherited = $capabilities['profile'];
            unset($capabilities['profile']);
            $parent = $data['profiles'][$inherited];
            $capabilities = \array_merge($parent, $capabilities);
        }
        return [$profile, $capabilities];
    }

    /**
     * Instantiate new profile from name
     *
     * @param string $profile_name
     * @param array $capabilities
     * @return Profile
     */
    private static function newProfile($profile_name, $capabilities)
    {
        switch ($profile_name) {
            case 'bematech':
                return new Bematech($capabilities);

            case 'epson':
            case 'tmt20':
                return new Epson($capabilities);

            case 'elgin':
                return new Elgin($capabilities);

            case 'daruma':
                return new Daruma($capabilities);

            case 'diebold':
                return new Diebold($capabilities);

            case 'sweda':
                return new Sweda($capabilities);

            case 'dataregis':
                return new Dataregis($capabilities);

            case 'controlid':
                return new ControliD($capabilities);

            case 'perto':
                return new Perto($capabilities);

            case 'generic':
                return new Generic($capabilities);

            default:
                return new Epson($capabilities);
        }
    }

    /**
     * Load all  models and capabilities and return
     *
     * @return array
     */
    private static function loadCapabilities()
    {
        return require(__DIR__ . '/resources/capabilities.php');
    }

    /**
     * Get all models and capabilities
     *
     * @return array
     */
    public static function getAll()
    {
        $data = self::loadCapabilities();
        foreach ($data['models'] as $model => $capabilities) {
            list($profile, $capabilities) = self::expandCapabilities($model, $capabilities, $data);
            $data['models'][$model] = $capabilities;
            $data['models'][$model]['profile'] = $profile;
        }
        return $data['models'];
    }

    /**
     * Selected profile name
     *
     * @return string
     */
    public function getName()
    {
        return $this->profile->getName();
    }

    /**
     * Selected profile
     *
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
