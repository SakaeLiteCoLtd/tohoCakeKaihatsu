<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Office Entity
 *
 * @property int $id
 * @property string $name
 * @property int $company_id
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_staff
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Department[] $departments
 * @property \App\Model\Entity\Occupation[] $occupations
 * @property \App\Model\Entity\Position[] $positions
 * @property \App\Model\Entity\Staff[] $staffs
 */
class Office extends Entity
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
        'name' => true,
        'company_id' => true,
        'delete_flag' => true,
        'created_at' => true,
        'created_staff' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'company' => true,
        'departments' => true,
        'occupations' => true,
        'positions' => true,
        'staffs' => true
    ];
}
