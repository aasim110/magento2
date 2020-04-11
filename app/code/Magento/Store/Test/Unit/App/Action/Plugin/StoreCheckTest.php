<?php declare(strict_types=1);
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Store\Test\Unit\App\Action\Plugin;

use Magento\Framework\App\Action\AbstractAction;
use Magento\Store\App\Action\Plugin\StoreCheck;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StoreCheckTest extends TestCase
{
    /**
     * @var StoreCheck
     */
    protected $_plugin;

    /**
     * @var StoreManagerInterface|MockObject
     */
    protected $_storeManagerMock;

    /**
     * @var Store|MockObject
     */
    protected $_storeMock;

    /**
     * @var AbstractAction|MockObject
     */
    protected $subjectMock;

    protected function setUp(): void
    {
        $this->_storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $this->_storeMock = $this->createMock(Store::class);
        $this->_storeManagerMock->expects(
            $this->any()
        )->method(
            'getStore'
        )->will(
            $this->returnValue($this->_storeMock)
        );
        $this->subjectMock = $this->getMockBuilder(AbstractAction::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->_plugin = new StoreCheck($this->_storeManagerMock);
    }

    public function testBeforeExecuteWhenStoreNotActive()
    {
        $this->expectException('Magento\Framework\Exception\State\InitException');
        $this->expectExceptionMessage('Current store is not active.');
        $this->_storeMock->expects($this->any())->method('isActive')->will($this->returnValue(false));
        $this->_plugin->beforeExecute($this->subjectMock);
    }

    public function testBeforeExecuteWhenStoreIsActive()
    {
        $this->_storeMock->expects($this->any())->method('isActive')->will($this->returnValue(true));
        $result = $this->_plugin->beforeExecute($this->subjectMock);
        $this->assertNull($result);
    }
}
