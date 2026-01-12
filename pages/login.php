<?php
include '../includes/header.inc.php';
include '../includes/navbar.inc.php';
?>


<h3 style="text-align: center; font-weight: bold; color: white;">Login Page</h3>
<form class="col-md-8 col-lg-6 mx-auto"> 
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label" style="font-weight: bold; color: white;">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text" style="font-weight: bold; color: white;">We'll never share your email with anyone else.</div>
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label" style="font-weight: bold; color: white;">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1" style="font-weight: bold; color: white;">Check me out</label>
    </div>
    <button style="text-align: center;" type="submit" class="btn btn-primary">Submit</button>
</form>

<?php
include '../includes/footer.inc.php';
?>