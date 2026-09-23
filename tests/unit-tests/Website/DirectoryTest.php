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

namespace Hyn\Tenancy\Tests\Website;

use Hyn\Tenancy\Tests\TestCase;
use Hyn\Tenancy\Website\Directory;
use Illuminate\Contracts\Foundation\Application;
use PHPUnit\Framework\Attributes\Test;

class DirectoryTest extends TestCase
{
    /**
     * @var Directory
     */
    protected $directory;

    protected function duringSetUp(Application $app)
    {
        $this->directory = $app->make(Directory::class);
    }

    #[Test]
    public function can_switch_website()
    {
        $this->setUpWebsites(true);

        $this->assertNull($this->directory->getWebsite());

        $this->directory->setWebsite($this->website);

        $this->assertEquals($this->website, $this->directory->getWebsite());
    }
}
