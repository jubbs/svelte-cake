<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'username' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-07-08 04:39:27',
                'modified' => '2023-07-08 04:39:27',
                'roles_id' => 1,
                'phone' => 'Lorem ipsum dolor sit amet',
                'inactive' => 1,
                'full_name' => 'Lorem ipsum dolor sit amet',
                'deleted' => 1,
                'hours_per_week' => 1.5,
            ],
        ];
        parent::init();
    }
}
