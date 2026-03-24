<?php
$id = $_GET['id'];
$targetUser = readUser($id);
if ($targetUser == null || $targetUser->level == 'admin') {
    header('Location: ./?page=user/list');
}

$nameErr = $usernameErr = $passwdErr = '';
$name = $targetUser->name;
$username = $targetUser->username;

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
    // if (empty($passwd)) {
    //     $passwdErr = 'Please input password!';
    // }
    if ($targetUser->username !== $username && usernameExists($username)) {
        $usernameErr = 'Please choose another username !';
    }
    if (empty($nameErr) && empty($usernameErr)) {
        try {
            if (updateUser($id, $name, $username, $passwd, $photo)) {
                echo '<div class="alert alert-success" role="alert">
                Update Name and Username successful! <a href="./?page=user/list">Go to list
                </a>
            </div>';
            } else {
                if (updateUser($id, $name, $username, $passwd, $photo)) {
                    echo '<div class="alert alert-success" role="alert">
                    Update Name, Username and Password successful! <a href="./?page=user/list">Go to list
                    </a>
                </div>';
                }
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">
            ' . $e->getMessage() . '
            </div>';
        }
    }
}
?>


<form method="post" action="./?page=user/update&id=<?php echo $id; ?>
" enctype="multipart/form-data" class="col-md-10 col-lg-6 mx-auto">
    <h3 style="font-weight: bold; text-align: center;">Update User</h3>
    <div class="d-flex justify-content-center">
        <input name="photo" type="file" id="profileUpload" hidden>
        <label role="button" for="profileUpload">
            <img src="<?php echo $targetUser->photo ?: './assets/images/Profile_PNG.png'; ?>
            " class="rounded img-thumbnail" style="max-width:200px">
        </label>
    </div>
    <div class="mb-3">
        <label class="form-label">Name</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person-fill"></i>
            </span>
            <input name="name" value="<?php echo $name ?>" type="text" class="form-control
                <?php echo empty($nameErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback"><?php echo $nameErr ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person-fill"></i>
                </span>
                <input name="username" value="<?php echo $username ?>" type="text" class="form-control
                    <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
                <div class="invalid-feedback"><?php echo $usernameErr ?></div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person-lock"></i>
                    </span>
                    <input name="passwd" type="password" class="form-control
                        <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>">
                    <div class="invalid-feedback"><?php echo $passwdErr ?></div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
</form>