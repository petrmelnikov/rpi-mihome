<?php

namespace App;


class Device
{
    const TYPE_MIPLUG = 'miplug';
    const TYPE_YEELIGHT = 'yeelight';
    const TYPE_HUMIDIFIER = 'humidifier';

    const EXECUTABLE = [
        self::TYPE_MIPLUG => 'plug_cli.py',
        self::TYPE_YEELIGHT => 'cli.py yeelight',
        self::TYPE_HUMIDIFIER => 'cli.py airhumidifiermiot',
    ];

    private $id;
    private $ip;
    private $name;
    private $model;
    private $token;
    private $type;
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

    public function getType(): string {
        if (!isset($this->type)) {
            if ($this->model === 'chuangmi.plug.m1') {
                $this->type = self::TYPE_MIPLUG;
            } elseif (false !== stristr($this->model, 'yeelink.light')) {
                $this->type = self::TYPE_YEELIGHT;
            } elseif ($this->model === 'zhimi.humidifier.ca4') {
                $this->type = self::TYPE_HUMIDIFIER;
            } else {
                throw new \Exception('Unknown device! Can\'t detect type!');
            }
        }
        return $this->type;
    }

    public function getExecutable(): string
    {
        return self::EXECUTABLE[$this->getType()];
    }

    public function setRawStatus(string $rawStatus): self
    {
        $this->rawStatus = $rawStatus;
        return $this;
    }

    public function getRawStatus(): ?string
    {
        return $this->rawStatus;
    }

    public function getStatus(): array {
        if (null === $this->getRawStatus()) {
            return [];
        }
        $pattern = '/(.+):\s(.+)\n?/';
        preg_match_all($pattern, $this->getRawStatus(), $matches);
        $result = [];
        foreach ($matches[1] as $key => $name) {
            $value = $matches[2][$key];
            if (!isset($result[$name])) {
                $result[$name] = $value;
            } else {
                $result[$name] = [$result[$name]];
                $result[$name][] = $value;
            }
        }
        return $result;
    }

    public function getStatusValue(string $valueName): string {
        $status = $this->getStatus();
        if (isset($status[$valueName])) {
            if (is_array($status[$valueName])) {
                foreach ($status[$valueName] as $value) {
                    if (!stristr($value, '°F')) {
                        return $value;
                    }
                }
            } else {
                return $status[$valueName];
            }
        }
        return '';
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