<?php


namespace App;


class UrlParameterHelper
{
    private $params = [];
    private $paramsString = '';

    public function setParamsString(string $paramsString) {
        $this->paramsString = $paramsString;
        parse_str($paramsString, $params);
        $this->params = $params ?? [];
        return $this;
    }

    public function setParams(array $params) {
        $this->params = $params;
        $this->paramsString = http_build_query($this->params);
        return $this;
    }

    public function getParam(string $name) {
        return $this->params[$name] ?? null;
    }

    public function setParam(string $name, $value) {
        $this->params[$name] = $value;
        $this->paramsString = http_build_query($this->params);
        return $this;
    }

    public function getParamsString(): string {
        return $this->paramsString;
    }

    public function getParams(): array {
        return $this->params;
    }
}