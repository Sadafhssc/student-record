<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iRecord - Student Record System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <?php include '../partials/_nav.php'; ?>

    <div class="container my-3 col-md-6">
      <h2 class="text-center">iRecord - Student Record System</h2>
      <form action='/studentrecord/public/index.php' method='POST' enctype="multipart/form-data">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" maxlength="20" class="form-control border border-primary" id="name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Email address</label>
          <input type="email" maxlength="35" class="form-control border border-primary" id="email" name="email" aria-describedby="emailHelp" required>
          <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
          <label for="idNumber" class="form-label">Identification Number</label>
          <input type="number" maxlength="6" class="form-control border border-primary" id="idNumber" name="idNumber" required>
        </div>
        <div class="mb-3">
          <label for="age" class="form-label">Age</label>
          <input type="number" maxlength="2" class="form-control border border-primary" id="age" name="age" required>
        </div>
        <div class="mb-3">
          <label for="gender" class="form-label">Gender</label>
          <select class="form-select border border-primary" id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Upload Image</label>
          <input type="file" class="form-control border border-primary" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary mb-4">Submit</button>
      </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
