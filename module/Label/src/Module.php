<?php
namespace Label;

/**
 * Module
 * @author bojan
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
