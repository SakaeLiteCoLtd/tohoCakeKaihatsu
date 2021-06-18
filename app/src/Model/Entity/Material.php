<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Material Entity
 *
 * @property int $id
 * @property int $factory_id
 * @property string $material_code
 * @property string $name
 * @property string|null $sakuin
 * @property string|null $grade
 * @property string|null $color
 * @property string|null $maker
 * @property int $material_supplier_id
 * @property int|null $material_type_id
 * @property string|null $tanni
 * @property string|null $tanni_kosu
 * @property int $is_active
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Factory $factory
 * @property \App\Model\Entity\MaterialType $material_type
 * @property \App\Model\Entity\PriceMaterial[] $price_materials
 * @property \App\Model\Entity\ProductMachineMaterial[] $product_machine_materials
 * @property \App\Model\Entity\不使用productMaterialParent[] $不使用product_material_parents
 * @property \App\Model\Entity\不使用productMaterial[] $不使用product_materials
 */
class Material extends Entity
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
        'factory_id' => true,
        'material_code' => true,
        'name' => true,
        'sakuin' => true,
        'grade' => true,
        'color' => true,
        'maker' => true,
        'material_supplier_id' => true,
        'material_type_id' => true,
        'tanni' => true,
        'tanni_kosu' => true,
        'is_active' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'factory' => true,
        'material_type' => true,
        'price_materials' => true,
        'product_machine_materials' => true,
        '不使用product_material_parents' => true,
        '不使用product_materials' => true
    ];
}
