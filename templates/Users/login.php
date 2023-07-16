<?php

$this->extend('../layout/TwitterBootstrap/signin');

?>


<!-- /.login-logo -->

<?= $this->Flash->render() ?>



<main class="form-signin w-100 m-auto">

  
  
  
  
  <?= $this->Form->create(null) ?>
  <img class="mb-4" src="https://svelte.dev/_app/immutable/assets/svelte-logo.5c5d7d20.svg" alt="Svelte" width="72" height="57">
  <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
  
  <div class="form-floating">
    <?= $this->Form->text('username', ['required' => true, 'label' => '', 'class' => 'form-control', 'placeholder' => 'Email or Username']) ?>
    <label for="floatingInput">Email address / Username</label>
  </div>
  <div class="form-floating">
    <?= $this->Form->pasword('password', ['required' => true, 'label' => '', 'class' => 'form-control', 'placeholder' => 'Password']) ?>
    <label for="floatingPassword">Password</label>
  </div>
  
  <div class="form-check text-start my-3">
    <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
    <label class="form-check-label" for="flexCheckDefault">
      Remember me
    </label>
  </div>
  
  <?php echo $this->Form->submit('Sign In', ['class' => 'btn btn-primary w-100 py-2']); ?>
  <p class="mt-5 mb-3 text-body-secondary">HHA</p>
  <?= $this->Form->end() ?>
</main>