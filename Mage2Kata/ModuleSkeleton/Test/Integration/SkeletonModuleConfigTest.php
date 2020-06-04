<?php declare(strict_types=1);

namespace Mage2Kata\ModuleSkeleton\Test\Integration;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleList;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\DeploymentConfig\Reader as ConfigReader;

class SkeletonModuleConfigTest extends TestCase
{
    private $moduleName = 'Mage2Kata_ModuleSkeleton';

    public function testTheModuleIsRegistered(): void
    {
        $registrar = new ComponentRegistrar();
        $this->assertArrayHasKey($this->moduleName, $registrar->getPaths(ComponentRegistrar::MODULE));
    }

    public function testTheModuleIsConfiguredAndEnabledInTheTestEnv(): void
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class);

        $this->assertTrue($moduleList->has($this->moduleName), 'The module is not enabled in the test env');
    }

    public function testTheModuleIsConfiguredAndEnabledInTheRealEnv(): void
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        /** @var DirectoryList $dirList */
        $dirList = $objectManager->create(DirectoryList::class, ['root' => BP]);

        /** @var ConfigReader $configReader */
        $configReader = $objectManager->create(ConfigReader::class, ['dirList' => $dirList]);

        /** @var DeploymentConfig $deploymentConfig */
        $deploymentConfig = $objectManager->create(DeploymentConfig::class, ['reader' => $configReader]);

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class, ['config' => $deploymentConfig]);

        $this->assertTrue($moduleList->has($this->moduleName), 'The module is not enabled in the real env');
    }
}