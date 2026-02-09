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
        $nameErr = 'Please input your name.';
    }
    if (empty($username)) {
        $usernameErr = 'Please input your username.';
    }
    if (empty($passwd)) {
        $passwordErr = 'Please input your password';
    }
    if (empty($confirm_password)) {
        $confirmPasswdErr = 'Please input confirm your password.';
    }
    if ($passwd !== $confirm_password) {
        $confirmPasswdErr = 'Password does not match.';
    }
    if ($usernameExists($username)) {
        $usernameErr = 'Please choose a different username.';
    }
    if (empty($nameErr) && empty($usernameErr) && empty($confirmPasswdErr)) {
        if (registerUser($name, $username, $passwd)) {
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
        <label class="form-label" style="font-weight: bold; color: white;">Username</label>
        <input name="username" value="<?php echo $username ?>" type="text" class="form-control
        <?php echo empty($usernameErr) ? '' : 'is-invalid'; ?>">
        <div class="invalid-feedback"><?php echo $usernameErr ?? ''; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-weight: bold; color: white;">Password</label>
            <input name="passwd" type="password" class="form-control
            <?php echo empty($passwdErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback"><?php echo $passwdErr ?? ''; ?>
        </div>
            <div class="mb-3">
                <label class="form-label" style="font-weight: bold; color: white;">Confirm Password</label>
                <input name="confirm_passwd" type="password" class="form-control
                 <?php echo empty($confirmPasswdErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback">
                <?php echo $confirmPasswdErr ?? ''; ?>
            </div>
                <button style="text-align: center;" type="submit" class="btn btn-primary">Submit</button>
</form>