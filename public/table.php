<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iRecord - Student Record System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <?php 
      include '../partials/_nav.php'; // Include navigation bar
    ?>

    <div class="container my-4">
        <h2 class="text-center my-4">Student Records</h2>

        <!-- Check if $students exists and is an array -->
        <?php if (isset($students) && is_array($students) && !empty($students)): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Sr.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Id</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iterate through each student record and display data -->
                <?php foreach ($students as $index => $student): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($student['name']); ?></td>
                    <td><?= htmlspecialchars($student['email']); ?></td>
                    <td><?= htmlspecialchars($student['id']); ?></td>
                    <td><?= htmlspecialchars($student['gender']); ?></td>
                    <td><?= htmlspecialchars($student['age']); ?></td>
                    <td>
                    <?php if (!empty($student['image']) && file_exists('public/images/' . $student['image'])): ?>
                        <!-- Display student image if available -->
                        <img src='public/images/<?= htmlspecialchars($student['image']); ?>' 
                             width='100' 
                             alt='Student Image' 
                             class="img-thumbnail">
                    <?php else: ?>
                        <!-- Display default image if no image available -->
                        <img src='public/images/default.png' 
                             width='100' 
                             alt='No Image' 
                             class="img-thumbnail">
                    <?php endif; ?>
                    </td>
                    <td>
                        <!-- Edit and delete buttons for each student -->
                        <button 
                            class="btn btn-sm btn-primary edit" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal" 
                            data-id="<?= $student['srno']; ?>" 
                            data-name="<?= htmlspecialchars($student['name']); ?>" 
                            data-email="<?= htmlspecialchars($student['email']); ?>" 
                            data-idnumber="<?= htmlspecialchars($student['id']); ?>" 
                            data-gender="<?= htmlspecialchars($student['gender']); ?>" 
                            data-age="<?= htmlspecialchars($student['age']); ?>">
                            Edit
                        </button>
                        <button
                            class="btn btn-sm btn-danger delete"
                            data-id="<?= $student['srno']; ?>">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <!-- Message when no students exist -->
        <p class="text-center text-muted">No records found. Please add some students.</p>
        <?php endif; ?>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit this Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to edit student data -->
                    <form action="/studentrecord/public/index.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="snoEdit" id="snoEdit"> <!-- Hidden input to store student serial number -->
                        <div class="mb-3">
                            <label for="nameEdit" class="form-label"><strong>Name</strong></label>
                            <input type="text" class="form-control" name="nameEdit" id="nameEdit" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailEdit" class="form-label"><strong>Email</strong></label>
                            <input type="email" class="form-control" name="emailEdit" id="emailEdit" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="idEdit" class="form-label"><strong>ID</strong></label>
                            <input type="text" class="form-control" name="idEdit" id="idEdit" placeholder="Enter ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="ageEdit" class="form-label"><strong>Age</strong></label>
                            <input type="number" class="form-control" name="ageEdit" id="ageEdit" placeholder="Enter age" min="1" max="120" required>
                        </div>
                        <div class="mb-3">
                            <label for="genderEdit" class="form-label"><strong>Gender</strong></label>
                            <select class="form-select" id="genderEdit" name="genderEdit" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="imageEdit" class="form-label"><strong>Upload New Image</strong></label>
                            <input type="file" class="form-control" name="imageEdit" id="imageEdit" accept="image/*">
                            <small class="form-text text-muted">Leave blank to keep the existing image.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Record</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Populate the edit modal with student data on click of edit button
        document.querySelectorAll('.edit').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const email = button.getAttribute('data-email');
                const idnumber = button.getAttribute('data-idnumber');
                const gender = button.getAttribute('data-gender');
                const age = button.getAttribute('data-age');

                document.getElementById('snoEdit').value = id;
                document.getElementById('nameEdit').value = name;
                document.getElementById('emailEdit').value = email;
                document.getElementById('idEdit').value = idnumber;
                document.getElementById('genderEdit').value = gender;
                document.getElementById('ageEdit').value = age;
            });
        });

        // Handle deletion of student record on delete button click
        document.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = button.getAttribute('data-id');
                if (confirm("Are you sure you want to delete this record?")) {
                    window.location = `/studentrecord/public/index.php?delete=${id}`;
                }
            });
        });
    </script>
  </body>
</html>
