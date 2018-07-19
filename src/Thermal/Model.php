<?php

namespace Thermal;

use Thermal\Profile\Profile;
use Thermal\Profile\EscBema;
use Thermal\Profile\EscPOS;

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
            } else {
                if (!isset($data['models'][$name])) {
                    throw new \Exception(sprintf('Printer model "%s" not supported', $name), 404);
                }
                $capabilities = $data['models'][$name];
                $capabilities['model'] = $name;
            }
            // fill inherited fields
            $profile = $capabilities['profile'];
            $profile = \is_array($profile) ? $profile : ['inherited' => $profile];
            $profile_name = $profile['inherited'];
            while (isset($profile['inherited'])) {
                $inherited = $profile['inherited'];
                unset($profile['inherited']);
                $parent = $data['profiles'][$inherited];
                $profile = \array_merge($parent, $profile);
            }
            $capabilities['profile'] = $profile;
            $this->profile = self::newProfile($profile_name, $capabilities);
        }
    }

    private static function newProfile($profile_name, $capabilities)
    {
        if ($profile_name == 'escbema') {
            return new EscBema($capabilities);
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
        return $data['models'];
    }

    public function getName()
    {
        return $this->profile->getName();
    }

    public function getProfile()
    {
        return $this->profile;
    }
}
