<?php
namespace Ecommerce121\Core\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Command\Command;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Module\ModuleList\Loader;

/**
 * Class ModuleDeleteCommand
 * @package Ecommerce121\Core\Console\Command
 */
class ModuleDeleteCommand extends Command
{
    /**
     * Input parameter
     */
    const INPUT_KEY_REMOVE_MODULE = 'name';

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $setup;


    /**
     * ModuleDeleteCommand constructor.
     * @param \Magento\Framework\App\ResourceConnection $setup
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $setup,
        DeploymentConfig $deploymentConfig,
        DeploymentConfig\Writer $writer,
        Loader $loader)
    {
        $this->setup = $setup;
        $this->deploymentConfig = $deploymentConfig;
        $this->writer = $writer;
        $this->loader = $loader;
        parent::__construct();
    }

    /**
     * set up
     */
    protected function configure()
    {
        $options = [
            new InputOption(self::INPUT_KEY_REMOVE_MODULE, null, InputOption::VALUE_REQUIRED, 'name')];

        $this->setName('module:121ecommerce:delete')->setDescription('Delete module from table setup module and config.php')->setDefinition($options);
        parent::configure();
    }

    /**
     * Execute command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $input->getOption(self::INPUT_KEY_REMOVE_MODULE);
        $message = $this->validate($module);
        if (!empty($message)) {
            $output->writeln($message);
            return Cli::RETURN_FAILURE;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'You are about to remove an entry from setup module table. Are you sure?[y/N]',
            false
        );

        if (!$helper->ask($input, $output, $question) && $input->isInteractive()) {
            return Cli::RETURN_FAILURE;
        }

        $connection = $this->setup->getConnection();
        $tableName  = $this->setup->getTableName('setup_module');
        $result = $connection->delete($tableName,'module='."'".$module."'");
        if ($result > 0) {
            $this->removeModuleFromConfig($output, [$module]);
        } else {
            $output->writeln("<error>Module: $module does not exist.</error>");
        }
    }

    /**
     * Removes module from deployment configuration
     *
     * @param OutputInterface $output
     * @param string[] $modules
     * @return void
     */
    public function removeModuleFromConfig(OutputInterface $output, array $modules)
    {
        $output->writeln(
            '<info>Module ' . implode(', ', $modules) .  ' was removed successfully.</info>'
        );
        $configuredModules = $this->deploymentConfig->getConfigData(
            \Magento\Framework\Config\ConfigOptionsListConstants::KEY_MODULES
        );
        $existingModules = $this->loader->load($modules);
        $newModules = [];
        foreach (array_keys($existingModules) as $module) {
            $newModules[$module] = isset($configuredModules[$module]) ? $configuredModules[$module] : 0;
        }
        $this->writer->saveConfig(
            [
                \Magento\Framework\Config\File\ConfigFilePool::APP_CONFIG =>
                    [\Magento\Framework\Config\ConfigOptionsListConstants::KEY_MODULES => $newModules]
            ],
            true
        );
    }

    /**
     * Validate input entry
     * @param $module
     * @return string
     */
    protected function validate($module)
    {
        $message = '';
        if (empty($module)) {
            $message = '<error>You must specify the name of the module.</error>';
        }
        return $message;
    }
}
