<?php

include 'topbar.php';
include 'sidebar.php';



?>
            <div class="main-panel">

                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title"> Add User </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./user-view.php">View User</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add User</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">

        <p id="sample"></p>
        <form id="mainForm" class="form-sample" enctype="multipart/form-data">
    <!-- Add hidden key field -->
    <input type="hidden" name="key" value="userAdd">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input name="name" type="text" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input name="email" type="email" class="form-control" required>
                    </div>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-9">
                    <input name="password" type="password" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Mobile</label>
                <div class="col-sm-9">
                    <input name="mobile" type="text" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Gender</label>
                <div class="col-sm-9">
                    <select name="gender" class="form-select">
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-9">
                    <input name="address" type="text" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        <div class="form-group row">
                <label class="col-sm-3 col-form-label">Country</label>
                <div class="col-sm-9">
                    <input name="country" type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
            <label class="col-sm-3 col-form-label">User Role</label>
                <div class="col-sm-9">
                    <select name="userRole" class="form-select" requred>
                        <option>Normal</option>
                        <option>Admin</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary me-2">Submit</button>
</form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024.
                            All rights reserved.</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script>

var paragraph = document.querySelector("#sample");
document.getElementById("mainForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    fetch('./api/design.php', {
        method: 'POST',
        mode : 'same-origin',
        credentials: 'same-origin' ,
        body : formData
      })
        .then((response) => {
          console.log('Raw Response:', response);
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then((data) => {
          if (data.success) {
            paragraph.innerText = data.message;
            document.getElementById("mainForm").reset();

        } else {
            paragraph.innerText = data.message;
        }

        })
        .catch((error) => {
          console.error('Fetch error:', error);
        paragraph.innerText = 'Something went wrong. Please try again.';
        });
});


</script>

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="./assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- <script src="./assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="./assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="./assets/js/off-canvas.js"></script>
    <script src="./assets/js/misc.js"></script>
    <script src="./assets/js/settings.js"></script>
    <script src="./assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- End custom js for this page -->
</body>

</html>