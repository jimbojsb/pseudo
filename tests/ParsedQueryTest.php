<?php
class ParsedQueryTest extends PHPUnit_Framework_TestCase
{
    public function testQueryHashing()
    {
        $sql = "SELECT foo FROM bar WHERE baz";
        $q = new \Pseudo\ParsedQuery($sql);
        $p = new \PHPSQLParser();
        $parsed = $p->parse($sql);
        $hashed = sha1(serialize($parsed));
        $this->assertEquals($hashed, $q->getHash());
    }

    public function testIsEquals()
    {
        $sql = "SELECT foo FROM bar WHERE baz";
        $q1 = new \Pseudo\ParsedQuery($sql);
        $q2 = new \Pseudo\ParsedQuery($sql);
        $this->assertTrue($q1->isEqualTo($q2));
        $this->assertTrue($q2->isEqualTo($q1));
        $this->assertTrue($q1->isEqualTo($sql));
    }
}