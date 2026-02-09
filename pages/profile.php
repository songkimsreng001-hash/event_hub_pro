<?php
$oldPasswd = $newPasswd = $confirmPasswd = '';
$oldPasswdErr = $newPasswdErr = '';

if (isset($_POST['changePasswd'], $_POST['oldPasswd'], $_POST['newPasswd'], $_POST['confirmPasswd'])) {
    $oldPasswd = trim($_POST['oldPasswd']);
    $newPasswd = trim($_POST['newPasswd']);
    $confirmPasswd = trim($_POST['confirmPasswd']);

    if (empty($oldPasswd)) {
        $oldPasswdErr = 'Please input your old password.';
    }
    if (empty($newPasswd)) {
        $newPasswdErr = 'Please input your new password.';
    }
    if (empty($confirmPasswd)) {
        $newPasswdErr = 'Please confirm your new password.';
    }
    if ($newPasswd !== $confirmPasswd) {
        $newPasswdErr = 'Password is incorrect.';
    }
    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassword($newPasswd)) {
            header('Location: ./?page=logout');
        } else {
            echo '<div class="alert alert-danger" role="alert">Try again.</div>';
        }
    }
}
?>

<div class="row">
    <div class="col-lg-6">
        <h3 style="text-align: center; font-weight: bold; color: white;">Profile Page</h3>
        <form method="post" action="./?page=profile">
            <div class="d-flex flex-column align-items-center">
                <input name="photo" type="file" id="profileUpload" hidden>
                <label for="profileUpload" class="d-flex flex-column align-items-center">
                    <img src=".assets/images/Profile_PNG.png" class="rounded">
    </div>
    <div class="d-flex justify-content-center">
        <button type="submit" name="deletePhoto" class="btn btn-danger">Delete</button>
        <button type="submit" name="uploadPhoto" class="btn btn-success ms-2">Upload</button>

    </div>
</div>

<div class="col-6">
    <form method="post" action="./?page=profile" class="col-md-8 col-lg-6 mx-auto">
        <div class="mb-3">
            <label class="form-label" style="font-weight: bold; color: white;">Old Password</label>
            <input value="<?php echo $oldPasswd ?>" type="password" class="form-control
            <?php echo empty($oldPasswdErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback">
            <?php echo $oldPasswdErr ?? ''; ?>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-weight: bold; color: white;">New Password</label>
            <input name="newPasswd" type="password" class="form-control
            <?php echo empty($newPasswdErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback">
            <?php echo $newPasswdErr ?? ''; ?>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-weight: bold; color: white;">Confirm New Password</label>
            <input name="confirmPasswd" type="password" class="form-control">
            </div>
        </div>
        <button type="submit" name="changePasswd" class="btn btn-primary">Change Password</button>
    </form>
</div>