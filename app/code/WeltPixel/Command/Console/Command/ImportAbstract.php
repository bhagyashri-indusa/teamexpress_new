<?php
namespace WeltPixel\Command\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Magento\Framework\File\Csv;
use \Magento\Config\Model\ResourceModel\Config;
use \Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;

class ImportAbstract extends Command
{
    /**
     * @var Csv
     */
    protected $csvFile;

    /**
     * @var Config
     */
    protected $resourceConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ComponentRegistrarInterface
     */
    protected $componentRegistrar;

    /**
     * @var \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory
     */
    protected $themeFactory;

    /**
     * ExportConfigurationsCommand constructor.
     * @param Csv $csvFile
     * @param Config $resourceConfig
     * @param StoreManagerInterface $storeManager
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $themeFactory
     */
    public function __construct(
        Csv $csvFile,
        Config $resourceConfig,
        StoreManagerInterface $storeManager,
        ComponentRegistrarInterface $componentRegistrar,
        \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $themeFactory
    )
    {
        $this->csvFile = $csvFile;
        $this->resourceConfig = $resourceConfig;
        $this->storeManager = $storeManager;
        $this->componentRegistrar = $componentRegistrar;
        $this->themeFactory = $themeFactory;
        parent::__construct();
    }

    /**
     * @param array $csvData
     * @param \Magento\Store\Api\Data\StoreInterface $store
     */
    protected function importCsvData($csvData, $store)
    {
        $storeId = $store->getId();
        foreach ($csvData as $data) {
            if (!isset($data[0])) {
                continue;
            }
            $scopeId = $storeId;
            /** If GLOBAL store was used we import in DEFAULT SCOPE */
            if ($storeId != 0) {
                if ($data[2] == \Magento\Framework\App\Config::SCOPE_TYPE_DEFAULT) {
                    $scopeId = 0;
                } elseif ($data[2] == \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES) {
                    $scopeId = $store->getWebsiteId();
                }
            } else {
                $data[2] = \Magento\Framework\App\Config::SCOPE_TYPE_DEFAULT;
            }
            $this->resourceConfig->saveConfig($data[0], $data[1], $data[2], $scopeId);
        }
    }
}