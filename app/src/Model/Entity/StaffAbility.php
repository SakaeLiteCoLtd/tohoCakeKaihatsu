<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StaffAbility Entity
 *
 * @property int $id
 * @property int $staff_id
 * @property int $menu_id
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Staff $staff
 * @property \App\Model\Entity\Menu $menu
 */
class StaffAbility extends Entity
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
        'staff_id' => true,
        'menu_id' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'staff' => true,
        'menu' => true
    ];
}
