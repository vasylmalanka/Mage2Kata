<?php declare(strict_types=1);

namespace Mage2Kata\ActionController\Test\Integration;

use Mage2Kata\ActionController\Controller\Index\Index;
use Magento\Framework\App\Route\ConfigInterface as RouteConfig;
use Magento\Framework\App\Router\Base as BaseRouter;
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\Request;
use PHPUnit\Framework\TestCase;

class RouteConfigTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();
    }

    /**
     * @magentoAppArea frontend
     */
    public function testTheModuleRegistersMage2KataFrontname(): void
    {
        /** @var RouteConfig $routeConfig */
        $routeConfig = $this->objectManager->create(RouteConfig::class);

        $this->assertContains(
            'Mage2Kata_ActionController',
            $routeConfig->getModulesByFrontName('mage2kata_actioncontroller')
        );
    }

    /**
     * @magentoAppArea frontend
     */
    public function testTheMage2KataIndexActionCanBeFound(): void
    {
        $this->markTestSkipped();

        /** @var Request $request */
        $request = $this->objectManager->create(Request::class);
        $request->setModuleName('mage2kata_actioncontroller');
        $request->setControllerName('index');
        $request->setActionName('index');

        /** @var BaseRouter $baseRouter */
        $baseRouter = $this->objectManager->create(BaseRouter::class);

        $expectedAction = Index::class;
        $this->assertInstanceOf($expectedAction, $baseRouter->match($request));
    }
}