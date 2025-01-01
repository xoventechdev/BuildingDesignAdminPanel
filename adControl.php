<?php

include "topbar.php";
include "sidebar.php";

$stmt = $pdo->prepare("SELECT * FROM adscontrol");
$stmt->execute();
$ads = $stmt->fetch(PDO::FETCH_ASSOC);

?>
            <div class="main-panel">

                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title"> Ads Control </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./index.php">Dashboard</a></li>
                                <li class="breadcrumb-item google" aria-current="page">Ads Control</li>
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
    <input type="hidden" name="key" value="adsControl">
                                    <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Banner</label>
                                                    <div class="col-sm-9">
                                                    <select name="banner" class="form-select" required>
        <option value="google" <?php echo $ads["banner"] === "google"
            ? "selected"
            : ""; ?>>Google</option>
        <option value="meta" <?php echo $ads["banner"] === "meta"
            ? "selected"
            : ""; ?>>Meta</option>
    </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Interstitial</label>
                                                    <div class="col-sm-9">
                                                    <select name="interstitial" class="form-select" required>
        <option value="google" <?php echo $ads["interstitial"] === "google"
            ? "selected"
            : ""; ?>>Google</option>
        <option value="meta" <?php echo $ads["interstitial"] === "meta"
            ? "selected"
            : ""; ?>>Meta</option>
    </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Rewarded</label>
                                                    <div class="col-sm-9">
                                                    <select name="rewarded" class="form-select" required>
        <option value="google" <?php echo $ads["rewarded"] === "google"
            ? "selected"
            : ""; ?>>Google</option>
        <option value="meta" <?php echo $ads["rewarded"] === "meta"
            ? "selected"
            : ""; ?>>Meta</option>
    </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">App open</label>
                                                    <div class="col-sm-9">
                                                    <select name="appOpen" class="form-select" required>
        <option value="google" <?php echo $ads["appOpen"] === "google"
            ? "selected"
            : ""; ?>>Google</option>
    </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>        
                                        <div class="row">
                                            <div class="col-md-6">  
                                            <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">API Key</label>
                                                    <div class="col-sm-9">
                                                        <input name="apiKey" type="text" value="<?php echo htmlspecialchars($ads['apiKey']); ?>" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                 <button type="submit" class="btn btn-primary me-2">Submit</button>
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
    credentials: 'include', 
    body: formData
      })
        .then((response) => {
          console.log('Raw Response:', response);
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then((data) => {
          console.log('Response JSON:', data);

          if (data.success) {
            location.reload();
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