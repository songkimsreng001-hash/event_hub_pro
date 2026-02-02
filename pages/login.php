<?php
$nameErr = $usernameErr = '';
$name = $username = '';
if (isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);



    if (empty($username)) {
        $usernameErr = 'Please input your username.';
    }
    if (empty($password)) {
        $passwordErr = 'Please input your password';
    }
    if (empty($usernameErr) && empty($passwordErr)) {
        $user = logUserIn($username, $password);
        if ($user && is_object($user)) {
            $_SESSION['user_id'] = $user->id;
            header('Location: ./?page=dashboard');
        } else{
            $usernameErr = 'Username or password is incorrect.';
        }
    }
}
?>

<form method="post" action="./?page=login" class="col-md-8 col-lg-6 mx-auto">
    <h3 style="text-align: center; font-weight: bold; color: white;">Login Page</h3>
        <div class="mb-3">
            <label class="form-label" style="font-weight: bold; color: white;">Username</label>
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
                <button style="text-align: center;" type="submit" class="btn btn-primary">Submit</button>
</form>