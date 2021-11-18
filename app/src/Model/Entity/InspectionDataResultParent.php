<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InspectionDataResultParent Entity
 *
 * @property int $id
 * @property int $inspection_data_conditon_parent_id
 * @property int $inspection_standard_size_parent_id
 * @property int $product_condition_parent_id
 * @property int $product_id
 * @property string $lot_number
 * @property \Cake\I18n\FrozenTime $datetime
 * @property int $staff_id
 * @property int $appearance
 * @property float $result_weight
 * @property int $judge
 * @property int|null $kanryou_flag
 * @property float|null $total_amount
 * @property string|null $bik
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\InspectionDataConditonParent $inspection_data_conditon_parent
 * @property \App\Model\Entity\InspectionStandardSizeParent $inspection_standard_size_parent
 * @property \App\Model\Entity\ProductConditionParent $product_condition_parent
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Staff $staff
 * @property \App\Model\Entity\InspectionDataResultChild[] $inspection_data_result_children
 */
class InspectionDataResultParent extends Entity
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
        'inspection_data_conditon_parent_id' => true,
        'inspection_standard_size_parent_id' => true,
        'product_condition_parent_id' => true,
        'product_id' => true,
        'lot_number' => true,
        'datetime' => true,
        'staff_id' => true,
        'appearance' => true,
        'result_weight' => true,
        'judge' => true,
        'kanryou_flag' => true,
        'total_amount' => true,
        'bik' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'inspection_data_conditon_parent' => true,
        'inspection_standard_size_parent' => true,
        'product_condition_parent' => true,
        'product' => true,
        'staff' => true,
        'inspection_data_result_children' => true
    ];
}
