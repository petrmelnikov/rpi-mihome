<?php

namespace App;

class MiioWrapper {

    const MIPLUG_EXECUTABLE = 'miplug';
    const YEELIGHT_EXECUTABLE = 'miiocli yeelight';

    private $homeDirectoryPath;
    private $debugCmds;

    public function __construct() {
        $this->homeDirectoryPath = trim(shell_exec('echo ~'));
        $this->debugCmds = $_ENV['DEBUG_CMD'] ?? false;
    }

    private function shellExec(string $cmd): ?string {
        $exportLocalBinPath = 'export PATH=$PATH:'.$this->homeDirectoryPath.'/.local/bin;';
        $fullCmd = $exportLocalBinPath.$cmd;
        if ($this->debugCmds) {
            echo $fullCmd;
        }
        return shell_exec($fullCmd);
    }

    public function getDevicePowerStateByModel(string $ip, string $token, string $model) {
        $executable = $this->getExecutableByModel($model);
        return $this->getDevicePowerState($ip, $token, $executable);
    }

    private function getExecutableByModel(string $model): string {
        if ($model === 'chuangmi.plug.m1') {
            return self::MIPLUG_EXECUTABLE;
        } elseif (false !== stristr($model, 'yeelink.light')) {
            return self::YEELIGHT_EXECUTABLE;
        }
    }

    private function getDevicePowerState(string $ip, string $token, string $commandExecutable): ?bool {
        $result = $this->shellExec($commandExecutable.' --ip '.$ip.' --token '.$token.' status');
        if (false === stristr($result, 'power: ')) {
            return null;
        } else {
            if (false !== stristr($result, 'power: true')) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function toggleSwitch($ip, $token, $model) {
        if ($model === 'chuangmi.plug.m1') {
            $miPowerPlugIsOn = $this->getDevicePowerState($ip, $token, self::MIPLUG_EXECUTABLE);
            if (null === $miPowerPlugIsOn) {
                return;
            }
            if ($miPowerPlugIsOn) {
                $param = 'off';
            } else {
                $param = 'on';
            }
            $command = self::MIPLUG_EXECUTABLE;
        } elseif (false !== stristr($model, 'yeelink.light')) {
            $param = 'toggle';
            $command = self::YEELIGHT_EXECUTABLE;
        }
        $this->shellExec($command.' --ip '.$ip.' --token '.$token.' '.$param);
    }

    public function on($ip, $token, $model) {
        return $this->setState($ip, $token, $model, 'on');
    }

    public function off($ip, $token, $model) {
        return $this->setState($ip, $token, $model, 'off');
    }

    public function setState($ip, $token, $model, $param) {
        if ($model === 'chuangmi.plug.m1') {
            $command = self::MIPLUG_EXECUTABLE;
        } elseif (false !== stristr($model, 'yeelink.light')) {
            $command = self::YEELIGHT_EXECUTABLE;
        }
        $this->shellExec($command.' --ip '.$ip.' --token '.$token.' '.$param);
    }
}