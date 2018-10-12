<?php

namespace Thermal;

use Thermal\Profile\Daruma;
use Thermal\Profile\Diebold;
use Thermal\Profile\Elgin;
use Thermal\Profile\EscBema;
use Thermal\Profile\EscPOS;
use Thermal\Profile\EscMode;
use Thermal\Profile\Generic;
use Thermal\Profile\Perto;
use Thermal\Profile\Profile;

class Model
{
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
        while (isset($capabilities['profile'])) {
            $inherited = $capabilities['profile'];
            unset($capabilities['profile']);
            $parent = $data['profiles'][$inherited];
            $capabilities = \array_merge($parent, $capabilities);
        }
        return [$profile, $capabilities];
    }

    private static function newProfile($profile_name, $capabilities)
    {
        if ($profile_name == 'escbema') {
            return new EscBema($capabilities);
        }
        if ($profile_name == 'escmode') {
            return new EscMode($capabilities);
        }
        if ($profile_name == 'elgin') {
            return new Elgin($capabilities);
        }
        if ($profile_name == 'daruma') {
            return new Daruma($capabilities);
        }
        if ($profile_name == 'diebold') {
            return new Diebold($capabilities);
        }
        if ($profile_name == 'generic') {
            return new Generic($capabilities);
        }
        if ($profile_name == 'perto') {
            return new Perto($capabilities);
        }
        // default profile
        return new EscPOS($capabilities);
    }

    private static function loadCapabilities()
    {
        return require(__DIR__ . '/resources/capabilities.php');
    }

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

    public function getName()
    {
        return $this->profile->getName();
    }

    /**
     * @return \Thermal\Profile\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
