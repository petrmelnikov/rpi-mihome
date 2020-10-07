<?php

namespace App;

class MiioWrapper {

    private $homeDirectoryPath;
    private $debugCmd;

    public function __construct() {
        $this->homeDirectoryPath = trim(shell_exec('echo ~'));
        $this->debugCmd = $_ENV['DEBUG_CMD'] === 'true' ? true : false;
    }

    private function shellExec(string $cmd): ?string {
        $exportLocalBinPath = 'export PATH=$PATH:'.$this->homeDirectoryPath.'/.local/bin;';
        $fullCmd = $exportLocalBinPath.$cmd;
        if ($this->debugCmd) {
            echo $fullCmd;
            die;
        }
        return shell_exec($fullCmd);
    }

    public function updateDeviceStatus(Device &$device) {
        $rawStatus = $this->shellExec($device->getExecutable().' --ip '.$device->getIp().' --token '.$device->getToken().' status');
        $device->setRawStatus($rawStatus ?? ''); //temporary solution
    }

    public function toggleSwitch(Device $device) {
        $model = $device->getModel();
        if ($model === 'chuangmi.plug.m1') {
            $this->updateDeviceStatus($device);
            $miPowerPlugIsOn = $device->getPowerState();
            if (null === $miPowerPlugIsOn) {
                return;
            }
            if ($miPowerPlugIsOn) {
                $param = 'off';
            } else {
                $param = 'on';
            }
        } elseif (false !== stristr($model, 'yeelink.light')) {
            $param = 'toggle';
        }
        $this->shellExec($device->getExecutable().' --ip '.$device->getIp().' --token '.$device->getToken().' '.$param);
    }

    public function on(Device $device) {
        return $this->setState($device, 'on');
    }

    public function off(Device $device) {
        return $this->setState($device, 'off');
    }

    private function setState(Device $device, $param) {
        $this->shellExec($device->getExecutable().' --ip '.$device->getIp().' --token '.$device->getToken().' '.$param);
    }
}