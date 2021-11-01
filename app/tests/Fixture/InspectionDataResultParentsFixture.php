<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InspectionDataResultParentsFixture
 *
 */
class InspectionDataResultParentsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'inspection_data_conditon_parent_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '当日条件', 'precision' => null, 'autoIncrement' => null],
        'inspection_standard_size_parent_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '検査表画像', 'precision' => null, 'autoIncrement' => null],
        'product_condition_parent_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '温度条件', 'precision' => null, 'autoIncrement' => null],
        'product_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '途中で長さがかわることがあるため', 'precision' => null, 'autoIncrement' => null],
        'lot_number' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '最初=「S」、以降連番（1，2，3，…）', 'precision' => null, 'fixed' => null],
        'datetime' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'staff_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'appearance' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '外観　0=良、1=不良', 'precision' => null, 'autoIncrement' => null],
        'kangou' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '勘合　0=良、1=不良', 'precision' => null, 'autoIncrement' => null],
        'result_weight' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'judge' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '0=合、1=否', 'precision' => null, 'autoIncrement' => null],
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
                'inspection_data_conditon_parent_id' => 1,
                'inspection_standard_size_parent_id' => 1,
                'product_condition_parent_id' => 1,
                'product_id' => 1,
                'lot_number' => 'Lorem ipsum dolor sit amet',
                'datetime' => '2021-11-01 09:39:57',
                'staff_id' => 1,
                'appearance' => 1,
                'kangou' => 1,
                'result_weight' => 1,
                'judge' => 1,
                'delete_flag' => 1,
                'created_at' => '2021-11-01 09:39:57',
                'created_staff' => 1,
                'updated_at' => '2021-11-01 09:39:57',
                'updated_staff' => 1
            ],
        ];
        parent::init();
    }
}
