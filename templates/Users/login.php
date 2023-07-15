<?php $this->layout = 'login'; ?>

<div class="users form">
    <?= $this->Flash->render() ?>

    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->control('username', ['type' => 'text', 'required' => true, 'label' => '', 'placeholder' => 'email or username']) ?>
        <?= $this->Form->control('password', ['type' => 'password', 'required' => true, 'label' => '', 'placeholder' => 'password']) ?>

    </fieldset>
    <div class="row">
        <div class="col-xs-8">
            <?= $this->Form->control('RememberMe', ['type' => 'checkbox']) ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            <?php echo $this->Form->submit(__('Login'));
            ?>
        </div>
        <?= $this->Form->end() ?>

        <div class="col-xs-12" style="display:flex; justify-content: space-between">
            <div>
            <?php 
            // echo $this->Form->postLink('Google', [
            //     'prefix' => false,
            //     'plugin' => 'ADmad/SocialAuth',
            //     'controller' => 'Auth',
            //     'action' => 'login',
            //     'provider' => 'google',
            //     '?' => ['redirect' => $this->request->getQuery('redirect')]
            // ], ['class' => 'btn button-google']);

            ?>
            </div><div>

            <?php echo $this->Html->link('Create Account', [
                'action' => 'registerUser'
            ], ['class' => 'btn btn-warning']);

            ?>
            </div>
        </div>


        <!-- /.col -->
    </div>



</div>
<style>
    .button-google {
        background-image: url("/img/btn_google_signin.png");
        background-color: #ffffff;
        width: 133px;
        background-size: contain;
        border: none;
        text-indent: 1000px;
        overflow: hidden;
    }
</style>
<div style="width:100%; text-align:center; padding:20px;">
<?= $this->Html->link(
    'Forgot Password',
    ['controller' => 'Users', 'action' => 'resetPassword'],
    ['class' => 'button', 'target' => '_blank']
); ?>
</div>
