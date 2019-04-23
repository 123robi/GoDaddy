<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\TeamMembersTable|\Cake\ORM\Association\HasMany $TeamMembers
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\HasMany $Teams
 * @property \App\Model\Table\FeesTable|\Cake\ORM\Association\BelongsToMany $Fees
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('TeamMembers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Teams', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Fees', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'fee_id',
            'joinTable' => 'users_fees'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name', ['message' => 'Name is required!']);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table','message' => 'User already exists']);

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 15)
            ->allowEmpty('phone_number');

        $validator
            ->scalar('address')
            ->maxLength('address', 50)
            ->allowEmpty('address');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmpty('password', 'Password should not be empty');

        $validator
            ->scalar('facebook_json')
            ->allowEmpty('facebook_json');

        $validator
            ->integer('real_user')
            ->requirePresence('real_user', 'create')
            ->notEmpty('real_user');

        $validator
            ->scalar('fcm')
            ->maxLength('fcm', 255)
            ->allowEmpty('fcm');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    public function getUser(\Cake\Datasource\EntityInterface $profile) {
        // Make sure here that all the required fields are actually present
        if (empty($profile->email)) {
            $profile->email = $uniqid = 'required@' . uniqid() . '.com';
        }

        // Check if user with same email exists. This avoids creating multiple
        // user accounts for different social identities of same user. You should
        // probably skip this check if your system doesn't enforce unique email
        // per user.
        $user = $this->find()
            ->where(['email' => $profile->email])
            ->first();

        if ($user) {
            return $user;
        }

        // Create new user account
        $user = $this->newEntity(['name' => $profile->full_name, 'email' => $profile->email, 'real_user' => 1, 'password' => '$%!ALkh#541gasg@#%123^#']);
        $user = $this->save($user);

        if (!$user) {
            throw new \RuntimeException('Unable to save new User ' . json_encode(['name' => $profile->full_name, 'email' => $profile->email, 'real_user' => 1, 'password' => '$%!ALkh#541gasg@#%123^#']));
        }

        return $user;
    }
}
