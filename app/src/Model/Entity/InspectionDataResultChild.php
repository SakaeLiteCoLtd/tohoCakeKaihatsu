<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InspectionDataResultChild Entity
 *
 * @property int $id
 * @property int $inspection_data_result_parent_id
 * @property int $inspection_standard_size_child_id
 * @property float $result_size
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\InspectionDataResultParent $inspection_data_result_parent
 * @property \App\Model\Entity\InspectionStandardSizeChild $inspection_standard_size_child
 */
class InspectionDataResultChild extends Entity
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
        'inspection_data_result_parent_id' => true,
        'inspection_standard_size_child_id' => true,
        'result_size' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'inspection_data_result_parent' => true,
        'inspection_standard_size_child' => true
    ];
}
