<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductMaterialMachine Entity
 *
 * @property int $id
 * @property int $product_condition_parent_id
 * @property int $cylinder_numer
 * @property string $cylinder_name
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\ProductMaterialParent $product_material_parent
 * @property \App\Model\Entity\ProductMachineMaterial[] $product_machine_materials
 */
class ProductMaterialMachine extends Entity
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
        'product_condition_parent_id' => true,
        'cylinder_number' => true,
        'cylinder_name' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'product_material_parent' => true,
        'product_machine_materials' => true
    ];
}
