<?php
use PHPUnit\Framework\TestCase;

class EarthenAuthHelperTest extends TestCase
{
    public function testGetNoEcobrickAlertEnglish()
    {
        $msg = getNoEcobrickAlert('en');
        $this->assertStringContainsString('No ecobrick could be found', $msg);
    }
}
