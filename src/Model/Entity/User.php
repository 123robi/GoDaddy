<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property string $address
 * @property string $password
 * @property string $facebook_json
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $real_user
 * @property string $fcm
 *
 * @property \App\Model\Entity\TeamMember[] $team_members
 * @property \App\Model\Entity\Team[] $teams
 * @property \App\Model\Entity\Fee[] $fees
 */
class User extends Entity
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
        'email' => true,
        'phone_number' => true,
        'address' => true,
        'password' => true,
        'facebook_json' => true,
        'created' => true,
        'modified' => true,
        'real_user' => true,
        'fcm' => true,
        'team_members' => true,
        'teams' => true,
        'fees' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
	protected function _setPassword($password)
	{
		if (strlen($password) > 0) {
			return (new DefaultPasswordHasher)->hash($password);
		}
	}
}
