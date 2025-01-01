<?php

include 'topbar.php';
include 'sidebar.php';



?>
            <div class="main-panel">

                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title"> Add Design </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./design-view.php">View Design</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Design</li>
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
    <input type="hidden" name="key" value="itemAdd">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-9">
                    <input name="title" type="text" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bed Room</label>
                <div class="col-sm-9">
                    <select name="bedroom" class="form-select" required>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Floor Number</label>
                <div class="col-sm-9">
                    <select name="floor" class="form-select" required>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <select name="status" class="form-select" required>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Font Design</label>
                <div class="col-sm-9 d-flex align-items-center">
                    <input type="file" id="fontDesignInput" name="fontDesign" class="form-control" accept="image/*" required>
                    <img id="fontDesignPreview" src="" alt="Font Preview" style="max-height: 100px; display: none; margin-left: 10px;">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <div id="floor-design-container" class="mb-3">
                    <div class="form-group row floor-design-row">
                        <label class="col-sm-3 col-form-label">Floor Design</label>
                        <div class="col-sm-9 d-flex align-items-center">
                            <input type="file" class="floor-design-input form-control" name="floor_design[]" accept="image/*" required>
                            <img class="floor-design-preview" src="" alt="Floor Preview" style="max-height: 100px; display: none; margin-left: 10px;">
                            <button type="button" class="btn btn-danger ms-2 remove-floor-design">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-floor-design" class="btn btn-secondary mb-3">Add Floor Design</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">About Building</label>
                <div class="col-sm-10">
                    <textarea name="info" class="form-control" id="exampleTextarea1" rows="4"></textarea>
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

// Font Design Preview
document.getElementById('fontDesignInput').addEventListener('change', function () {
    const file = this.files[0];
    const preview = document.getElementById('fontDesignPreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});

// Floor Design Preview
document.getElementById('floor-design-container').addEventListener('change', function (e) {
    if (e.target.classList.contains('floor-design-input')) {
        const file = e.target.files[0];
        const preview = e.target.nextElementSibling; // The next sibling is the image preview

        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
});

// Add New Floor Design Row
document.getElementById('add-floor-design').addEventListener('click', function () {
    const container = document.getElementById('floor-design-container');
    const newRow = document.querySelector('.floor-design-row').cloneNode(true);

    // Reset input and preview
    const input = newRow.querySelector('.floor-design-input');
    const preview = newRow.querySelector('.floor-design-preview');
    input.value = '';
    preview.src = '';
    preview.style.display = 'none';

    container.appendChild(newRow);
});

// Remove Floor Design Row
document.getElementById('floor-design-container').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-floor-design')) {
        const row = e.target.closest('.floor-design-row');
        if (document.querySelectorAll('.floor-design-row').length > 1) {
            row.remove();
        } else {
            alert('At least one floor design must be provided.');
        }
    }
});


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
          console.log('Response JSON:', data);

          if (data.success) {
            paragraph.innerText = data.message;
            
            // Reset the form after a successful response
            document.getElementById("mainForm").reset();
            
            // Optionally, if you want to clear specific fields or previews
            const fontDesignPreview = document.getElementById("fontDesignPreview");
            if (fontDesignPreview) {
                fontDesignPreview.style.display = 'none';
                fontDesignPreview.src = '';
            }
            
            // Clear dynamically added floor design inputs and previews
            const floorDesignPreviews = document.querySelectorAll('.floor-design-preview');
            floorDesignPreviews.forEach(preview => {
                preview.style.display = 'none';
                preview.src = '';
            });
            const floorDesignInputs = document.querySelectorAll('.floor-design-input');
            floorDesignInputs.forEach(input => input.value = '');

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