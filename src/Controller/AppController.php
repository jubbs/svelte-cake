<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{



    public function viewClasses(): array
    {
        return [JsonView::class];
    }
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
        $this->loadComponent('Authentication.Authentication', [
            'logoutRedirect' => '/users/login'  // Default is false
        ]);
    }
    public function beforeFilter(EventInterface $event)
    {

        
        $this->currentUser = false;
        if ($this->Authentication->getIdentity()) {

            
            if ($this->Authentication->getIdentity()->getIdentifier() == null) {
                $this->Authentication->logout();
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
            //  EventManager::instance()->on(new RequestMetadata($this->request, $this->Authentication->getIdentity()->getIdentifier())); for audit stash
            $UsersTable = $this->fetchTable('Users');
            $this->currentUser =  $UsersTable->get($this->Authentication->getIdentity()->getIdentifier(), 
                    ['contain' => [
                        'Roles']]);
            $this->set('currentUser', $this->currentUser);





        } 


    }

}


