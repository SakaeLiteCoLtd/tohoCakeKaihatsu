<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Material Entity
 *
 * @property int $id
 * @property int $factory_id
 * @property string $material_code
 * @property string $grade
 * @property string $color
 * @property string $maker
 * @property int $type_id
 * @property int $is_active
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\MaterialType $material_type
 * @property \App\Model\Entity\PriceMaterial[] $price_materials
 * @property \App\Model\Entity\ProductMaterial[] $product_materials
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
        'grade' => true,
        'color' => true,
        'maker' => true,
        'type_id' => true,
        'is_active' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'material_type' => true,
        'price_materials' => true,
        'product_materials' => true
    ];
}
