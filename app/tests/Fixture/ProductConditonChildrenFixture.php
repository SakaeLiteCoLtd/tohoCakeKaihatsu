<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductConditonChildrenFixture
 *
 */
class ProductConditonChildrenFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'product_conditon_children';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'product_material_machine_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cylinder_number' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cylinder_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'temp_1' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'temp_1_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '10', 'comment' => ''],
        'temp_1_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-10', 'comment' => ''],
        'temp_2' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'temp_2_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '10', 'comment' => ''],
        'temp_2_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-10', 'comment' => ''],
        'temp_3' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'temp_3_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '10', 'comment' => ''],
        'temp_3_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-10', 'comment' => ''],
        'temp_4' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'temp_4_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '10', 'comment' => ''],
        'temp_4_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-10', 'comment' => ''],
        'temp_5' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'temp_5_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '10', 'comment' => ''],
        'temp_5_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-10', 'comment' => ''],
        'temp_6' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'temp_6_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '10', 'comment' => ''],
        'temp_6_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-10', 'comment' => ''],
        'temp_7' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'temp_7_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '10', 'comment' => ''],
        'temp_7_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-10', 'comment' => ''],
        'extrude_roatation' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'extrusion_load' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'extrusion_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '5', 'comment' => ''],
        'extrusion_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-5', 'comment' => ''],
        'pickup_speed' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'pickup_speed_upper_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '1', 'comment' => ''],
        'pickup_speed_lower_limit' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '-1', 'comment' => ''],
        'screw_mesh_1' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'screw_number_1' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'screw_mesh_2' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'screw_number_2' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'screw_mesh_3' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'screw_number_3' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'delete_flag' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_staff' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'updated_at' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'updated_staff' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'product_material_machine_id' => 1,
                'cylinder_number' => 1,
                'cylinder_name' => 'Lorem ipsum dolor sit amet',
                'temp_1' => 1,
                'temp_1_upper_limit' => 1,
                'temp_1_lower_limit' => 1,
                'temp_2' => 1,
                'temp_2_upper_limit' => 1,
                'temp_2_lower_limit' => 1,
                'temp_3' => 1,
                'temp_3_upper_limit' => 1,
                'temp_3_lower_limit' => 1,
                'temp_4' => 1,
                'temp_4_upper_limit' => 1,
                'temp_4_lower_limit' => 1,
                'temp_5' => 1,
                'temp_5_upper_limit' => 1,
                'temp_5_lower_limit' => 1,
                'temp_6' => 1,
                'temp_6_upper_limit' => 1,
                'temp_6_lower_limit' => 1,
                'temp_7' => 1,
                'temp_7_upper_limit' => 1,
                'temp_7_lower_limit' => 1,
                'extrude_roatation' => 1,
                'extrusion_load' => 1,
                'extrusion_upper_limit' => 1,
                'extrusion_lower_limit' => 1,
                'pickup_speed' => 1,
                'pickup_speed_upper_limit' => 1,
                'pickup_speed_lower_limit' => 1,
                'screw_mesh_1' => 'Lorem ipsum dolor sit amet',
                'screw_number_1' => 'Lorem ipsum dolor sit amet',
                'screw_mesh_2' => 'Lorem ipsum dolor sit amet',
                'screw_number_2' => 'Lorem ipsum dolor sit amet',
                'screw_mesh_3' => 'Lorem ipsum dolor sit amet',
                'screw_number_3' => 'Lorem ipsum dolor sit amet',
                'delete_flag' => 1,
                'created_at' => '2021-05-23 00:56:10',
                'created_staff' => 1,
                'updated_at' => '2021-05-23 00:56:10',
                'updated_staff' => 1
            ],
        ];
        parent::init();
    }
}
