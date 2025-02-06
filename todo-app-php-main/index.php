<?php include 'database/database.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Todo App </title>
  <link href="statics/css/bootstrap.min.css" rel="stylesheet">
  <script src="statics/js/bootstrap.js"></script>
  <script src="https://kit.fontawesome.com/cafc63f120.js" crossorigin="anonymous"></script>

  <style>
    .border {
      height: 300px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
    }

    .border p.text-secondary {
      max-height: 120px;
      overflow-y: auto;
      word-wrap: break-word;
      padding-right: 5px;
    }
  </style>


</head>

<body>
  <div>
    <nav class="navbar navbar-light bg-white shadow-sm">
      <div class="container-fluid d-flex justify-content-center align-items-center">
        <div class="navbar-logo d-flex align-items-center me-4">
          <i class="fa-regular fa-pen-to-square" style="color:rgb(0, 128, 0); font-size: 50px;"></i>
          <span class="ms-2 display-6 fw-bold">NoteBook</span>
        </div>
        <div class="d-flex align-items-center">
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addTodoModal">
            Add Todo
          </button>
        </div>
        <form id="searchForm" class="d-flex mx-4" style="flex-grow: 0; width: 20%;">
          <input class="form-control me-2" type="search" id="searchInput" placeholder="Search" aria-label="Search">
          <button class="btn btn-sm btn-success" type="submit">Search</button>
        </form>
      </div>
    </nav>
  </div>

  <div class="container mt-5">
    <div class="row">
      <?php
      $res = $conn->query("SELECT * FROM todo");
      ?>
      <?php if ($res->num_rows > 0): ?>
        <?php while ($row = $res->fetch_assoc()): ?>
          <div class="col-md-3 d-flex">
            <div class="border rounded p-4 my-3 w-100">
              <h5 class="fw-bold"><?= $row['title']; ?></h5>
              <p class="text-secondary"><?= $row['description']; ?></p>
              <p class="fw-bold">
                <small>
                  <?php
                  switch ($row['subject']) {
                    case 0:
                      echo "Default";
                      break;
                    case 1:
                      echo "Personal";
                      break;
                    case 2:
                      echo "School-related";
                      break;
                    case 3:
                      echo "Task";
                      break;
                  }
                  ?>
                </small>
              </p>
              <div class="d-flex justify-content-between">
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateTodoModal" data-id="<?= $row['id']; ?>" data-title="<?= $row['title']; ?>" data-description="<?= $row['description']; ?>" data-subject="<?= $row['subject']; ?>">Edit</button>
                <a href="handlers/delete_todo_handler.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="row justify-content-center">
          <div class="col-md-6 border rounded p-3 my-3 text-center">
            <p class="text-muted">!--Notebook Empty--!</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php include 'views/add_todo.php'; ?>

  <div class="modal fade" id="updateTodoModal" tabindex="-1" aria-labelledby="updateTodoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateTodoModalLabel">Edit Todo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateTodoForm" action="handlers/update_todo_handler.php" method="POST">
            <input type="hidden" name="id" id="updateTodoId">
            <div class="mb-3">
              <label for="updateTodoTitle" class="form-label">Title</label>
              <input type="text" class="form-control" id="updateTodoTitle" name="title" required>
            </div>
            <div class="mb-3">
              <label for="updateTodoDescription" class="form-label">Description</label>
              <textarea class="form-control" id="updateTodoDescription" name="description" required></textarea>
            </div>
            <div class="mb-3">
              <label for="updateTodoSubject" class="form-label">Subject</label>
              <select class="form-select" id="updateTodoSubject" name="subject" required>
                <option value="0">Default</option>
                <option value="1">Personal</option>
                <option value="2">School-related</option>
                <option value="3">Task</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var updateTodoModal = document.getElementById('updateTodoModal');
      updateTodoModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var description = button.getAttribute('data-description');
        var subject = button.getAttribute('data-subject');

        updateTodoModal.querySelector('#updateTodoId').value = id;
        updateTodoModal.querySelector('#updateTodoTitle').value = title;
        updateTodoModal.querySelector('#updateTodoDescription').value = description;
        updateTodoModal.querySelector('#updateTodoSubject').value = subject;
      });
    });
  </script>

  <script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
      e.preventDefault();

      var searchValue = document.getElementById('searchInput').value;
      var formData = new FormData();
      formData.append("search", searchValue);

      fetch("handlers/search_todo_handler.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          var container = document.querySelector(".container .row");
          container.innerHTML = '';

          if (data.length > 0) {
            data.forEach(todo => {
              container.innerHTML += `
                    <div class="col-md-3 d-flex">
                      <div class="border rounded p-4 my-3 w-100">
                        <h5 class="fw-bold">${todo.title}</h5>
                        <p class="text-secondary">${todo.description}</p>
                        <p class="fw-bold"><small>
                          ${getSubjectName(todo.subject)}
                        </small></p>
                        <div class="d-flex justify-content-between">
                          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateTodoModal"
                            data-id="${todo.id}" data-title="${todo.title}" data-description="${todo.description}" data-subject="${todo.subject}">
                            Edit
                          </button>
                          <a href="handlers/delete_todo_handler.php?id=${todo.id}" class="btn btn-sm btn-danger">Delete</a>
                        </div>
                      </div>
                    </div>
                `;
            });
          } else {
            container.innerHTML = `
                <div class="row justify-content-center">
                  <div class="col-md-6 border rounded p-3 my-3 text-center">
                    <p class="text-muted">!-- No matching results --!</p>
                  </div>
                </div>
            `;
          }
        });
    });

    function getSubjectName(subject) {
      switch (parseInt(subject)) {
        case 1:
          return "Personal";
        case 2:
          return "School-related";
        case 3:
          return "Task";
        default:
          return "Default";
      }
    }
  </script>


</body>

</html>