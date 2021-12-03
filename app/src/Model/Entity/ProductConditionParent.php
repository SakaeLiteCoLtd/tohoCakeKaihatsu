<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductConditionParent Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string $machine_num
 * @property string $product_condition_code
 * @property int $version
 * @property \Cake\I18n\FrozenTime $start_datetime
 * @property \Cake\I18n\FrozenTime|null $finish_datetime
 * @property int $is_active
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\InspectionDataResultParent[] $inspection_data_result_parents
 * @property \App\Model\Entity\ProductMaterialMachine[] $product_material_machines
 * @property \App\Model\Entity\ShotWork[] $shot_works
 * @property \App\Model\Entity\ShotdataBase[] $shotdata_bases
 */
class ProductConditionParent extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'product_id' => true,
        'machine_num' => true,
        'product_condition_code' => true,
        'version' => true,
        'start_datetime' => true,
        'finish_datetime' => true,
        'is_active' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product' => true,
        'inspection_data_result_parents' => true,
        'product_material_machines' => true,
        'shot_works' => true,
        'shotdata_bases' => true
    ];
}
