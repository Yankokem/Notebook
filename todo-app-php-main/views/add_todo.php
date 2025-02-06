<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Create Todo </title>
  <link href="../statics/css/bootstrap.min.css" rel="stylesheet">
  <script src="../statics/js/bootstrap.js"></script>
</head>

<body>
  <!-- Add Todo Modal -->
<div class="modal fade" id="addTodoModal" tabindex="-1" aria-labelledby="addTodoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="addTodoModalLabel">Create Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form" action="handlers/add_todo_handler.php" method="POST">
          <div class="my-3">
            <label>Title</label>
            <input class="form-control" type="text" name="title" required />
          </div>
          <div class="my-3">
            <label>Description</label>
            <textarea class="form-control" name="description" required></textarea>
          </div>
          <div class="my-3">
            <button type="submit" class="btn btn-outline-dark">Create Todo</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</body>

</html>
