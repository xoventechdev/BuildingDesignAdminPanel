<?php


include 'topbar.php';
include 'sidebar.php';

$users = $pdo->query("SELECT * FROM usertable ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);


?>
      <div class="main-panel">

        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title"> User </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./user-add.php">Add User</a></li>
                <li class="breadcrumb-item active" aria-current="page">User</li>
              </ol>
            </nav>
          </div>
          <div class="card">
            
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <div id="order-listing_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                      <div class="row">
                        <div class="col-sm-12">
                          <table id="order-listing" class="table dataTable no-footer"
                            aria-describedby="order-listing_info">
                            <thead>
                              <tr>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="order-listing" rowspan="1"
                                  colspan="1" aria-sort="ascending"
                                  aria-label="Order #: activate to sort column descending" style="width: 52.4896px;">
                                  ID</th>
                                <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1"
                                  aria-label="Purchased On: activate to sort column ascending"
                                  style="width: 97.1562px;">Email </th>
                                <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1"
                                  aria-label="Customer: activate to sort column ascending" style="width: 68.8854px;">
                                  Name</th>
                                <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1"
                                  aria-label="Ship to: activate to sort column ascending" style="width: 50.3854px;">
                                  Total Views</th>
                                <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1"
                                  aria-label="Base Price: activate to sort column ascending" style="width: 73.5938px;">
                                  Total Download</th>
                                <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1"
                                  aria-label="Actions: activate to sort column ascending" style="width: 55.6667px;">
                                  Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $index => $user): ?>
                              <tr>
                                <td class="sorting_1"><?php echo $user['id']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['name']; ?></td>
                                <td><?php echo $user['totalView']; ?></td>
                                <td><?php echo $user['totalDownload']; ?></td>
                                </td>
                                <td>
                                  <a class="btn btn-outline-primary" href="user-edit.php?id=<?php echo $user['id']; ?>">Edit</a>
                                  <button class="btn btn-outline-danger" onclick="showSwal('<?php echo $user['id']; ?>')">Delete</button>

                                </td>
                              </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024. All rights
              reserved.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <script>
    
    showSwal = function (id) {
  swal({
    title: 'Are you sure?',
    text: 'You are removing an item!',
    icon: 'warning',
    buttons: {
      cancel: {
        text: 'Cancel',
        value: null,
        visible: true,
        className: 'btn btn-danger',
        closeModal: true,
      },
      confirm: {
        text: 'Delete',
        value: true,
        visible: true,
        className: 'btn btn-primary',
        closeModal: false,
      },
    },
  }).then((isConfirmed) => {
    if (isConfirmed) {

      // Send DELETE request
      fetch('./delete.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, type : 'item' }),
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
            swal('Deleted!', data.message, 'success').then(() => {
              location.reload();
            });
          } else {
            swal('Error!', data.message, 'error');
          }
        })
        .catch((error) => {
          console.error('Fetch error:', error);
          swal('Error!', 'Something went wrong. Please try again.', 'error');
        });
    }
  });
};



  </script>

<?php

ob_end_flush(); // End buffering if necessary (optional)
?>

  <!-- container-scroller -->
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="./assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="./assets/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="./assets/vendors/sweetalert/sweetalert.min.js"></script>
  <script src="./assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="./assets/js/off-canvas.js"></script>
  <!-- <script src="./assets/js/hoverable-collapse.js"></script> -->
  <script src="./assets/js/misc.js"></script>
  <script src="./assets/js/settings.js"></script>
  <script src="./assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="./assets/js/data-table.js"></script>
  
  <!-- <script src="./assets/js/alerts.js"></script> -->
  <!-- End custom js for this page -->
</body>

</html>