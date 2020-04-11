<?php declare(strict_types=1);
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Store\Test\Unit\Model\Resolver;

use Magento\Framework\App\ScopeInterface;
use Magento\Store\Model\Resolver\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test class for \Magento\Store\Model\Resolver\Store
 */
class StoreTest extends TestCase
{
    /**
     * @var Store
     */
    protected $_model;

    /**
     * @var MockObject
     */
    protected $_storeManagerMock;

    protected function setUp(): void
    {
        $this->_storeManagerMock = $this->createMock(StoreManagerInterface::class);

        $this->_model = new Store($this->_storeManagerMock);
    }

    protected function tearDown(): void
    {
        unset($this->_storeManagerMock);
    }

    public function testGetScope()
    {
        $scopeMock = $this->createMock(ScopeInterface::class);
        $this->_storeManagerMock
            ->expects($this->once())
            ->method('getStore')
            ->with(0)
            ->will($this->returnValue($scopeMock));

        $this->assertEquals($scopeMock, $this->_model->getScope());
    }

    public function testGetScopeWithInvalidScope()
    {
        $this->expectException('Magento\Framework\Exception\State\InitException');
        $scopeMock = new \StdClass();
        $this->_storeManagerMock
            ->expects($this->once())
            ->method('getStore')
            ->with(0)
            ->will($this->returnValue($scopeMock));

        $this->assertEquals($scopeMock, $this->_model->getScope());
    }
}
