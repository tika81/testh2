<?php
namespace Core\Resource;

/**
 * Write Resource Interface
 * @author TikaLT
 */
interface WriteResourceInterface
{
    public function add();
    public function edit($id);
}
