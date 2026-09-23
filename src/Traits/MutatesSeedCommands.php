<?php

/*
 * This file is part of the hyn/multi-tenant package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/hyn/multi-tenant
 */

namespace Hyn\Tenancy\Traits;

use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Database\Connection;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Symfony\Component\Console\Input\InputOption;

trait MutatesSeedCommands
{
    use AddWebsiteFilterOnCommand;
    /**
     * @var WebsiteRepository
     */
    private $websites;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Resolver $resolver)
    {
        parent::__construct($resolver);

        $this->setName('tenancy:' . $this->getName());
        $this->mutateDefinition();

        $this->websites = app(WebsiteRepository::class);
        $this->connection = app(Connection::class);
    }

    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->input->setOption('force', true);
        $this->input->setOption('database', $this->connection->tenantName());

        $this->processHandle();
    }

    /**
     * Adds the website filter and the configured tenant seeder to the definition.
     *
     * Laravel 13 declares db:seed through a signature instead of getOptions(),
     * so the definition is mutated directly to support both styles.
     */
    private function mutateDefinition(): void
    {
        $definition = $this->getDefinition();

        $definition->addOption(new InputOption(...$this->addWebsiteOption()));

        if ($seedClass = config('tenancy.db.tenant-seed-class')) {
            $definition->getOption('class')->setDefault($seedClass);
        }
    }
}
