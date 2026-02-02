<?php
$nameErr = $usernameErr = '';
$name = $username = '';
    if (isset($_POST['name'], $_POST['username'], $_POST['password'], $_POST['confirm_password'])) {
        $name = trim($_POST['name']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Basic validation
       
         if (empty($name)) {
            $nameErr ='Please input your name.';
        } 
         if (empty($username)) {
            $usernameErr ='Please input your username.';
        } 
         if (empty($password)) {
            $passwordErr ='Please input your password';
        } 
         if (empty($confirm_password)) {
            $confirmPasswordErr ='Please input confirm your password.';
        } 
        if ($password !== $confirm_password) {
            $confirmPasswordErr = 'Password does not match.';
        }
        if ($usernameExists($username)) {
            $usernameErr = 'Please choose a different username.';
        }
        if (empty($nameErr) && empty($usernameErr) && empty($passwordErr)) {
            if (registerUser($name, $username, $password)) {
                // Clear form values after successful registration
              $name = $username = '';
                echo '<div class="alert alert-success" role="alert">
                Registration successful! You can now <a href="./?page=login" class="alert-link">login</a>.
              </div>';
              
            } else {
                echo '<div class="alert alert-danger" role="alert">
                Registration failed! Please try again later.
              </div>';
            }
        }
    }
?>

<form method="post" action="./?page=register" class="col-md-8 col-lg-6 mx-auto">
    <h3 style="text-align: center; font-weight: bold; color: white;">Register Page</h3>
    <div class="mb-3">
        <label class="form-label" style="font-weight: bold; color: white;">Name</label>
        <input name="name" value="<?php echo $name ?>" type="text" class="form-control
        <?php echo empty($nameErr) ? '' : 'is-invalid'; ?>">
        <div class="invalid-feedback"><?php echo $nameErr ?? ''; ?>
    </div>
    <div class="mb-3">
        <label class="form-label" style="font-weight: bold; color: white;">username</label>
        <input name="username" value="<?php echo $username ?>" type="text" class="form-control
        <?php echo empty($usernameErr) ? '' : 'is-invalid'; ?>">
        <div class="invalid-feedback"><?php echo $usernameErr ?? ''; ?>
    </div>
    <div class="mb-3">
        <label class="form-label" style="font-weight: bold; color: white;">Password</label>
        <input name="password" type="password" class="form-control
        <?php echo empty($passwordErr) ? '' : 'is-invalid'; ?>">
        <div class="invalid-feedback"><?php echo $passwordErr ?? ''; ?>
    </div>
    <div class="mb-3">
        <label class="form-label" style="font-weight: bold; color: white;">Confirm Password</label>
        <input name="confirm_password" type="password" class="form-control
        <?php echo empty($confirmPasswordErr) ? '' : 'is-invalid'; ?>">
        <div class="invalid-feedback"><?php echo $confirmPasswordErr ?? ''; ?>
    </div>
    <button  style="text-align: center;" type="submit" class="btn btn-primary">Submit</button>
</form>

