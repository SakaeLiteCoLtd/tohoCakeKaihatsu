<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Staff Entity
 *
 * @property int $id
 * @property int $factory_id
 * @property int|null $department_id
 * @property int|null $position_id
 * @property string $staff_code
 * @property string $name
 * @property int $sex
 * @property string|null $mail
 * @property string|null $tel
 * @property string|null $address
 * @property \Cake\I18n\FrozenDate $birth
 * @property \Cake\I18n\FrozenDate $date_start
 * @property \Cake\I18n\FrozenDate|null $date_finish
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Factory[] $factories
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\Position $position
 * @property \App\Model\Entity\InspectionDataResultParent[] $inspection_data_result_parents
 * @property \App\Model\Entity\LoginStaff[] $login_staffs
 * @property \App\Model\Entity\StaffAbility[] $staff_abilities
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\不使用productMaterialLotNumber[] $不使用product_material_lot_numbers
 */
class Staff extends Entity
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
        'department_id' => true,
        'position_id' => true,
        'staff_code' => true,
        'name' => true,
        'sex' => true,
        'mail' => true,
        'tel' => true,
        'address' => true,
        'birth' => true,
        'date_start' => true,
        'date_finish' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'factories' => true,
        'department' => true,
        'position' => true,
        'inspection_data_result_parents' => true,
        'login_staffs' => true,
        'staff_abilities' => true,
        'users' => true,
        '不使用product_material_lot_numbers' => true
    ];
}
