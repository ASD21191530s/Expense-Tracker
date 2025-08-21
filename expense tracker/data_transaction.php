<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transaction Details</title>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <style>

    .card {
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .table thead {
      background-color: #0d6efd;
      color: white;
    }
    td[contenteditable] {
      background-color: #fff3cd;
      border: 1px dashed #ffc107;
    }
    .action-buttons .btn {
      margin-right: 5px;
    }
          tfoot input {
      width: 100%;
      box-sizing: border-box;
    }
    #userTable tfoot {
  background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
  backdrop-filter: blur(8px);
  color: #212529; /* Dark text for readability */
}

#userTable tfoot input {
  background:lightgrey;
  color: #212529;
  border: 1px solid rgba(0,0,0,0.1);
  border-radius: 6px;
  padding: 4px 6px;
  outline: none;
}

#userTable tfoot input:focus {
  border-color: #0d6efd; /* Bootstrap primary */
  box-shadow: 0 0 4px rgba(13,110,253,0.5);
}
</style>
  

</head>
<body>


<div class="container">
  <div class="card p-4">
    <h3 class="text-center mb-4"></h3>

    <?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "expense tracker"; // avoid spaces in DB name

$conn = mysqli_connect($servername, $username, $password, $db);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}      $sql = "SELECT id,transactiontype,amount,category,date,paymentmethod,notes FROM transaction";
      $result = mysqli_query($conn, $sql);
    ?>

    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="userTable">
        <thead>
          <tr>
            <th style="background-color:black; color:white;">ID</th>
            <th style="background-color:black; color:white;">Transaction Type</th>
            <th style="background-color:black; color:white;">Amount</th>
            <th style="background-color:black; color:white;">Category</th>
            <th style="background-color:black; color:white;">Date</th>
            <th style="background-color:black; color:white;">Payment Method</th>
            <th style="background-color:black; color:white;">Notes</th>
            <th style="background-color:black; color:white;">Actions</th>
          </tr>
        </thead>
         <tfoot>
    <tr>
   <th>ID</th>
            <th>Transaction Type</th>
            <th>Amount</th>
            <th>Category</th>
            <th>Date</th>
            <th>Payment Method</th>
            <th>Notes</th>
            <th>Actions</th>

    </tr>
  </tfoot>
        <tbody>
          <?php
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr data-id='{$row['id']}'>
                        <td>{$row['id']}</td>
                        <td class='editable' data-field='transactiontype'>{$row['transactiontype']}</td>
                        <td class='editable' data-field='amount'>{$row['amount']}</td>
                        <td class='editable' data-field='category'>{$row['category']}</td>
                        <td class='editable' data-field='date'>{$row['date']}</td>
                        <td class='editable' data-field='paymentmethod'>{$row['paymentmethod']}</td>
                        <td class='editable' data-field='notes'>{$row['notes']}</td>

                        <td class='action-buttons'>
                          <button class='btn btn-sm btn-warning editBtn'>Edit</button>
                          <button class='btn btn-sm btn-danger deleteBtn'>Delete</button>
                         <a href='pdf_transaction.php?id={$row['id']}' target='_blank' class='btn btn-sm btn-info'>Print PDF</a>

                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='6'>No data found</td></tr>";
            }
            mysqli_close($conn);
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

 <script>
$(document).ready(function() {
    // Add input boxes to each footer cell
    $('#userTable tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search '+title+'" />');
    });

    // Initialize DataTable
    var table = $('#userTable').DataTable();

    // Apply column search
    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change clear', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });
});
</script>
<!-- jQuery AJAX Script -->
<script>
  $(document).ready(function(){
    $('#userTable').DataTable();

    // Toggle Edit/Save
    $(document).on('click', '.editBtn', function () {
      const btn = $(this);
      const row = btn.closest('tr');
      const isEditing = btn.text() === 'Save';

      if (!isEditing) {
        // Enable editing
        row.find('.editable').attr('contenteditable', 'true').addClass('editing');
        btn.text('Save');
      } else {
        // Save changes
        const id = row.data('id');
        let data = { id: id };

        row.find('.editable').each(function () {
          const field = $(this).data('field');
          const value = $(this).text().trim();
          data[field] = value;
        });

        $.post('update_transaction.php', data, function (response) {
          alert(response);
          row.find('.editable').removeAttr('contenteditable').removeClass('editing');
          btn.text('Edit');
        });
      }
    });

    // Delete row
    $(document).on('click', '.deleteBtn', function () {
      if (!confirm("Are you sure you want to delete this record?")) return;

      const row = $(this).closest('tr');
      const id = row.data('id');

      $.post('delete_transaction.php', { id: id }, function (response) {
        alert(response);
        row.remove();
      });
    });
  });
</script>

</body>
</html>