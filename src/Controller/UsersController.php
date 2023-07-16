<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Mailer\Mailer;
use League\Container\Exception\NotFoundException;
use Cake\Routing\Router;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login', 'tokensignin', 'logout', 'resetPassword', 'test','edit']);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Roles'],
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles'],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200])->all();
        $this->set(compact('user', 'roles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200])->all();
        $this->set(compact('user', 'roles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        //Log::write('error', 'Something did not work');
        //dd(getenv('DATABASE'));
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // Restfull login
        if ($this->request->getParam('_ext') == 'json') {
            if ($result->isValid()) {
                $user = $this->currentUser;

                $bytes = random_bytes(50);
                $user->token = bin2hex($bytes);
                $user->token_timestamp = FrozenTime::now();
                if ($this->Users->save($user)) {
                    $valid = ['valid' => true, 'token' => $user->token, 'level' => $user->role->level];
                } else {
                    $valid = ['valid' => false, 'message' => "Err 35463: Unable to write token."];
                }
            } else {
                $valid = ['valid' => false, 'message' => "Incorrect username or Password. Please try again."];
            }
            $this->set('valid', $valid);
            $this->viewBuilder()->setOption('serialize', 'valid');
        } else {
            // Form login
            if ($result->isValid()) {

                $redirect = $this->request->getQuery('redirect', [
                    'controller' => 'Users',
                    'action' => 'index',
                ]);

                // Set the Current.venue session variable. If the user changes the current venue away from their default venue it will remember the selection

                $session = $this->request->getSession();
                $session->write('Current.venue', $this->currentUser->default_venue_id);
                return $this->redirect($redirect);
            }
            // display error if user submitted and authentication failed
            if ($this->request->is('post') && !$result->isValid()) {
                $this->Flash->error(__('Invalid username or password'));
            }
        }
    }


    public function logout()
    {
        if ($this->request->getParam('_ext') == 'json') {
            $this->Authentication->logout();
            $valid = ['valid' => false, 'message' => "Logged Out"];
            $this->set('valid', $valid);
            $this->viewBuilder()->setOption('serialize', 'valid');
        } else {
            // Form login
            return $this->redirect($this->Authentication->logout());
        }
    }

    
    public function resetPassword()
    {

        $reset = false;


        if ($this->request->getQuery('reset')) {
            if ($this->request->is(array('post', 'put'))) {

                if ($this->request->getData('action') == 'newpass') {
                    $user = $this->Users->get($this->request->getData('id'));

                    if (!$user) {
                        throw new NotFoundException(__('Invalid user'));
                    }
                    if ($user->reset_hash != $this->request->getQuery('reset')) {
                        $this->Flash->error(__('There was a problem matching the reset code. Please, try again.'));
                    } else {
                        if ($this->request->getData('newpass') != $this->request->getData('newpassconfirm')) {
                            $this->Flash->error(__('The passwords do not match please try again'));
                            $reset = true;
                            $this->set('reset_user', $this->request->getData());
                        } else {
                            if (time() > ($user->reset_time->format("U") + 3600)) {
                                $this->Flash->error(__('The reset code you entered has expired. Please, try again.'));
                            } else {

                                $user->reset_hash = null;
                                $user->password = $this->request->getData('newpass');
                                if ($this->Users->save($user)) {
                                    $this->Flash->success(__('Successfully reset your password. Please, sign in.'));
                                    return $this->redirect(array('action' => 'login'));
                                } else {

                                    $this->Flash->error(__('There was a problem changing your password. Please, try again.'));
                                }
                            }
                        }
                    }
                }
            } else {
                $user = $this->Users->find()
                    ->where(['reset_hash' => $this->request->getQuery('reset')])
                    ->first();


                if (is_null($user)) {
                    $this->Flash->error(__('The reset code you entered could not be found. Please, try again.'));
                    return $this->redirect(array('action' => 'resetPassword'));
                } else {
                    if (time() > ($user->reset_time->format("U") + 3600)) {
                        $this->Flash->error(__('The reset code you entered has expired. Please, try again.'));
                        return $this->redirect(array('action' => 'resetPassword'));
                    } else {
                        $reset = true;
                        $this->set('reset_user', $user);
                    }
                }
            }
        } else if ($this->request->is('post', 'put')) { // The user has entered there email or username and pressed submit
            if ($this->request->getData('username') == "") {
                $this->Flash->error(__('You must enter a username or email. Please, try again.'));
                $this->set('reset', false);
                return;
            }
            if ($this->request->getData('action') == 'submit') {
                $user = $this->Users->find()
                    ->where(['OR' => [
                        'email' => $this->request->getData('username'),
                        'username' => $this->request->getData('username')
                    ]])

                    ->contain(['Roles'])->first();


                if ($user) {

                    $resethash = (new DefaultPasswordHasher())->hash(uniqid(md5(strval(mt_rand()))));


                    $user->reset_hash = $resethash;
                    $user->reset_time = date('Y-m-d H:i:s', time());

                    if ($this->Users->save($user)) {
                        $Email = new Mailer('default');
                        $Email->setFrom(['noreply@pay2play.co.nz' => 'Pay2Play'])
                            ->setEmailFormat('html')
                            ->setTo($user->email)
                            ->setSubject('Pay2Play Password Reset')
                            ->viewBuilder()
                            ->setTemplate('passwordreset');

                        $Email->setViewVars([
                            'name' => $user->fullname,
                            'link' => Router::url([
                                'controller' => 'users',
                                'action' => 'reset_password',
                                '?' => ['reset' => $resethash]
                            ], true)
                        ]);

                        $Email->deliver();

                        $this->Flash->success(__('Please check your email inbox for the reset link.'));
                    } else {
                        $this->Flash->error(__('Unable to generate reset code'));
                    }
                } else {
                    $this->Flash->success(__('Please check your email inbox for the reset link.'));
                }
            }
        }
        $this->set('reset', $reset);
    }

    public function registerUser()
    {

        if ($this->request->is('post')) {

            if ($this->request->getData('action') == 'register') {

                $data = $this->request->getData();

                if (!$data) {
                    $this->Flash->error(__('Something went wrong with the registration. Please, try again'));
                    return $this->redirect(array('action' => 'registerUser'));
                } else {
                    // Retrieve the role_id for General Users
                    $role_id = $this->Users->Roles->find()->where(['name' => 'General User'])
                        ->first()->id;

                    $new_user = $this->Users->newEmptyEntity();
                    $new_user->username = $data['email'];
                    $new_user->email = $data['email'];
                    $new_user->role_id = $role_id;
                    $new_user->fname = $data['fname'];
                    $new_user->sname = $data['sname'];
                    $new_user->phone = $data['phone'];
                    $new_user->password = $data['password'];
                    $resethash = (new DefaultPasswordHasher())->hash(uniqid(md5(strval(mt_rand()))));
                    $new_user->reset_hash = $resethash;
                    $new_user->reset_time = date('Y-m-d H:i:s', time());



                    if ($this->Users->save($new_user)) {
                        $Email = new Mailer('default');
                        $Email->setFrom(['noreply@pay2play.co.nz' => 'Pay2Play'])
                            ->setEmailFormat('html')
                            ->setTo($data['email'])
                            ->setSubject('Confirm your email address for Pay2Play')
                            ->viewBuilder()
                            ->setTemplate('confirm_email');

                        $Email->setViewVars([
                            'name' => $data['fname'],
                            'link' => Router::url([
                                'controller' => 'users',
                                'action' => 'confirm_email',
                                '?' => ['reset' => $resethash]
                            ], true)
                        ]);

                        $Email->deliver();

                        $this->Flash->success(__('Successfully registered. Please check your email inbox for the link to confirm your email address.'));
                        return $this->redirect(array('action' => 'login'));
                    } else {
                        $errors = $new_user->getErrors();
                        foreach ($errors as $error_field => $message) {
                            foreach ($message as $fail => $msg)
                                $this->Flash->error(__($error_field . ": " . $msg));
                        }
                    }
                }
            }
        }
    } 

    public function test(){

    }

}
