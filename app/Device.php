<?php

namespace App;


class Device
{
    const MIPLUG_EXECUTABLE = 'miplug';
    const YEELIGHT_EXECUTABLE = 'miiocli yeelight';

    private $id;
    private $ip;
    private $name;
    private $model;
    private $token;
    private $executable;
    private $rawStatus = null;

    public function __construct(array $data)
    {
        foreach($data as $name => $value) {
            if (property_exists($this, $name)) {
                $setter = 'set'.ucfirst($name);
                $this->$setter($value);
            }
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getExecutable(): string
    {
        if (!isset($this->executable)) {
            if ($this->model === 'chuangmi.plug.m1') {
                $this->executable = self::MIPLUG_EXECUTABLE;
            } elseif (false !== stristr($this->model, 'yeelink.light')) {
                $this->executable = self::YEELIGHT_EXECUTABLE;
            } else {
                throw new \Exception('Unknown device! Can\'t detect executable!');
            }
        }
        return $this->executable;
    }

    public function setRawStatus(string $rawStatus): self
    {
        $this->rawStatus = $rawStatus;
        return $this;
    }

    public function getPowerState(): ?bool {
        if (false === stristr($this->rawStatus, 'power: ')) {
            return null;
        } else {
            if (false !== stristr($this->rawStatus, 'power: true')) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getBrightness(): ?int {
        if (false === stristr($this->rawStatus, 'brightness: ')) {
            return null;
        } else {
            preg_match('/(brightness:\s?)(\d+)/i', $this->rawStatus, $matches);
            return $matches[2] ?? null;
        }
    }
}