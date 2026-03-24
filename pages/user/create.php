<?php
$nameErr = $usernameErr = $passwdErr = '';
$name = $username = '';

if (isset($_POST['name'], $_POST['username'], $_POST['passwd'], $_FILES['photo'])) {
    $photo = $_FILES['photo'];
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $passwd = trim($_POST['passwd']);
    if (empty($name)) {
        $nameErr = 'Please input name!';
    }
    if (empty($username)) {
        $usernameErr = 'Please input username!';
    }
    if (empty($passwd)) {
        $passwdErr = 'Please input password!';
    }
    if (usernameExists($username)) {
        $usernameErr = 'Please choose another username !';
    }
    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        try {
            if (createUser($name, $username, $passwd, $photo)) {
                $name = $username = '';
                echo '<div class="alert alert-success" role="alert">
            Create successful! <a href="./?page=user/list">go to list</a>
            </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">
            Create failed! Please try again.
            </div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">
            ' . $e->getMessage() . '
            </div>';
        }
    }
}
?>


<form method="post" action="./?page=user/create" enctype="multipart/form-data" class="col-md-10 col-lg-6 mx-auto">
    <h3 class="text-center fw-bold">Create User</h3>
    <div class="d-flex justify-content-center mb-3">
        <input name="photo" type="file" id="profileUpload" hidden>
        <label for="profileUpload" role="button">
            <img src="./assets/images/Profile_PNG.png"
                 class="rounded img-thumbnail"
                 style="max-width:200px;">
        </label>
    </div>
    <div class="mb-3">
        <label class="form-label">Name</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person-fill"></i>
            </span>
            <input name="name"
                   value="<?php echo $name ?>"
                   type="text"
                   class="form-control <?php echo empty($nameErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback">
                <?php echo $nameErr ?>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person-fill"></i>
            </span>
            <input name="username"
                   value="<?php echo $username ?>"
                   type="text"
                   class="form-control <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback">
                <?php echo $usernameErr ?>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person-lock"></i>
            </span>
            <input name="passwd"
                   type="password"
                   class="form-control <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback">
                <?php echo $passwdErr ?>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary px-4">Submit</button>
    </div>

</form>