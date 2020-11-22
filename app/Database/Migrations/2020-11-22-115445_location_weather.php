<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LocationWeather extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'id'          => [
                    'type'           => 'INT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'country'     => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'city'        => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'temperature' => [
                    'type' => 'DECIMAL(4,1)',
                ],
                'created_at'  => [
                    'type'    => 'TIMESTAMP',
                    'comment' => 'Data utworzenia',
                    'null'    => true,
                ],
                'updated_at'  => [
                    'type'    => 'TIMESTAMP',
                    'comment' => 'Data edycji',
                    'null'    => true,
                ],
            ]
        );

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('country');
        $this->forge->addKey('city');
        $this->forge->createTable('location_weather');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('location_weather');
    }
}
