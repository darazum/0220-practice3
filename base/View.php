<?php
namespace Base;

class View
{
    private $templatePath;

    public function setTemplatePath(string $path): self
    {
        $this->templatePath = $path;
        return $this;
    }

    public function render(string $tplName, array $data = [])
    {
        extract($data);
        include $this->templatePath . DIRECTORY_SEPARATOR . $tplName;
    }
}